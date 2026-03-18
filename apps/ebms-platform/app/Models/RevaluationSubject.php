<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevaluationSubject extends Model
{
    public $timestamps = false;

    protected $fillable = ['revaluation_enrollment_id', 'subject_id', 'subject_code'];

    public function revaluationEnrollment(): BelongsTo
    {
        return $this->belongsTo(RevaluationEnrollment::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
