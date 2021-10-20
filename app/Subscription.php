<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $casts = [
        'authorization' => 'object',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
