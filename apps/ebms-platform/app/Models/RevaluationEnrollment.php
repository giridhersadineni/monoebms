<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RevaluationEnrollment extends Model
{
    protected $fillable = [
        'original_enrollment_id', 'exam_id', 'student_id', 'hall_ticket',
        'fee_amount', 'fee_paid_at', 'challan_number', 'status',
    ];

    protected $casts = [
        'fee_paid_at' => 'datetime',
        'fee_amount'  => 'integer',
    ];

    public function originalEnrollment(): BelongsTo
    {
        return $this->belongsTo(ExamEnrollment::class, 'original_enrollment_id');
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(RevaluationSubject::class);
    }
}
