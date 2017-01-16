<?php
namespace App\Traits;

use App\Models\Option;
use App\Models\Sku;
use Abort;
use Log;

trait OptionControllTraits{


    private function bindOption($option){
        $response = [];
        foreach ($option as $key => $value) {
            $response[] = array(
                "skuId" => $value->sku_id,
                "sku" => Sku::find($value->sku_id)->value("sku"),
                "name" => array(
                    "origin" => $value->original_name,
                    "zh" => $value->chinese_name,
                ),
                "price" => $value->price
            );
        }
        return $response;
    }
    private function bindOptionZh($option){
        $response = [];
        foreach ($option as $key => $value) {
            $response[] = array(
                "sku" => Sku::find($value->sku_id)->value("sku"),
                "name" => $value->chinese_name,
                "price" => $value->price
            );
        }
        return $response;
    }

    private function setOption($options){
        $result = [];
        $index = 0;
        foreach ($options as $key => $option) {
            $result[] = array(
                "market_id" => $this->market_id,
                "product_id" => $this->product->id,
                "sku_id" => $this->createSku($option,$index),
                "original_name" => $option["name"]["origin"],
                "chinese_name" => $option["name"]["zh"],
                // "korean_name" => $option["name"]["ko"],
                // "english_name" => $option["name"]["en"],
                "price" => $option["price"],
                // "stock" => $option["stock"],
                // "safe_stock" => $option["safeStock"],
            );
            $index++;
        }
        return $result;
    }
    private function updateOptions($options){
        $this->isDirdyOption($options);
        $checkedArray = [];
        foreach ($options as $key => $value) {
            $targetOption = Option::wheresku_id($value["skuId"])->firstOrFail();
            $targetOption["original_name"] = $value["name"]["origin"];
            $targetOption["chinese_name"] = $value["name"]["zh"];
            $targetOption["price"] = $value["price"];
            if (!$targetOption->save()) Abort::Error("0040","Option Update Fail");

            $targetSku = Sku::whereid($targetOption["sku_id"])->whereproduct_id($this->product->id)->firstOrFail();
            $targetSku["description"] = $value["name"]["origin"];
            if (!$targetSku->save()) Abort::Error("0040","Sku Update Fail");
        }
        return true;
    }
    private function isDirdyOption($options){
        if ( count($this->product->option()->get()) !==  count($options)) Abort::Error("0040","Can not add option at update product");
        return false;
    }
    private function createSku($option,$index){
        $sku = array(
            "market_id" => $this->market_id,
            "product_id" => $this->product->id,
            "sku" =>
                "MK".$this->market_id.
                "CT".$this->product->category_id.
                "DV".$this->product->division_id.
                "ST".$this->product->sector_id.
                "PD".$this->product_id.
                "ID".$index,
            "description" => $option["name"]["origin"],
        );
        $id = Sku::firstOrCreate($sku)->id;
        return $id;
    }
}
 ?>
