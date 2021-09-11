<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dislike extends Model
{
    public function code()
    {
        return $this->belongsTo(CompanyCode::class);
    }
}
