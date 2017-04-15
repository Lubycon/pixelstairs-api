<?php

namespace App\Classes;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Abort;

use App\Models\Content;

use DB;
use Log;

class Pager
{
    private $model;
    private $finalModel = null;
    private $baseTableName;
    private $baseTablePath;
    private $query;
    private $firstFageNumber = 1;
    private $maxSize = 100;
    private $defaultSize = 20;
    private $sortDefault = array('key' => 'id','value' => 'desc'); // 0 = recent, default result

    private $searchQuery;
    private $filterQuery;
    private $sortQuery;
    private $rangeQuery;

    private $partsModels = [];
    private $joinedmodel;

    private $pageNumber;
    private $pageSize;

    public $paginator;
    public $totalCount;
    public $currentPage;
    public $collection;


    public $DBquery;

    public function __construct(){
        DB::connection()->enableQueryLog();
        $this->DBquery = DB::getQueryLog();
    }

    public function search($section,$query){
        $this->baseTableName = strtolower(str_plural($section));
        $this->baseTablePath = 'App\Models\\'.title_case($section);
        $this->setModel($section);
        $this->query = $query;
        $this->rangeQuery = null; // check in queryParsing function
        $this->searchQuery = $this->queryParser('search');
        $this->filterQuery = $this->queryParser('filter');
        $this->sortQuery = $this->queryParser('sort');
        $this->pageNumber = $this->setPageNumber();
        $this->pageSize = $this->setPageSize();
        $this->joinedmodel = $this->joinModel($this->model);
        $this->modelFiltering($this->filterQuery,$this->searchQuery);
        $this->modelSorting($this->sortQuery);
        $this->bindData();
        return $this;
    }

    private function queryParser($query){
        $result = [];
        if( isset( $this->query[$query] ) ){
            $queries = $this->query[$query];
            $explodeQuery = explode('||',$queries);
            foreach( $explodeQuery as $key => $value ){
                $queryString = urldecode($value);
                $split = preg_split('(<[=>]?|>=?|==|:)',$queryString);
                $checkColumn = $this->columnChecker($split[0]);
                $searchTable = $checkColumn['tableName'];
                $searchKey = $checkColumn['columnName'];
                $searchValue = $this->stringToValueChecker($split[1]);
                $comparison = $this->getComparision($queryString,$split);

                if( $this->isRangeFilter($searchValue) ){
                    $this->rangeQuery = array(
                        'table' => $searchTable,
                        'key' => $searchKey,
                        'value' => $this->getRangeArray($searchValue),
                    );
                }else{
                    $result[] = array(
                        'table' => $searchTable,
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
    private function isRangeFilter($value){
        return strpos($value,'~');
    }
    private function isColumnQuery($comparison){
        return $comparison !== '=';
    }
    private function getRangeArray($value){
        $explodeValue = explode('~',$value);
        return array($explodeValue[0],$explodeValue[1]);
    }
    private function columnChecker($string){
        $checked = $this->stringToKeyChecker($string);
        $explodeValue = explode('.',$checked);
        if( count($explodeValue) > 1 ){
            return [
                "tableName" => $explodeValue[0],
                "columnName" => $explodeValue[1],
            ];
        }
        return [
            "tableName" => $this->baseTableName,
            "columnName" => $checked,
        ];
    }

    // Functions that must be added continuously
    private function stringToKeyChecker($string){
        $checked = config("pager.searchKeyConversion.$string");
        if(is_null($checked)) Abort::Error('0040','Undefinded search key '.$string);
        return $checked;
    }
    private function stringToValueChecker($string){
        $checked = config("pager.searchValueConversion.$string");
        if(is_null($checked)) return $string;
        return $checked;
    }
    private function setModel($section){
        $checked = config("pager.partsModel.$section");
        if( is_null($checked) ) Abort::Error('0040','Unknown Model'); //error point
        $this->setPartsModel($checked);
        $this->model = new $this->baseTablePath;
    }

    // must exist in main model in parts function
    private function setPartsModel($parts){
        foreach($parts as $value){
            $this->partsModels[] = $value;
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
        $this->initModelFilter($this->joinedmodel);

        if( $this->hasRangeFilter($this->rangeQuery) ){
            $this->finalModel = $this->finalModel->whereBetween(
                $this->rangeQuery['key'],
                $this->rangeQuery['value']
            );
        }
        if( $this->hasQuery($searchQuery) ) {
            foreach ($searchQuery as $key => $value) {
                if( $this->isIdKey($value['key']) ){
                    $this->finalModel = $this->finalModel->where($value['table'].'.'.$value['key'],$value['comparision'],$value['value']);
                }else{
                    $this->finalModel = $this->finalModel->where($value['table'].'.'.$value['key'],'LIKE','%'.$value['value'].'%');
                }
            }
        }
        if( $this->hasQuery($filterQuery) ) {
            foreach ($filterQuery as $key => $value) {
                $this->finalModel = $this->finalModel->where($value['table'].'.'.$value['key'],$value['comparision'],$value['value']);
            }
        }
    }
    private function modelSorting($sortQuery){
        if( $this->hasQuery($sortQuery) ){
            foreach( $sortQuery as $key => $value ){
                $this->finalModel = $this->finalModel->orderBy(
                    $value['table'].'.'.$value['key'],
                    $this->sortDirectionCheck($value['value'])
                );
            }
        }else{
            $this->initModelSort();
        }
    }
    private function isIdKey($key){
        $lowCase = strtolower($key);
        return !is_null(strpos($lowCase,'id'));
    }
    private function sortDirectionCheck($direction){
        if($direction == 'desc' || $direction == 'asc') return $direction;
        Abort::Error('0040','Check Sort Direction');
    }

    private function hasRangeFilter($rangeQuery){
        return !is_null($rangeQuery);
    }
    private function hasQuery($query){
        return count($query);
    }
    private function initModelFilter($baseModel){
        $this->finalModel = $baseModel;
    }
    private function initModelSort(){
        $this->finalModel = $this->finalModel->orderBy(
            $this->baseTableName.'.'.$this->sortDefault['key'],
            $this->sortDefault['value']
        );
    }
    private function joinModel($baseModel){
        $joinedModel = $baseModel;
        $operator = '=';
        foreach( $this->partsModels as $key => $value ){
            //join('join_table_name','base_table_key_column','operator','join_table_key_column');
            $joinedModel= $joinedModel->leftjoin(
                $value['join_table_name'],
                $this->getJoinTableColumnInfo($value),
                $operator,
                $value['join_table_name'].'.'.$value['join_table_key_column']
            );
        }
        return $joinedModel;
    }

    private function getJoinTableColumnInfo($value){
        return is_null(strpos($value['base_table_key_column'],'.'))
            ? $this->baseTableName.'.'.$value['base_table_key_column']
            : $value['base_table_key_column'];
    }

    private function bindData(){
        $this->paginator = $this->finalModel->select($this->baseTableName.'.*')->
        paginate($this->pageSize, ['*'], 'page', $this->pageNumber);
        Log::debug('paginate', [DB::getQueryLog()]);
        $this->totalCount = $this->paginator->total();
        $this->currentPage = $this->paginator->currentPage();
        $this->collection = $this->paginator->getCollection();
    }

    public function getCollection(){
        return $this->collection;
    }
}
