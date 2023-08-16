<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对一，实体有一个分类.
     *
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(EntityCategory::class, 'id', 'category_id');
    }

    /**
     * 远程一对一，实体有一个管理者.
     *
     * @return HasManyThrough
     */
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,  // 远程表
            EntityUserTrack::class,   // 中间表
            'entity_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'user_id' // 中间表对远程表的关联字段
        );
    }

    /**
     * 一对多，实体有很多用户管理记录.
     *
     * @return HasMany
     */
    public function userTracks(): HasMany
    {
        return $this->hasMany(EntityUserTrack::class, 'entity_id', 'id');
    }

    /**
     * 一对多，作为子实体有很多绑定记录.
     *
     * @return HasMany
     */
    public function bindingTracksAsChild(): HasMany
    {
        return $this->hasMany(EntityBindingTrack::class, 'child_entity_id', 'id');
    }

    /**
     * 远程一对多，实体有多个子实体.
     *
     * @return HasManyThrough
     */
    public function entities(): HasManyThrough
    {
        return $this->hasManyThrough(
            Entity::class,  // 远程表
            EntityBindingTrack::class,   // 中间表
            'entity_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'child_entity_id' // 中间表对远程表的关联字段
        );
    }

    /**
     * 查询访问器，子实体数量.
     *
     * @return Attribute
     */
    protected function entitiesCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->bindingTracks()->where('child_entity_id', '!=', $this->getKey())->count();
            }
        );
    }

    /**
     * 一对多，作为实体有很多绑定子实体记录.
     *
     * @return HasMany
     */
    public function bindingTracks(): HasMany
    {
        return $this->hasMany(EntityBindingTrack::class, 'entity_id', 'id');
    }
}
