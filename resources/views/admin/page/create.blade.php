@extends('admin.layouts.app')
@section('title', 'Add Page ')
@section('content')
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="page-title">{{ $data == null ? 'Add' : 'Update ' }} Page
                        {{ $data == null ? '' : '#' . $data->id }}</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><i class="mdi mdi-home-outline">Page Management</i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $data == null ? 'Add' : 'Update ' }} Page</li>
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
                            <h4 class="box-title">{{ $data == null ? 'Upload' : 'Update ' }} Page Details</h4>
                        </div>
                        <!-- /.box-header -->
                        <form class="form" method="post"
                            action="{{ $data == null ? 
                                (Auth::user()->assigned_regions && !Auth::user()->hasRole('admin') && Auth::user()->role != 1 ? route('admin.region.page.store') : route('admin.page.store')) : 
                                (Auth::user()->assigned_regions && !Auth::user()->hasRole('admin') && Auth::user()->role != 1 ? route('admin.region.page.update', $data->id) : route('admin.page.update', $data->id)) }}"
                            enctype="multipart/form-data" id="file-upload">
                            @csrf
                            <input type="hidden" id="section_data" name="section_data">
                            {{ $data != null ? method_field('PUT') : '' }}
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group{{ $errors->has('name') ? 'has-error' : '' }}">
                                            <label for="name" class="control-label">{{ 'Name' }}</label>
                                            <input class="form-control" name="name" type="text" id="name"
                                                value="{{ $data == null ? old('name') : (auth()->user()->assigned_regions && !auth()->user()->hasRole('admin') && auth()->user()->role != 1 ? preg_replace('/-\w+$/', '', $data->name) : $data->name) }}" required>
                                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                                        </div>
                                        @if(auth()->user()->role == 1 || auth()->user()->hasRole('admin'))
                                        <div class="form-group{{ $errors->has('region_id') ? 'has-error' : '' }}">
                                            <label for="region_id" class="control-label">{{ 'Region' }}</label>
                                            <select class="form-control" name="region_id" id="region_id" required>
                                                <option value="">Select a Region</option>
                                                @foreach($regions as $region)
                                                    <option value="{{ $region->id }}" {{ ($data && $data->region_id == $region->id) ? 'selected' : '' }}>
                                                        {{ $region->country }} ({{ $region->code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
                                        </div>
                                        @else
                                            @php
                                                $userRegion = \App\Models\Region::where('code', Auth::user()->assigned_regions)->first();
                                            @endphp
                                            @if($userRegion)
                                                <input type="hidden" name="region_id" value="{{ $userRegion->id }}">
                                                <div class="form-group">
                                                    <label class="control-label">Region</label>
                                                    <input type="text" class="form-control" value="{{ $userRegion->country }} ({{ $userRegion->code }})" readonly>
                                                </div>
                                            @endif
                                        @endif
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <input type="file" class="dropify" name="page_image" id="image" {{ $data != null ? 'data-default-file = ' .asset($data->image) : ''}}>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Status</label>
                                            <select name="status" id="" class="form-control">
                                                <option value="0"
                                                    {{ $data != null && $data->status == 0 ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="1"
                                                    {{ $data != null && $data->status == 1 ? 'selected' : '' }}>
                                                    Deactive</option>
                                            </select>
                                        </div>
                                        @if ($data)
                                            @foreach ($data->sections as $item)
                                                <div class="form-group">
                                                    <label for="{{ $item->slug }}">{{ $item->name }}</label>
                                                    @if ($item->type == 'text')
                                                        <input type="text" class="form-control"
                                                            name="section[{{ $item->slug }}]"
                                                            value="{{ $item->value }}">
                                                    @elseif ($item->type == 'textarea')
                                                        <textarea class="editor" name="section[{{ $item->slug }}]" value={{ $item->value }}>{{ $item->value }}</textarea>
                                                    @elseif ($item->type == 'image')
                                                        <input type="file" class="dropify"
                                                            name="image[{{ $item->slug }}]"
                                                            {{ $item->value != null ? 'data-default-file = ' . asset($item->value) : '' }}>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif

                                        <div id="extra-sections"></div>
                                        @if ($data && env('APP_DEBUG'))
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#add-section">
                                                    Add Section
                                                </button>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success result"
                                            role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                            style="width: 0%" id="progressBar">0%</div>

                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-rounded btn-warning btn-outline mr-1">
                                    <i class="ti-trash"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-rounded btn-primary btn-outline" onclick="progress()">
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
@push('modals')
    <div class="modal modal-primary fade" id="add-section">
        <div class="modal-dialog">
            <div class="modal-content bg-primary">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Section</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name-section">Name</label>
                                    <input id="section_title" type="text" class="form-control" name="title">
                                </div>
                                <div class="form-group">
                                    <label for="name-section">Type</label>
                                    <select id="section_type" class="form-control">
                                        <option value="text">Text</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="image">Image</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" id="sectionsave" class="btn btn-primary float-right">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endpush
@push('js')
    @if ($data)
        <script>
            $('#sectionsave').click(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    _token: "{{ csrf_token() }}",
                    url: "{{ Auth::user()->assigned_regions && !Auth::user()->hasRole('admin') && Auth::user()->role != 1 ? route('admin.section.create') : route('admin.section.create') }}",
                    type: "post",
                    data: {
                        name: $('#section_title').val(),
                        type: $('#section_type').val(),
                        page_id: {{ $data->id }}
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            $('#add-section').modal('hide');
                            let formInput;
                            if ($('#section_type').val() == 'text') {
                                formInput = `<div class="form-group">
                                            <label  class="control-label">${$('#section_title').val()}</label>
                                            <input class="form-control" name="section[${response.slug}]" type="text">
                                        </div>`;

                            } else if ($('#section_type').val() == 'textarea') {
                                formInput = `<div class="form-group">
                                            <label  class="control-label">${$('#section_title').val()}</label>
                                            <textarea class="editor" name="section[${response.slug}]"></textarea>
                                        </div>`;
                            } else if ($('#section_type').val() == 'image') {
                                formInput = `<div class="form-group">
                                            <label class="control-label">${$('#section_title').val()}</label>
                                            <input type="file" class="dropify" name="image[${response.slug}]">
                                        </div>`;
                            }
                            $('#extra-sections').append(formInput)
                            if ($('#section_type').val() == 'textarea') {
                                setTimeout(() => {
                                    $('.editor').summernote();
                                }, 5);
                            } else if ($('#section_type').val() == 'image') {
                                setTimeout(() => {
                                    $('.dropify').dropify();
                                }, 5);
                            }

                        }

                    }
                })
            })

            $('input[type="checkbox"]').change(function() {
                ($(this).prop("checked")) ? $(this).val(1): $(this).val(0);
            })
            $('#addsection').click(function() {
                $('#add-section-modal').modal()
            })
        </script>
    @endif

@endpush
