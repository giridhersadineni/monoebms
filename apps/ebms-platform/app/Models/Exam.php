<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'semester', 'course', 'exam_type', 'month', 'year',
        'status', 'scheme', 'revaluation_open', 'fee_json',
    ];

    protected $casts = [
        'fee_json'         => 'array',
        'revaluation_open' => 'boolean',
        'semester'         => 'integer',
        'year'             => 'integer',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(ExamEnrollment::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeForCourse($query, string $course)
    {
        return $query->where('course', $course);
    }

    public function getMonthNameAttribute($value): string
    {
        if (! $value) {
            return '—';
        }
        if (is_numeric($value)) {
            $d = \DateTime::createFromFormat('!m', (int) $value);
            return $d ? $d->format('F') : '—';
        }
        // Legacy string value e.g. "NOVEMBER"
        return ucfirst(strtolower($value));
    }

    public function getFeeForCourse(string $course, bool $above2 = false): int
    {
        $fees = $this->fee_json ?? [];
        $key  = strtoupper($course);

        if (! isset($fees[$key])) {
            return (int) ($fees['default'] ?? 0);
        }

        return (int) ($above2 ? ($fees[$key]['above_2_sem'] ?? $fees[$key]['regular'] ?? 0) : ($fees[$key]['regular'] ?? 0));
    }
}
