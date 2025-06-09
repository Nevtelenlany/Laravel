<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SparePartResource\Pages;
use App\Filament\Resources\SparePartResource\RelationManagers;
use App\Models\SparePart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;


class SparePartResource extends Resource
{
    protected static ?string $model = SparePart::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function getNavigationGroup(): string
    {
        return __('module_names.navigation_groups.maintenance');
    }

    public static function getModelLabel(): string
    {
        return __('fields.spare_part');
    }

    public static function getPluralModelLabel(): string
    {
        return __('fields.spare_parts');
    }

    public static function getNavigationLabel(): string
    {
        return __('fields.spare_parts');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device.name')->label(__('fields.device'))->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->label(__('fields.name'))->sortable()->searchable(),
                Tables\Columns\TextColumn::make('part_number')->label(__('fields.part_number'))->sortable()->searchable(),
                Tables\Columns\TextColumn::make('stock_quantity')->label(__('fields.stock_quantity'))->sortable(),
                Tables\Columns\TextColumn::make('unit_price')->label(__('fields.unit_price'))->money('HUF')->sortable(),
            ])
            ->defaultSort('name');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('device_id')
                ->label(__('fields.device'))
                ->relationship('device', 'name')
                ->required(),

            Forms\Components\TextInput::make('name')
                ->label(__('fields.name'))
                ->required(),

            Forms\Components\TextInput::make('part_number')
                ->label(__('fields.part_number'))
                ->required(),

            Forms\Components\TextInput::make('stock_quantity')
                ->label(__('fields.stock_quantity'))
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('unit_price')
                ->label(__('fields.unit_price_with_currency'))
                ->numeric()
                ->required()
                ->prefix('Ft'),

            TinyEditor::make('note')
    ->label(__('fields.note'))
    ->profile('simple') // vagy 'full', ha sok funkciót szeretnél
    ->fileAttachmentsDisk('public')
    ->fileAttachmentsDirectory('uploads')
    ->columnSpan('full')
    ->required(false),

        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpareParts::route('/'),
            'create' => Pages\CreateSparePart::route('/create'),
            'edit' => Pages\EditSparePart::route('/{record}/edit'),
        ];
    }
}
