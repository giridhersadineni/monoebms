<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id', 'hall_ticket', 'memo_no',
        'part1_cgpa', 'part2_cgpa', 'all_cgpa',
        'part1_division', 'part2_division', 'final_division',
        'part1_subjects', 'part2_subjects', 'remarks',
        'created_by', 'modified_by',
    ];

    protected $casts = [
        'part1_cgpa' => 'decimal:2',
        'part2_cgpa' => 'decimal:2',
        'all_cgpa'   => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
