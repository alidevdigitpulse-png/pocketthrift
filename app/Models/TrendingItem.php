<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrendingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_type',
        'item_id',
        'region_id',
        'user_id',
        'position',
        'row'
    ];

    protected $casts = [
        'position' => 'integer',
        'region_id' => 'integer',
        'item_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Get the region that owns the trending item.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the user who marked the item as trending.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the actual item that is trending (store, category, or offer).
     */
    public function item()
    {
        // Use a polymorphic relation so Eloquent can eager-load the related item.
        // We store `item_type` as short keys ('store', 'category', 'offer') and map them
        // to model classes via a morph map in AppServiceProvider.
        return $this->morphTo(__FUNCTION__, 'item_type', 'item_id');
    }

    /**
     * Scope to get trending items for a specific region and type.
     */
    public function scopeByRegionAndType($query, $regionId, $itemType)
    {
        return $query->where('region_id', $regionId)
                    ->where('item_type', $itemType)
                    ->orderBy('position', 'asc');
    }

    /**
     * Scope to get all trending items for a specific region.
     */
    public function scopeByRegion($query, $regionId)
    {
        return $query->where('region_id', $regionId)
                    ->orderBy('item_type', 'asc')
                    ->orderBy('position', 'asc');
    }
}