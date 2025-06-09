<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;


class ListUsers extends ListRecords
{
    public ?string $activeTab = 'all';
    protected static string $resource = UserResource::class;

    public function getTabs(): array
{
    $tabs = [
        'all' => Tab::make()
            ->label(__('fields.all'))
            ->icon('heroicon-o-list-bullet')
            ->badge(\App\Models\User::query()->count()),
    ];

    $roles = Role::all()->pluck('name');
    foreach ($roles as $role) {
        $tabs[$role] = Tab::make()
            ->label($role)
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->whereHas('roles', fn ($q) => $q->where('name', $role))
            )
            ->badge(\App\Models\User::query()
                ->whereHas('roles', fn ($q) => $q->where('name', $role))
                ->count()
            )
            ->icon('heroicon-o-user-group');
    }

    return $tabs;
}

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
