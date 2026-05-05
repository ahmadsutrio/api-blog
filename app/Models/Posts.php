<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Posts extends Model
{
    protected $table = "posts";
    public $fillable =[
        'title',
        'slug',
        'category',
        'content',
        'image',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class,'user_id','id');
    }
}
