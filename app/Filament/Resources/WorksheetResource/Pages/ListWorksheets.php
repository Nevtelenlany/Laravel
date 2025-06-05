<?php

namespace App\Filament\Resources\WorksheetResource\Pages;

use App\Enums\WorksheetPriority;
use App\Filament\Resources\WorksheetResource;
use App\Models\Worksheet;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListWorksheets extends ListRecords
{
    protected static string $resource = WorksheetResource::class;

    public ?string $activeTab = 'all'; // 💡 Ez volt hiányzó

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make()
                ->label(__('fields.all'))
                ->icon('heroicon-o-list-bullet')
                ->badge(Worksheet::query()->count()),
        ];

        $priorities = array_column(WorksheetPriority::cases(), 'value');

        foreach ($priorities as $priority) {
            $tabs[$priority] = Tab::make()
                ->label($priority)
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('priority', $priority)
                )
                ->badge(
                    Worksheet::query()->where('priority', $priority)->count()
                )
                ->icon(WorksheetPriority::tryFrom($priority)?->getIcon());
        }

        return $tabs;
    }
}
