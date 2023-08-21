<?php

namespace App\Filament\Actions;

use App\Models\Entity;
use App\Models\EntityBindingTrack;
use App\Models\EntityUserTrack;
use App\Models\User;
use App\Services\EntityService;
use App\Utils\NotificationUtil;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class EntityAction
{
    /**
     * 分配管理者按钮.
     *
     * @param Model|null $out_entity
     * @return Action
     */
    public static function createUserTrack(Model $out_entity = null): Action
    {
        return Action::make('分配管理者')
            ->form([
                Select::make('user_id')
                    ->label('管理者')
                    ->options(User::query()->pluck('name', 'id'))
                    ->required(),
                TextInput::make('comment')
                    ->label('说明')
                    ->required(),
            ])
            ->action(function (array $data, Entity $entity) use ($out_entity): void {
                if ($out_entity) {
                    $entity = $out_entity;
                }
                EntityUserTrack::query()
                    ->updateOrCreate([
                        'entity_id' => $entity->getKey(),
                        'user_id' => $data['user_id'],
                        'comment' => $data['comment'],
                    ]);
                NotificationUtil::make(true, '实体已归属管理者');
            })
            ->icon('heroicon-s-user-plus');
    }

    /**
     * 解除管理者按钮.
     *
     * @param Model|null $out_entity
     * @return Action
     */
    public static function deleteUserTrack(Model $out_entity = null): Action
    {
        return Action::make('解除管理者')
            ->form([
                TextInput::make('delete_comment')
                    ->label('解除说明')
                    ->required(),
            ])
            ->action(function (array $data, Entity $entity) use ($out_entity): void {
                if ($out_entity) {
                    $entity = $out_entity;
                }
                $entity_user_track = EntityUserTrack::query()->where('entity_id', $entity->getKey())->first();
                if (empty($entity_user_track)) {
                    NotificationUtil::make(false, '找不到此实体的管理者');
                } else {
                    $entity_user_track->setAttribute('delete_comment', $data['delete_comment']);
                    $entity_user_track->save();
                    $entity_user_track->delete();
                    NotificationUtil::make(true, '实体已解除管理者');
                }
            })
            ->icon('heroicon-s-user-minus');
    }

    /**
     * 绑定子实体按钮.
     *
     * @param string $entity_id
     * @return Action
     */
    public static function createBindingTrack(string $entity_id): Action
    {
        return Action::make('绑定子实体')
            ->form([
                Select::make('entities')
                    ->multiple()
                    // 前端验证
                    ->options(EntityService::adminSelectionForEntityBindingTracks($entity_id))
                    ->label('实体')
                    ->required(),
                TextInput::make('comment')
                    ->label('绑定原因')
                    ->required(),
            ])
            ->action(function (array $data) use ($entity_id): void {
                // $datum 是单个子实体
                foreach ($data['entities'] as $datum) {
                    // 后端验证
                    // 如果子实体添加自己，则跳过实现去重
                    // 如果绕过前端添加了已经存在的绑定关系，则不会重复创建数据
                    if ($entity_id == $datum) {
                        continue;
                    }
                    // 不允许两个实体互相绑定
                    $entity_bind_track = EntityBindingTrack::query()
                        ->where('child_entity_id', $entity_id)
                        ->where('entity_id', $datum)
                        ->first();
                    if ($entity_bind_track) {
                        NotificationUtil::make(false, '实体之间不允许互相绑定');
                        continue;
                    }
                    EntityBindingTrack::query()->firstOrCreate([
                        'entity_id' => $entity_id,
                        'child_entity_id' => $datum,
                        'comment' => $data['comment'],
                    ]);
                }
                NotificationUtil::make(true, '已绑定子实体');
            })
            ->icon('heroicon-s-squares-plus');
    }

    /**
     * 解除绑定子实体按钮.
     *
     * @return Action
     */
    public static function deleteBindingTrack(): Action
    {
        return Action::make('解除绑定')
            ->form([
                TextInput::make('delete_comment')
                    ->label('解除绑定原因')
                    ->required(),
            ])
            ->action(function (EntityBindingTrack $entityBindingTrack) {
                if ($entityBindingTrack->delete()) {
                    NotificationUtil::make(true, '已解除子实体绑定');
                } else {
                    NotificationUtil::make(false, '解除失败');
                }
            });
    }

    /**
     * 绑定至父实体按钮.
     *
     * @param string $entity_id
     * @return Action
     */
    public static function createBindingTrackAsChild(string $entity_id): Action
    {
        return Action::make('绑定至父实体')
            ->form([
                Select::make('entity')
                    // 前端验证
                    ->options(EntityService::adminSelectionForEntityBindingTracks($entity_id))
                    ->label('实体')
                    ->required(),
                TextInput::make('comment')
                    ->label('绑定原因')
                    ->required(),
            ])
            ->action(function (array $data) use ($entity_id) {
                $parent_entity_id = $data['entity'];
                $binding_track = EntityBindingTrack::query()
                    ->where('child_entity_id', $parent_entity_id)
                    ->first();
                // 验证所绑定的父实体是不是实体本身
                // 验证所绑定的父实体是不是已经是自己的子实体
                if ($parent_entity_id == $entity_id) {
                    NotificationUtil::make(false, '实体不允许绑定自己');
                } elseif ($binding_track) {
                    NotificationUtil::make(false, '绑定的父实体不能是自己的子实体');
                } else {
                    EntityBindingTrack::query()->firstOrCreate([
                        'entity_id' => $parent_entity_id,
                        'child_entity_id' => $entity_id,
                        'comment' => $data['comment'],
                    ]);
                    NotificationUtil::make(true, '已绑定至父实体');
                }
            });
    }

    /**
     * 解除绑定至父实体按钮.
     *
     * @param string $entity_id
     * @return Action
     */
    public static function deleteBindingTrackAsChild(string $entity_id): Action
    {
        return Action::make('解除绑定')
            ->form([
                TextInput::make('delete_comment')
                    ->label('解除绑定原因')
                    ->required(),
            ])
            ->action(function (array $data) use ($entity_id) {
                $entity_binding_track = EntityBindingTrack::query()
                    ->where('child_entity_id', $entity_id)
                    ->firstOrFail();
                $entity_binding_track->setAttribute('delete_comment', $data['delete_comment']);
                if ($entity_binding_track->delete()) {
                    NotificationUtil::make(true, '已解除父实体绑定');
                } else {
                    NotificationUtil::make(false, '解除失败');
                }
            });
    }
}
