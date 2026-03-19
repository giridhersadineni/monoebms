<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseGroup extends Model
{
    protected $fillable = ['course_id', 'code', 'name', 'mediums', 'schemes', 'is_active'];

    protected $casts = [
        'mediums'   => 'array',
        'schemes'   => 'array',
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
