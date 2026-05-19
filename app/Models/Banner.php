<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'image',
        'button_text',
        'url',
        'country_codes',
    ];

    /**
     * Accessor: Convert generic country_codes string to array
     */
    public function getCountryCodesAttribute($value)
    {
        if ($value) {
            return explode(',', $value);
        }
        return [];
    }

    /**
     * Mutator: Convert array to comma-separated string
     */
    public function setCountryCodesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['country_codes'] = implode(',', $value);
        } else {
            $this->attributes['country_codes'] = $value;
        }
    }

    /**
     * Scope to filter banners by region codes
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
}
