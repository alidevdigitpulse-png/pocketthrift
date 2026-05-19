@extends('admin.layouts.app')

@section('title', isset($data) ? 'Edit Page' : 'Create Page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{ isset($data) ? 'Edit Page' : 'Create Page' }}
                    </h6>
                </div>
                <div class="card-body">
                    <form id="pageForm" method="POST" action="{{ isset($data) ? route('admin.page.update', $data->id) : route('admin.page.store') }}">
                        @csrf
                        @if(isset($data))
                            @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $data->title ?? '') }}" required>
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="url_slug">URL Slug</label>
                                    <input type="text" class="form-control" id="url_slug" name="url_slug" value="{{ old('url_slug', $data->url_slug ?? '') }}">
                                    <small class="form-text text-muted">Leave empty to auto-generate from title</small>
                                    @error('url_slug')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="content_body">Content Body</label>
                                    <textarea class="editor" id="content_body" name="content_body" rows="10">{!! old('content_body', $data->content_body ?? '') !!}</textarea>
                                    @error('content_body')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="seo_title">SEO Title</label>
                                    <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ old('seo_title', $data->seo_title ?? '') }}">
                                    @error('seo_title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="seo_meta_keyword">SEO Meta Keywords</label>
                                    <textarea class="form-control" id="seo_meta_keyword" name="seo_meta_keyword" rows="3">{{ old('seo_meta_keyword', $data->seo_meta_keyword ?? '') }}</textarea>
                                    @error('seo_meta_keyword')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $data->meta_description ?? '') }}</textarea>
                                    @error('meta_description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="meta_robots">Meta Robots</label>
                                    <input type="text" class="form-control" id="meta_robots" name="meta_robots" value="{{ old('meta_robots', $data->meta_robots ?? '') }}">
                                    <small class="form-text text-muted">e.g., index, follow</small>
                                    @error('meta_robots')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">Page Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="country_codes">Available in Regions</label>
                                            <select class="form-control" id="country_codes" name="country_codes[]" multiple>
                                                @foreach(\App\Models\Region::all() as $region)
                                                    @php
                                                        $selectedCodes = old('country_codes', $data->country_codes ?? []);
                                                        if (is_string($selectedCodes)) {
                                                            $selectedCodes = explode(',', $selectedCodes);
                                                        }
                                                    @endphp
                                                    <option value="{{ $region->code }}" 
                                                        {{ in_array($region->code, $selectedCodes) ? 'selected' : '' }}>
                                                        {{ $region->country }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple regions</small>
                                            @error('country_codes')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="start_date">Start Date</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', isset($data) && $data->start_date ? $data->start_date->format('Y-m-d') : '') }}">
                                            @error('start_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="end_date">End Date</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', isset($data) && $data->end_date ? $data->end_date->format('Y-m-d') : '') }}">
                                            @error('end_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="sort">Sort Order</label>
                                            <input type="number" class="form-control" id="sort" name="sort" value="{{ old('sort', $data->sort ?? 0) }}">
                                            @error('sort')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="active" name="active" value="1" {{ old('active', $data->active ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active">Active</label>
                                            @error('active')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.page.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">{{ isset($data) ? 'Update Page' : 'Create Page' }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('url_slug');
    
    if (!slugInput.value) {
        titleInput.addEventListener('input', function() {
            const title = this.value.trim();
            if (!slugInput.value) { // Only auto-generate if slug is empty
                const slug = title.toLowerCase()
                    .replace(/[^\w\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-'); // Replace spaces with hyphens
                slugInput.value = slug;
            }
        });
    }

    // Initialize multiple select for country codes
    const countryCodesSelect = document.getElementById('country_codes');
    if (countryCodesSelect) {
        // Add select/deselect all functionality
        const selectAllBtn = document.createElement('button');
        selectAllBtn.type = 'button';
        selectAllBtn.className = 'btn btn-sm btn-outline-secondary mb-2';
        selectAllBtn.textContent = 'Select All';
        selectAllBtn.addEventListener('click', function() {
            for (let i = 0; i < countryCodesSelect.options.length; i++) {
                countryCodesSelect.options[i].selected = true;
            }
        });

        const selectNoneBtn = document.createElement('button');
        selectNoneBtn.type = 'button';
        selectNoneBtn.className = 'btn btn-sm btn-outline-secondary mb-2 ml-2';
        selectNoneBtn.textContent = 'Select None';
        selectNoneBtn.addEventListener('click', function() {
            for (let i = 0; i < countryCodesSelect.options.length; i++) {
                countryCodesSelect.options[i].selected = false;
            }
        });

        countryCodesSelect.parentNode.insertBefore(selectAllBtn, countryCodesSelect);
        countryCodesSelect.parentNode.insertBefore(selectNoneBtn, countryCodesSelect);
    }

    // Handle form submission
    const form = document.getElementById('pageForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                alert(data.message);
                window.location.href = '{{ route("admin.page.index") }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the page.');
        });
    });
});
</script>
@endsection