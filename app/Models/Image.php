<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Image
 *
 * @property int $id
 * @property string $url
 * @property int $index
 * @property bool $is_pixel_own
 * @property int $image_group_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ImageGroup[] $imageGroup
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereImageGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereIndex($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereIsPixelOwn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image whereUrl($value)
 * @mixin \Eloquent
 */
class Image extends Model {

	use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'image_group_id' => 'string',
    ];
	protected $dates = ['deleted_at'];
	protected $fillable = array('url', 'index', 'is_pixel_own', 'image_group_id');

    public function getObject(){
        return [
            "id" => $this->id,
            "file" => $this->url,
            "index" => $this->index,
            "isPixelOwn" => $this->is_pixel_own,
            "deleted" => false
        ];
    }

	public function imageGroup()
	{
		return $this->belongsToMany('App\Models\ImageGroup');
	}

}