<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Region;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create some sample pages for testing
        $usRegion = Region::where('code', 'us')->first();
        $ukRegion = Region::where('code', 'uk')->first();
        
        if ($usRegion) {
            Page::create([
                'title' => 'Test Page - US',
                'seo_title' => 'Test Page SEO Title - US',
                'url_slug' => 'test-page-us',
                'content_body' => '<p>This is a test page for US region.</p>',
                'meta_description' => 'Test page description for US',
                'region_id' => $usRegion->id,
                'country_codes' => json_encode(['us']),
                'active' => true,
                'sort' => 1,
                'created_by' => 1
            ]);
        }
        
        if ($ukRegion) {
            Page::create([
                'title' => 'Test Page - UK',
                'seo_title' => 'Test Page SEO Title - UK',
                'url_slug' => 'test-page-uk',
                'content_body' => '<p>This is a test page for UK region.</p>',
                'meta_description' => 'Test page description for UK',
                'region_id' => $ukRegion->id,
                'country_codes' => json_encode(['uk']),
                'active' => true,
                'sort' => 2,
                'created_by' => 1
            ]);
        }
        
        // Create a page available in all regions
        Page::create([
            'title' => 'Global Page',
            'seo_title' => 'Global Page SEO Title',
            'url_slug' => 'global-page',
            'content_body' => '<p>This is a global test page.</p>',
            'meta_description' => 'Global test page description',
            'region_id' => $usRegion ? $usRegion->id : null,
            'country_codes' => json_encode([]), // Empty array means available in all regions
            'active' => true,
            'sort' => 3,
            'created_by' => 1
        ]);
    }
}