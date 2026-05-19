@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Category</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $category->title) }}" maxlength="255" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_eng">Title (English)</label>
                                    <input type="text" name="title_eng" id="title_eng" class="form-control @error('title_eng') is-invalid @enderror" value="{{ old('title_eng', $category->title_eng) }}" maxlength="255">
                                    @error('title_eng')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seo_title">SEO Title</label>
                                    <input type="text" name="seo_title" id="seo_title" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title', $category->seo_title) }}" maxlength="255">
                                    @error('seo_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url_slug">URL Slug <span class="text-danger">*</span></label>
                                    <input type="text" name="url_slug" id="url_slug" class="form-control @error('url_slug') is-invalid @enderror" value="{{ old('url_slug', $category->url_slug) }}" maxlength="255" required>
                                    @error('url_slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="3" maxlength="255">{{ old('meta_description', $category->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seo_meta_keyword">SEO Meta Keywords</label>
                                    <textarea name="seo_meta_keyword" id="seo_meta_keyword" class="form-control @error('seo_meta_keyword') is-invalid @enderror" rows="3" maxlength="255">{{ old('seo_meta_keyword', $category->seo_meta_keyword) }}</textarea>
                                    @error('seo_meta_keyword')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_h1">H1 Title</label>
                                    <input type="text" name="title_h1" id="title_h1" class="form-control @error('title_h1') is-invalid @enderror" value="{{ old('title_h1', $category->title_h1) }}" maxlength="255">
                                    @error('title_h1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subtitle_h2">H2 Subtitle</label>
                                    <input type="text" name="subtitle_h2" id="subtitle_h2" class="form-control @error('subtitle_h2') is-invalid @enderror" value="{{ old('subtitle_h2', $category->subtitle_h2) }}" maxlength="255">
                                    @error('subtitle_h2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="content_body">Content Body</label>
                            <textarea name="content_body" id="content_body" class="form-control @error('content_body') is-invalid @enderror" rows="5">{{ old('content_body', $category->content_body) }}</textarea>
                            @error('content_body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <input type="file" name="logo" id="logo" class="dropify @error('logo') is-invalid @enderror">
                                    @if($category->logo)
                                        <p>Current Logo: <img src="{{ asset('uploads/' . $category->logo) }}" alt="Current Logo" width="50" height="50"></p>
                                    @endif
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image_alt">Image Alt Text</label>
                                    <input type="text" name="image_alt" id="image_alt" class="form-control @error('image_alt') is-invalid @enderror" value="{{ old('image_alt', $category->image_alt) }}" maxlength="255">
                                    @error('image_alt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image_title">Image Title</label>
                                    <input type="text" name="image_title" id="image_title" class="form-control @error('image_title') is-invalid @enderror" value="{{ old('image_title', $category->image_title) }}" maxlength="255">
                                    @error('image_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_robots">Meta Robots</label>
                                    <input type="text" name="meta_robots" id="meta_robots" class="form-control @error('meta_robots') is-invalid @enderror" value="{{ old('meta_robots', $category->meta_robots) }}" maxlength="255">
                                    @error('meta_robots')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sort">Sort Order</label>
                                    <input type="number" name="sort" id="sort" class="form-control @error('sort') is-invalid @enderror" value="{{ old('sort', $category->sort) }}">
                                    @error('sort')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="active">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" value="1" {{ old('active', $category->active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">Active</label>
                                    </div>
                                    @error('active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_seasonal">Seasonal</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_seasonal" name="is_seasonal" value="1" {{ old('is_seasonal', $category->is_seasonal) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_seasonal">Is Seasonal</label>
                                    </div>
                                    @error('is_seasonal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="country_codes">Regions (Country Codes)</label>
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
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Category</button>
                            <a href="{{ route('admin.category.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include select2 CSS and JS if not already included -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize select2 for the country codes multi-select
    $('#country_codes').select2({
        placeholder: 'Select regions...',
        allowClear: true
    });
});
</script>
@endsection