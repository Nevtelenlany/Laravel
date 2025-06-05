<?php

namespace App\Filament\Resources\WorksheetResource\Pages;

use App\Filament\Resources\WorksheetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;

class CreateWorksheet extends CreateRecord
{
    protected static string $resource = WorksheetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!isset($data['creator_id'])) {
            $data['creator_id'] = Auth::id();
        }

        return $data;
    }
}

