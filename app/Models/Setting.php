<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $fillable = ['title','content','seller_id'];
    protected $casts = [
        'content' => 'array'
    ];
    public static function getSetting($seller_id, $title)
    {
        return self::where('seller_id', $seller_id)
                ->where('title', $title)
                ->first();
    }
}
