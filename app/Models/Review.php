<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Abort;

class Review extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'product_id' => 'string',
        'image_id' => 'string',
        'image_group_id' => 'string',
    ];

    // for apply
    public function applyProduct($user){
        $this->applyDuplicateCheck($user);
        $this->giveProduct()->create([
            "apply_user_id" => $user->id,
            "accept_user_id" => $this->user_id,
        ]);
    }

    // for accept
    public function acceptUser($user_id){
        $acceptApply = $this->giveProduct()->whereapply_user_id($user_id)->firstOrFail();
        $this->reviewStockCheck();
        $this->acceptStatusCheck($acceptApply);

        $acceptApply->save();
        $this->save();
    }
    public function applyDuplicateCheck($user){
        if($this->giveProduct()->whereapply_user_id($user->id)->count() ) Abort::Error('0040','Already Applied');
    }
    public function acceptStatusCheck($acceptApply){
        if( $acceptApply->give_status_code == "0401" ) Abort::Error('0040','Already Accepted Apply');
        if( $acceptApply->give_status_code == "0402" ) Abort::Error('0040','Already Failed Apply');
        if( $acceptApply->give_status_code != "0400" ) Abort::Error('0040','Unknown Status Error');
        $acceptApply->give_status_code = "0401";
    }
    public function reviewStockCheck(){
        if( $this->give_stock <= 0 ) Abort::Error('0040','This review have no stock');
        $this->give_stock = $this->give_stock - 1;
    }



    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function option()
    {
        return $this->belongsTo('App\Models\Option','sku','sku');
    }



    public function image()
    {
        return $this->hasOne('App\Models\Image','id','image_id');
    }
    public function imageGroup()
    {
        return $this->hasOne('App\Models\ImageGroup','id','image_group_id');
    }


    // get reference data
    // hasMany('remote_table_column_name','local_column_name');
    public function answer()
    {
        return $this->hasMany('App\Models\ReviewAnswer','review_id','id');
    }
    public function giveProduct()
    {
        return $this->hasMany('App\Models\GiveProduct','review_id','id');
    }
}
