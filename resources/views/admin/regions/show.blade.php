@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Region Details</h4>
                    <div class="card-options">
                        <a href="{{ route('admin.region.edit', $region->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <a href="{{ route('admin.region.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $region->id }}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{ $region->country }}</td>
                                </tr>
                                <tr>
                                    <th>Code</th>
                                    <td>{{ $region->code }}</td>
                                </tr>
                                <tr>
                                    <th>Sort</th>
                                    <td>{{ $region->sort }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $region->active ? 'badge-success' : 'badge-danger' }}">
                                            {{ $region->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $region->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $region->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Created By</th>
                                    <td>{{ $region->creator ? $region->creator->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Updated By</th>
                                    <td>{{ $region->updater ? $region->updater->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Deleted At</th>
                                    <td>{{ $region->deleted_at ? $region->deleted_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Deleted By</th>
                                    <td>{{ $region->deleter ? $region->deleter->name : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection