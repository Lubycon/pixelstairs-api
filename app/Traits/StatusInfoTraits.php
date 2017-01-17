<?php
namespace App\Traits;

use Log;
use Carbon\Carbon;

trait StatusInfoTraits{
    public function statusUpdate($request,$status_code){
        if( !$this->isSameStatus($status_code) ){
            $this->statusPermissionCheck($request);
            $this->forConfirm($status_code);
            return $status_code;
        }
        return $this->product->status_code;
    }
    public function forConfirm($status_code){
        if( $status_code == '0301' ){
            $sale = $this->productSale($this->product);
            Log::info($sale);
            $this->startDateUpdate();
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
        if( $this->product->status_code == $status_code ){
            return true;
        }
        return false;
    }
    private function startDateUpdate(){
        $this->product->start_date = Carbon::now()->toDateTimeString();
    }
}
?>