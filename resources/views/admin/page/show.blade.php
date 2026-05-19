@extends('layouts.admin')

@section('title', 'View Page - ' . $page->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">View Page: {{ $page->title }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3>{{ $page->title }}</h3>
                            <p><strong>URL Slug:</strong> {{ $page->url_slug }}</p>
                            <p><strong>Content:</strong></p>
                            <div class="content-body">
                                {!! $page->content_body !!}
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">Page Information</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>SEO Title:</strong> {{ $page->seo_title ?? 'N/A' }}</p>
                                    <p><strong>Meta Description:</strong> {{ $page->meta_description ?? 'N/A' }}</p>
                                    <p><strong>Meta Keywords:</strong> {{ $page->seo_meta_keyword ?? 'N/A' }}</p>
                                    <p><strong>Meta Robots:</strong> {{ $page->meta_robots ?? 'N/A' }}</p>
                                    <p><strong>Available in Regions:</strong>
                                        @if($page->country_codes && is_array($page->country_codes) && count($page->country_codes) > 0)
                                            {{ implode(', ', $page->country_codes) }}
                                        @else
                                            All Regions
                                        @endif
                                    </p>
                                    <p><strong>Start Date:</strong> {{ $page->start_date ? $page->start_date->format('Y-m-d') : 'N/A' }}</p>
                                    <p><strong>End Date:</strong> {{ $page->end_date ? $page->end_date->format('Y-m-d') : 'N/A' }}</p>
                                    <p><strong>Sort Order:</strong> {{ $page->sort }}</p>
                                    <p><strong>Active:</strong> 
                                        @if($page->active)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </p>
                                    <p><strong>Created:</strong> {{ $page->created_at ? $page->created_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
                                    <p><strong>Updated:</strong> {{ $page->updated_at ? $page->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('admin.page.index') }}" class="btn btn-secondary">Back to List</a>
                            <a href="{{ route('admin.page.edit', $page->id) }}" class="btn btn-primary">Edit Page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection