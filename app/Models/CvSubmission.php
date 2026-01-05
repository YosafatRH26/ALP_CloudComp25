<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvSubmission extends Model
{
    use HasFactory;

    protected $table = 'cv_submissions';

    protected $fillable = [
        'user_id',
        'stored_path',
        'original_filename',
        'input_mode',
        'language',
        'analysis_mode',
        'is_submitted_to_admin',
        'submitted_at',
    ];

    protected $casts = [
        'is_submitted_to_admin' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function analysis()
    {
        // Relasi ke CvAnalysis
        return $this->hasOne(CvAnalysis::class, 'cv_submission_id');
    }
}