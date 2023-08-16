<?php

namespace App\Filament\Resources\EntityResource\Pages;

use App\Filament\Resources\EntityResource;
use App\Models\EntityBindingTrack;
use Filament\Resources\Pages\CreateRecord;

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
     * 实体创建后执行，创建一条实体绑定记录，为了在绑定子实体的下拉多选框中方便排除自己
     *
     * @return void
     */
    protected function afterCreate(): void
    {
        EntityBindingTrack::query()
            ->firstOrCreate([
                'entity_id' => $this->getRecord()->getKey(),
                'child_entity_id' => $this->getRecord()->getKey()
            ]);
    }
}
