<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    public function contacts(): HasMany
    {
        return $this->hasMany(VendorContactTrack::class, 'vendor_id', 'id');
    }
}
