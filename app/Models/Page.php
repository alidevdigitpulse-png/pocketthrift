<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Page extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'title',
        'seo_title',
        'seo_meta_keyword',
        'url_slug',
        'meta_description',
        'meta_robots',
        'content_body',
        'country_codes',
        'start_date',
        'end_date',
        'active',
        'sort',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        // 'country_codes' => 'array', // Removed array cast to store as string
        'active' => 'boolean',
        'sort' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'url_slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getCountryCodeListAttribute()
    {
        // Return the comma-separated string for display purposes
        $value = $this->attributes['country_codes'] ?? null;
        if (is_array($value)) {
            return implode(',', $value);
        }
        // If it's already a string (CSV or single value), return it
        return $value ?: '';
    }

    // Alias for the view to use country_codes_string
    public function getCountryCodesStringAttribute()
    {
        return $this->country_code_list;
    }

    public function getCountryCodesCollectionAttribute()
    {
        $regionCodes = $this->country_codes;
        
        // Handle if it's a string (CSV)
        if (is_string($regionCodes)) {
            $regionCodes = explode(',', $regionCodes);
        }
        
        if (!empty($regionCodes) && is_array($regionCodes)) {
            return \App\Models\Region::whereIn('code', $regionCodes)->get();
        }
        return collect();
    }

    /**
     * Scope to filter pages by region codes
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
                    $sub->orWhereRaw("FIND_IN_SET(?, country_codes)", [$code])
                        ->orWhere('country_codes', 'LIKE', '%"' . $code . '"%')
                        ->orWhere('country_codes', 'LIKE', '%'.$code.'%');
                }
            });
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