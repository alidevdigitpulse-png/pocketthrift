@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Categories</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.category.create') }}" class="btn btn-primary">Add New Category</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.category.index') }}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="text" name="search" class="form-control" placeholder="Search categories..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="active" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="is_seasonal" class="form-control">
                                            <option value="">All Seasonal</option>
                                            <option value="1" {{ request('is_seasonal') == '1' ? 'selected' : '' }}>Seasonal</option>
                                            <option value="0" {{ request('is_seasonal') == '0' ? 'selected' : '' }}>Not Seasonal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        @if(auth()->user()->role == 1 || auth()->user()->hasRole('admin') || auth()->user()->hasRole('super admin'))
                                            <select name="region" class="form-control">
                                                <option value="">All Regions</option>
                                                @foreach($regions as $region)
                                                    <option value="{{ $region->code }}" {{ request('region') == $region->code ? 'selected' : '' }}>
                                                        {{ $region->country }} ({{ $region->code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="region" value="{{ auth()->user()->assigned_regions }}">
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('admin.category.index') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Regions</th>
                                    <th>Sort</th>
                                    <th>Status</th>
                                    <th>Seasonal</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ Str::limit($category->title, 20) }}</td>
                                        <td>
                                            @if($category->country_code_list)
                                                {{ Str::limit($category->country_code_list, 20) }}
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->sort }}</td>
                                        <td>
                                            <span class="badge {{ $category->active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $category->active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $category->is_seasonal ? 'badge-warning' : 'badge-info' }}">
                                                {{ $category->is_seasonal ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td>{{ $category->start_date ? $category->start_date->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ $category->end_date ? $category->end_date->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ $category->created_at ? $category->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.category.show', $category->id) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No categories found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection