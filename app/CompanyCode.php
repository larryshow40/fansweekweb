<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyCode extends Model
{
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function dislikes()
    {
        return $this->hasMany(Dislike::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(CompanyCodeComment::class)->whereNull('parent_id');
    }

   
}
