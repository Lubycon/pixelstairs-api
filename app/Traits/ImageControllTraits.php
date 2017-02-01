<?php
namespace App\Traits;

use App\Models\Image;
use Abort;
use Log;

trait ImageControllTraits
{
    public function createExternalImageArray($imageArray){
        $result = [];
        foreach( $imageArray as $value ){
            $result[] = $image = new Image([
                "index" => $value['index'],
                "url" => $value['file'],
            ]);
        }
        Log::info($result);
        return $result;
    }
    public function updateExternalImageArray($product,$imageArray){
        $originalImageIds = $product->imageGroup->image->pluck('id')->toArray();
        $currentImageIds = [];
        $result = [];
        foreach( $imageArray as $value ){
            if( isset($value['id']) && !$value['deleted'] ){
                $currentImageIds[] = $value['id'];
                Image::findOrFail($value['id'])->update([
                    "index" => $value['index'],
                    "url" => $value['file'],
                ]);
            }else{
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
}
 ?>
