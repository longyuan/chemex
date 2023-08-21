<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntityBindingTrack extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对一，实体绑定记录属于一个实体.
     *
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'child_entity_id', 'id');
    }

    /**
     * 一对一，作为子实体的实体绑定记录属于一个实体.
     *
     * @return BelongsTo
     */
    public function entityAsChild(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_id', 'id');
    }
}
