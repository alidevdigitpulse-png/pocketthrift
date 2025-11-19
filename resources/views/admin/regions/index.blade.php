@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Regions</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.region.create') }}" class="btn btn-primary">Add New Region</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.region.index') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="search" class="form-control" placeholder="Search regions..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="active" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('admin.region.index') }}" class="btn btn-secondary">Reset</a>
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
                                    <th>Country</th>
                                    <th>Code</th>
                                    <th>Sort</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($regions as $region)
                                    <tr>
                                        <td>{{ $region->id }}</td>
                                        <td>{{ $region->country }}</td>
                                        <td>{{ $region->code }}</td>
                                        <td>{{ $region->sort }}</td>
                                        <td>
                                            <span class="badge {{ $region->active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $region->active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $region->creator ? $region->creator->name : 'N/A' }}</td>
                                        <td>{{ $region->created_at ? $region->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.region.edit', $region->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.region.destroy', $region->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this region?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No regions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $regions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection