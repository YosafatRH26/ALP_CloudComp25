<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvAnalysis extends Model
{
    protected $fillable = [
        'cv_submission_id',
        'resume_score',
        'ats_score',
        'main_skills_json',
        'division_recommendations_json',
        'skill_gap_json',
        'readiness_scores_json',
        'feedback_text',
        'raw_ai_response',
    ];

    protected $casts = [
        'main_skills_json'              => 'array',
        'division_recommendations_json' => 'array',
        'skill_gap_json'                => 'array',
        'readiness_scores_json'         => 'array',
        'raw_ai_response'               => 'array',
    ];

    public function cvSubmission()
    {
        return $this->belongsTo(CvSubmission::class);
    }

    // Cek apakah CV ini milik user tertentu
    public function isOwnedBy($userId)
    {
        return $this->cvSubmission && $this->cvSubmission->user_id == $userId;
    }
}
