<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemUsage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_id',
        'used_in_id',
        'note',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'used_in_id');
    }
}
