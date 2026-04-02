<?php

namespace Database\Seeders;

use App\Models\FeatureFlag;
use Illuminate\Database\Seeder;

class FeatureFlagSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            ['name' => 'enrollment',   'label' => 'Enrollment'],
            ['name' => 'hall_ticket',  'label' => 'Hall Ticket Download'],
            ['name' => 'results',      'label' => 'Results'],
            ['name' => 'revaluation',  'label' => 'Revaluation'],
            ['name' => 'profile',      'label' => 'Profile'],
        ];

        foreach ($features as $feature) {
            FeatureFlag::firstOrCreate(['name' => $feature['name']], [
                'label'   => $feature['label'],
                'enabled' => true,
                'message' => null,
            ]);
        }
    }
}
