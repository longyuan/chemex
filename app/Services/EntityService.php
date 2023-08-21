<?php

namespace App\Services;

use App\Models\Entity;
use App\Models\EntityBindingTrack;

class EntityService
{
    /**
     * 实体绑定相关操作选单列表数据.
     * 排除实体在前端渲染选单列表时排除一些逻辑问题的记录.
     *
     * @param string $entity_id
     * @return array
     */
    public static function adminSelectionForEntityBindingTracks(string $entity_id): array
    {
        // 不允许实体绑定实体本身
        $entities = Entity::query()
            ->where('id', '!=', $entity_id)
            ->pluck('asset_number', 'id')
            ->toArray();
        // 不允许实体绑定自己的父实体，不允许实体间互相绑定
        $entity_binding_tracks = EntityBindingTrack::query()
            ->where('entity_id', $entity_id)
            ->pluck('child_entity_id', 'child_entity_id')
            ->toArray();
        $entities = array_diff_key($entities, $entity_binding_tracks);
        // 不允许实体重复绑定自己的子实体
        $entity_binding_tracks = EntityBindingTrack::query()
            ->where('child_entity_id', $entity_id)
            ->pluck('entity_id', 'entity_id')
            ->toArray();
        return array_diff_key($entities, $entity_binding_tracks);
    }
}
