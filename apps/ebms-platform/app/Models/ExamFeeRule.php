<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamFeeRule extends Model
{
    protected $fillable = [
        'exam_id', 'course', 'group_code',
        'fee_regular', 'fee_supply_upto2', 'fee_improvement', 'fee_fine',
    ];

    protected $casts = [
        'fee_regular'      => 'integer',
        'fee_supply_upto2' => 'integer',
        'fee_improvement'  => 'integer',
        'fee_fine'         => 'integer',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
