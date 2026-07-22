<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class SizzlingoBlogProductService
{
    private const PRODUCTS_CACHE_KEY =
        'sizzlingo.blog.available-products.v1';

   /**
 * Blog content ko H3 sections mein divide karke
 * har H3 heading ke neeche 3 relevant products attach karta hai.
 */
    public function buildSections(Blog $blog): array
    {
        $content = html_entity_decode(
            (string) $blog->content_body,
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        $sections = $this->splitContentByHeadings(
            $content
        );

        if (empty($sections)) {
            return [
                [
                    'heading_html' => '',
                    'heading_text' => '',
                    'body_html' => $content,
                    'products' => collect(),
                ],
            ];
        }

        try {
            $products = $this->getProducts();
        } catch (Throwable $exception) {
            report($exception);

            /*
             * API fail ho to blog content phir bhi
             * normally display hoga.
             */
            $products = collect();
        }

        if ($products->isEmpty()) {
            foreach ($sections as &$section) {
                $section['products'] = collect();
            }

            unset($section);

            return $sections;
        }

        /*
         * Products ko different headings ke neeche
         * repeat hone se initially avoid karenge.
         */
        $usedProductKeys = [];

        foreach ($sections as &$section) {
            if (
                trim(
                    (string) $section['heading_text']
                ) === ''
            ) {
                $section['products'] = collect();

                continue;
            }

            $selectedProducts =
                $this->selectProductsForSection(
                    $products,
                    (string) $section['heading_text'],
                    (string) $section['body_html'],
                    $usedProductKeys
                );

            $section['products'] = $selectedProducts;

            foreach ($selectedProducts as $product) {
                $usedProductKeys[] =
                    $this->getProductKey($product);
            }

            $usedProductKeys = array_values(
                array_unique($usedProductKeys)
            );
        }

        unset($section);

        return $sections;
    }

    /**
     * Shopify products ko cache ke through load karo.
     */
    private function getProducts(): Collection
    {
        return Cache::remember(
            self::PRODUCTS_CACHE_KEY,
            now()->addHour(),
            function (): Collection {
                $rawProducts =
                    $this->fetchProductsFromShopify();

                return $this->formatProducts(
                    $rawProducts
                );
            }
        );
    }

    /**
     * All Shopify products fetch karo.
     *
     * Product collections bhi fetch hongi taake
     * content aur collection mapping dono use hon.
     */
    private function fetchProductsFromShopify(): array
    {
        $query = <<<'GRAPHQL'
query GetBlogProducts($first: Int!) {
    products(first: $first) {
        nodes {
            id
            title
            handle
            description(truncateAt: 500)
            productType
            tags
            availableForSale
            onlineStoreUrl

            featuredImage {
                url
                altText
            }

            priceRange {
                minVariantPrice {
                    amount
                    currencyCode
                }

                maxVariantPrice {
                    amount
                    currencyCode
                }
            }

            variants(first: 1) {
                nodes {
                    compareAtPrice {
                        amount
                        currencyCode
                    }
                }
            }

            collections(first: 10) {
                nodes {
                    title
                    handle
                }
            }
        }
    }
}
GRAPHQL;

        $data = $this->storefrontRequest(
            $query,
            [
                'first' => 100,
            ]
        );

        $products = data_get(
            $data,
            'products.nodes',
            []
        );

        if (!is_array($products)) {
            throw new RuntimeException(
                'Invalid Shopify products response.'
            );
        }

        return $products;
    }

    /**
     * Shopify products ko Blade-friendly format mein convert karo.
     */
    private function formatProducts(
        array $rawProducts
    ): Collection {
        $storeUrl = $this->getStoreUrl();

        return collect($rawProducts)
            ->filter(function ($product): bool {
                return is_array($product)
                    && !empty($product['title'])
                    && !empty($product['handle']);
            })
            ->map(function (
                array $product,
                int $position
            ) use ($storeUrl): array {
                $title = trim(
                    (string) ($product['title'] ?? '')
                );

                $handle = strtolower(
                    trim(
                        (string) (
                            $product['handle'] ?? ''
                        ),
                        '/'
                    )
                );

                $minimumAmount = (float) data_get(
                    $product,
                    'priceRange.minVariantPrice.amount',
                    0
                );

                $maximumAmount = (float) data_get(
                    $product,
                    'priceRange.maxVariantPrice.amount',
                    $minimumAmount
                );

                $currencyCode = strtoupper(
                    (string) data_get(
                        $product,
                        'priceRange.minVariantPrice.currencyCode',
                        'AUD'
                    )
                );

                $compareAtAmount = data_get(
                    $product,
                    'variants.nodes.0.compareAtPrice.amount'
                );

                $compareAtAmount = is_numeric(
                    $compareAtAmount
                )
                    ? (float) $compareAtAmount
                    : null;

                $isOnSale =
                    $compareAtAmount !== null
                    && $compareAtAmount > $minimumAmount;

                $hasPriceRange = abs(
                    $maximumAmount - $minimumAmount
                ) > 0.009;

                $imageUrl = data_get(
                    $product,
                    'featuredImage.url'
                );

                $imageAlt = data_get(
                    $product,
                    'featuredImage.altText'
                );

                $onlineStoreUrl = data_get(
                    $product,
                    'onlineStoreUrl'
                );

                $productUrl =
                    is_string($onlineStoreUrl)
                    && trim($onlineStoreUrl) !== ''
                        ? trim($onlineStoreUrl)
                        : $storeUrl
                            . '/products/'
                            . rawurlencode($handle);

                $productUrl =
                    $this->addAffiliateReference(
                        $productUrl
                    );

                $collections = collect(
                    data_get(
                        $product,
                        'collections.nodes',
                        []
                    )
                )
                    ->filter(
                        fn ($collection): bool =>
                            is_array($collection)
                    )
                    ->map(function (
                        array $collection
                    ): string {
                        return trim(
                            (
                                $collection['title']
                                ?? ''
                            )
                            . ' '
                            . str_replace(
                                '-',
                                ' ',
                                (
                                    $collection['handle']
                                    ?? ''
                                )
                            )
                        );
                    })
                    ->filter()
                    ->implode(' ');

                return [
                    'id' => $product['id'] ?? null,

                    'title' => $title,

                    'handle' => $handle,

                    'description' => trim(
                        (string) (
                            $product['description']
                            ?? ''
                        )
                    ),

                    'product_type' => trim(
                        (string) (
                            $product['productType']
                            ?? ''
                        )
                    ),

                    'tags' => is_array(
                        $product['tags'] ?? null
                    )
                        ? $product['tags']
                        : [],

                    'collection_text' => $collections,

                    'available' => (bool) (
                        $product['availableForSale']
                        ?? false
                    ),

                    'image' =>
                        is_string($imageUrl)
                        && $imageUrl !== ''
                            ? $imageUrl
                            : null,

                    'image_alt' =>
                        is_string($imageAlt)
                        && $imageAlt !== ''
                            ? $imageAlt
                            : $title,

                    'price_label' => $hasPriceRange
                        ? 'From '
                            . $this->formatMoney(
                                $minimumAmount,
                                $currencyCode
                            )
                        : $this->formatMoney(
                            $minimumAmount,
                            $currencyCode
                        ),

                    'compare_at_price' => $isOnSale
                        ? $this->formatMoney(
                            $compareAtAmount,
                            $currencyCode
                        )
                        : null,

                    'on_sale' => $isOnSale,

                    'url' => $productUrl,

                    /*
                     * Same score hone par Shopify order
                     * preserve karne ke liye.
                     */
                    '_position' => $position,
                ];
            })
            ->filter(
                fn (array $product): bool =>
                    $product['available'] === true
            )
            ->values();
    }

    /**
     * HTML ko H2 headings aur unke body content
     * mein divide karo.
     */

    /**
 * HTML ko H3 headings aur unke body content
 * mein divide karo.
 */
private function splitContentByHeadings(
    string $content
): array {
    /*
    |--------------------------------------------------------------------------
    | Split content using H3 headings
    |--------------------------------------------------------------------------
    |
    | Har H3 ek separate product-matching section banega.
    | H3 ke baad ka content next H3 tak usi section ka body hoga.
    |
    */

    $parts = preg_split(
        '/(<h3\b[^>]*>.*?<\/h3>)/is',
        $content,
        -1,
        PREG_SPLIT_DELIM_CAPTURE
        | PREG_SPLIT_NO_EMPTY
    );

    if (!is_array($parts) || empty($parts)) {
        return [
            [
                'heading_html' => '',
                'heading_text' => '',
                'body_html' => $content,
                'products' => collect(),
            ],
        ];
    }

    $sections = [];

    /*
     * First H3 se pehle ka content.
     * Is introduction content ke neeche products nahi aayenge.
     */
    $preamble = '';

    $currentSection = null;

    foreach ($parts as $part) {
        $isHeading = preg_match(
            '/^\s*<h3\b/i',
            $part
        ) === 1;

        /*
        |--------------------------------------------------------------------------
        | New H3 found
        |--------------------------------------------------------------------------
        */

        if ($isHeading) {
            /*
             * Previous section complete ho chuka hai.
             */
            if ($currentSection !== null) {
                $sections[] = $currentSection;
            }

            $headingText = trim(
                html_entity_decode(
                    strip_tags($part),
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                )
            );

            $currentSection = [
                'heading_html' => $part,
                'heading_text' => $headingText,
                'body_html' => '',
                'products' => collect(),
            ];

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Content before or after an H3
        |--------------------------------------------------------------------------
        */

        if ($currentSection === null) {
            $preamble .= $part;
        } else {
            $currentSection['body_html'] .= $part;
        }
    }

    /*
     * Last H3 section add karo.
     */
    if ($currentSection !== null) {
        $sections[] = $currentSection;
    }

    /*
    |--------------------------------------------------------------------------
    | Content before first H3
    |--------------------------------------------------------------------------
    */

    if (trim($preamble) !== '') {
        array_unshift(
            $sections,
            [
                'heading_html' => '',
                'heading_text' => '',
                'body_html' => $preamble,
                'products' => collect(),
            ]
        );
    }

    /*
     * Content mein H3 na ho to original content normally show hoga.
     */
    if (empty($sections)) {
        $sections[] = [
            'heading_html' => '',
            'heading_text' => '',
            'body_html' => $content,
            'products' => collect(),
        ];
    }

    return $sections;
}
    /**
     * Heading aur uske content ke base par
     * exactly top 3 products select karo.
     */
    private function selectProductsForSection(
        Collection $products,
        string $heading,
        string $bodyHtml,
        array $usedProductKeys
    ): Collection {
        /*
         * Heading ko multiple times include karke
         * uski importance increase ki gayi hai.
         */
        $matchingText = implode(
            ' ',
            [
                $heading,
                $heading,
                $heading,
                $heading,
                strip_tags($bodyHtml),
            ]
        );

        $keywords = $this->extractKeywords(
            $matchingText
        );

        $rankedProducts = $products
            ->map(function (
                array $product
            ) use ($keywords): array {
                $product['_score'] =
                    $this->calculateProductScore(
                        $product,
                        $keywords
                    );

                return $product;
            })
            ->sort(function (
                array $first,
                array $second
            ): int {
                $scoreComparison =
                    ($second['_score'] ?? 0)
                    <=>
                    ($first['_score'] ?? 0);

                if ($scoreComparison !== 0) {
                    return $scoreComparison;
                }

                return (
                    $first['_position'] ?? 0
                )
                <=>
                (
                    $second['_position'] ?? 0
                );
            })
            ->values();

        /*
         * Pehle previously unused products.
         */
        $selectedProducts = $rankedProducts
            ->reject(function (
                array $product
            ) use ($usedProductKeys): bool {
                return in_array(
                    $this->getProductKey($product),
                    $usedProductKeys,
                    true
                );
            })
            ->take(3)
            ->values();

        /*
         * Agar available unique products kam reh gaye,
         * relevant products ko repeat kar sakte hain.
         */
        if ($selectedProducts->count() < 3) {
            $selectedKeys = $selectedProducts
                ->map(
                    fn (array $product): string =>
                        $this->getProductKey($product)
                )
                ->all();

            $additionalProducts = $rankedProducts
                ->reject(function (
                    array $product
                ) use ($selectedKeys): bool {
                    return in_array(
                        $this->getProductKey($product),
                        $selectedKeys,
                        true
                    );
                })
                ->take(
                    3 - $selectedProducts->count()
                );

            $selectedProducts =
                $selectedProducts
                    ->concat($additionalProducts)
                    ->take(3)
                    ->values();
        }

        return $selectedProducts
            ->map(function (
                array $product
            ): array {
                unset(
                    $product['_score'],
                    $product['_position']
                );

                return $product;
            })
            ->values();
    }

    /**
     * Blog section se meaningful keywords nikalo.
     */
    private function extractKeywords(
        string $text
    ): Collection {
        $normalizedText =
            $this->normalizeText($text);

        $words = preg_split(
            '/\s+/',
            $normalizedText,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        if (!is_array($words)) {
            return collect();
        }

        return collect($words)
            ->map(
                fn (string $word): string =>
                    $this->stemWord($word)
            )
            ->filter(function (
                string $word
            ): bool {
                return mb_strlen($word) >= 3
                    && !in_array(
                        $word,
                        $this->stopWords(),
                        true
                    );
            })
            ->countBy()
            ->sortDesc()
            ->take(50);
    }

    /**
     * Product relevance score calculate karo.
     */
    private function calculateProductScore(
        array $product,
        Collection $keywords
    ): int {
        if ($keywords->isEmpty()) {
            return 0;
        }

        $titleTokens = $this->tokenSet(
            (string) ($product['title'] ?? '')
        );

        $typeTokens = $this->tokenSet(
            (string) (
                $product['product_type'] ?? ''
            )
        );

        $tagTokens = $this->tokenSet(
            implode(
                ' ',
                $product['tags'] ?? []
            )
        );

        $collectionTokens = $this->tokenSet(
            (string) (
                $product['collection_text'] ?? ''
            )
        );

        $descriptionTokens = $this->tokenSet(
            (string) (
                $product['description'] ?? ''
            )
        );

        $score = 0;

        foreach (
            $keywords as $keyword => $frequency
        ) {
            $frequency = min(
                (int) $frequency,
                4
            );

            if (isset($titleTokens[$keyword])) {
                $score += 16 * $frequency;
            }

            if (isset($collectionTokens[$keyword])) {
                $score += 13 * $frequency;
            }

            if (isset($typeTokens[$keyword])) {
                $score += 11 * $frequency;
            }

            if (isset($tagTokens[$keyword])) {
                $score += 9 * $frequency;
            }

            if (
                isset(
                    $descriptionTokens[$keyword]
                )
            ) {
                $score += 3 * $frequency;
            }
        }

        return $score;
    }

    /**
     * Text ko token lookup array mein convert karo.
     */
    private function tokenSet(string $text): array
    {
        $normalizedText =
            $this->normalizeText($text);

        $words = preg_split(
            '/\s+/',
            $normalizedText,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        if (!is_array($words)) {
            return [];
        }

        $tokens = collect($words)
            ->map(
                fn (string $word): string =>
                    $this->stemWord($word)
            )
            ->filter(
                fn (string $word): bool =>
                    mb_strlen($word) >= 2
            )
            ->unique()
            ->values()
            ->all();

        return array_fill_keys(
            $tokens,
            true
        );
    }

    /**
     * Singular/plural matching improve karo.
     */
    private function stemWord(string $word): string
    {
        $word = strtolower(trim($word));

        if (
            strlen($word) > 4
            && Str::endsWith($word, 'ies')
        ) {
            return substr($word, 0, -3) . 'y';
        }

        if (
            strlen($word) > 4
            && Str::endsWith($word, 'es')
            && !Str::endsWith($word, 'ses')
        ) {
            return substr($word, 0, -2);
        }

        if (
            strlen($word) > 3
            && Str::endsWith($word, 's')
            && !Str::endsWith($word, 'ss')
        ) {
            return substr($word, 0, -1);
        }

        return $word;
    }

    /**
     * Matching se generic words remove karo.
     */
    private function stopWords(): array
    {
        return [
            'about',
            'after',
            'again',
            'also',
            'and',
            'are',
            'because',
            'been',
            'before',
            'best',
            'blog',
            'but',
            'can',
            'choose',
            'conclusion',
            'express',
            'food',
            'foods',
            'for',
            'from',
            'get',
            'halal',
            'has',
            'have',
            'how',
            'into',
            'made',
            'meal',
            'meals',
            'more',
            'most',
            'our',
            'product',
            'products',
            'ready',
            'service',
            'services',
            'sydney',
            'sizzlingo',
            'that',
            'the',
            'their',
            'these',
            'they',
            'this',
            'through',
            'understanding',
            'what',
            'when',
            'where',
            'which',
            'why',
            'with',
            'worth',
            'you',
            'your',
        ];
    }

    private function normalizeText(
        string $text
    ): string {
        $text = html_entity_decode(
            strip_tags($text),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        $text = Str::ascii(
            Str::lower($text)
        );

        $text = str_replace(
            [
                '-',
                '/',
                '&',
                ',',
                '.',
                ':',
                ';',
                '(',
                ')',
                '[',
                ']',
            ],
            ' ',
            $text
        );

        $text = preg_replace(
            '/[^a-z0-9\s]/',
            ' ',
            $text
        ) ?? '';

        return trim(
            preg_replace(
                '/\s+/',
                ' ',
                $text
            ) ?? ''
        );
    }

    /**
     * Shopify Storefront API request.
     */
    private function storefrontRequest(
        string $query,
        array $variables = []
    ): array {
        $storeDomain = trim(
            (string) config(
                'services.sizzlingo.store_domain'
            )
        );

        $apiVersion = trim(
            (string) config(
                'services.sizzlingo.api_version',
                '2026-07'
            )
        );

        $accessToken = trim(
            (string) config(
                'services.sizzlingo.storefront_token'
            )
        );

        if ($storeDomain === '') {
            throw new RuntimeException(
                'Sizzzlingo Shopify store domain is missing.'
            );
        }

        if (
            $accessToken === ''
            || $accessToken ===
                'PASTE_STOREFRONT_ACCESS_TOKEN_HERE'
        ) {
            throw new RuntimeException(
                'Sizzzlingo Storefront API token is missing.'
            );
        }

        $endpoint = sprintf(
            'https://%s/api/%s/graphql.json',
            $storeDomain,
            $apiVersion
        );

        $requestOptions = [
            'connect_timeout' => 10,
        ];

        /*
         * Local WAMP SSL workaround only.
         */
        if (
            app()->environment('local')
            && PHP_OS_FAMILY === 'Windows'
        ) {
            $requestOptions['verify'] = false;
        }

        $response = Http::withHeaders([
            'X-Shopify-Storefront-Access-Token' =>
                $accessToken,

            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'PocketThrift/1.0',
        ])
            ->timeout(30)
            ->withOptions($requestOptions)
            ->retry(2, 500)
            ->post(
                $endpoint,
                [
                    'query' => $query,

                    'variables' => empty($variables)
                        ? new \stdClass()
                        : $variables,
                ]
            );

        if (!$response->successful()) {
            throw new RuntimeException(
                'Shopify API returned HTTP '
                . $response->status()
                . '. Response: '
                . Str::limit(
                    $response->body(),
                    700
                )
            );
        }

        $responseData = $response->json();

        if (!is_array($responseData)) {
            throw new RuntimeException(
                'Shopify returned invalid JSON.'
            );
        }

        if (!empty($responseData['errors'])) {
            throw new RuntimeException(
                'Shopify GraphQL error: '
                . json_encode(
                    $responseData['errors'],
                    JSON_UNESCAPED_SLASHES
                    | JSON_UNESCAPED_UNICODE
                )
            );
        }

        $data = $responseData['data'] ?? null;

        if (!is_array($data)) {
            throw new RuntimeException(
                'Shopify response contains no valid data.'
            );
        }

        return $data;
    }

    private function addAffiliateReference(
        string $url
    ): string {
        $affiliateRef = trim(
            (string) config(
                'services.sizzlingo.affiliate_ref',
                'SEFood10'
            )
        );

        if ($affiliateRef === '') {
            return $url;
        }

        if (
            preg_match(
                '/([?&])ref=[^&#]*/i',
                $url
            ) === 1
        ) {
            return preg_replace(
                '/([?&])ref=[^&#]*/i',
                '$1ref='
                    . rawurlencode($affiliateRef),
                $url
            ) ?? $url;
        }

        $separator = str_contains($url, '?')
            ? '&'
            : '?';

        return $url
            . $separator
            . http_build_query(
                [
                    'ref' => $affiliateRef,
                ],
                '',
                '&',
                PHP_QUERY_RFC3986
            );
    }

    private function formatMoney(
        float $amount,
        string $currencyCode
    ): string {
        $symbols = [
            'AUD' => 'A$',
            'USD' => '$',
            'NZD' => 'NZ$',
            'GBP' => '£',
            'EUR' => '€',
        ];

        $prefix = $symbols[$currencyCode]
            ?? $currencyCode . ' ';

        return $prefix
            . number_format(
                $amount,
                2
            );
    }

    private function getStoreUrl(): string
    {
        return rtrim(
            (string) config(
                'services.sizzlingo.store_url',
                'https://www.sizzzlingoexpress.com.au'
            ),
            '/'
        );
    }

    private function getProductKey(
        array $product
    ): string {
        return (string) (
            $product['id']
            ?? $product['handle']
            ?? ''
        );
    }
}
