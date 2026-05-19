@extends('admin.layouts.app')

@section('title', 'Create Offer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Create New Offer</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.offer.index') }}" class="btn btn-secondary">Back to Offers</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.offer.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="store_id">Store</label>
                                    <select name="store_id" id="store_id" class="form-control select2 @error('store_id') is-invalid @enderror">
                                        <option value="">Select Store</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                                {{ $store->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('store_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seasonal_id">Seasonal Category</label>
                                    <select name="seasonal_id" id="seasonal_id" class="form-control select2 @error('seasonal_id') is-invalid @enderror">
                                        <option value="">Select Seasonal Category (Optional)</option>
                                        @foreach($seasonals as $seasonal)
                                            <option value="{{ $seasonal->id }}" {{ old('seasonal_id') == $seasonal->id ? 'selected' : '' }}>
                                                {{ $seasonal->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('seasonal_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Code</label>
                                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" 
                                           value="{{ old('code') }}">
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label for="discount" id="discountLabel">Discount</label>
                                    <input type="text" name="discount" id="discount" class="form-control"
                                           value="{{ old('discount') }}" maxlength="50">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                        <option value="">Select Type</option>
                                        @foreach($types as $typeOption)
                                            <option value="{{ $typeOption }}" {{ old('type') == $typeOption ? 'selected' : '' }}>
                                                {{ $typeOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="affiliate_links">Affiliate Links</label>
                                    <textarea name="affiliate_links" id="affiliate_links" class="form-control @error('affiliate_links') is-invalid @enderror" rows="3">{{ old('affiliate_links') }}</textarea>
                                    @error('affiliate_links')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo">Logo URL</label>
                                    <input type="file" name="logo" id="logo" class="dropify" 
                                           value="{{ old('logo') }}">
                                    @error('logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="free_delivery">Free Delivery</label>
                                    <select name="free_delivery" id="free_delivery" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="verified">Verified</label>
                                    <select name="verified" class="form-control">
                                        <option value="0">Not Verified</option>
                                        <option value="1">Verified</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="new_recently_updated">Recently Updated</label>
                                    <select name="new_recently_updated" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="terms_and_conditions">Terms and Conditions</label>
                            <textarea name="terms_and_conditions" id="terms_and_conditions" class="form-control @error('terms_and_conditions') is-invalid @enderror" rows="4">{{ old('terms_and_conditions') }}</textarea>
                            @error('terms_and_conditions')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sort">Sort Order</label>
                                    <input type="number" name="sort" id="sort" class="form-control @error('sort') is-invalid @enderror" 
                                           value="{{ old('sort', 0) }}">
                                    @error('sort')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Country Codes Section -->
                        <div class="form-group">
                            <label for="country_codes">Select Regions</label>
                            @if(auth()->user()->role == 1 || auth()->user()->hasRole('admin') || auth()->user()->hasRole('super admin'))
                                <select name="country_codes[]" id="country_codes" class="form-control select2 @error('country_codes') is-invalid @enderror" multiple>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ in_array($region->id, old('country_codes', [])) ? 'selected' : '' }}>
                                            {{ $region->country }} ({{ $region->code }})
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <p class="form-control-static">
                                    Your assigned region: 
                                    @if($regions->count() > 0)
                                        {{ $regions->first()->country }} ({{ $regions->first()->code }})
                                    @else
                                        None assigned
                                    @endif
                                </p>
                                <input type="hidden" name="country_codes[]" value="{{ $regions->count() > 0 ? $regions->first()->id : '' }}">
                            @endif
                            @error('country_codes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary">Create Offer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        // Initialize select2 for the country codes multi-select
        if ($('#country_codes').length) {
            setTimeout(function() {
                $('#country_codes').select2({
                    placeholder: 'Select regions...',
                    allowClear: true
                });
            }, 500);
        }

        // Initialize select2 for store dropdown
        if ($('#store_id').length) {
            setTimeout(function() {
                $('#store_id').select2({
                    placeholder: 'Select Store',
                    allowClear: true
                });
            }, 500);
        }

        // Initialize select2 for seasonal category dropdown
        if ($('#seasonal_id').length) {
            setTimeout(function() {
                $('#seasonal_id').select2({
                    placeholder: 'Select Seasonal Category (Optional)',
                    allowClear: true
                });
            }, 500);
        }

        // Handle Sale/Offer type and Free Delivery to disable fields
        function toggleFields() {
            var type = $('#type').val();
            var freeDelivery = $('#free_delivery').val();
            
            // Enable free delivery option (the user wants it available)
            $('#free_delivery').prop('disabled', false);

            if (freeDelivery == '1') {
                $('#discountLabel').text('Free Delivery');
            } else {
                $('#discountLabel').text('Discount');
            }

            // Ensure discount field is not disabled
            $('#discount').prop('disabled', false);
        }

        $('#type, #free_delivery').on('change', toggleFields);
        // Initial check on load
        toggleFields();

        // Re-enable disabled fields before form submission so they are sent to the server
        $('form').on('submit', function() {
            $(this).find(':disabled').prop('disabled', false);
        });
    });
</script>
@endpush
@endsection