<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = ['code', 'name', 'total_semesters', 'is_active'];

    protected $casts = [
        'total_semesters' => 'integer',
        'is_active'       => 'boolean',
    ];

    public function groups(): HasMany
    {
        return $this->hasMany(CourseGroup::class)->orderBy('code');
    }

    public function activeGroups(): HasMany
    {
        return $this->hasMany(CourseGroup::class)->where('is_active', true)->orderBy('code');
    }
}
