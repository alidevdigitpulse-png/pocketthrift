@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Category Details</h4>
                    <div class="card-options">
                        <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <a href="{{ route('admin.category.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $category->id }}</td>
                                </tr>
                                <tr>
                                    <th>Title</th>
                                    <td>{{ $category->title }}</td>
                                </tr>
                                <tr>
                                    <th>Title (English)</th>
                                    <td>{{ $category->title_eng ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>SEO Title</th>
                                    <td>{{ $category->seo_title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>URL Slug</th>
                                    <td>{{ $category->url_slug }}</td>
                                </tr>
                                <tr>
                                    <th>Meta Description</th>
                                    <td>{{ Str::limit($category->meta_description, 100) ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>SEO Meta Keywords</th>
                                    <td>{{ Str::limit($category->seo_meta_keyword, 100) ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $category->active ? 'badge-success' : 'badge-danger' }}">
                                            {{ $category->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Is Seasonal</th>
                                    <td>
                                        <span class="badge {{ $category->is_seasonal ? 'badge-warning' : 'badge-info' }}">
                                            {{ $category->is_seasonal ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>H1 Title</th>
                                    <td>{{ $category->title_h1 ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>H2 Subtitle</th>
                                    <td>{{ $category->subtitle_h2 ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Logo</th>
                                    <td>{{ $category->logo ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Image Alt Text</th>
                                    <td>{{ $category->image_alt ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Image Title</th>
                                    <td>{{ $category->image_title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Meta Robots</th>
                                    <td>{{ $category->meta_robots ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Start Date</th>
                                    <td>{{ $category->start_date ? $category->start_date->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>End Date</th>
                                    <td>{{ $category->end_date ? $category->end_date->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Sort Order</th>
                                    <td>{{ $category->sort }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Content Body</h5>
                            <div class="border p-3">
                                {!! $category->content_body ?? 'N/A' !!}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h5>Assigned Regions</h5>
                            <ul class="list-group">
                                @forelse($assignedRegions as $region)
                                    <li class="list-group-item">{{ $region->country }} ({{ $region->code }})</li>
                                @empty
                                    <li class="list-group-item">No regions assigned</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $category->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $category->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated By</th>
                                    <td>{{ $category->user ? $category->user->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Deleted At</th>
                                    <td>{{ $category->deleted_at ? $category->deleted_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Deleted By</th>
                                    <td>{{ $category->deleter ? $category->deleter->name : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection