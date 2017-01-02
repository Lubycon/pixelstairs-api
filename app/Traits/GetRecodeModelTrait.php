<?php
namespace App\Traits;

use App\Models\View;
use App\Models\Donwload;
use App\Models\Like;
// use App\Models\Bookmark;

use App\Models\Content;
use App\Models\Post;
use App\Models\Comment;

use Carbon\Carbon;

use Log;

trait GetRecodeModelTrait{

    function defineModel($type){
        $getClass = $this->findModel($type);
        return $getClass;
    }
    function findModel($type){
        switch($type){
            case 'view' :
            $class = (object)array(
                'model' => new View,
                'type' => 'simplex',
                'column' => 'view_count'
            ); $this->setViewData($class->model); break;
            case 'download' :
            $class = (object)array(
                'model' => new Download,
                'type' => 'simplex',
                'column' => 'download_count'
            ); $this->setDownloadData($class->model); break;
            case 'like' :
            $class = (object)array(
                'model' => new Like,
                'type' => 'toggle',
                'column' => 'like_count'
            ); $this->setLikeData($class->model); break;
            default : $class = null ; break;
        }
        return $class;
    }

    function getCountColumn($type){
        switch($type){
            case 'view' :  $columnName = 'view_count'; break;
            case 'download' :  $columnName = 'download_count'; break;
            case 'like' :  $columnName = 'like_count'; break;
            default : $columnName = null ; break;
        }
        return $columnName;
    }

    function getPostModel($sector){
        switch($sector){
            case 'content' :  $class = new Content ; break;
            case 'post' :  $class = new Post ; break;
            case 'comment' :  $class = new Comment ; break;
            default : $class = null ; break;
        }
        return $class;
    }
    function getPost($model,$postId){
        $post = $model->find($postId);
        return $post;
    }


    function isOverlapCheck($model,$postId){
        $ipColumnName = 'ipv4';
        $idColumnName = 'give_user_id';
        $userId = $this->giveUserId;
        $userIp = $this->giveUserIp;
        $limitHours = -5;
        $limitTime = Carbon::now($limitHours)->toDateTimeString();
        $boardId = $this->boardId;
        $postId = $this->postId;

        if($this->countType == 'simplex' ){
            $whereModel = $model->where($ipColumnName,'=',$userIp)
                                ->where('created_at','>',$limitTime)
                                ->where('board_id','=',$boardId)
                                ->where('post_id','=',$postId)
                                ->exists();
            return $whereModel ? false : true ;
        }
        if($this->countType == 'toggle' ){
            $whereModel = $model->where($idColumnName,'=',$userId)
                                ->where('created_at','>',$limitTime)
                                ->where('board_id','=',$boardId)
                                ->where('post_id','=',$postId)
                                ->exists();
            return $whereModel !== null ? true : false ;
        }
    }

    function setViewData($class){
        $class->give_user_id = $this->giveUserId;
        $class->take_user_id = $this->takeUserId;
        $class->ipv4 = $this->giveUserIp;
        $class->board_id = $this->boardId;
        $class->post_id = $this->postId;
    }
    function setDownloadData($class){
        $class->give_user_id = $this->giveUserId;
        $class->take_user_id = $this->takeUserId;
        $class->ipv4 = $this->giveUserIp;
        $class->board_id = $this->boardId;
        $class->post_id = $this->postId;
    }
    function setLikeData($class){
        $class->give_user_id = $this->giveUserId;
        $class->take_user_id = $this->takeUserId;
        $class->board_id = $this->boardId;
        $class->post_id = $this->postId;
    }
}
 ?>
