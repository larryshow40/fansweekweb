<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public function code()
    {
        return $this->belongsTo(CompanyCode::class);
    }    
}
