<?php

namespace App\Classes;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Abort;

use DB;
use Log;

class PageController
{
    private $model;
    private $finalModel = null;
    private $baseTableName;
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

    public function __construct($section,$query){

        DB::connection()->enableQueryLog();
        $this->DBquery = DB::getQueryLog();
        $lastQuery = end($query);

        $this->baseTableName = strtolower(str_plural($section));
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
//        $columnList = DB::getSchemaBuilder()->getColumnListing($this->baseTableName);
//        if(in_array($columnName,$columnList)) return $columnName;
//        Abort::Error('0040','Unknown Filter Key '.$string);
    }

    // Functions that must be added continuously
    private function stringToKeyChecker($string){
        switch($string){
            case 'id' : return 'id';

            case 'applyUserId' : return 'give_products.apply_user_id';
            case 'acceptUserId' : return 'give_products.accept_user_id';

            case 'haitaoProductId' : return 'products.haitao_product_id';
            case 'haitaoUserId' : return 'users.haitao_user_id';

            case 'userId' : return 'users.id';
            case 'reviewId' : return 'reviews.id';
            case 'productId' : return 'products.id';

            case 'isWrittenReview' : return 'awards.is_written_review';

            case 'originTitle' : return $this->baseTableName.'_title_translate.original';
            // order, product divide
            case 'stock' : return 'stock';
            case 'safeStock' : return 'safe_stock';
            case 'productStatusCode' : return 'product_status_code';
            case 'createDate' : return 'created_at';
            case 'endDate' : return 'end_date';
            case 'marketCategoryId' : return 'section_market_infos.market_category_id';
        }
        Abort::Error('0040','Undefinded search key '.$string);
    }
    private function stringToValueChecker($string){
        switch($string){
            case 'isNull' : return NUll;
        }
        return $string;
    }
    private function setModel($section){
        switch($section){
            case 'product' :
                $this->setPartsModel([
                    [
                        "join_table_name" => $this->baseTableName.'_title_translate',
                        "base_table_key_column" => "title_translate_id",
                        "join_table_key_column" => "id",
                    ],
                ]);
                $this->model = new Product; break;
//            case 'order' :
//                $this->setPartsModel([
//                    [
//                        "join_table_name" => 'products',
//                        "base_table_key_column" => "orders.product_id",
//                        "join_table_key_column" => "id",
//                    ]
//                ]);
//                $this->model = new Order; break;
//                break;
            case 'category' :
                $this->setPartsModel([
                    [
                        "join_table_name" => $this->baseTableName.'_name_translate',
                        "base_table_key_column" => "name_translate_id",
                        "join_table_key_column" => "id",
                    ]
                ]);
                $this->model = new Category; break;
            case 'division' :
                $this->setPartsModel([
                    [
                        "join_table_name" => $this->baseTableName.'_name_translate',
                        "base_table_key_column" => "name_translate_id",
                        "join_table_key_column" => "id",
                    ]
                ]);
                $this->model = new Division; break;
            case 'section' :
                $this->setPartsModel([
                    [
                        "join_table_name" => 'section_market_infos',
                        "base_table_key_column" => "sections.id",
                        "join_table_key_column" => "section_id",
                    ],
                    [
                        "join_table_name" => $this->baseTableName.'_name_translate',
                        "base_table_key_column" => "name_translate_id",
                        "join_table_key_column" => "id",
                    ]
                ]);
                $this->model = new Section; break;
            case 'survey' :
                $this->setPartsModel([
                    [
                        "join_table_name" => 'users',
                        "base_table_key_column" => "surveys.user_id",
                        "join_table_key_column" => "id",
                    ]
                ]);
                $this->model = new Survey; break;
            case 'review' :
                $this->setPartsModel([
                    [
                        "join_table_name" => 'users',
                        "base_table_key_column" => "reviews.user_id",
                        "join_table_key_column" => "id",
                    ],
                    [
                        "join_table_name" => 'products',
                        "base_table_key_column" => "reviews.product_id",
                        "join_table_key_column" => "id",
                    ]
                ]);
                $this->model = new Review; break;
            case 'give_product' :
                $this->setPartsModel([
                    [
                        "join_table_name" => 'reviews',
                        "base_table_key_column" => "give_products.review_id",
                        "join_table_key_column" => "id",
                    ]
                ]);
                $this->model = new GiveProduct; break;
            case 'free_gift_group' :
                $this->setPartsModel([
                    [
                        "join_table_name" => 'products',
                        "base_table_key_column" => "free_gift_groups.product_id",
                        "join_table_key_column" => "id",
                    ]
                ]);
                $this->model = new FreeGiftGroup; break;
            case 'award' :
                $this->setPartsModel([
                    [
                        "join_table_name" => 'users',
                        "base_table_key_column" => "awards.user_id",
                        "join_table_key_column" => "id",
                    ]
                ]);
                $this->model = new Award; break;
            default : Abort::Error('0040','Unknown Model') ;break; //error point
        }
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
