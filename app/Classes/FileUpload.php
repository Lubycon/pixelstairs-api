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
    private $storage;
    private $tempStorage;
    private $ownCheckers;
    private $model;
    private $inputFile;
    private $fileExt;
    private $responsiveResolution;
    private $nullCheck = false;

    public function __construct($model,$inputFile,$ext){


        if(is_null($inputFile)) return $this->nullCheck = true;
        $this->storage = Storage::disk(config('filesystems.default'));
        $this->responsiveResolution = config('filesystems.responsive_resolution');
        $this->tempStorage = config('filesystems.temp_storage');
        $this->ownCheckers = [
            "snake" => config('filesystems.own_checker'),
            "camel" => camel_case(config('filesystems.own_checker')),
        ];

        $this->model = $model;
        $this->inputFile = $inputFile;
        $this->fileExt = $ext;
        $this->modelName = $this->getModelName($this->model);
        $this->modelId = $this->getModelId($this->model);
        $this->isGroup = $this->isGrouping($this->inputFile);
        $this->groupModel = $this->createImageGroupModel($this->inputFile);
        $this->inputFile = $this->setToArray($this->inputFile);
        $this->inputFile = $this->fileTypeCheck($this->inputFile);
        $this->inputFile = $this->uploadS3($this->inputFile);
        $this->createModel = $this->createImageModel($this->inputFile);
        return $this->getResult();
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
    protected function createImageModel($inputFile){
        $modelId = null;
        $images = [];
        foreach($inputFile as $key => $value ){
            $isMittyOwn = $this->isMittyOwn($value);
            if( isset($value['id']) ){
                $images[] = Image::findOrFail($value['id'])->update([
                    "index" => $value['index'],
                    "url" => $value['url'],
                    $this->ownCheckers['snake'] => $isMittyOwn,
                    "image_group_id" => $this->isGroup ? $this->groupModel['id'] : null,
                ]);
            }else{
                $images[] = Image::create([
                    "index" => $value['index'],
                    "url" => $value['url'],
                    $this->ownCheckers['snake'] => $isMittyOwn,
                    "image_group_id" => $this->isGroup ? $this->groupModel['id'] : null,
                ]);
            }
        }
        return $this->isGroup ? $this->groupModel : $images[0] ;
    }

    protected function isMittyOwn($fileObj){
        $isMittyOwn = null;
        if( isset($fileObj[$this->ownCheckers['camel']]) ){
            $isMittyOwn = $fileObj[$this->ownCheckers['camel']] ? true : false ;
        }else if( $fileObj['type'] == 'base64' ){
            $isMittyOwn = true;
        }else{
            $isMittyOwn = false;
        }
        return $isMittyOwn;
    }

    protected function createImageGroupModel($inputFile){
        if( $this->isGroup ){
            if( $groupId = $this->findGroupExist($inputFile) ){
                $model = ImageGroup::findOrFail($groupId);
            }else{
                $model = ImageGroup::create([
                    "model_name" => $this->modelName,
                ]);
            }
        }
        return isset($model) ? $model : NULL;
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
    protected function responsiveUploadUrl($file){
        $image = $this->getResizeImages($file);
        $uploadPath = $this->modelName.'/'.$this->modelId.'/'.$this->setRandomFileName();
        foreach($image as $key => $value){
            $this->storage->getDriver()->getAdapter()->getClient()->upload(
                env('S3_BUCKET'), // upload bucket
                $uploadPath.$key, $value, // upload path.file name
                'public-read', // permission
                ['params' => [ // metadata
                    'ContentType' => 'image/jpeg',
                ]]);
        }
        return env('S3_PATH').$uploadPath;
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
    protected function getInternalS3Url($path){
        $explode = explode(env('S3_PATH'),$path);
        return $explode[1];
    }

    protected function getResizeImages($file){
        $imageMake = Intervention::make($file);
        $image = [];

        foreach( $this->responsiveResolution as $key => $value ){
            $image[$value] = $imageMake->widen((int)$value)->stream('jpg',100);
        }

        return $image;
    }
    protected function setToArray($inputFile){
        if( !$this->isGroup ) $checker[] = $inputFile;
        return isset($checker) ? $checker : $inputFile;
    }
    protected function fileTypeCheck($inputFile){
        foreach( $inputFile as $key => $value ){
            $fileType = $this->getFileType($value);
            $inputFile[$key]['type'] = $fileType;
        }
        return $inputFile;
    }
    protected function getFileType($value){
        $file = $value['file'];
        if( $this->isBase64($file) ){ return "base64"; }
        else if( $this->isUrl($file) ){ return "url"; }
        else if( is_null($file) ){ return null; }
        else{ Abort::Error('0050',"Unknown file data"); }
    }
    protected function isBase64($file){
        $explodeBase64 = explode('data:image/jpeg;base64,',$file);
        return count($explodeBase64) > 1;
    }
    protected function isUrl($file){
        $explodeBase64 = explode('http',$file);
        return count($explodeBase64) > 1;
    }
    protected function getModelName($model){
        $explode =  explode('\\',strtolower(get_class($model)));
        return end($explode);
    }
    protected function getModelId($model){
        return $model->id;
    }
    protected function setRandomFileName(){
        return Str::random(30);
    }
    protected function isGrouping($inputFile){
        return !isset($inputFile['file']);
    }
    public function getResult(){
        if( $this->nullCheck ) return null;
        return $this->createModel['id'];
    }
}