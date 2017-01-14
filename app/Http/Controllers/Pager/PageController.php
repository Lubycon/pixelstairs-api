<?php

namespace App\Http\Controllers\Pager;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Category;
use App\Models\Division;
use App\Models\Sector;

use Abort;

use DB;
use Log;

class PageController extends Controller
{
    private $model;
    private $finalModel = null;
    private $query;
    private $firstFageNumber = 1;
    private $maxSize = 100;
    private $defaultSize = 20;
    private $sortDefault = array('id' => 'desc'); // 0 = recent, default result

    private $searchQuery;
    private $filterQuery;
    private $sortQuery;
    private $rangeQuery;

    private $pageNumber;
    private $pageSize;

    private $withUserModel;
    public $paginator;
    public $totalCount;
    public $currentPage;
    public $collection;


    public $DBquery;

    public function __construct($section,$query){

        DB::connection()->enableQueryLog();
        $this->DBquery = DB::getQueryLog();
        $lastQuery = end($query);

        $this->setModel($section);
        $this->query = $query;
        $this->rangeQuery = null; // check in queryParsing function
        $this->searchQuery = $this->queryParser('search');
        $this->filterQuery = $this->queryParser('filter');
        $this->sortQuery = $this->queryParser('sort');
        $this->pageNumber = $this->setPageNumber();
        $this->pageSize = $this->setPageSize();
        $this->modelFiltering($this->filterQuery,$this->searchQuery);
        $this->modelSorting($this->sortQuery);
        $this->bindData();
    }

    private function queryParser($query){
        $result = [];
        if( isset( $this->query[$query] ) ){
            $queries = $this->query[$query];
            $explodeQuery = explode('||',$queries);
            foreach( $explodeQuery as $key => $value ){

                $split = preg_split('(<[=>]?|>=?|==|~|:)',$value);
                $searchKey = $this->columnChecker($split[0]);
                $searchValue = $this->stringToValueChecker($split[1]);
                $comparison = $this->getComparision($value,$split);

                if( $this->isRangeFilter($comparison) ){
                    $this->rangeQuery = array(
                        'key' => $searchKey,
                        'value' => $this->getRangeArray($searchValue),
                    );
                }else{
                    $result[] = array(
                        'key' => $searchKey,
                        'comparision'=> $comparison,
                        'value' => $this->isColumnQuery($comparison)
                            ? DB::raw( $this->stringToKeyChecker($searchValue) )
                            : $searchValue,
                    );
                }
            }
        }
        return $result;
    }
    private function getComparision($subject,$search){
        $result = str_replace($search,'',$subject);
        $result = $result == ':' ? '=' : $result;
        return $result;
    }
    private function isRangeFilter($comparison){
        return $comparison == '~';
    }
    private function isColumnQuery($comparison){
        return $comparison !== '=';
    }
    private function getRangeArray($value){
        $explodeValue = explode('~',$value);
        return array($explodeValue[0],$explodeValue[1]);
    }
    private function columnChecker($string){
        $columnName = $this->stringToKeyChecker($string);
        $tableName = strtolower(str_plural(explode('\\',get_class($this->model))[2]));
        $columnList = DB::getSchemaBuilder()->getColumnListing($tableName);

        if(in_array($columnName,$columnList)) return $columnName;
        Abort::Error('0040','Unknown Filter Key'.$string);
    }

    // Functions that must be added continuously
    private function stringToKeyChecker($string){
        switch($string){
            case 'id' : return 'id';
            case 'haitaoProductId' : return 'haitao_product_id';
            // order, product divide
            case 'stock' : return 'stock';
            case 'safeStock' : return 'safe_stock';
            case 'statusCode' : return 'status_code';
            case 'createDate' : return 'created_at';
            case 'endDate' : return 'end_date';
            case 'marketCategoryId' : return 'market_category_id';
        }
        Abort::Error('0040','Undefinded search key'.$string);
    }
    private function stringToValueChecker($string){
        switch($string){
            case 'isNull' : return NUll;
        }
        return $string;
    }
    private function setModel($section){
        switch($section){
            case 'product' : $this->model = new Product; break;
            case 'category' : $this->model = new Category; break;
            case 'division' : $this->model = new Division; break;
            case 'sector' : $this->model = new Sector; break;
            default : Abort::Error('0040','Unknown Model') ;break; //error point
        }
    }

    private function setPageNumber(){
        return isset($this->query['pageIndex']) ? $this->query['pageIndex'] : $this->firstFageNumber;
    }
    private function setPageSize()
    {
        return isset($this->query['pageSize']) && $this->query['pageSize'] <= $this->maxSize ? $this->query['pageSize'] : $this->defaultSize;
    }

    private function modelFiltering($filterQuery,$searchQuery){
        $this->initModelFilter();

        if( $this->hasRangeFilter($this->rangeQuery) ){
            $this->finalModel = $this->finalModel->whereBetween(
                $this->rangeQuery['key'],
                $this->rangeQuery['value']
            );
        }
        if( $this->hasQuery($searchQuery) ) {
            foreach ($searchQuery as $key => $value) {
                $this->finalModel = $this->finalModel->where($value['key'],$value['comparision'],$value['value']);
            }
        }
        if( $this->hasQuery($filterQuery) ) {
            foreach ($filterQuery as $key => $value) {
                $this->finalModel = $this->finalModel->where($value['key'],$value['comparision'],$value['value']);
            }
        }
    }
    private function modelSorting($sortQuery){
        if( $this->hasQuery($sortQuery) ){
            foreach( $sortQuery as $key => $value ){
                $this->finalModel = $this->finalModel->orderBy(
                    $value['key'],
                    $value['value']
                );
            }
        }else{
            $this->initModelSort();
        }
    }

    private function hasRangeFilter($rangeQuery){
        return !is_null($rangeQuery);
    }
    private function hasQuery($query){
        return count($query);
    }
    private function initModelFilter(){
        $this->finalModel = $this->model;
    }
    private function initModelSort(){
        $this->finalModel = $this->finalModel->orderBy(
            key($this->sortDefault),
            $this->sortDefault[key($this->sortDefault)]
        );
    }

    private function bindData(){
//        $this->withUserModel = $this->setModel;
        $this->paginator = $this->finalModel->
        paginate($this->pageSize, ['*'], 'page', $this->pageNumber);
        Log::debug('pagnator', [DB::getQueryLog()]);
        $this->totalCount = $this->paginator->total();
        $this->currentPage = $this->paginator->currentPage();
        $this->collection = $this->paginator->getCollection();
    }

    public function getCollection(){
        return $this->collection;
    }
}
