<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abort;
use App\Models\Comment;
use App\Models\Board;
use App\Models\User;
use App\Models\Post;
use App\Models\Content;

use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\CheckContoller;
use App\Http\Controllers\Pager\PageController;

use App\Http\Requests\Comment\CommentUploadRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;

use Log;

class CommentController extends Controller
{
    public function store(CommentUploadRequest $request,$category,$board_id){
        $data = $request->json()->all();

        $tokenData = CheckContoller::checkToken($request);
        $findUser = User::findOrFail($tokenData->id);
        $targetPostModel = "App\Models\\".title_case(Board::where('name','=',$category)->value('group'));
        $getUserId = $targetPostModel::find($board_id)->value('user_id');
        $comments = new Comment;

        $comments->give_user_id = $findUser->id;
        $comments->take_user_id = $getUserId;
        $comments->board_id = Board::where('name','=',$category)->value('id');
        $comments->post_id = $board_id;
        $comments->content = $data['content'];

        if($comments->save()){
          return response()->success();
        };
        Abort::Error('0040');
    }
    public function getList(Request $request,$category,$board_id=false){
        $query = $request->query();
        $board_id = $board_id ? $query['boardId'] = $board_id : null ;
        $controller = new PageController('comment',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
            "comments" => []
        );
        foreach($collection as $array){
            $result->comments[] = (object)array(
                "commentData" => (object)array(
                     "id" => $array->id,
                     "board_id" => $array->post_id,
                     "content" => $array->content,
                     "date" => Carbon::instance($array->created_at)->toDateTimeString(),
                ),
                "userData" => (object)array(
                    "id" => $array->user->id,
                    "nickname" => $array->user->nickname,
                    "profile" => $array->user->profile_img
                )
            );
        };

        if(!empty($result->comments)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    public function update(CommentUpdateRequest $request,$category,$board_id,$comment_id){
        $data = $request->json()->all();

        $tokenData = CheckContoller::checkToken($request);
        $findUser = User::findOrFail($tokenData->id);
        $comments = Comment::findOrFail($comment_id);

        $comments->content = $data['content'];
        if($comments->save()){
          return response()->success();
        };

        Abort::Error('0040');
    }
    public function delete(Request $request,$category,$board_id,$comment_id){
        $tokenData = CheckContoller::checkToken($request);
        $findUser = User::findOrFail($tokenData->id);
        $comments = Comment::findOrFail($comment_id);

        if($comments->delete()){
          return response()->success();
        }
    }

    private function isSameUser($findUser,$comments){
        return $findUser->id != $comments->give_user_id;
    }
    private function isSamePost($comments,$board_id){
        return $comments->post_id != $board_id;
    }
    private function isSameBoard($comments,$category){
        return $comments->board_id != Board::where('name','=',$category)->value('id');
    }
}
