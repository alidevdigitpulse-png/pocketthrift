@extends('admin.layouts.app')

@section('title', 'Create Store')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Create New Store</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">Back to Stores</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.stores.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span> <span class="badge badge-info char-count" data-for="title">0/50</span></label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title') }}" maxlength="50" required>
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
                                    <select name="category_id" id="category_id" class="form-control select2 @error('category_id') is-invalid @enderror">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                           value="{{ old('url_slug') }}" maxlength="255" required>
                                    @error('url_slug')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seo_title">SEO Title <span class="badge badge-info char-count" data-for="seo_title">0/70</span></label>
                                    <input type="text" name="seo_title" id="seo_title" class="form-control @error('seo_title') is-invalid @enderror" 
                                           value="{{ old('seo_title') }}" maxlength="70">
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
                                    <label for="seo_meta_keyword">SEO Meta Keywords <span class="badge badge-info char-count" data-for="seo_meta_keyword">0/50</span></label>
                                    <input type="text" name="seo_meta_keyword" id="seo_meta_keyword" class="form-control @error('seo_meta_keyword') is-invalid @enderror" 
                                           value="{{ old('seo_meta_keyword') }}" maxlength="50">
                                    @error('seo_meta_keyword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_description">Meta Description <span class="badge badge-info char-count" data-for="meta_description">0/170</span></label>
                                    <textarea name="meta_description" id="meta_description" class="form-control @error('meta_description') is-invalid @enderror" maxlength="170">{{ old('meta_description') }}</textarea>
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
                                    <label for="title_h1">H1 Title <span class="badge badge-info char-count" data-for="title_h1">0/80</span></label>
                                    <input type="text" name="title_h1" id="title_h1" class="form-control @error('title_h1') is-invalid @enderror" 
                                           value="{{ old('title_h1') }}" maxlength="80">
                                    @error('title_h1')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subtitle_h2">H2 Subtitle <span class="badge badge-info char-count" data-for="subtitle_h2">0/80</span></label>
                                    <input type="text" name="subtitle_h2" id="subtitle_h2" class="form-control @error('subtitle_h2') is-invalid @enderror" 
                                           value="{{ old('subtitle_h2') }}" maxlength="80">
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
                            <textarea name="content_body" id="content_body" class="editor" rows="5">{{ old('content_body') }}</textarea>
                            @error('content_body')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <input type="file" name="logo" id="logo" class="dropify @error('logo') is-invalid @enderror">
                                    @error('logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image_alt">Image Alt Text <span class="badge badge-info char-count" data-for="image_alt">0/50</span></label>
                                    <input type="text" name="image_alt" id="image_alt" class="form-control @error('image_alt') is-invalid @enderror" 
                                           value="{{ old('image_alt') }}" maxlength="50">
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
                                    <label for="image_title">Image Title <span class="badge badge-info char-count" data-for="image_title">0/50</span></label>
                                    <input type="text" name="image_title" id="image_title" class="form-control @error('image_title') is-invalid @enderror" 
                                           value="{{ old('image_title') }}" maxlength="50">
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
                                    <select name="meta_robots" id="meta_robots" 
                                            class="form-control @error('meta_robots') is-invalid @enderror">
                            
                                        <option value="Index, Follow" {{ old('meta_robots') == 'Index, Follow' ? 'selected' : '' }}>
                                            Index, Follow
                                        </option>
                            
                                        <option value="Noindex, Follow" {{ old('meta_robots') == 'Noindex, Follow' ? 'selected' : '' }}>
                                            Noindex, Follow
                                        </option>
                            
                                        <option value="Index, Nofollow" {{ old('meta_robots') == 'Index, Nofollow' ? 'selected' : '' }}>
                                            Index, Nofollow
                                        </option>
                            
                                        <option value="Noindex, Nofollow" {{ old('meta_robots') == 'Noindex, Nofollow' ? 'selected' : '' }}>
                                            Noindex, Nofollow
                                        </option>
                            
                                    </select>
                            
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
                                    <textarea name="affiliate_links" id="affiliate_links" class="form-control @error('affiliate_links') is-invalid @enderror" rows="3">{{ old('affiliate_links') }}</textarea>
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
                                    <textarea name="contact_details" id="contact_details" class="form-control @error('contact_details') is-invalid @enderror" rows="3">{{ old('contact_details') }}</textarea>
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
                                           value="{{ old('play_store') }}">
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
                                           value="{{ old('app_store') }}">
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
                                                <option value="{{ $region->id }}" {{ in_array($region->id, old('country_codes', [])) ? 'selected' : '' }}>
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
                                    <label for="sort">Sort Order</label>
                                    <input type="number" name="sort" id="sort" class="form-control @error('sort') is-invalid @enderror" 
                                           value="{{ old('sort', 0) }}">
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
                                        <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active', true) ? 'checked' : '' }}>

                                        <label class="form-check-label" for="active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- FAQs Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title">FAQs</h5>
                            </div>
                            <div class="card-body">
                                <!-- FAQ Form for adding multiple FAQs -->
                                <div id="faq-container">
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
                                
                                <button type="button" class="btn btn-sm btn-success" id="add-faq">Add Another FAQ</button>
                            </div>
                        </div>

                        <!-- Social Links Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title">Social Links</h5>
                            </div>
                            <div class="card-body">
                                <div id="social-links-container">
                                    <div class="social-link-entry mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="social_app_id" class="form-label">Social App</label>
                                                <select name="social_app_id[]" class="form-control">
                                                    <option value="">Select Social App</option>
                                                    @foreach($socialApps as $app)
                                                        <option value="{{ $app->id }}">{{ $app->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="social_link" class="form-label">Social Link</label>
                                                <input type="text" name="social_link[]" class="form-control" placeholder="Social Link URL">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="social_link_sort" class="form-label">Sort</label>
                                                <input type="number" name="social_link_sort[]" class="form-control" placeholder="Sort" value="0">
                                                <button type="button" class="btn btn-sm btn-danger remove-social-link mt-1" style="display:none;">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-success" id="add-social-link">Add Another Social Link</button>
                            </div>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="submit" class="btn btn-primary">Create Store</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        if ($('#country_codes').length) {
            setTimeout(function() {
                $('#country_codes').select2({
                    placeholder: 'Select regions...',
                    allowClear: true
                });
            }, 500);
        }

        // Initialize select2 for category dropdown
        if ($('#category_id').length) {
            setTimeout(function() {
                $('#category_id').select2({
                    placeholder: 'Select Category',
                    allowClear: true
                });
            }, 500);
        }
        
        // Add FAQ functionality - wait for document to be fully ready
        $(document).off('click', '#add-faq').on('click', '#add-faq', function(e) {
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
                    '<button type="button" class="btn btn-sm btn-danger remove-faq mt-1">Remove</button>' +
                '</div>' +
            '</div>' +
            '</div>';
            
            $('#faq-container').append(newFaqHtml);
        });
        
        // Use event delegation for remove buttons
        $(document).off('click', '.remove-faq').on('click', '.remove-faq', function(e) {
            e.preventDefault();
            $(this).closest('.faq-entry').remove();
        });

        // Social Links Functionality
        $(document).off('click', '#add-social-link').on('click', '#add-social-link', function(e) {
            e.preventDefault();
            
            var newSocialLinkHtml = '<div class="social-link-entry mb-3">' +
                '<div class="row">' +
                '<div class="col-md-4">' +
                    '<label for="social_app_id" class="form-label">Social App</label>' +
                    '<select name="social_app_id[]" class="form-control">' +
                        '<option value="">Select Social App</option>' +
                        '@foreach($socialApps as $app)' +
                            '<option value="{{ $app->id }}">{{ $app->title }}</option>' +
                        '@endforeach' +
                    '</select>' +
                '</div>' +
                '<div class="col-md-6">' +
                    '<label for="social_link" class="form-label">Social Link</label>' +
                    '<input type="text" name="social_link[]" class="form-control" placeholder="Social Link URL">' +
                '</div>' +
                '<div class="col-md-2">' +
                    '<label for="social_link_sort" class="form-label">Sort</label>' +
                    '<input type="number" name="social_link_sort[]" class="form-control" placeholder="Sort" value="0">' +
                    '<button type="button" class="btn btn-sm btn-danger remove-social-link mt-1">Remove</button>' +
                '</div>' +
            '</div>' +
            '</div>';
            
            $('#social-links-container').append(newSocialLinkHtml);
        });

        $(document).off('click', '.remove-social-link').on('click', '.remove-social-link', function(e) {
            e.preventDefault();
            $(this).closest('.social-link-entry').remove();
        });

        // Initialize character counters
        $('.char-count').each(function() {
            var targetId = $(this).data('for');
            var input = $('#' + targetId);
            if (input.length) {
                var max = input.attr('maxlength');
                var count = input.val().length;
                $(this).text(count + '/' + max);
                
                // Set color based on usage
                updateCharCountColor($(this), count, max);
            }
        });

        // Update counts on input
        $(document).on('input', 'input[maxlength], textarea[maxlength]', function() {
            var inputId = $(this).attr('id');
            var counter = $('.char-count[data-for="' + inputId + '"]');
            if (counter.length) {
                var max = $(this).attr('maxlength');
                var count = $(this).val().length;
                counter.text(count + '/' + max);
                updateCharCountColor(counter, count, max);
            }
        });

        function updateCharCountColor(counter, count, max) {
            counter.removeClass('badge-info badge-warning badge-danger');
            if (count >= max) {
                counter.addClass('badge-danger');
            } else if (count >= max * 0.9) {
                counter.addClass('badge-warning');
            } else {
                counter.addClass('badge-info');
            }
        }
    });
</script>
@endpush