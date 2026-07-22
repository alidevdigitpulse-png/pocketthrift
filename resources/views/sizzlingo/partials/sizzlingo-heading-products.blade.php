@if (isset($products) && $products instanceof \Illuminate\Support\Collection && $products->isNotEmpty())
    <section class="sbg-related-products" aria-label="Related SizzlinGo products">
        <div class="sbg-related-products__top">
            <span>
                <i class="fas fa-utensils" aria-hidden="true"></i>

                Related SizzlinGo Products
            </span>
        </div>

        <div class="sbg-product-grid">

            @foreach ($products->take(3) as $product)
                <article class="sbg-product-card">

                    <a href="{{ $product['url'] }}" class="sbg-product-card__image" target="_blank"
                        rel="nofollow sponsored noopener" aria-label="{{ $product['title'] }}">
                        <img src="{{ $product['image'] ?: asset('uploads/page/sizzlingo/sizzlingo-logo.jpg') }}"
                            alt="{{ $product['image_alt'] }}" width="420" height="300" loading="lazy">

                        @if (!empty($product['on_sale']))
                            <span class="sbg-product-card__sale">
                                Sale
                            </span>
                        @endif
                    </a>

                    <div class="sbg-product-card__body">

                        <h3>
                            <a href="{{ $product['url'] }}" target="_blank" rel="nofollow sponsored noopener">
                                {{ $product['title'] }}
                            </a>
                        </h3>

                        <div class="sbg-product-card__prices">

                            @if (!empty($product['compare_at_price']))
                                <del>
                                    {{ $product['compare_at_price'] }}
                                </del>
                            @endif

                            <strong>
                                {{ $product['price_label'] }}
                            </strong>

                        </div>

                        <a href="{{ $product['url'] }}" class="sbg-product-card__button" target="_blank"
                            rel="nofollow sponsored noopener">
                            View Product

                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>

                    </div>

                </article>
            @endforeach

        </div>
    </section>
@endif
