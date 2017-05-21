<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

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
