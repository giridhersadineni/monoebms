<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'semester', 'course', 'exam_type', 'month', 'year',
        'status', 'scheme', 'revaluation_open',
        'fee_regular', 'fee_supply_upto2', 'fee_improvement', 'fee_fine',
    ];

    protected $casts = [
        'revaluation_open' => 'boolean',
        'semester'         => 'integer',
        'year'             => 'integer',
        'fee_regular'      => 'integer',
        'fee_supply_upto2' => 'integer',
        'fee_improvement'  => 'integer',
        'fee_fine'         => 'integer',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(ExamEnrollment::class);
    }

    public function feeRules(): HasMany
    {
        return $this->hasMany(ExamFeeRule::class);
    }

    // Status constants
    const STATUS_NOTIFY    = 'NOTIFY';
    const STATUS_RUNNING   = 'RUNNING';
    const STATUS_REVALOPEN = 'REVALOPEN';
    const STATUS_CLOSED    = 'CLOSED';

    /** Next status in the admin cycle: NOTIFY → RUNNING → CLOSED → NOTIFY */
    public function nextStatus(): string
    {
        return match ($this->status) {
            self::STATUS_NOTIFY    => self::STATUS_RUNNING,
            self::STATUS_RUNNING   => self::STATUS_CLOSED,
            self::STATUS_REVALOPEN => self::STATUS_CLOSED,
            default                => self::STATUS_NOTIFY,
        };
    }

    /** True when students may submit enrollment forms (NOTIFY = enrollment window) */
    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_NOTIFY);
    }

    /** True when the exam is in progress and hall tickets can be downloaded */
    public function canDownloadHallTicket(): bool
    {
        return $this->status === self::STATUS_RUNNING;
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

    public function calculateFee(int $subjectCount, ?string $course = null, ?string $groupCode = null): int
    {
        $rule = $this->resolveFeeRule($course, $groupCode);

        $feeRegular   = $rule?->fee_regular      ?? $this->fee_regular;
        $feeSupply    = $rule?->fee_supply_upto2  ?? $this->fee_supply_upto2;
        $feeImprove   = $rule?->fee_improvement   ?? $this->fee_improvement;
        $fine         = $rule?->fee_fine          ?? $this->fee_fine ?? 0;

        // Improvement exams: always per-subject, no threshold
        if ($this->exam_type === 'improvement' && $feeImprove) {
            return $feeImprove * max(1, $subjectCount) + $fine;
        }

        // Supply exams: per-subject fee for ≤2 papers; full regular fee for 3+
        if ($this->exam_type === 'supplementary' && $feeSupply !== null && $subjectCount > 0 && $subjectCount <= 2) {
            return $feeSupply * $subjectCount + $fine;
        }

        return ($feeRegular ?? 0) + $fine;
    }

    /**
     * Return the resolved fee components for a given student course/group,
     * falling back to exam-level values where no rule matches.
     */
    public function resolvedFeeComponents(?string $course, ?string $groupCode): array
    {
        $rule = $this->resolveFeeRule($course, $groupCode);
        return [
            'fee_regular'      => $rule?->fee_regular      ?? $this->fee_regular      ?? 0,
            'fee_supply_upto2' => $rule?->fee_supply_upto2 ?? $this->fee_supply_upto2 ?? 0,
            'fee_improvement'  => $rule?->fee_improvement  ?? $this->fee_improvement  ?? 0,
            'fee_fine'         => $rule?->fee_fine         ?? $this->fee_fine         ?? 0,
        ];
    }

    private function resolveFeeRule(?string $course, ?string $groupCode): ?ExamFeeRule
    {
        if (! $this->exists) {
            return null;
        }

        /** @var Collection $rules */
        $rules = $this->feeRules;

        // 1. Exact match: course + group
        if ($course && $groupCode) {
            $r = $rules->where('course', $course)->where('group_code', $groupCode)->first();
            if ($r) return $r;
        }
        // 2. Course only (group_code IS NULL in DB)
        if ($course) {
            $r = $rules->where('course', $course)->whereNull('group_code')->first();
            if ($r) return $r;
        }
        // 3. Exam-wide default (both null)
        return $rules->whereNull('course')->whereNull('group_code')->first();
    }
}
