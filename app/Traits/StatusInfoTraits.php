<?php
namespace App\Traits;

use Log;
use Carbon\Carbon;

trait StatusInfoTraits{

    private $targetProduct;

    public function statusUpdate($request,$product,$status_code){
        $this->targetProduct = $product;
        if( !$this->isSameStatus($status_code) ){
            $this->statusPermissionCheck($request);
            $this->forConfirm($status_code);
            $this->targetProduct->status_code = $status_code;
        }
        return $this->targetProduct;
    }
    public function forConfirm($status_code){
        if( $status_code == '0301' ){
            //set after haito api
//            $sale = $this->productSale($this->product);
            $this->startDateUpdate();
            return true;
        }else if( $status_code == '0302' ){
            //set after haito api
//            $sale = $this->productSale($this->product);
            $this->endDateUpdate();
            return true;
        }
        return false;
    }
    private function statusPermissionCheck($request){
        $user = $this->getUserByTokenRequestOrFail($request);
        if ($user->grade == "superAdmin" || $user->grade == "admin"){
            return true;
        }
        Abort::Error("0043", "Can not change status");
    }
    private function isSameStatus($status_code){
        if( $this->targetProduct->status_code == $status_code ){
            return true;
        }
        return false;
    }
    private function startDateUpdate(){
        $this->targetProduct->start_date = Carbon::now()->toDateTimeString();
    }
    private function endDateUpdate(){
        $this->targetProduct->end_date = Carbon::now()->toDateTimeString();
    }
}
?>