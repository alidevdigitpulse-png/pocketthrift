@extends('admin.layouts.app')

@section('title', 'Edit Store')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Edit Store: {{ $store->title }}</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">Back to Stores</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.stores.update', $store->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title', $store->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $store->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url_slug">URL Slug <span class="text-danger">*</span></label>
                                    <input type="text" name="url_slug" id="url_slug" class="form-control @error('url_slug') is-invalid @enderror" 
                                           value="{{ old('url_slug', $store->url_slug) }}" required>
                                    @error('url_slug')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seo_title">SEO Title</label>
                                    <input type="text" name="seo_title" id="seo_title" class="form-control @error('seo_title') is-invalid @enderror" 
                                           value="{{ old('seo_title', $store->seo_title) }}">
                                    @error('seo_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seo_meta_keyword">SEO Meta Keywords</label>
                                    <input type="text" name="seo_meta_keyword" id="seo_meta_keyword" class="form-control @error('seo_meta_keyword') is-invalid @enderror" 
                                           value="{{ old('seo_meta_keyword', $store->seo_meta_keyword) }}">
                                    @error('seo_meta_keyword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $store->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_h1">H1 Title</label>
                                    <input type="text" name="title_h1" id="title_h1" class="form-control @error('title_h1') is-invalid @enderror" 
                                           value="{{ old('title_h1', $store->title_h1) }}">
                                    @error('title_h1')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subtitle_h2">H2 Subtitle</label>
                                    <input type="text" name="subtitle_h2" id="subtitle_h2" class="form-control @error('subtitle_h2') is-invalid @enderror" 
                                           value="{{ old('subtitle_h2', $store->subtitle_h2) }}">
                                    @error('subtitle_h2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content_body">Content Body</label>
                            <textarea name="content_body" id="content_body" class="form-control @error('content_body') is-invalid @enderror" rows="5">{{ old('content_body', $store->content_body) }}</textarea>
                            @error('content_body')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo">Logo URL</label>
                                    <input type="text" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" 
                                           value="{{ old('logo', $store->logo) }}">
                                    @error('logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image_alt">Image Alt Text</label>
                                    <input type="text" name="image_alt" id="image_alt" class="form-control @error('image_alt') is-invalid @enderror" 
                                           value="{{ old('image_alt', $store->image_alt) }}">
                                    @error('image_alt')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image_title">Image Title</label>
                                    <input type="text" name="image_title" id="image_title" class="form-control @error('image_title') is-invalid @enderror" 
                                           value="{{ old('image_title', $store->image_title) }}">
                                    @error('image_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_robots">Meta Robots</label>
                                    <input type="text" name="meta_robots" id="meta_robots" class="form-control @error('meta_robots') is-invalid @enderror" 
                                           value="{{ old('meta_robots', $store->meta_robots) }}">
                                    @error('meta_robots')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="affiliate_links">Affiliate Links (JSON)</label>
                                    <textarea name="affiliate_links" id="affiliate_links" class="form-control @error('affiliate_links') is-invalid @enderror" rows="3">{{ old('affiliate_links', $store->affiliate_links) }}</textarea>
                                    @error('affiliate_links')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_details">Contact Details (JSON)</label>
                                    <textarea name="contact_details" id="contact_details" class="form-control @error('contact_details') is-invalid @enderror" rows="3">{{ old('contact_details', $store->contact_details) }}</textarea>
                                    @error('contact_details')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="play_store">Play Store URL</label>
                                    <input type="text" name="play_store" id="play_store" class="form-control @error('play_store') is-invalid @enderror" 
                                           value="{{ old('play_store', $store->play_store) }}">
                                    @error('play_store')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="app_store">App Store URL</label>
                                    <input type="text" name="app_store" id="app_store" class="form-control @error('app_store') is-invalid @enderror" 
                                           value="{{ old('app_store', $store->app_store) }}">
                                    @error('app_store')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="country_codes">Select Regions</label>
                                    @if(auth()->user()->role == 1 || auth()->user()->hasRole('admin') || auth()->user()->hasRole('super admin'))
                                        <select name="country_codes[]" id="country_codes" class="form-control select2 @error('country_codes') is-invalid @enderror" multiple>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->id }}" {{ in_array($region->id, old('country_codes', $selectedRegions ?? [])) ? 'selected' : '' }}>
                                                    {{ $region->country }} ({{ $region->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <p class="form-control-static">
                                            Your assigned region: 
                                            @if($regions->count() > 0)
                                                {{ $regions->first()->country }} ({{ $regions->first()->code }})
                                            @else
                                                None assigned
                                            @endif
                                        </p>
                                        <input type="hidden" name="country_codes[]" value="{{ $regions->count() > 0 ? $regions->first()->id : '' }}">
                                    @endif
                                    @error('country_codes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                           value="{{ old('start_date', $store->start_date) }}">
                                    @error('start_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date', $store->end_date) }}">
                                    @error('end_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sort">Sort Order</label>
                                    <input type="number" name="sort" id="sort" class="form-control @error('sort') is-invalid @enderror" 
                                           value="{{ old('sort', $store->sort) }}">
                                    @error('sort')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active', $store->active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Existing FAQs Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title">Existing FAQs</h5>
                            </div>
                            <div class="card-body">
                                <div id="existing-faqs-container">
                                    @if($store->faqs->count() > 0)
                                        @foreach($store->faqs as $faq)
                                        <div class="faq-entry mb-3">
                                            <input type="hidden" name="faq_id[]" value="{{ $faq->id }}">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <input type="text" name="existing_faq_question[]" class="form-control" placeholder="FAQ Question" value="{{ old('existing_faq_question.'.$loop->index, $faq->question) }}">
                                                </div>
                                                <div class="col-md-5">
                                                    <textarea name="existing_faq_answer[]" class="form-control" placeholder="FAQ Answer" rows="2">{{ old('existing_faq_answer.'.$loop->index, $faq->answer) }}</textarea>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" name="existing_faq_sort[]" class="form-control" placeholder="Sort" value="{{ old('existing_faq_sort.'.$loop->index, $faq->sort) }}">
                                                    <button type="button" class="btn btn-sm btn-danger remove-existing-faq mt-1">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <p>No existing FAQs for this store.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Add New FAQs Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title">Add New FAQs</h5>
                            </div>
                            <div class="card-body">
                                <div id="new-faq-container">
                                    <div class="faq-entry mb-3">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <input type="text" name="faq_question[]" class="form-control" placeholder="FAQ Question">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="faq_answer[]" class="form-control" placeholder="FAQ Answer">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" name="faq_sort[]" class="form-control" placeholder="Sort" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="button" class="btn btn-sm btn-success" id="add-new-faq">Add Another FAQ</button>
                            </div>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="submit" class="btn btn-primary">Update Store</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include select2 CSS and JS if not already included -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endsection

@push('js')
<script>
    $(function() {
        // Initialize select2 for the country codes multi-select
        if ($('#country_codes').length) {
            setTimeout(function() {
                $('#country_codes').select2({
                    placeholder: 'Select regions...',
                    allowClear: true
                });
            }, 500);
        }
        
        // Add new FAQ functionality
        $(document).off('click', '#add-new-faq').on('click', '#add-new-faq', function(e) {
            e.preventDefault();
            
            var newFaqHtml = '<div class="faq-entry mb-3">' +
                '<div class="row">' +
                '<div class="col-md-5">' +
                    '<input type="text" name="faq_question[]" class="form-control" placeholder="FAQ Question">' +
                '</div>' +
                '<div class="col-md-5">' +
                    '<input type="text" name="faq_answer[]" class="form-control" placeholder="FAQ Answer">' +
                '</div>' +
                '<div class="col-md-2">' +
                    '<input type="number" name="faq_sort[]" class="form-control" placeholder="Sort" value="0">' +
                    '<button type="button" class="btn btn-sm btn-danger remove-new-faq mt-1">Remove</button>' +
                '</div>' +
            '</div>' +
            '</div>';
            
            $('#new-faq-container').append(newFaqHtml);
        });
        
        // Use event delegation for remove buttons
        $(document).off('click', '.remove-new-faq').on('click', '.remove-new-faq', function(e) {
            e.preventDefault();
            $(this).closest('.faq-entry').remove();
        });
        
        // Handle removing existing FAQs
        $(document).off('click', '.remove-existing-faq').on('click', '.remove-existing-faq', function(e) {
            e.preventDefault();
            var faqEntry = $(this).closest('.faq-entry');
            var faqId = faqEntry.find('input[name="faq_id[]"]').val();
            
            // Create hidden input to mark for deletion
            var hiddenInput = $('<input type="hidden" name="delete_faq_ids[]" value="' + faqId + '">');
            faqEntry.append(hiddenInput);
            
            // Hide the entry visually
            faqEntry.hide();
            
            // Add a message indicating it will be deleted
            var deleteMessage = $('<div class="alert alert-warning">This FAQ will be deleted when you save the store. <button type="button" class="btn btn-sm btn-info restore-faq">Restore</button></div>');
            faqEntry.after(deleteMessage);
            
            // Add event listener to restore button
            deleteMessage.find('.restore-faq').click(function() {
                faqEntry.show();
                deleteMessage.remove();
                hiddenInput.remove();
            });
        });
    });
</script>
@endpush