@extends('admin.layouts.app')
@section('title', 'Add Testimonial ')
@section('content')
<div class="container-full">
	<div class="content-header">
	    <div class="d-flex align-items-center">
	        <div class="mr-auto">
	            <h3 class="page-title">{{ $data == null ? 'Add' : 'Update ' }} Testimonial {{ $data == null ? '' :'#'.$data->id }}</h3>
	            <div class="d-inline-block align-items-center">
	                <nav>
	                    <ol class="breadcrumb">
	                        <li class="breadcrumb-item">
	                        	<a href="#"><i class="mdi mdi-home-outline">Testimonial Management</i></a>
	                        </li>
	                        <li class="breadcrumb-item active" aria-current="page">{{ $data == null ? 'Add' : 'Update ' }} Testimonial</li>
	                    </ol>
	                </nav>
	            </div>
	        </div>
	    </div>
	</div>
	<section class="content">
	    <div class="row">
	        <div class="col-lg-12 col-12">
	            <div class="box">
                  @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
	                <div class="box-header with-border">
	                    <h4 class="box-title">{{ $data == null ? 'Upload' : 'Update ' }} Testimonial Details</h4>
	                </div>
	                <!-- /.box-header -->
	                <form class="form" method="post" action="{{ $data == null ? route('testimonial.store') : route('testimonial.update', $data->id) }}" enctype="multipart/form-data" id="file-upload">
                	@csrf
                	{{ $data != null ? method_field('PUT') : '' }}
	                    <div class="box-body">
	                        <div class="row">
	                            <div class="col-md-12">
	                                   <div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">{{ 'Name' }}</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ $data == null ? old('name') : $data->name }}" required>

    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('comments') ? 'has-error' : ''}}">
    <label for="comments" class="control-label">{{ 'Comments' }}</label>
    <textarea class="editor" id="comments" name="comments">{!! $data == null ? old('comments') : $data->comments !!}</textarea>

    {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('image') ? 'has-error' : ''}}">
    <label for="image" class="control-label">{{ 'Image' }}</label>
    <input type="file" class="dropify" name="image" {{ $data != null ? 'data-default-file = ' .asset($data->image) : ''}}>

    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>

                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <select name="status" id="" class="form-control">
                                            <option value="0" {{ ($data != null) && ($data->status == 0) ? 'selected' : '' }}>Active</option>
                                            <option value="1" {{ ($data != null) && ($data->status == 1) ? 'selected' : '' }}>Deactive</option>
                                        </select>
                                    </div>
									<div class="form-group">
										<div class="progress">
											<div class="progress-bar progress-bar-striped progress-bar-animated bg-success result" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="progressBar">0%</div>
											
										</div>
									</div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="box-footer">
	                        <button type="button" class="btn btn-rounded btn-warning btn-outline mr-1">
	                        <i class="ti-trash"></i> Cancel
	                        </button>
	                        <button type="submit" class="btn btn-rounded btn-primary btn-outline" onclick="progress()"
							>
	                        <i class="ti-save-alt"></i> Save
	                        </button>
	                    </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</section>
</div>

@endsection

@push('css')
    <style>
        .toggle.switch {
            float: right;
            border-radius: 23px;
        }

        span.toggle-handle.btn.btn-light.btn-sm {
            border-radius: 50%;
        }

        .toggle.btn.btn-sm.switch.btn-primary span {
            margin-right: 15px;
        }

        .toggle.btn.btn-sm.switch.btn-light.off span {
            margin-left: 15px;
        }
    </style>
@endpush

@push('js')
	<script>
        $('input[type="checkbox"]').change(function() {
           	($(this).prop("checked")) ? $(this).val(1) : $(this).val(0);
        })
    </script>
@endpush
