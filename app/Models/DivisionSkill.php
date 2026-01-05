<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivisionSkill extends Model
{
    protected $fillable = [
        'division_id',
        'skill_name',
        'importance_level',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
