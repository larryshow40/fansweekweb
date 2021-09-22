<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyCodeComment extends Model
{
    protected $fillable = ['user_id', 'company_code_id', 'parent_id', 'body'];

    /**
     * The belongs to Relationship
     *
     * @var array
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The has Many Relationship
     *
     * @var array
     */
    public function replies()
    {
        return $this->hasMany(CompanyCodeComment::class, 'parent_id');
    }
}
