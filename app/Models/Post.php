<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'author_id', 'category_id'];

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function author(){
        return $this->belongsTo(User::class,'author_id');
    }
}
