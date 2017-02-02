<?php
namespace App\Traits;

use Storage;
use Abort;
use Log;

trait S3StorageControllTraits
{

    public function reviewThumbnailUpload($review,$base64){
        if( !is_null($base64) ){
            $imageData = $this->base64ImageDecode($base64);
            $fileName = $this->setRandomFileName('jpg');
            $path = $this->setTempFilePath($fileName);
            file_put_contents($path, $imageData);
            $uploadPath = 'review/'.$review->id.'/'.$fileName;
            Storage::disk('s3')->put($uploadPath, file_get_contents($path), 'public');
            unlink($path);
            return $uploadPath;
        }else{
            Abort::Error('0040','not base64');
        }
    }

    public function userThumbnailUpload($user,$base64){
        if( !is_null($base64) ){
            $imageData = $this->base64ImageDecode($base64);
            $fileName = $this->setRandomFileName('jpg');
            $path = $this->setTempFilePath($fileName);
            file_put_contents($path, $imageData);
            $uploadPath = 'user/'.$user->id.'/'.$fileName;
            Storage::disk('s3')->put($uploadPath, file_get_contents($path), 'public');
            unlink($path);
            return $uploadPath;
        }else{
            return env('S3_PATH').env('USER_DEFAULT_IMG_URL');
        }
    }

    public function base64ImageDecode($base64){
        $explodeBase64 = explode('data:image/jpeg;base64,',$base64);
        return base64_decode($explodeBase64[1]);
    }
    public function setRandomFileName($ext){
        return mt_rand(1000000,9000000).'.'.$ext;
    }
    public function setTempFilePath($fileName){
        return public_path().'/tmp/'.$fileName;
    }



}
 ?>
