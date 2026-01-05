<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvSubmission extends Model
{
    use HasFactory;

    protected $table = 'cv_submissions';

    // PERBAIKAN: Pastikan 'is_submitted_to_admin' dan 'submitted_at' ada di sini!
    protected $fillable = [
        'user_id',
        'stored_path',
        'original_filename',
        'input_mode',
        'language',
        'analysis_mode',
        'is_submitted_to_admin', // <--- PENTING
        'submitted_at',          // <--- PENTING
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
        return $this->hasOne(CvAnalysis::class, 'cv_submission_id');
    }
}