<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\DeviceType;

class DeviceTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('DeviceType', DeviceType::query()->count())->label(__('module_names.device_types.plural_label')),

        ];
    }
}
