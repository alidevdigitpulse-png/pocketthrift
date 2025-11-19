@extends('layouts.app')

{{-- Page Title --}}
@section('title', $page->name)

{{-- Page-wise Schema Slot --}}
@push('schemas')
    {{-- You can paste page-specific schema here using @push from controller OR view --}}
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>{{ $page->name }}</h1>
            
            @if($page->image)
                <img src="{{ asset($page->image) }}" alt="{{ $page->name }}" class="img-fluid mb-4">
            @endif

            <!-- Display sections if they exist -->
            @if($page->sections->count() > 0)
                @foreach($page->sections as $section)
                    <div class="page-section mb-4">
                        <h3>{{ $section->name }}</h3>

                        @if($section->type == 'image')
                            @if($section->value)
                                <img src="{{ asset($section->value) }}" alt="{{ $section->name }}" class="img-fluid mb-3">
                            @endif

                        @elseif($section->type == 'textarea')
                            <div class="section-content">
                                {!! $section->value !!}
                            </div>

                        @else
                            <p>{{ $section->value }}</p>
                        @endif

                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
