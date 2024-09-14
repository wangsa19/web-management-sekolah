<?php

namespace App\Filament\Resources\StudentResource\Widgets;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Students', Student::count())->url('admin/students', false),
            Stat::make('Teachers', Teacher::count())->url('admin/teachers', false),
            Stat::make('Subjects', Subject::count())->url('admin/subjects', false),
        ];
    }
}
