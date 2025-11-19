@extends('layouts.app')

@section('title', 'Pages')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Pages</h1>
            
            @if($pages->count() > 0)
                <div class="row">
                    @foreach($pages as $page)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                @if($page->image)
                                    <img src="{{ asset($page->image) }}" class="card-img-top" alt="{{ $page->name }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $page->name }}</h5>
                                    <a href="{{ route('page.show', $page->slug) }}" class="btn btn-primary">View Page</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{ $pages->links() }}
            @else
                <p>No pages available.</p>
            @endif
        </div>
    </div>
</div>
@endsection