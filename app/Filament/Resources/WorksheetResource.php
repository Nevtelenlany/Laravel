<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorksheetResource\Pages;
use App\Filament\Resources\WorksheetResource\RelationManagers;
use App\Models\Worksheet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\WorksheetPriority;
use App\Models\User;
use Filament\Forms\Components;
use Illuminate\Support\Facades\Auth;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;



class WorksheetResource extends Resource
{
    protected static ?string $model = Worksheet::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    protected static ?int $navigationSort = 7;

    public static function getNavigationGroup(): string
    {
        return __('module_names.navigation_groups.failure_report');
    }

    public static function getModelLabel(): string
    {
        return __('module_names.worksheets.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('module_names.worksheets.plural_label');
    }

public static function form(Form $form): Form
{
    $user = Auth::user();
    $isMaintainer = $user->can('update worksheets');
    $isOperator = ! $isMaintainer;

    return $form->schema([
        Components\Section::make()->schema([
            Components\Select::make('device_id')
                ->label(__('module_names.devices.label'))
                ->relationship('device', 'name')
                ->required(),

            Components\Select::make('creator_id')
                ->label(__('fields.creator'))
                ->relationship('creator', 'name')
                ->default($isOperator ? $user->id : null)
                ->disabled($isOperator)
                ->required(),

            Components\Select::make('repairer_id')
                ->label(__('fields.repairer'))
                ->options(User::role('repairer')->get()->pluck('name', 'id'))
                ->disabled($isOperator),

            Components\Select::make('priority')
                ->label(__('fields.priority'))
                ->options(WorksheetPriority::class)
                ->default('Normál')
                ->required(),

            TinyEditor::make('description')
                ->label(__('fields.description'))
                ->profile('simple') // vagy 'full' ha extra funkciókat szeretnél
                ->required()
                ->fileAttachmentsDisk('public')
                ->fileAttachmentsDirectory('uploads')
                ->columnSpanFull()
                ->disabled(fn (?Worksheet $record) => $record && $isOperator),

            Components\DatePicker::make('due_date')
                ->label(__('fields.due_date'))
                ->hidden($isOperator)
                ->minDate(now()),

            Components\DatePicker::make('finish_date')
                ->label(__('fields.finish_date'))
                ->disabled($isOperator)
                ->minDate(now())
                ->default(now()),

            Components\FileUpload::make('attachments')
                ->label(__('fields.attachments'))
                ->required()
                ->image()
                ->imageEditor()
                ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                ->imageEditorEmptyFillColor('#000000')
                ->imageEditorViewportWidth('1920')
                ->imageEditorViewportHeight('1080')
                ->multiple()
                ->preserveFilenames()
                ->openable()
                ->downloadable()
                ->columnSpanFull(),

            TinyEditor::make('comment')
                ->label(__('fields.note'))
                ->profile('simple')
                ->fileAttachmentsDisk('public')
                ->fileAttachmentsDirectory('uploads')
                ->columnSpanFull()
                ->required(false),
        ]),
    ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                    ->dateTime('Y-m-d H:i')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('device.name')
                    ->label(__('module_names.devices.label'))
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('fields.description'))
                    ->limit(30)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('priority')
                    ->label(__('fields.priority'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label(__('fields.creator'))
                    ->numeric()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('repairer.name')
                    ->label(__('fields.repairer'))
                    ->numeric()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('due_date')
                    ->label(__('fields.due_date'))
                    ->date('Y-m-d')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('finish_date')
                    ->label(__('fields.finish_date'))
                    ->date('Y-m-d')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->dateTime('Y-m-d H:i')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
        'index' => Pages\ListWorksheets::route('/'),
        'create' => Pages\CreateWorksheet::route('/create'),
        'view' => Pages\ViewWorksheet::route('/{record}'),
        'edit' => Pages\EditWorksheet::route('/{record}/edit'),
    ];
}

public static function getEloquentQuery(): Builder
{
    $user = Auth::user();

    if (! $user?->can('update worksheets')) {
        return parent::getEloquentQuery()
            ->where('creator_id', $user->id);
    }

    return parent::getEloquentQuery();
}

}
