<?php

namespace App\Filament\Resources\DeviceTypeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\DeviceResource;

class DevicesRelationManager extends RelationManager
{
    protected static string $relationship = 'devices';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('erp_code')->label(__('fields.erp_code'))
                    ->searchable()->sortable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->url(fn(): string => DeviceResource::getUrl('create')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->url(fn($record): string => DeviceResource::getUrl('edit', [
                    "record" => $record->id
                ])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('module_names.devices.plural_label');
    }
    public static function getModelLabel(): string
    {
        return __('module_names.devices.label');
    }
}
