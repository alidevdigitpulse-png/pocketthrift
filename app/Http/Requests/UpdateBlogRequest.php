<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Models\Region;

class UpdateBlogRequest extends FormRequest
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
        $blog = $this->route('blog'); // Get the blog being updated
        
        return [
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_meta_keyword' => 'nullable|string',
            'url_slug' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) use ($blog) {
                $this->validateUniqueSlugPerRegion($value, $blog->id, $fail);
            }],
            'meta_description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'content_body' => 'nullable|string',
            'blog_table' => 'nullable|string',
            'est_read_time' => 'nullable|integer|min:0',
            'logo' => 'nullable|string|max:255',
            'image_alt' => 'nullable|string|max:255',
            'image_title' => 'nullable|string|max:255',
            'meta_robots' => 'nullable|string|max:500',
            'country_codes' => 'nullable|array',
            'active' => 'boolean',
            'sort' => 'required|integer',
            'updated_by' => 'nullable|exists:users,id',
            'faq_question' => 'nullable|array',
            'faq_question.*' => 'nullable|string',
            'faq_answer' => 'nullable|array',
            'faq_answer.*' => 'nullable|string',
            'faq_sort' => 'nullable|array',
            'faq_sort.*' => 'nullable|integer',
        ];
    }

    /**
     * Validate that URL slug is unique within the selected region(s)
     */
    protected function validateUniqueSlugPerRegion($urlSlug, $excludeBlogId, $fail)
    {
        $user = Auth::user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        // Determine which region codes to check
        $regionCodesToCheck = [];
        if ($isAdmin && $this->has('country_codes') && is_array($this->country_codes)) {
            // Admin selected specific regions - get their codes
            $regionCodesToCheck = $this->country_codes;
        } else {
            // Region-wise user - check their assigned region
            if ($user->assigned_regions) {
                $regionCodesToCheck = [$user->assigned_regions];
            }
        }
        
        // Check for existing blogs with same slug in the same region(s)
        if (!empty($regionCodesToCheck)) {
            $query = Blog::where('url_slug', $urlSlug);
            
            // Exclude current blog if updating
            if ($excludeBlogId) {
                $query->where('id', '!=', $excludeBlogId);
            }
            
            // Check if slug exists in any of the selected regions
            // Blogs store country_codes as comma-separated values
            $query->where(function($q) use ($regionCodesToCheck) {
                foreach ($regionCodesToCheck as $code) {
                    $q->orWhereRaw("FIND_IN_SET(?, REPLACE(country_codes, ' ', '')) > 0", [$code]);
                }
            });
            
            $existingBlog = $query->first();
            
            if ($existingBlog) {
                $fail('The url slug has already been taken in the selected region.');
            }
        }
    }
}
