<?php

namespace App\Classes;

use Storage;
use Abort;
use Log;

use Illuminate\Support\Str;
use Image;

class FileUpload
{
    private $storage;
    private $tempStorage;
    private $model;
    private $inputFile;
    private $fileExt;
//
//    private $modelName;
//    private $modelId;
//    private $isGroup;
//
//    private $saveFileName;


    public function __construct($model,$inputFile,$ext){
        $this->storage = Storage::disk('s3');
        $this->tempStorage = $tempStorage = public_path().'/tmp/';
        $this->model = $model;
        $this->inputFile = $inputFile;
        $this->fileExt = $ext;
        $this->modelName = $this->getModelName($this->model);
        $this->modelId = $this->getModelId($this->model);
        $this->isGroup = $this->isGrouping($this->inputFile);
        $this->inputFile = $this->setToArray($this->inputFile);
        $this->inputFile = $this->fileTypeCheck($this->inputFile);
        $this->inputFile = $this->moveToTempFiles($this->inputFile);

        Log::info($this->inputFile);

        $this->result = $this->getResult();
    }

    private function moveToTempFiles($inputFile){
        foreach($inputFile as $key => $value){
            if( $value['type'] == 'base64' ){
                $imageData = $this->getResizeBase64($value['file']);

//                $newName = $this->setRandomFileName();
//                $path = $this->tempStorage.$newName;
//                file_put_contents($path, $imageData); // here add resolution divide
//                $inputFile[$key]['tempFile'][] = $newName;
            }
        }
        return $inputFile;
    }

    protected function getResizeBase64($base64){
        $image = Image::make($base64);
        $image->resize(1920,1920)->save($this->tempStorage.'bar1920.jpg',100);
        $image->resize(720,720)->save($this->tempStorage.'bar720.jpg',100);
        $image->resize(360,360)->save($this->tempStorage.'bar360.jpg',100);


        return true;
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


    protected function base64ImageDecode($base64){
        $explodeBase64 = explode('data:image/jpeg;base64,',$base64);
        return base64_decode($explodeBase64[1]);
    }



    protected function getResult(){
        return $this->modelId;
    }

//
//
//    protected function userThumbnailUpload($user,$base64){
//        if( !is_null($base64) ){
//            $imageData = $this->base64ImageDecode($base64);
//            $fileName = $this->setRandomFileName('jpg');
//            $path = $this->setTempFilePath($fileName);
//            file_put_contents($path, $imageData);
//            $uploadPath = 'user/'.$user->id.'/'.$fileName;
//            $this->storage->put($uploadPath, file_get_contents($path), 'public');
//            unlink($path);
//            return env('S3_PATH').$uploadPath;
//        }else{
//            return env('S3_PATH').env('USER_DEFAULT_IMG_URL');
//        }
//    }
//
//    protected function setRandomFileName($ext){
//        return mt_rand(1000000,9000000).'.'.$ext;
//    }
//    protected function setTempFilePath($fileName){
//        return public_path().'/tmp/'.$fileName;
//    }
}