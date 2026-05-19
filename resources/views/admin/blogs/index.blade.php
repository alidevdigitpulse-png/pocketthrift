@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Blogs</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">Add New Blog</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.blog.index') }}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <select name="search" class="form-control blog-search-select2">
                                            <option></option>
                                            @if(request('search'))
                                                <option value="{{ request('search') }}" selected>{{ request('search') }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="active" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive</option>
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
                                        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Reset</a>
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
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Regions</th>
                                    <th>Sort</th>
                                    <th>Status</th>
                                    <th>Read Time</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blogs as $blog)
                                    <tr>
                                        <td>{{ $blog->id }}</td>
                                        <td>
                                            @if($blog->logo)
                                                <img src="{{ asset('uploads/' . $blog->logo) }}" alt="Blog Image" width="50" height="50">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($blog->title, 20) }}</td>
                                        <td>{{ $blog->category ? $blog->category->title : 'N/A' }}</td>
                                        <td>
                                            @php
                                                $codes = $blog->country_codes;
                                                if (empty($codes)) {
                                                    $raw = $blog->getRawOriginal('country_codes');
                                                    if (!empty($raw)) {
                                                        // Handle legacy CSV or bad JSON
                                                        $clean = str_replace(['[', ']', '"', "'"], '', $raw);
                                                        $codes = array_filter(array_map('trim', explode(',', $clean)));
                                                    }
                                                }
                                            @endphp

                                            @if(!empty($codes))
                                                @php
                                                    $regionNames = \App\Models\Region::whereIn('code', $codes)->pluck('country')->toArray();
                                                @endphp
                                                {{ Str::limit(implode(', ', $regionNames), 30) }}
                                            @else
                                                <span class="text-muted">All Regions</span>
                                            @endif
                                        </td>
                                        <td>{{ $blog->sort }}</td>
                                        <td>
                                            <span class="badge {{ $blog->active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $blog->active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $blog->est_read_time ? $blog->est_read_time . ' min' : 'N/A' }}</td>
                                        <td>{{ $blog->created_at ? $blog->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.blog.show', $blog->id) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" class="d-inline" id="delete-form-{{ $blog->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteBlog({{ $blog->id }})">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No blogs found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $blogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function deleteBlog(id) {
      Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
          if (result.isConfirmed) {
              document.getElementById('delete-form-' + id).submit();
          }
      })
  }

    $(document).ready(function() {
        $('.blog-search-select2').select2({
            placeholder: 'Search blogs...',
            allowClear: true,
            width: '100%',
            ajax: {
                url: '{{ route("admin.blog.search") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            tags: true
        });
    });
</script>
@endpush