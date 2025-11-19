@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Blog Details</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Back to List</a>
                            <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-primary">Edit</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>ID:</th>
                                    <td>{{ $blog->id }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $blog->category ? $blog->category->title : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Title:</th>
                                    <td>{{ $blog->title }}</td>
                                </tr>
                                <tr>
                                    <th>SEO Title:</th>
                                    <td>{{ $blog->seo_title ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>URL Slug:</th>
                                    <td>{{ $blog->url_slug }}</td>
                                </tr>
                                <tr>
                                    <th>Meta Description:</th>
                                    <td>{{ Str::limit($blog->meta_description, 100) ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Short Description:</th>
                                    <td>{{ Str::limit($blog->short_description, 100) ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Estimated Read Time:</th>
                                    <td>{{ $blog->est_read_time ? $blog->est_read_time . ' minutes' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Logo:</th>
                                    <td>
                                        @if($blog->logo)
                                            <img src="{{ asset('storage/uploads/' . $blog->logo) }}" alt="Blog Image" width="100" height="100">
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Image Alt Text:</th>
                                    <td>{{ $blog->image_alt ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Image Title:</th>
                                    <td>{{ $blog->image_title ?: 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Start Date:</th>
                                    <td>{{ $blog->start_date ? $blog->start_date->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>End Date:</th>
                                    <td>{{ $blog->end_date ? $blog->end_date->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Meta Robots:</th>
                                    <td>{{ $blog->meta_robots ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Blog Table:</th>
                                    <td>{{ $blog->blog_table ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Regions:</th>
                                    <td>
                                        @if($assignedRegions && count($assignedRegions))
                                            {{ implode(', ', $assignedRegions->pluck('country')->toArray()) }}
                                        @else
                                            All Regions
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Active:</th>
                                    <td>
                                        <span class="badge {{ $blog->active ? 'badge-success' : 'badge-danger' }}">
                                            {{ $blog->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Sort Order:</th>
                                    <td>{{ $blog->sort }}</td>
                                </tr>
                                <tr>
                                    <th>Created By:</th>
                                    <td>{{ $blog->creator ? $blog->creator->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Updated By:</th>
                                    <td>{{ $blog->updater ? $blog->updater->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $blog->created_at ? $blog->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At:</th>
                                    <td>{{ $blog->updated_at ? $blog->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Content Body:</h5>
                            <div class="border p-3" style="min-height: 200px;">
                                {!! $blog->content_body ?: '<em>No content body provided</em>' !!}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>SEO Meta Keywords:</h5>
                            <div class="border p-3">
                                {{ $blog->seo_meta_keyword ?: 'N/A' }}
                            </div>
                        </div>
                    </div>
                    
                    @if($blog->deleted_at)
                        <div class="alert alert-warning mt-4">
                            <strong>Deleted:</strong> This blog was deleted on {{ $blog->deleted_at->format('Y-m-d H:i:s') }}
                            @if($blog->deleter)
                                by {{ $blog->deleter->name }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection