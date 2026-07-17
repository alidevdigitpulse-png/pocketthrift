@extends('layouts.app')

@section('title', $collection['seo_title'] ?: $collection['title'])
@push('css')
    <link rel="stylesheet" href="{{ asset('front/css/sizzlingo-landing.css') }}?v={{ time() }}">
@endpush
@section('content')

    <main class="sizzlingo-collection-page">

        <section class="collection-header">
            <div class="container">

                <a
                    href="{{ route('region.sizzlingo.meals', [
                        'region' => $region,
                    ]) }}">
                    ← Back to all collections
                </a>

                <h1>{{ $collection['title'] }}</h1>

                @if (!empty($collection['description']))
                    <p>{{ $collection['description'] }}</p>
                @endif

                <p>
                    {{ $collection['products_count'] }}
                    products available
                </p>

            </div>
        </section>

        <section class="collection-products">
            <div class="container">

                @if ($products->isNotEmpty())

                    <div class="products-grid">

                        @foreach ($products as $product)
                            <article class="product-card">

                                <a href="{{ $product['url'] }}">

                                    @if (!empty($product['image']))
                                        <img src="{{ $product['image'] }}" alt="{{ $product['image_alt'] }}" width="500"
                                            height="500" loading="lazy">
                                    @endif

                                    <h2>{{ $product['title'] }}</h2>

                                </a>

                                @if (!empty($product['description']))
                                    <p>{{ $product['description'] }}</p>
                                @endif

                                <div class="product-price">

                                    <strong>
                                        {{ $product['price_label'] }}
                                    </strong>

                                    @if (!empty($product['compare_at_price']))
                                        <del>
                                            {{ $product['compare_at_price'] }}
                                        </del>
                                    @endif

                                </div>

                                <a href="{{ $product['url'] }}" class="view-product-button">
                                    {{ $product['available'] ? 'View Product' : 'Sold Out' }}
                                </a>

                            </article>
                        @endforeach

                    </div>
                @else
                    <div class="no-products">
                        <h2>No products found</h2>

                        <p>
                            There are currently no published products
                            in this collection.
                        </p>
                    </div>

                @endif

            </div>
        </section>

    </main>

@endsection
