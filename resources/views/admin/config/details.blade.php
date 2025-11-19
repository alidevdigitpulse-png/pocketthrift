@extends('admin.layouts.app')
@section('title', 'Config Settings')
@section('content')
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="page-title">Config</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><i class="mdi mdi-home-outline">Website Setting</i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Config</li>
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
                        <div class="box-header with-border">
                            <h4 class="box-title">Upload Config Details</h4>
                        </div>
                        <!-- /.box-header -->
                        <form class="form" method="post" action="{{ route('admin.config.post') }}"
                            enctype="multipart/form-data" id="file-upload">
                            @csrf
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        @foreach ($data as $key => $value)
                                            <div class="form-group">

                                                <label>{{ $value->name }}</label>
                                                @if ($value->has_image == 0)
                                                    <input type="text" class="form-control"
                                                        name="{{ $value->flag_type }}" value="{{ $value->flag_value }}">
                                                @elseif($value->has_image == 1)
                                                    <input type="file" class="dropify" name="{{ $value->flag_type }}"
                                                        value="{{ $value->flag_value }}"
                                                        data-default-file="{{ asset($value->flag_value) }}">
                                                @elseif($value->has_image == 2)
                                                    <textarea class="editor" id="editor-{{ $value->id }}" name="{{ $value->flag_type }}" rows="10" cols="80">{!! $value->flag_value !!}</textarea>
                                                @endif

                                            </div>
                                        @endforeach
                                        <div id="extra-sections"></div>
                                        @if ($data && env('APP_DEBUG'))
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#add-section">
                                                    Add New Config
                                                </button>
                                            </div>
                                        @endif


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped progress-bar-animated bg-success result"
                                            role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                            style="width: 0%" id="progressBar">0%</div>

                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
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
                    <!-- /.box -->
                </div>
            </div>
        </section>

    </div>
@endsection

@push('modals')
    <div class="modal modal-primary fade" id="add-section">
        <div class="modal-dialog">
            <div class="modal-content bg-primary">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Config</h4>
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
                                        <option value="0">Text</option>
                                        <option value="2">Textarea</option>
                                        <option value="1">Image</option>
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
@push('css')
    <style></style>
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
                    url: "{{ route('add.new.config') }}",
                    type: "post",
                    data: {
                        name: $('#section_title').val(),
                        has_image: $('#section_type').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            $('#add-section').modal('hide');
                            let formInput;
                            if ($('#section_type').val() == 0) {
                                formInput = `<div class="form-group">
                                            <label  class="control-label">${$('#section_title').val()}</label>
                                            <input class="form-control" name="${response.slug}" type="text">
                                        </div>`;


                            } else if ($('#section_type').val() == 2) {
                                formInput = `<div class="form-group">
                                            <label  class="control-label">${$('#section_title').val()}</label>
                                            <textarea class="editor" name="${response.slug}"></textarea>
                                        </div>`;
                                setTimeout(() => {
                                    $('.editor').summernote();
                                }, 5);
                            } else if ($('#section_type').val() == 1) {
                                formInput = `<div class="form-group">
                                            <label class="control-label">${$('#section_title').val()}</label>
                                            <input type="file" class="dropify" name="${response.slug}">
                                        </div>`;
                                setTimeout(() => {
                                    $('.dropify').dropify();
                                }, 5);
                            }
                            $('#extra-sections').append(formInput)
                            $('#section_title').val('')
                            $('#section_type').val('')

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
