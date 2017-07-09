<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

/**
 * App\Models\Quote
 *
 * @mixin \Eloquent
 * @property string $id
 * @property string $category
 * @property string $author
 * @property string $message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Quote whereAuthor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Quote whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Quote whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Quote whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Quote whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Quote whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Quote whereUpdatedAt($value)
 */
class Quote extends Model
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = ['author','message','category'];

    public static function getRandomQuoteByCategory($category){
        return self::where('category',$category)
            ->orderBy(DB::raw('RAND()'))
            ->firstOrFail();
    }

    public function getData(){
        return [
            "author" => $this->author,
            "message" => $this->message,
            "category" => $this->category,
        ];
    }
}
