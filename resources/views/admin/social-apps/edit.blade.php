@extends('admin.layouts.app')

@section('title', 'Edit Social App')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Edit Social App</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.social-app.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.social-app.update', $socialApp->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $socialApp->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort') is-invalid @enderror" id="sort" name="sort" value="{{ old('sort', $socialApp->sort) }}" min="0">
                                    @error('sort')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    
                                    @if($socialApp->logo)
                                        <div class="mb-2">
                                            <img src="{{ $socialApp->logo ? asset('uploads/' . $socialApp->logo) : asset($socialApp->logo) }}" alt="{{ $socialApp->title }}" style="width: 100px; height: 100px; object-fit: cover;" class="img-thumbnail">
                                            <p class="text-muted small">Current Logo</p>
                                        </div>
                                    @endif
                                    
                                    <input type="file" class="dropify" id="logo" data-default-file="{{ $socialApp->logo ? asset('uploads/' . $socialApp->logo) : asset($socialApp->logo) }}" name="logo" accept="image/*">
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Accepted formats: JPEG, PNG, JPG, GIF, SVG, WEBP (Max: 2MB)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update Social App</button>
                                <a href="{{ route('admin.social-app.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
