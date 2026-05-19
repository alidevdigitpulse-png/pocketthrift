<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'title_eng', 
        'seo_title',
        'seo_meta_keyword',
        'url_slug',
        'meta_description',
        'title_h1',
        'subtitle_h2',
        'content_body',
        'logo',
        'image_alt',
        'image_title',
        'meta_robots',
        'country_codes',
        'start_date',
        'end_date',
        'is_seasonal',
        'active',
        'sort',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'deleted_at' => 'datetime',
    ];

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

    public function getCountryCodeListAttribute()
    {
        // Return the comma-separated string for display purposes
        $value = $this->attributes['country_codes'] ?? null;
        if (is_array($value)) {
            return implode(',', $value);
        }
        return $value ?: '';
    }
    
    // Alias for the view to use country_codes_string
    public function getCountryCodesStringAttribute()
    {
        return $this->country_code_list;
    }

    public function getCountryCodesCollectionAttribute()
    {
        $regionCodes = $this->country_codes; // This will use the array cast
        if (!empty($regionCodes) && is_array($regionCodes)) {
            return \App\Models\Region::whereIn('code', $regionCodes)->get();
        }
        return collect();
    }

    /**
     * Scope to filter categories by region codes
     */
    public function scopeByRegionCodes($query, $regionCodes)
    {
        if (empty($regionCodes)) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }
        
        return $query->where(function($q) use ($regionCodes) {
            // Match records with no country_codes set
            $q->whereNull('country_codes')
              ->orWhere('country_codes', '[]') // Empty JSON array
              ->orWhere('country_codes', '')   // Empty string
              ;

            // If country_codes is stored as JSON array, use JSON_CONTAINS
            try {
                $q->orWhereRaw("(JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?))", [json_encode($regionCodes)]);
            } catch (\Exception $e) {
                // Ignore JSON functions if not supported by the DB engine
            }

            // Also support legacy comma-separated storage (FIND_IN_SET)
            $q->orWhere(function($sub) use ($regionCodes) {
                foreach ($regionCodes as $code) {
                    $sub->orWhereRaw("FIND_IN_SET(?, REPLACE(country_codes, ' ', '')) > 0", [$code])
                        ->orWhere('country_codes', 'LIKE', '%"' . $code . '"%')
                        ->orWhere('country_codes', $code) // Exact match
                        ->orWhere('country_codes', 'LIKE', $code . ',%') // Start
                        ->orWhere('country_codes', 'LIKE', '%,' . $code) // End
                        ->orWhere('country_codes', 'LIKE', '%,' . $code . ',%'); // Middle
                }
            });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    
    /**
     * Scope a query to only include active categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}