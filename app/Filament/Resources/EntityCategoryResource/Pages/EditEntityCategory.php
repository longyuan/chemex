<?php

namespace App\Filament\Resources\EntityCategoryResource\Pages;

use App\Filament\Resources\EntityCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEntityCategory extends EditRecord
{
    protected static string $resource = EntityCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
