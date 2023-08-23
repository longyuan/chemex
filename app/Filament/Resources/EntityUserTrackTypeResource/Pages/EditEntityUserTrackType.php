<?php

namespace App\Filament\Resources\EntityUserTrackTypeResource\Pages;

use App\Filament\Resources\EntityUserTrackTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEntityUserTrackType extends EditRecord
{
    protected static string $resource = EntityUserTrackTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
