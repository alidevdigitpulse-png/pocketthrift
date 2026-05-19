<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'category_id',
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
        'affiliate_links',
        'contact_details',
        'play_store',
        'app_store',
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
        'active' => 'boolean',
        'sort' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
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

    public function getCountryCodesCollectionAttribute()
    {
        if (!empty($this->attributes['country_codes'])) {
            $regionCodes = explode(',', $this->attributes['country_codes']);
            return \App\Models\Region::whereIn('code', $regionCodes)->get();
        }
        return collect();
    }
    
    // Add string accessor for admin views
    public function getCountryCodesStringAttribute()
    {
        return $this->attributes['country_codes'] ?: '';
    }
    
    /**
     * Scope to filter stores by region codes
     */
    public function scopeByRegionCodes($query, $regionCodes)
    {
        if (empty($regionCodes)) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }
        
        return $query->where(function($q) use ($regionCodes) {
            $q->whereNull('country_codes')
              ->orWhere('country_codes', '')
              ->orWhere(function($subQuery) use ($regionCodes) {
                  foreach ($regionCodes as $code) {
                      $subQuery->orWhereRaw("FIND_IN_SET(?, REPLACE(country_codes, ' ', '')) > 0", [$code]);
                  }
              });
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class)->orderBy('sort');
    }

    public function socialLinks()
    {
        return $this->hasMany(SocialLink::class)->orderBy('sort');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class)->orderBy('sort');
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