<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RegionFilter;
use App\Http\Controllers\{
    HomeController,
    CartController,
    ProductController,
    SuperAdminController,
    UserController,
    RegionChangeController,
    RegionController
};
use App\Http\Controllers\Admin\{
    AdminController,
    BannerController,
    CategoryController,
    ConfigController,
    CustomerController,
    InquiryController,
    OfferController,
    OrderController,
    PageController,
    PermissionsController,
    RegionController as AdminRegionController,
    RegionDashboardController,
    RolesController,
    StoreController
};
use App\Models\{Inquiry, Vehicle};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Region change route - needs to be outside the region group to avoid conflicts
Route::get('/change-region/{regionCode}', [RegionChangeController::class, 'changeRegion'])->name('change.region');
Route::get('/change-region-to-us', [RegionController::class, 'setRegionToUs'])->name('change.region.us');



Auth::routes();

// Socialite routes
Route::get('/redirect', [UserController::class, 'redirectFacebook']);
Route::get('/callback', [UserController::class, 'facebookCallback']);
Route::get('auth/google', [UserController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [UserController::class, 'handleGoogleCallback']);

// Inquiry route
Route::post('/inquiry-submit', [InquiryController::class, 'submit'])->name('inquiry.submit');

// Non-prefixed routes for US region
Route::group(['middleware' => [\App\Http\Middleware\RegionMiddleware::class]], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('aboutUs');
    Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacyPolicy');
    Route::get('/affiliate-disclaimer', [HomeController::class, 'affiliate'])->name('affiliateDisclaimer');
    Route::get('/imprint', [HomeController::class, 'imprint'])->name('imprint');
    Route::get('/terms-of-use', [HomeController::class, 'terms'])->name('termsofUse');
    Route::get('/all-regions', function () {
        $regions = \App\Models\Region::where('active', 1)->orderBy('sort', 'asc')->orderBy('country', 'asc')->get();
        return view('all-regions', compact('regions'));
    })->name('allRegions');
    Route::get('/contact-us', [\App\Http\Controllers\ContactController::class, 'index'])->name('contactUs');
    Route::post('/contact-us', [\App\Http\Controllers\ContactController::class, 'store'])->name('contactUs.store');
    Route::get('/coupons', [HomeController::class, 'categories'])->name('categories');
    Route::get('/coupons/{category}', [HomeController::class, 'categoryDetail'])->name('category.detail');
    Route::get('/stores', [HomeController::class, 'stores'])->name('stores');
    Route::get('/stores/{store}', [HomeController::class, 'storeDetail'])->name('store.detail');
    Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs');
    Route::get('/blog/{slug}', [HomeController::class, 'blogDetail'])->name('blog.detail');
    // Route::get('terms-and-conditions',[HomeController::class,'terms'])->name('terms');
    
    // Pages
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
});

// Prefixed routes for other regions
Route::group([
    'prefix' => '{region}',
    'where' => ['region' => '[a-z]{2}'],
    'middleware' => [\App\Http\Middleware\RegionMiddleware::class]
], function () {
    Route::get('/', [HomeController::class, 'index'])->name('region.home');
    Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('region.aboutUs');
    
    Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('region.privacyPolicy');
    Route::get('/affiliate-disclaimer', [HomeController::class, 'affiliate'])->name('region.affiliateDisclaimer');
    Route::get('/imprint', [HomeController::class, 'imprint'])->name('region.imprint');
    Route::get('/terms-of-use', [HomeController::class, 'terms'])->name('region.termsofUse');
    Route::get('/all-regions', function () {
        $regions = \App\Models\Region::where('active', 1)->orderBy('sort', 'asc')->orderBy('country', 'asc')->get();
        return view('all-regions', compact('regions'));
    })->name('region.allRegions');
    Route::get('/contact-us', [\App\Http\Controllers\ContactController::class, 'index'])->name('region.contactUs');
    Route::post('/contact-us', [\App\Http\Controllers\ContactController::class, 'store'])->name('region.contactUs.store');
    Route::get('/coupons', [HomeController::class, 'categories'])->name('region.categories');
    Route::get('/coupons/{category}', [HomeController::class, 'categoryDetail'])->name('region.category.detail');
    Route::get('/stores', [HomeController::class, 'stores'])->name('region.stores');
    Route::get('/stores/{store}', [HomeController::class, 'storeDetail'])->name('region.store.detail');
    Route::get('/blogs', [HomeController::class, 'blogs'])->name('region.blogs');
    Route::get('/blog/{slug}', [HomeController::class, 'blogDetail'])->name('region.blog.detail');
    // Route::get('terms-and-conditions',[HomeController::class,'terms'])->name('region.terms');
    
    // Region pages
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('region.page.show');
    Route::get('/pages', [PageController::class, 'index'])->name('region.pages.index');
});


Route::group(['middleware' => ['auth', 'isSuper']], function () {
    Route::resource('permissions', PermissionsController::class);
    Route::resource('roles', RolesController::class);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'isAdmin']], function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');

    // Route::get('/crud-generator', [Hamdan\CrudGenerator\Controllers\ProcessController::class, 'getGenerator'])->name('generator'); // Disabled - package not installed
    
    // Config
    Route::group(['prefix' => 'config'], function () {
        Route::get('favicon', [ConfigController::class, 'favicon'])->name('admin.config.favicon');
        Route::get('logo', [ConfigController::class, 'logo'])->name('admin.config.logo');
        Route::get('settings', [ConfigController::class, 'configSettings'])->name('admin.config.settings');
        Route::post('update', [ConfigController::class, 'configPost'])->name('admin.config.post');
        Route::get('option', [ConfigController::class, 'configOption'])->name('admin.config.option');
        Route::post('add-new-config', [ConfigController::class, 'addNewConfig'])->name('add.new.config');
        Route::post('option/update', [ConfigController::class, 'configOptionUpdate'])->name('admin.config.option.update');
    });

    // Section Create - for main admin
    Route::post('section-create', [PageController::class, 'sectionCreate'])->name('section.create');

    //Inquiry
    Route::get('inquiry', [InquiryController::class, 'index'])->name('inquiry.index');
    Route::get('inquiry-detail/{id}', [InquiryController::class, 'detail'])->name('inquiry.detail');

    // Banner
    Route::resource('banner', BannerController::class);

    // Page - for main admin users (admin group)
    Route::resource('page', \App\Http\Controllers\Admin\PageController::class)->names([
        'index' => 'admin.page.index',
        'create' => 'admin.page.create',
        'store' => 'admin.page.store',
        'show' => 'admin.page.show',  // Different name from public 'page.show'
        'edit' => 'admin.page.edit',
        'update' => 'admin.page.update',
        'destroy' => 'admin.page.destroy'
    ]);

    // Section Create - for main admin
    Route::post('section-create', [PageController::class, 'sectionCreate'])->name('section.create');

    //Order
    Route::resource('order', OrderController::class);
    Route::get('order-detail/{id}', [OrderController::class,'orderDetail'])->name('order_detail');

    //Delete Productimage
    Route::post('/product/delete-image', [ProductController::class, 'deleteImages'])->name('product.delete_img');

    //Attribute - Disabled - controller does not exist
    // Route::resource('attribute', AttributeController::class);

    //Delete Attribute Value - Disabled - controller does not exist
    // Route::post('/product/delete-attribute-value', [AttributeController::class, 'deleteAttrValue'])->name('attribute.deleteAttrValue');

    //Region
    Route::resource('region', AdminRegionController::class)->names([
        'index' => 'admin.region.index',
        'create' => 'admin.region.create',
        'store' => 'admin.region.store',
        'show' => 'admin.region.show',
        'edit' => 'admin.region.edit',
        'update' => 'admin.region.update',
        'destroy' => 'admin.region.destroy'
    ]);


    // Customer
    Route::resource('customer', CustomerController::class);

    require_once('crudweb.php');
});

// Routes for admins and region-wise users
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'isRegionUser']], function () {
    // Category
    Route::resource('category', CategoryController::class)->names([
        'index' => 'admin.category.index',
        'create' => 'admin.category.create',
        'store' => 'admin.category.store',
        'show' => 'admin.category.show',
        'edit' => 'admin.category.edit',
        'update' => 'admin.category.update',
        'destroy' => 'admin.category.destroy'
    ]);

    // Store
    // Route::resource('store', StoreController::class)->names([
    //     'index' => 'admin.store.index',
    //     'create' => 'admin.store.create',
    //     'store' => 'admin.store.store',
    //     'show' => 'admin.store.show',
    //     'edit' => 'admin.store.edit',
    //     'update' => 'admin.store.update',
    //     'destroy' => 'admin.store.destroy'
    // ]);
    
    Route::resource('stores', StoreController::class)->names([
    'index' => 'admin.stores.index',
    'create' => 'admin.stores.create',
    'store' => 'admin.stores.store',
    'show' => 'admin.stores.show',
    'edit' => 'admin.stores.edit',
    'update' => 'admin.stores.update',
    'destroy' => 'admin.stores.destroy'
]);
    
    // Store FAQs
    Route::post('store/{store}/faqs', [StoreController::class, 'storeFaq'])->name('admin.store.faq.store');
    Route::put('store/{store}/faqs/{faq}', [StoreController::class, 'updateFaq'])->name('admin.store.faq.update');
    Route::delete('store/{store}/faqs/{faq}', [StoreController::class, 'deleteFaq'])->name('admin.store.faq.delete');

    // Offer
    Route::resource('offer', OfferController::class)->names([
        'index' => 'admin.offer.index',
        'create' => 'admin.offer.create',
        'store' => 'admin.offer.store',
        'show' => 'admin.offer.show',
        'edit' => 'admin.offer.edit',
        'update' => 'admin.offer.update',
        'destroy' => 'admin.offer.destroy'
    ]);

    // Blog
    Route::resource('blog', \App\Http\Controllers\Admin\BlogController::class)->names([
        'index' => 'admin.blog.index',
        'create' => 'admin.blog.create',
        'store' => 'admin.blog.store',
        'show' => 'admin.blog.show',
        'edit' => 'admin.blog.edit',
        'update' => 'admin.blog.update',
        'destroy' => 'admin.blog.destroy'
    ]);


    
    // Page routes for region users (with different URL path to avoid conflicts) - using same controller
    Route::resource('region-pages', \App\Http\Controllers\Admin\PageController::class)->names([
        'index' => 'admin.region.page.index',
        'create' => 'admin.region.page.create',
        'store' => 'admin.region.page.store',
        'show' => 'admin.region.page.show',
        'edit' => 'admin.region.page.edit',
        'update' => 'admin.region.page.update',
        'destroy' => 'admin.region.page.destroy'
    ]);
    
    // Section Create - for region users
    Route::post('section-create', [PageController::class, 'sectionCreate'])->name('admin.section.create');

    // Region Dashboard
    Route::get('/region-dashboard', [RegionDashboardController::class, 'index'])->name('admin.region.dashboard');
    
    // Trending Items Management for region users
    Route::get('/trending-items', [RegionDashboardController::class, 'trendingForm'])->name('admin.region.dashboard.trending.form');
    Route::post('/trending-items', [RegionDashboardController::class, 'saveTrending'])->name('admin.region.dashboard.trending.save');
});

Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' =>  ['auth', 'isUser']], function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::get('/wishlist',[UserController::class,'wishlist'])->name('wishlist');
    Route::get('/order-hisory',[UserController::class,'orderHistory'])->name('orders');
    Route::get('generate-invoice/{id}', [UserController::class, 'generateInvoice'])->name('generateinvoice');
    Route::post('/account-update', [UserController::class, 'accountUpdate'])->name('update');
});