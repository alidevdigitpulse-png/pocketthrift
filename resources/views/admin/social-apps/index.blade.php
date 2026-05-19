@extends('admin.layouts.app')

@section('title', 'Social Apps')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Social Apps</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.social-app.create') }}" class="btn btn-primary">Add New Social App</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.social-app.index') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="search" class="form-control" placeholder="Search social apps..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('admin.social-app.index') }}" class="btn btn-secondary">Reset</a>
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
                                    <th>Logo</th>
                                    <th>Title</th>
                                    <th>Sort</th>
                                    <th>Created At</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($socialApps as $socialApp)
                                    <tr>
                                        <td>{{ $socialApp->id }}</td>
                                        <td>
                                            @if($socialApp->logo)
                                                <img src="{{ $socialApp->logo ? asset('uploads/' . $socialApp->logo) : asset($socialApp->logo) }}" alt="{{ $socialApp->title }}" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">No Logo</span>
                                            @endif
                                        </td>
                                        <td>{{ $socialApp->title }}</td>
                                        <td>{{ $socialApp->sort }}</td>
                                        <td>{{ $socialApp->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @if($socialApp->creator)
                                                {{ $socialApp->creator->name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.social-app.show', $socialApp->id) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('admin.social-app.edit', $socialApp->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.social-app.destroy', $socialApp->id) }}" method="POST" class="d-inline" id="delete-form-{{ $socialApp->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteSocialApp({{ $socialApp->id }})">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No social apps found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $socialApps->links() }}
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
  function deleteSocialApp(id) {
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
</script>
@endpush
