<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $fillable = ['name', 'label', 'enabled', 'message'];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public static function isEnabled(string $name): bool
    {
        $flag = static::firstWhere('name', $name);
        return $flag?->enabled ?? true;
    }
}
