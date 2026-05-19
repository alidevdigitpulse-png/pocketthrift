@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('content')
<section class="content-header">
    <h1>
        Edit Banner
        <small>Modify banner details</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('admin.banner.index') }}">Banner List</a></li>
        <li class="active">Edit Banner</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Banner Details</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="{{ route('admin.banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="box-body">
                        <div class="form-group @error('name') has-error @enderror">
                            <label for="name">Banner Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter banner name" value="{{ old('name', $banner->name) }}">
                            @error('name')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('type') has-error @enderror">
                            <label for="type">Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="Hero Slider" {{ old('type', $banner->type) == 'Hero Slider' ? 'selected' : '' }}>Hero Slider</option>
                                <option value="Top Banner" {{ old('type', $banner->type) == 'Top Banner' ? 'selected' : '' }}>Top Banner</option>
                                <option value="Bottom Banner" {{ old('type', $banner->type) == 'Bottom Banner' ? 'selected' : '' }}>Bottom Banner</option>
                            </select>
                            @error('type')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('image') has-error @enderror">
                            <label for="image">Banner Image</label>
                            <input type="file" id="image" class="dropify" name="image">
                            <p class="help-block">Upload to replace existing image (recommend size: 1200x400px).</p>
                            @if($banner->image)
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{ asset($banner->image) }}" alt="Current Image" class="img-thumbnail" style="max-height: 150px;">
                                        <p class="text-muted">Current Image</p>
                                    </div>
                                </div>
                            @endif
                            @error('image')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('button_text') has-error @enderror">
                            <label for="button_text">Button Text</label>
                            <input type="text" class="form-control" id="button_text" name="button_text" placeholder="Enter button text" value="{{ old('button_text', $banner->button_text) }}">
                            @error('button_text')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('url') has-error @enderror">
                            <label for="url">Button URL</label>
                            <input type="url" class="form-control" id="url" name="url" placeholder="Enter button URL" value="{{ old('url', $banner->url) }}">
                            @error('url')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('country_codes') has-error @enderror">
                            <label>Regions</label>
                            <select class="form-control select2" multiple="multiple" name="country_codes[]" data-placeholder="Select Regions" style="width: 100%;">
                                @foreach($regions as $region)
                                    <option value="{{ $region->code }}" 
                                        {{ (collect(old('country_codes', $selectedRegions))->contains($region->code)) ? 'selected' : '' }}>
                                        {{ $region->country }} ({{ strtoupper($region->code) }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="help-block">Leave empty to make this banner available for ALL permitted regions.</p>
                            @error('country_codes')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.banner.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    })
</script>
@endpush
