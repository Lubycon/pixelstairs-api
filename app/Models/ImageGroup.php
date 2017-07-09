<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ImageGroup
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ImageGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ImageGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ImageGroup whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ImageGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ImageGroup extends Model {

	use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];
	protected $dates = ['deleted_at'];

	public function getObject(){
	    $result = [];
        foreach($this->images as $value){
            $result[] = $value->getObject();
        }
        return $result;
    }

    public function images()
    {
        return $this->hasMany('App\Models\Image','image_group_id','id');
    }
}