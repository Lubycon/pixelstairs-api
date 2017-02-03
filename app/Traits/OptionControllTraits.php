<?php
namespace App\Traits;

use App\Models\Image;
use App\Models\Option;
use App\Models\OptionCollection;
use App\Models\OptionKey;
use Abort;
use Log;

trait OptionControllTraits
{

    public function setNewOption($options,$safeStock,$optionCollection)
    {
        $result = [];
        $index = 0;
        foreach ($options as $key => $option) {
            $result[] = new Option([
                "product_id" => $this->product->id,
                "sku" => $this->createSku($index),
                "price" => $option["price"],
                "stock" => $option["stock"],
                "safe_stock" => Option::absoluteSafeStockCkeck($safeStock),
                "translate_name_id" => $this->createTranslateName($option['name'])['id'],
                "image_id" => Image::create($this->createExternalImage( $option["thumbnailUrl"] ))['id'],
                "option_collection_id" => $optionCollection['id'],
            ]);
            $index++;
        }
        return $result;
    }

    private function updateOptions($options,$safeStock,$optionCollection){
        $this->isDirtyOption($options);
        foreach ($options as $key => $option) {
            $originalSku = $this->product->option[$key]->sku;
            if($originalSku !== $option['sku']) Abort::Error('0040',"Diff SKU");
                $this->product->option[$key]->price = $option["price"];
                $this->product->option[$key]->stock = $option["stock"];
                $this->product->option[$key]->safe_stock = Option::absoluteSafeStockCkeck($safeStock);
                $this->product->option[$key]->translate_name_id = $this->createTranslateName($option['name'])['id'];
                $this->product->option[$key]->image_id = Image::create($this->createExternalImage( $option["thumbnailUrl"] ))['id'];
                $this->product->option[$key]->option_collection_id = $optionCollection['id'];
                if (!$this->product->option[$key]->update()) Abort::Error("0040","Option Update Fail");
        }
        return true;
    }
    private function isDirtyOption($options){
        if ( count($this->product->option()->get()) !==  count($options)) Abort::Error("0040","Can not add option at update product");
        return;
    }


    public function createOptionCollection($optionKeys)
    {
        $optionCollection = array();
        $i = 0;
        foreach ($optionKeys as $key => $value) {
            $optionCollection['option_key_id_' . $i] = OptionKey::firstOrCreate($this->relationTranslateName($value))['id'];
            $i++;
        }
        return OptionCollection::firstOrCreate($optionCollection);
    }

    private function createSku($index)
    {
        $sku = "MK" . $this->market_id .
            "CT" . $this->product->category_id .
            "DV" . $this->product->division_id .
            "ST" . $this->product->sector_group_id .
            "PD" . $this->product->id .
            "ID" . $index;
        return $sku;
    }

    private function getSkuDetailInfo($sku)
    {
        $matches = array();
        preg_match('/MK(.*?)CT/s', $sku, $matches);
        $info['market_id'] = $matches[1];
        preg_match('/CT(.*?)DV/s', $sku, $matches);
        $info['category_id'] = $matches[1];
        preg_match('/DV(.*?)ST/s', $sku, $matches);
        $info['division_id'] = $matches[1];
        preg_match('/ST(.*?)PD/s', $sku, $matches);
        $info['section_group_id'] = $matches[1];
        preg_match('/PD(.*?)ID/s', $sku, $matches);
        $info['product_id'] = $matches[1];
        $explode = explode('ID',$sku);
        $info['index'] = $explode[1];

        return $info;
    }

}
 ?>
