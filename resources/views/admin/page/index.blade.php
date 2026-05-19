@extends('admin.layouts.app')

@section('title', 'Pages')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800">Pages</h1>
            <a href="{{ route('admin.page.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Add New Page
            </a>
        </div>
    </div>

    <!-- Search, Filters, and Results Section -->
    <div class="row mt-4">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pages List</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search pages...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="regionFilter">
                                <option value="">All Regions</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ $regionFilter == $region->id ? 'selected' : '' }}>
                                        {{ $region->country }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 text-right">
                            <button class="btn btn-success" id="searchBtn">Search</button>
                            <button class="btn btn-secondary" id="clearBtn">Clear</button>
                        </div>
                    </div>

                    <div id="tableData">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Region</th>
                                        <th>Active</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                    <tr>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            @if($item->country_codes && is_array($item->country_codes) && count($item->country_codes) > 0)
                                                {{ implode(', ', $item->country_codes) }}
                                            @else
                                                All Regions
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.page.edit', $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <!-- <a href="{{ route('admin.page.show', $item->id) }}" class="btn btn-info btn-sm">View</a> -->
                                            <form method="POST" action="{{ route('admin.page.destroy', $item->id) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this page?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const regionFilter = document.getElementById('regionFilter');
    const searchBtn = document.getElementById('searchBtn');
    const clearBtn = document.getElementById('clearBtn');
    const tableData = document.getElementById('tableData');

    function fetchData() {
        const searchValue = searchInput.value;
        const regionValue = regionFilter.value;

        // Show loading indicator
        tableData.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';

        fetch(`{{ route("admin.page.index") }}?search=${searchValue}&region=${regionValue}&onChange=true`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                let html = '<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Title</th><th>Region</th><th>Active</th><th>Created At</th><th>Actions</th></tr></thead><tbody>';
                
                data.data.data.forEach(item => {
                    html += `
                        <tr>
                            <td>${item.title}</td>
                            <td>${item.country_codes && Array.isArray(item.country_codes) && item.country_codes.length > 0 ? item.country_codes.join(', ') : 'All Regions'}</td>
                            <td>
                                ${item.active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'}
                            </td>
                            <td>${item.created_at ? new Date(item.created_at).toLocaleString() : 'N/A'}</td>
                            <td>
                                <a href="${item.edit_url}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="${item.show_url || '#'}" class="btn btn-info btn-sm">View</a>
                                <form method="POST" action="${item.delete_url}" style="display:inline;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this page?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    `;
                });
                
                html += '</tbody></table></div>';
                
                // Add pagination
                html += '<div class="d-flex justify-content-center">' + generatePagination(data.lastPage) + '</div>';
                
                tableData.innerHTML = html;
                
                // Add event listeners to delete forms
                document.querySelectorAll('form[method="POST"]').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        if (!confirm('Are you sure you want to delete this page?')) {
                            e.preventDefault();
                        }
                    });
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tableData.innerHTML = '<div class="alert alert-danger">Error loading data</div>';
        });
    }

    function generatePagination(lastPage) {
        let paginationHtml = '<nav><ul class="pagination justify-content-center">';
        for (let i = 1; i <= lastPage; i++) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        }
        paginationHtml += '</ul></nav>';
        return paginationHtml;
    }

    // Search button event
    searchBtn.addEventListener('click', fetchData);

    // Clear button event
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        regionFilter.value = '';
        fetchData();
    });

    // Enter key in search input
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            fetchData();
        }
    });

    // Change event on region filter
    regionFilter.addEventListener('change', fetchData);
});
</script>
@endsection