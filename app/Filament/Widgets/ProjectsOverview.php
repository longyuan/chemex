<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ProjectsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
//            Stat::make('我的项目', $project_counts)
        ];
    }
}
