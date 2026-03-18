<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gpa extends Model
{
    protected $table = 'gpas';

    protected $fillable = [
        'enrollment_id', 'hall_ticket', 'exam_id',
        'sgpa', 'cgpa', 'total_marks', 'result', 'processed_at',
    ];

    protected $casts = [
        'sgpa'         => 'decimal:2',
        'cgpa'         => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(ExamEnrollment::class);
    }
}
