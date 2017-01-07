<?php

namespace App\Http\Controllers\Pager;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Product;
// use App\Models\Content;
// use App\Models\Post;
// use App\Models\Comment;
// use App\Models\User;
// use App\Models\View;
// use App\Models\Board;

use Abort;

use DB;
use Log;

class PageController extends Controller
{
    private $model;
    private $setModel = null;
    private $query;
    private $categoryName;
    private $firstFageNumber = 0;
    private $maxSize = 50;
    private $defaultSize = 20;
    private $searchAllUser = false;
    private $searchAllPost = false;
    private $sortDefault = 0; // 0 = recent, default result
    private $sort;

    private $setPage;
    private $pageSize;
    private $searchUser;
    private $searchUserName = 'id';
    private $searchPostName = 'id';
    private $sortOption;

    private $userModelFunctionName = 'user';
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
        $this->sort = (object)array('option' => 'created_at','direction' => 'desc');

        $this->categoryName = $this->getSectionName($section);
        $this->setModel($section);
        $this->setPageRange();
        $this->setModelFilter();
        $this->bindData();
    }

    private function getSectionName($section){
        // if($section == 'comment'){
        //     return 'comment';
        // }else{
        //     return Board::where('name','=',$section)->value('group');
        // }
        return 'product';
    }

    private function setModel($section){
        switch($this->categoryName){
            case 'product' : $this->model = new Product; $this->initProduct(); break;
            default : break; //error point
        }
    }


    private function initProduct(){
        // if( isset($this->query['sort']) && $this->query['sort'] > 4 ){
        //     $this->query['sort'] = 1;
        // }
        // $this->searchUserName = 'user_id';
        $this->query['sort'] = 1;
        return;
    }


    private function setPageRange(){
        $this->setPage = isset($this->query['pageIndex']) ? $this->query['pageIndex'] : $this->firstFageNumber;
        $this->pageSize = isset($this->query['pageSize']) && $this->query['pageSize'] <= $this->maxSize ? $this->query['pageSize'] : $this->defaultSize;
        // $this->searchPost = isset($this->query['boardId']) ? $this->query['boardId'] : $this->searchAllPost;
        // $this->searchUser = isset($this->query['userId']) ? $this->query['userId'] : $this->searchAllUser;
        $this->sortOption = isset($this->query['sort']) ? $this->query['sort'] : $this->sortDefault;
        switch($this->sortOption){
            case 1 : break; //recent
            // case 2 : $this->sort->option = 'view_count' ; break; //view count
            // case 3 : $this->sort->option = 'comment_count' ; break; //comment count
            // case 4 : $this->sort->option = 'download_count' ; break; //download count
            default : break; //error point
        }
    }

    private function setModelFilter(){
        // if($this->searchUser){ //target users search
        // Log::debug($this->searchUserName.'='.$this->searchUser);
        //     $this->setModel = $this->model->where($this->searchUserName,'=',$this->searchUser);
        // }
        // if($this->searchPost){ // target posts search
        //     $this->setModel = $this->setModel == null
        //     ?$this->model->where($this->searchPostName,'=',$this->searchPost)
        //     :$this->setModel->where($this->searchPostName,'=',$this->searchPost);
        // }
        if($this->setModel == null){ //default search
            $this->initModel();
        }
    }

    private function initModel(){
        $this->setModel = $this->model;
    }

    private function bindData(){
        $this->withUserModel = $this->setModel;
        $this->paginator = $this->withUserModel->
            orderBy($this->sort->option,$this->sort->direction)->
            paginate($this->pageSize, ['*'], 'page', $this->setPage);
            // Log::debug('pagnator', [DB::getQueryLog()]);
        $this->totalCount = $this->paginator->total();
        $this->currentPage = $this->paginator->currentPage();
        $this->collection = $this->paginator->getCollection();

        if($this->collection->isEmpty()){
            return response()->success();
        }
    }

    public function getCollection(){
        return $this->collection;
    }
}
