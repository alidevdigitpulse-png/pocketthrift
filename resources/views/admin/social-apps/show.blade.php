@extends('admin.layouts.app')

@section('title', 'Social App Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="card-title">Social App Details</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.social-app.edit', $socialApp->id) }}" class="btn btn-primary">Edit</a>
                            <a href="{{ route('admin.social-app.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">ID</th>
                                    <td>{{ $socialApp->id }}</td>
                                </tr>
                                <tr>
                                    <th>Title</th>
                                    <td>{{ $socialApp->title }}</td>
                                </tr>
                                <tr>
                                    <th>Sort Order</th>
                                    <td>{{ $socialApp->sort }}</td>
                                </tr>
                                <tr>
                                    <th>Logo</th>
                                    <td>
                                        @if($socialApp->logo)
                                            <img src="{{ asset( substr($socialApp->logo, 0, 8) === 'uploads/' ? $socialApp->logo : 'uploads/' . $socialApp->logo ) }}" alt="{{ $socialApp->title }}" width="50" height="50">
                                        @else
                                            <span class="text-muted">No Logo</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Created At</th>
                                    <td>{{ $socialApp->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td>
                                        @if($socialApp->creator)
                                            {{ $socialApp->creator->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $socialApp->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated By</th>
                                    <td>
                                        @if($socialApp->updater)
                                            {{ $socialApp->updater->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($socialApp->deleted_at)
                                <tr>
                                    <th>Deleted At</th>
                                    <td>{{ $socialApp->deleted_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Deleted By</th>
                                    <td>
                                        @if($socialApp->deleter)
                                            {{ $socialApp->deleter->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
