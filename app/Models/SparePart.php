<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SparePart extends Model
{
    protected $fillable = [
        'name',
        'part_number',
        'stock_quantity',
        'unit_price',
        'note',
        'device_id',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
