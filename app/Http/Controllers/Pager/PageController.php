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
    private $sortDefault = array('value' => 'id','direction' => 'desc'); // 0 = recent, default result

    private $filterQuery;
    private $sortQuery;
    private $dateQuery;

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
        $this->dateQuery = null; // check in queryParsing function
        $this->filterQuery = $this->queryParser('filter');
        $this->sortQuery = $this->queryParser('sort');
        $this->pageNumber = $this->setPageNumber();
        $this->pageSize = $this->setPageSize();
        $this->modelFiltering($this->filterQuery);
        $this->modelSorting($this->sortQuery);
        $this->bindData();
    }

    private function queryParser($query){
        $result = [];
        if( isset( $this->query[$query] ) ){
            $queries = $this->query[$query];
            $explodeQuery = explode(',',$queries);
            foreach( $explodeQuery as $key => $value ){
                $explodeValue = explode(':',$value);
                $key = $this->columnChecker($explodeValue[0]);
                $value = $this->stringToValueChecker($explodeValue[1]);
                if( $this->isDateQuery($value) ){
                    $this->dateQuery = array($key => $this->getDateArray($value));
                }else{
                    $result[] = array($key => $value);
                }
            }
        }
        return $result;
    }
    private function isDateQuery($string){
        return strpos($string,'~');
    }
    private function getDatearray($value){
        $explodeValue = explode('~',$value);
        return array(
            $explodeValue[0],
            $explodeValue[1]
        );
    }
    private function columnChecker($string){
        $columnName = $this->stringToKeyChecker($string);

        $tableName = strtolower(str_plural(explode('\\',get_class($this->model))[2]));
        $columnList = DB::getSchemaBuilder()->getColumnListing($tableName);

        if(in_array($columnName,$columnList)) return $columnName;
        Abort::Error('0040','Unknown Filter Key');
    }
    private function stringToKeyChecker($string){
        switch($string){
            case 'createDate' : return 'created_at';
            case 'endDate' : return 'end_date';
            case 'marketCategoryId' : return 'market_category_id';
        }
        Abort::Error('0040','Undefinded search key');
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

    private function modelFiltering($filterQuery){
        if( !is_null($this->dateQuery) ){
            $this->finalModel = $this->model->whereBetween(
                key($this->dateQuery),
                $this->dateQuery[key($this->dateQuery)]
            );
        }
        if( $this->hasFilter($filterQuery) ){
            foreach( $filterQuery as $key => $value ){
                $this->finalModel = $this->finalModel->where(key($value),'=',$value[key($value)]);
            }
        }else{
            $this->initModelFilter();
        }
    }
    private function modelSorting($sortQuery){
        if( $this->hasSort($sortQuery) ){
            foreach( $sortQuery as $key => $value ){
                $this->finalModel = $this->finalModel->orderBy(
                    key($value),
                    $value[key($value)]
                );
            }
        }else{
            $this->initModelSort();
        }
    }

    private function hasFilter($filterQuery){
        return count($filterQuery);
    }
    private function hasSort($sortQuery){
        return count($sortQuery);
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
//        Log::debug('pagnator', [DB::getQueryLog()]);
        $this->totalCount = $this->paginator->total();
        $this->currentPage = $this->paginator->currentPage();
        $this->collection = $this->paginator->getCollection();
    }

    public function getCollection(){
        return $this->collection;
    }
}
