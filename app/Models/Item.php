<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'name',
        'serial',
        'quantity',
        'data',
        'notes',
        'item_type_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'item_tag_pivot');
    }

    // What this item is installed in (e.g. this RAM is used in PC-01)
    public function usages(): HasMany
    {
        return $this->hasMany(ItemUsage::class);
    }

    // What items are installed inside this item (e.g. PC-01 contains RAM, disk...)
    public function components(): HasMany
    {
        return $this->hasMany(ItemUsage::class, 'used_in_id');
    }
}
