@extends('admin.layouts.app')

@section('title', 'Offers')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center box-header">
                            <h3 class="box-title">Offers</h3>
                            <a href="{{ route('admin.offer.create') }}" class="btn btn-primary pull-right"><i
                                    class="fa fa-plus"></i> Add New Offer</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('admin.offer.index') }}">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Search offers..." value="{{ request('search') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <select name="active" class="form-control">
                                                <option value="">All Status</option>
                                                <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="type" class="form-control">
                                                <option value="">All Types</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type }}"
                                                        {{ request('type') == $type ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="store" id="store_filter" class="form-control select2">
                                                <option value="">All Stores</option>
                                                @foreach ($stores as $store)
                                                    <option value="{{ $store->id }}"
                                                        {{ request('store') == $store->id ? 'selected' : '' }}>
                                                        {{ $store->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="verified" class="form-control">
                                                <option value="">All Verified</option>
                                                <option value="active"
                                                    {{ request('verified') == 'active' ? 'selected' : '' }}>Verified
                                                </option>
                                                <option value="inactive"
                                                    {{ request('verified') == 'inactive' ? 'selected' : '' }}>Not Verified
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            @if (auth()->user()->role == 1 || auth()->user()->hasRole('admin') || auth()->user()->hasRole('super admin'))
                                                <select name="country" class="form-control">
                                                    <option value="">All Regions</option>
                                                    @foreach ($regions as $region)
                                                        <option value="{{ $region->code }}"
                                                            {{ request('country') == $region->code ? 'selected' : '' }}>
                                                            {{ $region->country }} ({{ $region->code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="hidden" name="country"
                                                    value="{{ auth()->user()->assigned_regions }}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                            <a href="{{ route('admin.offer.index') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Store</th>
                                        <th>Type</th>
                                        <th>Discount</th>
                                        <th>Verified</th>
                                        <th>Active</th>
                                        <th>Sort</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($offers as $offer)
                                        <tr>
                                            <td>{{ $offer->id }}</td>
                                            <td>{{ Str::limit($offer->title, 20) }}</td>
                                            <td>
                                                @if ($offer->store)
                                                    {{ Str::limit($offer->store->title, 15) }}
                                                @else
                                                    <span class="text-muted">None</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge
                                                @if ($offer->type == 'Deal') badge-info
                                                @elseif($offer->type == 'Offer') badge-success
                                                @elseif($offer->type == 'Code') badge-warning
                                                @else badge-danger @endif">
                                                    {{ $offer->type }}
                                                </span>
                                            </td>
                                            <td>{{ is_numeric($offer->discount) ? number_format((float) $offer->discount, 2) . '%' : 'N/A' }}
                                            </td>
                                            <td>
                                                @if ($offer->verified == 1)
                                                    <span class="badge badge-success">Verified</span>
                                                @else
                                                    <span class="badge badge-danger">Unverified</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $offer->active ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $offer->active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="sort-editable" data-id="{{ $offer->id }}"
                                                    style="cursor: pointer; text-decoration: underline; color: #cf5103;">
                                                    {{ $offer->sort }}
                                                </span>
                                            </td>
                                            <td>{{ $offer->start_date ? $offer->start_date->format('Y-m-d') : 'N/A' }}</td>
                                            <td>{{ $offer->end_date ? $offer->end_date->format('Y-m-d') : 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.offer.show', $offer->id) }}"
                                                    class="btn btn-sm btn-info">View</a>
                                                <a href="{{ route('admin.offer.edit', $offer->id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.offer.destroy', $offer->id) }}"
                                                    method="POST" class="d-inline" id="delete-form-{{ $offer->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="deleteOffer({{ $offer->id }})">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center">No offers found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $offers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function deleteOffer(id) {
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

            $(function() {
                // Initialize select2 for store filter
                if ($('#store_filter').length) {
                    $('#store_filter').select2({
                        placeholder: 'All Stores',
                        allowClear: true
                    });
                }


                // Inline sort editing
                $('.sort-editable').click(function() {
                    var $span = $(this);
                    var currentSort = $span.text().trim();
                    var offerId = $span.data('id');

                    // If already editing, do nothing
                    if ($span.find('input').length > 0) return;

                    var $input = $('<input>', {
                        type: 'number',
                        value: currentSort,
                        class: 'form-control form-control-sm',
                        style: 'width: 80px; display: inline-block;'
                    });

                    $span.html($input);
                    $input.focus();

                    $input.on('blur keypress', function(e) {
                        if (e.type === 'keypress' && e.which !== 13) return;

                        var newSort = $(this).val();

                        // If value hasn't changed, revert to text
                        if (newSort === currentSort) {
                            $span.text(currentSort);
                            return;
                        }

                        $.ajax({
                            url: '{{ route('admin.offer.update-single-sort') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: offerId,
                                sort: newSort
                            },
                            success: function(response) {
                                if (response.success) {
                                    $span.text(newSort);
                                    // Optional: Show success message/toast
                                    // toastr.success(response.message);
                                } else {
                                    $span.text(currentSort);
                                    alert('Failed to update sort order');
                                }
                            },
                            error: function(xhr) {
                                $span.text(currentSort);
                                var errorMsg = 'Error updating sort order';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                alert(errorMsg);
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
