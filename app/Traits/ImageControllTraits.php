<?php
namespace App\Traits;

use App\Models\Image;
use Abort;
use Log;

trait ImageControllTraits
{
    public function createExternalImage($image){
        return [
                "index" => $image['index'],
                "url" => $image['file'],
        ];
    }
    public function updateExternalImage($product,$image){
        $result = $product->image->url->update(["url" => $image['file']]);
        return $result;
    }

    public function createExternalImageArray($image){
        if( isset($image['file']) )$image = [$image];
        $result = [];
        foreach( $image as $value ){
            $result[] = $image = new Image([
                "index" => $value['index'],
                "url" => $value['file'],
            ]);
        }
        return $result;
    }
    public function updateExternalImageArray($product,$image){
        $originalImageIds = $product->imageGroup->image->pluck('id')->toArray();
        $currentImageIds = [];
        $result = [];
        foreach( $image as $value ){
            if( isset($value['id']) && $value['deleted'] == false ){
                $currentImageIds[] = $value['id'];
                Image::findOrFail($value['id'])->update([
                    "index" => $value['index'],
                    "url" => $value['file'],
                ]);
            }else if( $value['deleted'] == false ){
                $result[] = new Image([
                    "index" => $value['index'],
                    "url" => $value['file'],
                ]);
            }
        }
        $diff = array_diff($originalImageIds,$currentImageIds);
        $clear = $product->imageGroup->image()->whereIn('id',$diff)->delete();
        return $result;
    }

    public function createImageUploadArray($review,$imageArray){
        $result = [];
        foreach( $imageArray as $value ){
            $result[] = $image = new Image([
                "index" => $value['index'],
                "url" => $this->isBase64File( $value['file'] )
                ? $this->reviewThumbnailUpload($review,$value['file'])
                : $value['file'],
                "is_mitty_own" => true,
            ]);
        }
        return $result;
    }
    public function updateImageUploadArray($review,$imageArray){
        $originalImageIds = $review->imageGroup->image->pluck('id')->toArray();
        $currentImageIds = [];
        $result = [];
        foreach( $imageArray as $value ){

            Log::info( $this->isBase64File( $value['file'] ) );

            if( isset($value['id']) && $value['deleted'] == false ){
                $currentImageIds[] = $value['id'];
                Image::findOrFail($value['id'])->update([
                    "index" => $value['index'],
                    "url" => $this->isBase64File( $value['file'] )
                        ? $this->reviewThumbnailUpload($review,$value['file'])
                        : $value['file'],
                    "is_mitty_own" => true,
                ]);
            }else if( $value['deleted'] == false ){
                $result[] = new Image([
                    "index" => $value['index'],
                    "url" => $this->isBase64File( $value['file'] )
                        ? $this->reviewThumbnailUpload($review,$value['file'])
                        : $value['file'],
                    "is_mitty_own" => true,
                ]);
            }
        }
        $diff = array_diff($originalImageIds,$currentImageIds);
        $clear = $review->imageGroup->image()->whereIn('id',$diff)->delete();
        // storage image delete source needed
        return $result;
    }

    function isBase64File($s){
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) return false;
        $decoded = base64_decode($s, true);
        if(false === $decoded) return false;
        if(base64_encode($decoded) != $s) return false;
        return true;
    }
}
 ?>
