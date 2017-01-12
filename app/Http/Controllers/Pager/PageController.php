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
    private $filteredModel = null;
    private $query;
    private $firstFageNumber = 0;
    private $maxSize = 50;
    private $defaultSize = 20;
    private $sortDefault = 0; // 0 = recent, default result
    private $sort;

    private $pageNumber;
    private $pageSize;
    private $sortOption;
    private $filterOptions = [];

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

        $this->query = $query;
        $this->sort = (object)array('option' => 'id','direction' => 'desc');

        $this->setModel($section);
        $this->pageNumber = $this->setPageNumber();
        $this->pageSize = $this->setPageSize();
        $this->setSort();
        $this->setFilter();
        $this->modelFiltering();
        $this->bindData();
    }

    private function setModel($section){
        switch($section){
            case 'product' : $this->model = new Product; $this->initProduct(); break;
            case 'category' : $this->model = new Category; $this->initCategory(); break;
            case 'division' : $this->model = new Division; $this->initDivision(); break;
            case 'sector' : $this->model = new Sector; $this->initSector(); break;
            default : Abort::Error('0040','Unknown Model') ;break; //error point
        }
    }


    private function initProduct(){
        // if( isset($this->query['sort']) && $this->query['sort'] > 4 ){
        //     $this->query['sort'] = 1;
        // }
        $this->query['sort'] = 1;
        return;
    }
    private function initCategory(){
        $this->query['sort'] = 1;
        return;
    }
    private function initDivision(){
        $this->query['sort'] = 1;
        return;
    }
    private function initSector(){
        $this->query['sort'] = 1;
        return;
    }

    private function setPageNumber(){
        return isset($this->query['pageIndex']) ? $this->query['pageIndex'] : $this->firstFageNumber;
    }
    private function setPageSize(){
        return isset($this->query['pageSize']) && $this->query['pageSize'] <= $this->maxSize ? $this->query['pageSize'] : $this->defaultSize;
    }

    private function setFilter(){

        // in sector filter
        if( isset($this->query['marketCategoryId']) ){
            $this->filterOptions[] = array(
                "columnName" => 'market_category_id',
                "value" => $this->query['marketCategoryId'] == 'isNull' ? NULL : $this->query['marketCategoryId'] ,
            );
        }
        // in sector filter
    }

    private function setSort(){
        $this->sortOption = isset($this->query['sort']) ? $this->query['sort'] : $this->sortDefault;
        switch($this->sortOption){
            case 1 : break; //recent
            // case 2 : $this->sort->option = 'view_count' ; break; //view count
            // case 3 : $this->sort->option = 'comment_count' ; break; //comment count
            // case 4 : $this->sort->option = 'download_count' ; break; //download count
            default : break; //error point
        }
    }

    private function modelFiltering(){
        if( $this->hasFilter() ){
            foreach( $this->filterOptions as $key => $value ){
                $this->filteredModel = $this->model->where($value['columnName'],'=',$value['value']);
            }
        }

        //none filtered model
        if($this->filteredModel == null) $this->initModel();
    }

    private function hasFilter(){
        return count($this->filterOptions);
    }

    private function initModel(){
        $this->filteredModel = $this->model;
    }

    private function bindData(){
//        $this->withUserModel = $this->setModel;
        $this->paginator = $this->filteredModel->
            orderBy($this->sort->option,$this->sort->direction)->
            paginate($this->pageSize, ['*'], 'page', $this->pageNumber);
//             Log::debug('pagnator', [DB::getQueryLog()]);
        $this->totalCount = $this->paginator->total();
        $this->currentPage = $this->paginator->currentPage();
        $this->collection = $this->paginator->getCollection();
    }

    public function getCollection(){
//        return [];
        return $this->collection;
    }
}
