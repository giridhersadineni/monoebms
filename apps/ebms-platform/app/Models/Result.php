<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    protected $fillable = [
        'enrollment_id', 'subject_id', 'hall_ticket', 'exam_id',
        'ext_marks', 'int_marks', 'total_marks', 'grade', 'result',
        'credits', 'gp_value', 'gp_credits', 'is_malpractice',
        'is_absent_ext', 'is_absent_int', 'passed_by_floatation',
        'marks_secured', 'etotal', 'itotal', 'floatation_marks', 'float_deduct',
        'fl_scriptcode', 'moderation_marks', 'ac_marks', 'is_moderated',
        'part', 'semester', 'attempts',
    ];

    protected $casts = [
        'is_malpractice'       => 'boolean',
        'is_absent_ext'        => 'boolean',
        'is_absent_int'        => 'boolean',
        'passed_by_floatation' => 'boolean',
        'credits'              => 'decimal:1',
        'gp_value'             => 'decimal:2',
        'gp_credits'           => 'decimal:2',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(ExamEnrollment::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function scopeForHallTicket($query, string $hallTicket)
    {
        return $query->where('hall_ticket', $hallTicket);
    }

    public function scopeForExam($query, int $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopePassed($query)
    {
        return $query->where('result', 'P');
    }

    // Exclude EX grade papers from GPA calculation
    public function scopeExcludeGradeEx($query)
    {
        return $query->where('grade', '!=', 'EX');
    }
}
