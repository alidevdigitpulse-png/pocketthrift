<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Blog extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'category_id',
        'title',
        'seo_title',
        'seo_meta_keyword',
        'url_slug',
        'meta_description',
        'short_description',
        'content_body',
        'blog_table',
        'est_read_time',
        'logo',
        'image_alt',
        'image_title',
        'start_date',
        'end_date',
        'meta_robots',
        'country_codes',
        'active',
        'sort',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'category_id' => 'integer',
        'est_read_time' => 'integer',
        'active' => 'boolean',
        'sort' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted_by' => 'integer',
        // 'country_codes' => 'array', // Removed to support CSV string storage
        'start_date' => 'date',
        'end_date' => 'date',
        'deleted_at' => 'datetime',
    ];

    public function getCountryCodesAttribute($value)
    {
        // Convert comma-separated values to array when accessed
        if ($value) {
            // Trim whitespace from each code and filter out empty values
            return array_filter(array_map('trim', explode(',', $value)));
        }
        return [];
    }

    public function setCountryCodesAttribute($value)
    {
        // Convert array to comma-separated string when setting
        if (is_array($value)) {
            // Trim whitespace and filter out empty values before storing
            $cleanedValues = array_filter(array_map('trim', $value));
            $this->attributes['country_codes'] = implode(',', $cleanedValues);
        } else {
            $this->attributes['country_codes'] = $value;
        }
    }

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

    /**
     * Get the category that owns the blog.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include blogs for specific region codes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $regionCodes
     * @return \Illuminate\Database\Eloquent\Builder
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

    /**
     * Scope a query to only include active blogs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Get the creator of the blog.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of the blog.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the deleter of the blog.
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the FAQs associated with the blog.
     */
    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }
}