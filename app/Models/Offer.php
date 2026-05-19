<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'store_id',
        'seasonal_id',
        'code',
        'discount',
        'affiliate_links',
        'type',
        'free_delivery',
        'terms_and_conditions',
        'verified',
        'new_recently_updated',
        'country_codes',
        'start_date',
        'end_date',
        'logo',
        'active',
        'sort',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'new_recently_updated' => 'boolean',
        'active' => 'boolean',
        'sort' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function seasonal()
    {
        // This might reference a Season model if it exists, or return null if not applicable
        // For now, we'll assume it relates to categories with seasonal feature
        return $this->belongsTo(Category::class, 'seasonal_id');
    }

    public function getCountryCodesAttribute($value)
    {
        // Convert comma-separated values to array when accessed
        if ($value) {
            return explode(',', $value);
        }
        return [];
    }

    public function setCountryCodesAttribute($value)
    {
        // Convert array to comma-separated string when setting
        if (is_array($value)) {
            $this->attributes['country_codes'] = implode(',', $value);
        } else {
            $this->attributes['country_codes'] = $value;
        }
    }

    public function getCountryCodesCollectionAttribute()
    {
        if (!empty($this->attributes['country_codes'])) {
            $regionCodes = explode(',', $this->attributes['country_codes']);
            return \App\Models\Region::whereIn('code', $regionCodes)->get();
        }
        return collect();
    }

    /**
     * Scope to filter offers by region codes
     */
    public function scopeByRegionCodes($query, $regionCodes)
    {
        if (empty($regionCodes)) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }
        
        return $query->where(function($q) use ($regionCodes) {
            $q->whereNull('country_codes')
              ->orWhere('country_codes', '')
              ->orWhere('country_codes', 'NULL'); // Handle string "NULL" as available in all regions
              
            // Check for exact matches or comma-separated lists
            foreach ($regionCodes as $code) {
                $q->orWhere('country_codes', $code) // Exact match for single region code
                  ->orWhereRaw("FIND_IN_SET(?, REPLACE(country_codes, ' ', '')) > 0", [$code])
                  ->orWhere('country_codes', 'LIKE', '%"' . $code . '"%')
                  ->orWhere('country_codes', 'LIKE', $code . ',%') // Start
                  ->orWhere('country_codes', 'LIKE', '%,' . $code) // End
                  ->orWhere('country_codes', 'LIKE', '%,' . $code . ',%') // Middle
                  ->orWhere('country_codes', 'LIKE', '%' . $code . '%'); // Fallback
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}