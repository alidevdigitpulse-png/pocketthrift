@extends('admin.layouts.app')
@section('title', 'Page List')
@section('content')
<div class="container-full">
	<div class="content-header">
	    <div class="d-flex align-items-center">
	        <div class="mr-auto">
	            <h3 class="page-title">Page List</h3>
	            <div class="d-inline-block align-items-center">
	                <nav>
	                    <ol class="breadcrumb">
	                        <li class="breadcrumb-item">
	                        	<a href="#"><i class="mdi mdi-home-outline">Page Management</i></a>
	                        </li>
	                        <li class="breadcrumb-item active" aria-current="page">Page List</li>
	                    </ol>
	                </nav>
	            </div>
	        </div>
	    </div>
	</div>
	<section class="content">
		<div class="card">
		    <div class="card-header">
	    		<div class="col-md-12 pl-0">
	    			<h4 class="card-title">Page Lists</h4>
	    		</div>
	    		<div class="col-md-12 row">
	    			<div class="col-md-4">
	    				<form method="get" action="{{ Auth::user()->assigned_regions && !Auth::user()->hasRole('admin') && Auth::user()->role != 1 ? route('admin.region.page.index') : route('admin.page.index') }}">
	    					<div class="form-group">
	    						<label>Filter by Region:</label>
	    						<select name="region" class="form-control">
	    							<option value="">All Regions</option>
	    							@foreach($regions as $region)
	    								<option value="{{ $region->id }}" {{ request('region') == $region->id ? 'selected' : '' }}>
	    									{{ $region->country }} ({{ $region->code }})
	    								</option>
	    							@endforeach
	    						</select>
	    					</div>
	    					<div class="form-group">
	    						<input type="submit" class="btn btn-primary" value="Filter">
	    						<a href="{{ Auth::user()->assigned_regions && !Auth::user()->hasRole('admin') && Auth::user()->role != 1 ? route('admin.region.page.index') : route('admin.page.index') }}" class="btn btn-default">Reset</a>
	    					</div>
	    				</form>
	    			</div>
	    			<div class="col-md-8">
		    			<form method="get" action="{{ Auth::user()->assigned_regions && !Auth::user()->hasRole('admin') && Auth::user()->role != 1 ? route('admin.region.page.index') : route('admin.page.index') }}">
		    				@if(request('region'))
		    					<input type="hidden" name="region" value="{{ request('region') }}">
		    				@endif
			                <div class="input-group">
			                    <input type="search" id="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2" name="search" value="{{ Request::get('search') }}">
			                    <div class="input-group-append">
			                        <button class="btn" type="submit" id="button-addon3"><i class="ti-search"></i></button>
			                    </div>
			                </div>
			            </form>
			        </div>
	    		</div>
		    </div>
		</div>
		<div class="row" id="section-content">
			@foreach($data as $key => $value)
			<div class="col-md-12 col-lg-4">
			    <div class="card">
			        <img class="card-img-top" src="{{ $value->image != null ? asset($value->image) : asset('admin/image/no-image.jpg') }}" alt="{{ $value->title }}">
			        <div class="card-body">
			            <h4 class="card-title">{{ $value->name }}</h4>
			            <p class="card-text">
			                <small class="text-muted">
			                    Region: {{ $value->region ? $value->region->country . ' (' . $value->region->code . ')' : 'None' }}
			                </small>
			            </p>
			        </div>
			        <div class="card-footer justify-content-between d-flex">
			            <ul class="list-inline mb-0 mr-2">
			                <span class="badge {{ $value->status == 0 ? 'badge-primary' : 'badge-danger'}} ">{{ $value->status == 0 ? 'Active' : 'Deactive'}}</span>
			            </ul>
			            <ul class="list-inline mb-0">
			                <li><a href="{{ Auth::user()->assigned_regions && !Auth::user()->hasRole('admin') && Auth::user()->role != 1 ? route('admin.region.page.edit', $value->id) : route('admin.page.edit', $value->id) }}">Edit</a></li>
			                <li>
			                	<form action="{{ Auth::user()->assigned_regions && !Auth::user()->hasRole('admin') && Auth::user()->role != 1 ? route('admin.region.page.destroy', $value->id) : route('admin.page.destroy', $value->id) }}" method="POST">
			                		@csrf
			                		@method('DELETE')
			                		<button type="submit" class="delete">Delete</button>
			                	</form>
			                </li>
			            </ul>
			        </div>
			    </div>
			</div>
			@endforeach
		</div>
           <div id="pagination">
                {{ $data->links("pagination::custom-pagination") }}
            <div id="pagination">
	</section>
</div>
@endsection

@push('css')
<style>
       .error {
            margin: 0 auto;
        }

        ul.pagination {
            position: relative;
        }

        li.page-item {
            display: inline;
            text-align: center;
        }

        ul.pagination {
            /* background: #666; */
            /* display: inline-block; */
        }

        ul.pagination li {
            background: #fff;
            /* display: flex; */
            color: black !important;
        }

        ul.pagination span {
            background: transparent !important;
            color: #737373 !important;
        }

        ul.pagination {
            display: flex;
            justify-content: center;
        }

        li.page-item.active {
            background: #fd683e;
            border-color: coral !important;
        }

        .page-item.active .page-link {
            border-color: #fd683e;
            color: white !important;
        }
</style>
@endpush

@push('js')
    <script src="{{ asset('admin/js/search.js') }}"></script>
@endpush
