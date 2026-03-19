<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'code'             => 'BA',
                'name'             => 'Bachelor of Arts',
                'total_semesters'  => 6,
                'groups' => [
                    ['code' => 'EHPS',  'name' => 'E.H.PS.',                   'mediums' => ['TM', 'EM']],
                    ['code' => 'HPMLT', 'name' => 'H.P.MLT.',                  'mediums' => ['TM']],
                    ['code' => 'EHPA',  'name' => 'E.H.PA.',                   'mediums' => ['TM', 'EM']],
                    ['code' => 'EPP',   'name' => 'E.P.P.',                    'mediums' => ['TM', 'EM']],
                    ['code' => 'EHPSS', 'name' => 'E.H.PS. (Special)',         'mediums' => ['EM']],
                    ['code' => 'ESMLE', 'name' => 'E.S.MLE.',                  'mediums' => ['EM']],
                    ['code' => 'HPMLE', 'name' => 'H.P.MLE.',                  'mediums' => ['EM']],
                    ['code' => 'HSO',   'name' => "BA Honours (Sociology)",    'mediums' => ['EM']],
                    ['code' => 'SPP',   'name' => 'BA S.P.P.',                 'mediums' => ['EM']],
                    ['code' => 'JSP',   'name' => 'BA J.S.P.',                 'mediums' => ['EM']],
                ],
            ],
            [
                'code'             => 'BCOM',
                'name'             => 'Bachelor of Commerce',
                'total_semesters'  => 6,
                'groups' => [
                    ['code' => 'GEN', 'name' => 'B.Com (General)',                'mediums' => ['TM', 'EM']],
                    ['code' => 'CA',  'name' => 'B.Com (Computer Applications)',  'mediums' => ['EM']],
                    ['code' => 'FIN', 'name' => 'B.Com (Finance)',                'mediums' => ['EM']],
                ],
            ],
            [
                'code'             => 'BSC',
                'name'             => 'Bachelor of Science',
                'total_semesters'  => 6,
                'groups' => [
                    ['code' => 'BZC',   'name' => 'B.Sc. (BZC)',   'mediums' => ['EM']],
                    ['code' => 'BZG',   'name' => 'B.Sc. (BZG)',   'mediums' => ['EM']],
                    ['code' => 'MBZC',  'name' => 'B.Sc. (MBZC)',  'mediums' => ['EM']],
                    ['code' => 'BTZC',  'name' => 'B.Sc. (BTZC)',  'mediums' => ['EM']],
                    ['code' => 'MPC',   'name' => 'B.Sc. (MPC)',   'mediums' => ['EM']],
                    ['code' => 'MPG',   'name' => 'B.Sc. (MPG)',   'mediums' => ['EM']],
                    ['code' => 'MSTCS', 'name' => 'B.Sc. (MSTCS)', 'mediums' => ['EM']],
                    ['code' => 'MPE',   'name' => 'B.Sc. (MPE)',   'mediums' => ['EM']],
                ],
            ],
            [
                'code'             => 'BVOC',
                'name'             => 'Bachelor of Vocation',
                'total_semesters'  => 6,
                'groups'           => [],
            ],
        ];

        foreach ($data as $courseData) {
            $groups = $courseData['groups'];
            unset($courseData['groups']);

            $course = Course::updateOrCreate(
                ['code' => $courseData['code']],
                array_merge($courseData, ['is_active' => true])
            );

            foreach ($groups as $groupData) {
                $course->groups()->updateOrCreate(
                    ['code' => $groupData['code']],
                    [
                        'name'      => $groupData['name'],
                        'mediums'   => $groupData['mediums'],
                        'schemes'   => ['2016', '2020'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
