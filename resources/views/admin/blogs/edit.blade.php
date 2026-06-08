@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Blog</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select name="category_id" id="category_id" class="form-control">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $blog->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="title" class="form-label">Title *</label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ old('title', $blog->title) }}" required>
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="seo_title" class="form-label">SEO Title</label>
                                        <input type="text" name="seo_title" id="seo_title" class="form-control"
                                            value="{{ old('seo_title', $blog->seo_title) }}">
                                        @error('seo_title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="url_slug" class="form-label">URL Slug *</label>
                                        <input type="text" name="url_slug" id="url_slug" class="form-control"
                                            value="{{ old('url_slug', $blog->url_slug) }}" required>
                                        @error('url_slug')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <textarea name="meta_description" id="meta_description" class="form-control" rows="3">{{ old('meta_description', $blog->meta_description) }}</textarea>
                                        @error('meta_description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="short_description" class="form-label">Short Description</label>
                                        <textarea name="short_description" id="short_description" class="form-control" rows="3">{{ old('short_description', $blog->short_description) }}</textarea>
                                        @error('short_description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" name="image" id="image" class="dropify" accept="image/*"
                                            data-default-file="{{ $blog->logo ? asset('uploads/' . $blog->logo) : '' }}">
                                        <small class="form-text text-muted">Upload a featured image for the blog
                                            post</small>
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- <div class="form-group mb-3">
                                            <label for="logo" class="form-label">Logo</label>
                                            <input type="text" name="logo" id="logo" class="form-control"
                                                value="{{ old('logo', $blog->logo) }}">
                                            <small class="form-text text-muted">Logo filename</small>
                                            @error('logo')
        <div class="text-danger">{{ $message }}</div>
    @enderror
                                        </div> -->

                                    <div class="form-group mb-3">
                                        <label for="image_alt" class="form-label">Image Alt Text</label>
                                        <input type="text" name="image_alt" id="image_alt" class="form-control"
                                            value="{{ old('image_alt', $blog->image_alt) }}">
                                        @error('image_alt')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="image_title" class="form-label">Image Title</label>
                                        <input type="text" name="image_title" id="image_title" class="form-control"
                                            value="{{ old('image_title', $blog->image_title) }}">
                                        @error('image_title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="seo_meta_keyword" class="form-label">SEO Meta Keywords</label>
                                        <textarea name="seo_meta_keyword" id="seo_meta_keyword" class="form-control" rows="3">{{ old('seo_meta_keyword', $blog->seo_meta_keyword) }}</textarea>
                                        <small class="form-text text-muted">Separate keywords with commas</small>
                                        @error('seo_meta_keyword')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="meta_robots" class="form-label">Meta Robots</label>
                                        <select name="meta_robots" id="meta_robots"
                                            class="form-control @error('meta_robots') is-invalid @enderror">
                                            <option value="Index, Follow"
                                                {{ old('meta_robots', $blog->meta_robots) == 'Index, Follow' ? 'selected' : '' }}>
                                                Index, Follow
                                            </option>
                                            <option value="Noindex, Follow"
                                                {{ old('meta_robots', $blog->meta_robots) == 'Noindex, Follow' ? 'selected' : '' }}>
                                                Noindex, Follow
                                            </option>
                                            <option value="Index, Nofollow"
                                                {{ old('meta_robots', $blog->meta_robots) == 'Index, Nofollow' ? 'selected' : '' }}>
                                                Index, Nofollow
                                            </option>
                                            <option value="Noindex, Nofollow"
                                                {{ old('meta_robots', $blog->meta_robots) == 'Noindex, Nofollow' ? 'selected' : '' }}>
                                                Noindex, Nofollow
                                            </option>
                                        </select>
                                        <small class="form-text text-muted">Select Meta Robots option</small>
                                        @error('meta_robots')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="est_read_time" class="form-label">Estimated Read Time
                                            (minutes)</label>
                                        <input type="number" name="est_read_time" id="est_read_time"
                                            class="form-control" value="{{ old('est_read_time', $blog->est_read_time) }}"
                                            min="0">
                                        @error('est_read_time')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="blog_table" class="form-label">Blog Table</label>
                                        <input type="text" name="blog_table" id="blog_table" class="form-control"
                                            value="{{ old('blog_table', $blog->blog_table) }}">
                                        <small class="form-text text-muted">Additional table or data reference</small>
                                        @error('blog_table')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="start_date" class="form-label">Published Date</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control"
                                            value="{{ old('start_date', $blog->start_date ? $blog->start_date->format('Y-m-d') : '') }}">
                                        @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sort" class="form-label">Sort Order *</label>
                                        <input type="number" name="sort" id="sort" class="form-control"
                                            value="{{ old('sort', $blog->sort) }}" required>
                                        @error('sort')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="active" id="active"
                                                class="form-check-input" value="1"
                                                {{ old('active', $blog->active) ? 'checked' : '' }}>
                                            <label for="active" class="form-check-label">Active</label>
                                        </div>
                                        @error('active')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Region selection for admins -->
                                    @if (auth()->user()->role == 1 || auth()->user()->hasRole('admin') || auth()->user()->hasRole('super admin'))
                                        <div class="form-group mb-3">
                                            <label for="country_codes" class="form-label">Regions</label>
                                            <select name="country_codes[]" id="country_codes" class="form-control"
                                                multiple>
                                                @foreach ($regions as $region)
                                                    <option value="{{ $region->code }}"
                                                        {{ in_array($region->code, $selectedRegions) ? 'selected' : '' }}>
                                                        {{ $region->country }} ({{ $region->code }})</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple
                                                regions</small>
                                        </div>
                                    @else
                                        <input type="hidden" name="country_codes[]"
                                            value="{{ auth()->user()->assigned_regions }}">
                                    @endif

                                    <div class="form-group mb-3">
                                        <label for="updated_by" class="form-label">Updated By</label>
                                        <select name="updated_by" id="updated_by" class="form-control">
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('updated_by', auth()->id()) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                        @error('updated_by')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="content_body" class="form-label">Content Body</label>
                                        <textarea name="content_body" id="content_body" class="editor" rows="10">{!! old('content_body', $blog->content_body) !!}</textarea>
                                        @error('content_body')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- FAQs Section -->
                            <div class="card mt-4 mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">FAQs</h5>
                                </div>
                                <div class="card-body">
                                    <!-- FAQ Form for adding multiple FAQs -->
                                    <div id="faq-container">
                                        @if ($blog->faqs && $blog->faqs->count() > 0)
                                            @foreach ($blog->faqs as $faq)
                                                <div class="faq-entry mb-3">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <input type="text" name="faq_question[]"
                                                                class="form-control" placeholder="FAQ Question"
                                                                value="{{ $faq->question }}">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" name="faq_answer[]"
                                                                class="form-control" placeholder="FAQ Answer"
                                                                value="{{ $faq->answer }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="number" name="faq_sort[]" class="form-control"
                                                                placeholder="Sort" value="{{ $faq->sort }}">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove-faq mt-1">Remove</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="faq-entry mb-3">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <input type="text" name="faq_question[]" class="form-control"
                                                            placeholder="FAQ Question">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" name="faq_answer[]" class="form-control"
                                                            placeholder="FAQ Answer">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" name="faq_sort[]" class="form-control"
                                                            placeholder="Sort" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <button type="button" class="btn btn-sm btn-success" id="add-faq">Add Another
                                        FAQ</button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Blog</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for category dropdown
            $('#category_id').select2({
                placeholder: 'Select Category',
                allowClear: true,
                width: '100%'
            });

            // Add FAQ functionality
            $('#add-faq').on('click', function(e) {
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
            $(document).on('click', '.remove-faq', function(e) {
                e.preventDefault();
                $(this).closest('.faq-entry').remove();
            });
        });
    </script>
@endpush
