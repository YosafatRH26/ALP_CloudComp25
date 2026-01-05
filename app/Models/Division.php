<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function skills()
    {
        return $this->hasMany(DivisionSkill::class);
    }
}
