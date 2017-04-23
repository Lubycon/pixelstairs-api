<?php

namespace App\Classes;

use Storage;
use Abort;
use Log;

use Illuminate\Support\Str;
use Intervention;

use App\Models\Image;
use App\Models\ImageGroup;

class FileUpload
{
    // config info
    private $storage;
    private $storagePath;
    private $bucket;
    private $tempStorage;
    private $ownCheckers;
    private $nullCheck;
    // config info

    // model
    private $model;
    private $modelName;
    private $modelId;
    private $groupModel;
    private $createModel;
    // model

    // data
    private $inputFile;

    // info
    private $isGroup;
    private $responsiveResolution;
    // info


    public function __construct(){
        // init config...
        $this->initConfig();
    }


    // init function
    private function initConfig(){
        $this->storage = Storage::disk(config('filesystems.default'));
        $this->storagePath = env('S3_PATH');
        $this->responsiveResolution = config('filesystems.responsive_resolution');
        $this->tempStorage = config('filesystems.temp_storage');
        $this->ownCheckers = [
            "snake" => config('filesystems.own_checker'),
            "camel" => camel_case(config('filesystems.own_checker')),
        ];
        $this->bucket = env('S3_BUCKET');
    }
    // init function

    // progress functions
    public function upload($model,$inputFile,$isGroup=false){
        $this->nullChecker($inputFile);
//        try{
            if( !$this->nullCheck ){
                $this->setBasicVariable($model,$inputFile);
                $this->modelName = $this->getModelName($this->model);
                $this->modelId = $this->getModelId($this->model);
                $this->isGroup = $isGroup;
                $this->groupModel = $this->createImageGroupModel($this->inputFile);
                $this->inputFile = $this->setToArray($this->inputFile);
                $this->inputFile = $this->fileTypeCheck($this->inputFile);
                $this->inputFile = $this->uploadS3($this->inputFile);
                $this->createModel = $this->createImageModel($this->inputFile);
            }
            return $this;
//        }catch(\Exception $e){
//            Abort::Error('0050',$e->getMessage());
//        }
    }
    // progress functions

    // upload function
    protected function responsiveUploadUrl($file){
        $image = $this->getResizeImages($file);
        $uploadPath = $this->modelName.'/'.$this->modelId.'/'.$this->setRandomFileName();
        foreach($image as $key => $value){
            $this->storage->getDriver()->getAdapter()->getClient()->upload(
                $this->bucket, // upload bucket
                $uploadPath.$key, $value, // upload path.file name
                'public-read', // permission
                ['params' => [ // metadata
                    'ContentType' => 'image/jpeg',
                ]]);
        }
        return $this->storagePath.$uploadPath;
    }
    protected function responsiveDeleteUrl($url){
        foreach($this->responsiveResolution as $key => $value){
            $path = $this->getInternalS3Url($url.$value);
            if($this->storage->exists($path)) {
                $this->storage->delete($path);
            }else{
                // not exist file delete request
            }
        }
        return true;
    }
    private function uploadS3($inputFile){
        foreach($inputFile as $key => $value){
            if( is_null($value['type']) ) unset($inputFile[$key]); // null image
            if( isset( $value['id'] ) ){ //update or delete
                if( $value['deleted'] ){ // delete
                    if( $value[$this->ownCheckers['camel']] && $value['type'] == 'url' ) $this->responsiveDeleteUrl($value['file']);
                    Image::find($value['id'])->delete();
                    unset($inputFile[$key]);
                }else{ // update
                    if( $value[$this->ownCheckers['camel']] ){
                        if( $value['type'] == 'base64' ) $newUrl = $this->responsiveUploadUrl($value['file']);
                    }
                    $inputFile[$key]['url'] = isset($newUrl) ? $newUrl : $value['file'];
                }
            }else{ //create
                if( $value['type'] == 'base64' ) $newUrl = $this->responsiveUploadUrl($value['file']);
                $inputFile[$key]['url'] = isset($newUrl) ? $newUrl : $value['file'];
            }
        }
        return $inputFile;
    }
    // upload function
    
    // model function
    protected function createImageModel($inputFile){
        $modelId = null;
        $images = [];
        foreach($inputFile as $key => $value ){
            $ownerCheck = $this->ownerCheck($value);
            if( isset($value['id']) ){
                $images[] = Image::findOrFail($value['id'])->update([
                    "index" => isset($value['index']) ? $value['index'] : 0,
                    "url" => $value['url'],
                    $this->ownCheckers['snake'] => $ownerCheck,
                    "image_group_id" => $this->isGroup ? $this->groupModel['id'] : null,
                ]);
            }else{
                $images[] = Image::create([
                    "index" => isset($value['index']) ? $value['index'] : 0,
                    "url" => $value['url'],
                    $this->ownCheckers['snake'] => $ownerCheck,
                    "image_group_id" => $this->isGroup ? $this->groupModel['id'] : null,
                ]);
            }
        }
        return $this->isGroup ? $this->groupModel : $images[0] ;
    }
    protected function createImageGroupModel($inputFile){
        if( $this->isGroup ){
            if( $groupId = $this->findGroupExist($inputFile) ){
                $model = ImageGroup::findOrFail($groupId);
            }else{
                $model = ImageGroup::create([]);
            }
        }
        return isset($model) ? $model : NULL;
    }
    // model function
    
    // checker function
    protected function ownerCheck($fileObj){
        $ownerCheck = null;
        if( isset($fileObj[$this->ownCheckers['camel']]) ){
            $ownerCheck = $fileObj[$this->ownCheckers['camel']] ? true : false ;
        }else if( $fileObj['type'] == 'base64' ){
            $ownerCheck = true;
        }else{
            $ownerCheck = false;
        }
        return $ownerCheck;
    }
    private function nullChecker($inputFile){
        if(is_null($inputFile)) return $this->nullCheck = true;
    }
    protected function findGroupExist($inputFile){
        $groupId = null;
        foreach( $inputFile as $key => $value ){
            if( isset($value['id']) ){
                $findGroupId = Image::findOrFail($value['id'])->imageGroup->id;
                if( !is_null($groupId) && $groupId != $findGroupId ) Abort::Error('0040',"has different group id");
                $groupId = $findGroupId;
            }
        }
        return $groupId;
    }
    protected function fileTypeCheck($inputFile){
        foreach( $inputFile as $key => $value ){
            $fileType = $this->getFileType($value);
            $inputFile[$key]['type'] = $fileType;
        }
        return $inputFile;
    }
    protected function isBase64($file){
        $explodeBase64 = explode('data:image/jpeg;base64,',$file);
        return count($explodeBase64) > 1;
    }
    protected function isUrl($file){
        $explodeBase64 = explode('http',$file);
        return count($explodeBase64) > 1;
    }
    protected function isGrouping($inputFile){
        return !isset($inputFile['file']);
    }
    // checker function

    // set data
    private function setBasicVariable($model,$inputFile){
        $this->model = $model;
        $this->inputFile = $inputFile;
    }
    protected function setToArray($inputFile){
        if( isset($inputFile['file']) ) // if file is alone... grep into array
            $checker[] = $inputFile;
        return isset($checker) ? $checker : $inputFile;
    }
    protected function setRandomFileName(){
        return Str::random(30);
    }
    // set data

    // get data function
    protected function getResizeImages($file){
        $imageMake = Intervention::make($file);
        $image = [];

        foreach( $this->responsiveResolution as $key => $value ){
            $image[$value] = $imageMake->widen((int)$value)->stream('jpg',100);
        }

        return $image;
    }
    protected function getInternalS3Url($path){
        $explode = explode($this->storagePath,$path);
        return $explode[1];
    }
    protected function getFileType($value){
        $file = $value['file'];
        if( $this->isBase64($file) ){ return "base64"; }
        else if( $this->isUrl($file) ){ return "url"; }
        else if( is_null($file) ){ return null; }
        else{ Abort::Error('0050',"Unknown file data"); }
    }
    protected function getModelName($model){
        $explode =  explode('\\',strtolower(get_class($model)));
        return end($explode);
    }
    protected function getModelId($model){
        return $model->id;
    }
    public function getId(){
        if( $this->nullCheck ) return null;
        return $this->createModel['id'];
    }
    // get data function
}