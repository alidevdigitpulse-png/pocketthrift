<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Region;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_can_access_page_creation()
    {
        // Create an admin user (role 1)
        $adminUser = User::factory()->create(['role' => 1]);
        
        // Authenticate as the admin user
        $this->actingAs($adminUser);
        
        // Test that admin can access create page form
        $response = $this->get('/admin/page/create');
        $response->assertStatus(200); // Should be accessible to admin
    }

    public function test_region_user_can_access_page_creation()
    {
        // Create a region
        $region = Region::factory()->create(['code' => 'ca', 'country' => 'Canada']);
        
        // Create a region user (role 2) with assigned regions
        $regionUser = User::factory()->create([
            'role' => 2,
            'assigned_regions' => 'ca'
        ]);
        
        // Authenticate as the region user
        $this->actingAs($regionUser);
        
        // Test that region user can access create page form
        $response = $this->get('/admin/region-pages/create');
        $response->assertStatus(200); // Should be accessible to region user
    }

    public function test_region_user_create_page_has_region_code_appended()
    {
        // Create a region
        $region = Region::factory()->create(['code' => 'ca', 'country' => 'Canada']);
        
        // Create a region user (role 2) with assigned regions
        $regionUser = User::factory()->create([
            'role' => 2,
            'assigned_regions' => 'ca'
        ]);
        
        // Authenticate as the region user
        $this->actingAs($regionUser);
        
        // Test creating a page through the region user route
        $response = $this->post('/admin/region-pages', [
            'name' => 'Test Page',
            'status' => 1
        ]);
        
        // Check that the response is successful
        $response->assertJson(['status' => true]);
        
        // Verify the page was created with the region and name
        $this->assertDatabaseHas('pages', [
            'name' => 'Test Page-ca', // Should have region code appended
            'region_id' => $region->id,
        ]);
    }

    public function test_admin_user_can_create_page_for_any_region()
    {
        // Create a region
        $region = Region::factory()->create(['code' => 'us', 'country' => 'USA']);
        
        // Create an admin user (role 1)
        $adminUser = User::factory()->create(['role' => 1]);
        
        // Authenticate as the admin user
        $this->actingAs($adminUser);
        
        // Test creating a page through the admin route
        $response = $this->post('/admin/page', [
            'name' => 'Admin Test Page',
            'region_id' => $region->id,
            'status' => 1
        ]);
        
        // Check that the response is successful
        $response->assertJson(['status' => true]);
        
        // Verify the page was created with the specified region
        $this->assertDatabaseHas('pages', [
            'name' => 'Admin Test Page',
            'region_id' => $region->id,
        ]);
    }

    public function test_region_user_cannot_create_page_without_assigned_region()
    {
        // Create a user without assigned regions
        $user = User::factory()->create([
            'role' => 2,
            'assigned_regions' => null
        ]);
        
        // Authenticate as the user without assigned regions
        $this->actingAs($user);
        
        // Test creating a page - should fail due to middleware
        $response = $this->post('/admin/region-pages', [
            'name' => 'Test Page',
            'status' => 1
        ]);
        
        // The response should be a redirect or 403 since user doesn't pass isRegionUser middleware
        $response->assertStatus(302); // Redirect to login or dashboard
    }
}