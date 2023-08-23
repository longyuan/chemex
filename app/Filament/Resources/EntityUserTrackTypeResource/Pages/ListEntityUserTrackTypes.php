<?php

namespace App\Filament\Resources\EntityUserTrackTypeResource\Pages;

use App\Filament\Resources\EntityUserTrackTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEntityUserTrackTypes extends ListRecords
{
    protected static string $resource = EntityUserTrackTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
