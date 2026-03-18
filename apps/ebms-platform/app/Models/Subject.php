<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'code', 'name', 'course', 'group_code', 'medium', 'semester',
        'paper_type', 'elective_group', 'part', 'scheme',
    ];

    protected $casts = [
        'semester' => 'integer',
        'part'     => 'integer',
    ];

    public function enrollmentSubjects(): HasMany
    {
        return $this->hasMany(ExamEnrollmentSubject::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function scopeForCourse($query, string $course)
    {
        return $query->where('course', $course);
    }

    public function scopeForSemester($query, int $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeCompulsory($query)
    {
        return $query->where('paper_type', 'compulsory');
    }

    public function scopeElective($query)
    {
        return $query->where('paper_type', 'elective');
    }

    public function scopeElectiveGroup($query, string $group)
    {
        return $query->where('elective_group', $group);
    }
}
