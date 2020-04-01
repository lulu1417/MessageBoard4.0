<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'content', 'user_id',
    ];

    protected $hidden = [
        'updated_at'
    ];

    function user(){
        return $this->belongsTo(User::class);
    }

    function comments(){
        return $this->hasMany(Comment::class)->with(['user', 'replies'])->orderBy('created_at', 'desc');
    }

    function likes(){

        return $this->hasMany(Like::class)->with('user')->orderBy('created_at', 'desc');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
