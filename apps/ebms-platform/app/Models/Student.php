<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class Student extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'hall_ticket', 'dob', 'dost_id', 'name', 'father_name', 'mother_name',
        'email', 'phone', 'aadhaar', 'gender', 'caste', 'sub_caste',
        'course', 'course_name', 'group_code', 'medium', 'semester',
        'admission_year', 'scheme', 'address', 'address2', 'mandal',
        'city', 'state', 'pincode', 'challenged_quota', 'apaar_id',
        'ssc_hall_ticket', 'inter_hall_ticket', 'is_active',
        'photo_path', 'signature_path',
    ];

    protected $hidden = ['aadhaar', 'dob', 'dost_id'];

    protected $casts = [
        'dob'       => 'date',
        'is_active' => 'boolean',
        'semester'  => 'integer',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(ExamEnrollment::class);
    }

    public function grade(): HasOne
    {
        return $this->hasOne(Grade::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByHallTicket($query, string $hallTicket)
    {
        return $query->where('hall_ticket', $hallTicket);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) return null;
        $url  = Storage::disk('public')->url($this->photo_path);
        $full = storage_path('app/public/' . $this->photo_path);
        return $url . (file_exists($full) ? '?v=' . filemtime($full) : '');
    }

    public function getSignatureUrlAttribute(): ?string
    {
        if (!$this->signature_path) return null;
        $url  = Storage::disk('public')->url($this->signature_path);
        $full = storage_path('app/public/' . $this->signature_path);
        return $url . (file_exists($full) ? '?v=' . filemtime($full) : '');
    }
}
