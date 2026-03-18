<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamEnrollmentSubject extends Model
{
    public $timestamps = false;

    protected $table = 'exam_enrollment_subjects';

    protected $fillable = ['enrollment_id', 'subject_id', 'subject_code', 'subject_type'];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(ExamEnrollment::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
