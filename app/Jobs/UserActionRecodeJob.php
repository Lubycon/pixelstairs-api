<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Http\Request;

use App\Models\Board;

use App\Traits\GetUserModelTrait;
use App\Traits\GetRecodeModelTrait;

use Log;

class UserActionRecodeJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, GetUserModelTrait, GetRecodeModelTrait;

    protected $type;
    protected $data;
    protected $request;
    protected $sectorGroup;
    protected $boardId;
    protected $postId;
    protected $giveUser;
    protected $giveUserIp;
    protected $giveUserId;
    protected $takeUserId;
    protected $willCheck;

    // get data model
    protected $modelInfo;
    protected $recodeModel;
    protected $countType;
    protected $postColumn;
    protected $postModel;
    protected $post;
    protected $insertData;

    //get overlap check
    protected $overlap;


    public function __construct($request,$type,$data)
    {
        // setting variable
        $this->request = $request->all();
        $this->token = $request->header('X-lubycon-token');
        $this->type = $type;
        $this->data = $data;
        $this->sectorGroup = Board::find($this->data->board_id)->value('group');
        $this->boardId = $this->data->board_id;
        $this->postId = $this->data->id;
        $this->giveUser = $this->getUserByToken($this->token);
        $this->giveUserIp = $request->ip();
        $this->giveUserId = is_null($this->giveUser) ? null : $this->giveUser->id ;
        $this->takeUserId = $this->data->user_id;
        $this->willCheck; //for bookmark like comment_like
        // // setting model
    }


    public function handle()
    {
        $this->modelInfo = $this->defineModel($this->type);
        $this->recodeModel = $this->modelInfo->model;
        $this->countType = $this->modelInfo->type;
        $this->postColumn = $this->modelInfo->column;
        $this->postModel = $this->getPostModel($this->sectorGroup);
        $this->post = $this->getPost($this->postModel,$this->postId);
        $this->overlap = $this->isOverlapCheck($this->recodeModel,$this->postId);
        $countColumn = $this->postColumn;




        if( $this->countType == 'simplex' ){
            if($this->overlap){
                //count up
                $this->post->$countColumn++;
                $this->post->save();
                
                //recode write
                $this->recodeModel->save();
                Log::info('User Action Simplex event listen seccess');
                return;
            }
        }
        if($this->countType == 'toggle'){
            if(!$this->overlap){
                //count up
                $this->post->$countColumn++;
                $this->post->save();

                //recode write
                $this->recodeModel->save();
                Log::info('User Action Toggle event listen seccess');
                return;
            }else{
                //count down
                $this->post->$countColumn--;
                $this->post->save();
                // delete recode column
            }
        }
        Log::info('User Action event listen seccess / not recode cuz overlap');
        return;
    }
}
