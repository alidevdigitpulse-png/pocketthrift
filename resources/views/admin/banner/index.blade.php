@extends('admin.layouts.app')

@section('title', 'Banner Management')

@section('content')
<section class="content-header">
    <h1>
        Banner List
        <small>Manage your banners</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Banner List</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="d-flex justify-content-between align-items-center box-header">
                    <h3 class="box-title">All Banners</h3>
                    <a href="{{ route('admin.banner.create') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New Banner</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Button Text</th>
                                <th>URL</th>
                                <th>Regions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($banners as $banner)
                            <tr>
                                <td>{{ $banner->id }}</td>
                                <td>
                                    @if($banner->image)
                                        <img src="{{ asset($banner->image) }}" alt="{{ $banner->name }}" width="100">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $banner->name }}</td>
                                <td>
                                    @if($banner->type == 'Hero Slider')
                                        <span class="label label-primary">{{ $banner->type }}</span>
                                    @else
                                        <span class="label label-success">{{ $banner->type }}</span>
                                    @endif
                                </td>
                                <td>{{ $banner->button_text }}</td>
                                <td>{{ $banner->url }}</td>
                                <td>
                                    @if(!empty($banner->country_codes))
                                        @foreach($banner->country_codes as $code)
                                            <span class="label label-info">{{ strtoupper($code) }}</span>
                                        @endforeach
                                    @else
                                        <span class="label label-default">All Regions</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.banner.edit', $banner->id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a>
                                    
                                    <form action="{{ route('admin.banner.destroy', $banner->id) }}" method="POST" style="display:inline-block;" id="delete-form-{{ $banner->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteBanner({{ $banner->id }})"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(function () {
    $('#example1').DataTable()
  })

  function deleteBanner(id) {
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
