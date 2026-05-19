<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\Region;

class StoreStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'            => 'required|string|max:50',
            'category_id'      => 'nullable|exists:categories,id',
            'url_slug'         => ['required', 'string', 'max:255', function ($attribute, $value, $fail) {
                $this->validateUniqueSlugPerRegion($value, null, $fail);
            }],
            'active'           => 'boolean',
            'sort'             => 'integer|min:0',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'country_codes'    => 'nullable|array',
            'seo_title'        => 'nullable|string|max:70',
            'meta_robots'      => 'nullable|string|max:255',
            'seo_meta_keyword' => 'nullable|string|max:50',
            'meta_description' => 'nullable|string|max:170',
            'title_h1'         => 'nullable|string|max:80',
            'subtitle_h2'      => 'nullable|string|max:80',
            'image_alt'        => 'nullable|string|max:50',
            'image_title'      => 'nullable|string|max:50',
        ];
    }

    /**
     * Validate that URL slug is unique within the selected region(s)
     */
    protected function validateUniqueSlugPerRegion($urlSlug, $excludeStoreId, $fail)
    {
        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        // Determine which region codes to check
        $regionCodesToCheck = [];
        if ($isAdmin && $this->has('country_codes') && is_array($this->country_codes)) {
            $regionCodesToCheck = Region::whereIn('id', $this->country_codes)->pluck('code')->toArray();
        } else {
            if ($user->assigned_regions) {
                $regionCodesToCheck = [$user->assigned_regions];
            }
        }
        
        // Check for existing stores with same slug in the same region(s)
        if (!empty($regionCodesToCheck)) {
            $query = Store::where('url_slug', $urlSlug);
            
            // Exclude current store if updating
            if ($excludeStoreId) {
                $query->where('id', '!=', $excludeStoreId);
            }
            
            $query->where(function($q) use ($regionCodesToCheck) {
                foreach ($regionCodesToCheck as $code) {
                    $q->orWhereRaw("FIND_IN_SET(?, REPLACE(country_codes, ' ', '')) > 0", [$code]);
                }
            });
            
            $existingStore = $query->first();
            
            if ($existingStore) {
                $fail('The url slug has already been taken in the selected region.');
            }
        }
    }
}
