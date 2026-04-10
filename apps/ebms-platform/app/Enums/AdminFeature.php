<?php

namespace App\Enums;

enum AdminFeature: string
{
    case DashboardView       = 'dashboard.view';
    case StudentsView        = 'students.view';
    case StudentsEdit        = 'students.edit';
    case StudentsDelete      = 'students.delete';
    case EnrollmentsView     = 'enrollments.view';
    case EnrollmentsEdit     = 'enrollments.edit';
    case EnrollmentsDelete   = 'enrollments.delete';
    case ExamsView           = 'exams.view';
    case ExamsEdit           = 'exams.edit';
    case ExamsDelete         = 'exams.delete';
    case CoursesView         = 'courses.view';
    case CoursesEdit         = 'courses.edit';
    case CoursesDelete       = 'courses.delete';
    case PapersView          = 'papers.view';
    case PapersEdit          = 'papers.edit';
    case PapersDelete        = 'papers.delete';
    case DFormView           = 'dform.view';
    case AttendanceView      = 'attendance.view';
    case GradesheetsView     = 'gradesheets.view';
    case GradesheetsGenerate = 'gradesheets.generate';

    public function label(): string
    {
        return match($this) {
            self::DashboardView       => 'Dashboard',
            self::StudentsView        => 'View Students',
            self::StudentsEdit        => 'Edit Students',
            self::StudentsDelete      => 'Delete Students',
            self::EnrollmentsView     => 'View Enrollments',
            self::EnrollmentsEdit     => 'Edit Enrollments',
            self::EnrollmentsDelete   => 'Delete Enrollments',
            self::ExamsView           => 'View Exams',
            self::ExamsEdit           => 'Edit Exams',
            self::ExamsDelete         => 'Delete Exams',
            self::CoursesView         => 'View Courses',
            self::CoursesEdit         => 'Edit Courses',
            self::CoursesDelete       => 'Delete Courses',
            self::PapersView          => 'View Papers',
            self::PapersEdit          => 'Edit Papers',
            self::PapersDelete        => 'Delete Papers',
            self::DFormView           => 'View D-Form',
            self::AttendanceView      => 'View Attendance',
            self::GradesheetsView     => 'View Grade Sheets',
            self::GradesheetsGenerate => 'Generate Grade Sheets',
        };
    }

    public function group(): string
    {
        return match($this) {
            self::DashboardView       => 'General',
            self::StudentsView,
            self::StudentsEdit,
            self::StudentsDelete      => 'Students',
            self::EnrollmentsView,
            self::EnrollmentsEdit,
            self::EnrollmentsDelete   => 'Enrollments',
            self::ExamsView,
            self::ExamsEdit,
            self::ExamsDelete         => 'Exams',
            self::CoursesView,
            self::CoursesEdit,
            self::CoursesDelete       => 'Courses',
            self::PapersView,
            self::PapersEdit,
            self::PapersDelete        => 'Papers',
            self::DFormView           => 'Pre Exam',
            self::AttendanceView      => 'Pre Exam',
            self::GradesheetsView,
            self::GradesheetsGenerate => 'Academic Records',
        };
    }

    /** Roles that have this feature by default (without an explicit grant). */
    public function defaultRoles(): array
    {
        return match($this) {
            self::DashboardView,
            self::StudentsView,
            self::EnrollmentsView,
            self::ExamsView,
            self::CoursesView,
            self::PapersView,
            self::DFormView,
            self::AttendanceView,
            self::GradesheetsView     => ['admin', 'staff'],
            self::StudentsEdit,
            self::StudentsDelete,
            self::EnrollmentsEdit,
            self::EnrollmentsDelete,
            self::ExamsEdit,
            self::ExamsDelete,
            self::CoursesEdit,
            self::CoursesDelete,
            self::PapersEdit,
            self::PapersDelete        => ['admin'],
            self::GradesheetsGenerate => [],
        };
    }

    /** Group all cases by their group label for rendering. */
    public static function grouped(): array
    {
        $groups = [];
        foreach (self::cases() as $feature) {
            $groups[$feature->group()][] = $feature;
        }
        return $groups;
    }
}
