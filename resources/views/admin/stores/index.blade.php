@extends('admin.layouts.app')

@section('title', 'Stores')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Stores</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.stores.create') }}" class="btn btn-primary">Add New Store</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.stores.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="search" class="form-control" placeholder="Search stores..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="active" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="category" class="form-control">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        @if(auth()->user()->role == 1 || auth()->user()->hasRole('admin') || auth()->user()->hasRole('super admin'))
                                            <select name="country" class="form-control">
                                                <option value="">All Regions</option>
                                                @foreach($regions as $region)
                                                    <option value="{{ $region->code }}" {{ request('country') == $region->code ? 'selected' : '' }}>
                                                        {{ $region->country }} ({{ $region->code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="country" value="{{ auth()->user()->assigned_regions }}">
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">Reset</a>
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
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Sort</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stores as $store)
                                    <tr>
                                        <td>{{ $store->id }}</td>
                                        <td>{{ Str::limit($store->title, 20) }}</td>
                                        <td>
                                            @if($store->category)
                                                {{ $store->category->title }}
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $store->active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $store->active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $store->sort }}</td>
                                        <td>{{ $store->start_date ? $store->start_date->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ $store->end_date ? $store->end_date->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ $store->created_at ? $store->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.stores.show', $store->id) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('admin.stores.edit', $store->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this store?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No stores found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $stores->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection