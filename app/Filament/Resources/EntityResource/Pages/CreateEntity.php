<?php

namespace App\Filament\Resources\EntityResource\Pages;

use App\Filament\Resources\EntityResource;
use App\Models\Entity;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\Page;

class CreateEntity extends CreateRecord
{
    protected static string $resource = EntityResource::class;

    /**
     * 保存后跳转至列表.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * 生命周期函数
     * 实体创建后执行
     *
     * @return void
     */
    protected function afterCreate(): void
    {
    }
}
