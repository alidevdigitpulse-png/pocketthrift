<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Pagination\LengthAwarePaginator;
class SizzlingoController extends Controller
{
    private const COLLECTIONS_BACKUP_KEY =
        'sizzlingo.storefront.collections.backup';

    /**
     * Display Sizzzlingo landing page with all collections.
     */
    public function index(string $region)
    {
        $region = $this->validateRegion($region);

        $rawCollections = [];
        $collectionsError = null;
        $usingBackupData = false;

        /*
        |--------------------------------------------------------------------------
        | Fetch raw API data
        |--------------------------------------------------------------------------
        |
        | Route URLs ko is try block ke andar generate nahi kiya ja raha.
        | Isse route errors API errors ke taur par catch nahi honge.
        |
        */

        try {
            $rawCollections = $this->fetchCollections();

            if (!empty($rawCollections)) {
                Cache::put(
                    self::COLLECTIONS_BACKUP_KEY,
                    $rawCollections,
                    now()->addDays(7)
                );
            }
        } catch (Throwable $exception) {
            report($exception);

            $usingBackupData = true;

            $backupCollections = Cache::get(
                self::COLLECTIONS_BACKUP_KEY,
                []
            );

            $rawCollections = is_array($backupCollections)
                ? $backupCollections
                : [];

            if (app()->environment('local')) {
                $collectionsError = $exception->getMessage();
            }
        }

            /*
            |--------------------------------------------------------------------------
            | Format all Shopify collections
            |--------------------------------------------------------------------------
            */

            $formattedCollections = $this->formatCollections(
                $rawCollections,
                $region
            );

            /*
            |--------------------------------------------------------------------------
            | Collections pagination
            |--------------------------------------------------------------------------
            */

            $perPage = 10;

            $totalCollections = $formattedCollections->count();

            $lastPage = max(
                (int) ceil($totalCollections / $perPage),
                1
            );

            $currentPage = (int) LengthAwarePaginator::resolveCurrentPage();

            $currentPage = max(
                1,
                min($currentPage, $lastPage)
            );

            $currentPageCollections = $formattedCollections
                ->forPage($currentPage, $perPage)
                ->values();

            $collections = new LengthAwarePaginator(
                $currentPageCollections,
                $totalCollections,
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]
            );
        return view('sizzlingo.index', [
            'region' => $region,
            'collections' => $collections,
            'collectionsUnavailable' => $collections->isEmpty(),
            'collectionsError' => $collectionsError,
            'usingBackupData' => $usingBackupData,
            'sizzlingoBaseUrl' => $this->getStoreUrl(),
        ]);
    }

/**
 * Display selected Shopify collection
 * and its dependent products.
 */
/**
 * Show selected Shopify collection and its products.
 */
public function showCollection(
    string $region,
    string $handle
) {
    $region = strtolower(trim($region));
    $handle = strtolower(trim($handle));

    abort_unless($region === 'au', 404);

    abort_unless(
        preg_match('/^[a-z0-9\-]+$/', $handle) === 1,
        404
    );

    try {
        $rawCollection = $this->fetchCollectionProducts(
            $handle
        );

        if (
            !is_array($rawCollection)
            || empty($rawCollection['id'])
        ) {
            return response(
                'Collection not found.',
                404
            );
        }

        $collection = $this->formatCollectionProducts(
            $rawCollection
        );

        return view('sizzlingo.collection', [
            'region' => $region,
            'collection' => $collection,
            'products' => $collection['products'],
            'collectionError' => null,
            'usingBackupData' => false,
            'sizzlingoBaseUrl' => $this->getStoreUrl(),
        ]);
    } catch (Throwable $exception) {
        report($exception);

        if (app()->environment('local')) {
            return response()->json([
                'success' => false,
                'stage' => 'collection_view',
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ], 500);
        }

        return response(
            'Sizzzlingo products are temporarily unavailable.',
            503
        );
    }
}
    /**
     * Fetch all available collections.
     */
    private function fetchCollections(): array
    {
        $query = <<<'GRAPHQL'
query GetCollections($first: Int!) {
    collections(first: $first) {
        nodes {
            id
            title
            handle
            description

            image {
                url
                altText
            }
        }

        pageInfo {
            hasNextPage
            endCursor
        }
    }
}
GRAPHQL;

        $data = $this->storefrontRequest(
            $query,
            [
                'first' => 50,
            ]
        );

        $collections = data_get(
            $data,
            'collections.nodes',
            []
        );

        if (!is_array($collections)) {
            throw new RuntimeException(
                'Invalid collections data received from Shopify.'
            );
        }

        return $collections;
    }


/**
 * Fetch selected collection and its products.
 */
private function fetchCollectionProducts(
    string $handle
): ?array {
    $query = <<<'GRAPHQL'
query GetCollectionProducts(
    $handle: String!
    $productsFirst: Int!
) {
    collection(handle: $handle) {
        id
        title
        handle
        description

        image {
            url
            altText
        }

        seo {
            title
            description
        }

        products(first: $productsFirst) {
            nodes {
                id
                title
                handle
                description(truncateAt: 160)
                availableForSale

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
                        id
                        availableForSale

                        price {
                            amount
                            currencyCode
                        }

                        compareAtPrice {
                            amount
                            currencyCode
                        }
                    }
                }
            }
        }
    }
}
GRAPHQL;

    $data = $this->storefrontRequest(
        $query,
        [
            'handle' => $handle,
            'productsFirst' => 50,
        ]
    );

    $collection = data_get(
        $data,
        'collection'
    );

    return is_array($collection)
        ? $collection
        : null;
}

    /**
     * Send request using Laravel HTTP Client
     * to Shopify Storefront GraphQL API.
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

        /*
        |--------------------------------------------------------------------------
        | Validate configuration
        |--------------------------------------------------------------------------
        */

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
         * Local Windows/WAMP SSL workaround only.
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
            'Cache-Control' => 'no-cache',
            'Pragma' => 'no-cache',
        ])
            ->timeout(30)
            ->withOptions($requestOptions)
            ->retry(2, 500)
            ->post($endpoint, [
                'query' => $query,

                'variables' => empty($variables)
                    ? new \stdClass()
                    : $variables,
            ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                'Shopify Storefront API returned HTTP '
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

        /*
         * GraphQL can return errors with HTTP 200.
         */
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
                'Shopify GraphQL response contains no valid data.'
            );
        }

        return $data;
    }

    /**
     * Format landing-page collections.
     */
    private function formatCollections(
        array $rawCollections,
        string $region
    ): Collection {
        return collect($rawCollections)
            ->filter(function ($collection): bool {
                return is_array($collection)
                    && !empty($collection['title'])
                    && !empty($collection['handle']);
            })
            ->reject(function ($collection): bool {
                $handle = strtolower(
                    trim(
                        (string) (
                            $collection['handle'] ?? ''
                        )
                    )
                );

                return in_array(
                    $handle,
                    [
                        'frontpage',
                        'all',
                    ],
                    true
                );
            })
            ->map(function ($collection) use (
                $region
            ): array {
                $title = trim(
                    (string) (
                        $collection['title'] ?? ''
                    )
                );

                $handle = trim(
                    (string) (
                        $collection['handle'] ?? ''
                    ),
                    '/'
                );

                $description = trim(
                    (string) (
                        $collection['description'] ?? ''
                    )
                );

                $imageUrl = data_get(
                    $collection,
                    'image.url'
                );

                $imageAlt = data_get(
                    $collection,
                    'image.altText'
                );

                return [
                    'id' => $collection['id'] ?? null,

                    'title' => $title,

                    'handle' => $handle,

                    'description' => $description !== ''
                        ? Str::limit(
                            $description,
                            105
                        )
                        : 'Explore halal ready meals from Sizzzlingo Express.',

                    'image' => is_string($imageUrl)
                        && $imageUrl !== ''
                            ? $imageUrl
                            : null,

                    'image_alt' => is_string($imageAlt)
                        && $imageAlt !== ''
                            ? $imageAlt
                            : $title,

                    /*
                     * Correct internal collection route.
                     */
                    'url' => route(
                        'region.sizzlingo.collection',
                        [
                            'region' => $region,
                            'handle' => $handle,
                        ]
                    ),
                ];
            })
            ->values();
    }

/**
 * Add Sizzzlingo affiliate reference to an external URL.
 */
private function addAffiliateReference(string $url): string
{
    $affiliateRef = trim(
        (string) config(
            'services.sizzlingo.affiliate_ref'
        )
    );

    if ($affiliateRef === '') {
        return $url;
    }

    /*
     * URL mein pehle se ref parameter ho to duplicate na karo.
     */
    if (
        preg_match('/([?&])ref=/i', $url) === 1
    ) {
        return preg_replace(
            '/([?&])ref=[^&]*/i',
            '$1ref=' . rawurlencode($affiliateRef),
            $url
        );
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


   /**
 * Format selected collection and products.
 */
private function formatCollectionProducts(
    array $rawCollection
): array {
    $storeUrl = $this->getStoreUrl();

    /*
    |--------------------------------------------------------------------------
    | Sizzzlingo affiliate reference
    |--------------------------------------------------------------------------
    */

    $affiliateRef = trim(
        (string) config(
            'services.sizzlingo.affiliate_ref',
            'SEFood10'
        )
    );

    $rawProducts = data_get(
        $rawCollection,
        'products.nodes',
        []
    );

    $products = collect(
        is_array($rawProducts)
            ? $rawProducts
            : []
    )
        ->filter(function ($product): bool {
            return is_array($product)
                && !empty($product['title'])
                && !empty($product['handle']);
        })
        ->map(function ($product) use (
            $storeUrl,
            $affiliateRef
        ): array {
            $title = trim(
                (string) (
                    $product['title'] ?? ''
                )
            );

            $handle = strtolower(
                trim(
                    (string) (
                        $product['handle'] ?? ''
                    ),
                    '/'
                )
            );

            $imageUrl = data_get(
                $product,
                'featuredImage.url'
            );

            $imageAlt = data_get(
                $product,
                'featuredImage.altText'
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

            /*
            |--------------------------------------------------------------------------
            | Original Shopify product URL
            |--------------------------------------------------------------------------
            */

            $onlineStoreUrl = data_get(
                $product,
                'onlineStoreUrl'
            );

            $productUrl = is_string($onlineStoreUrl)
                && trim($onlineStoreUrl) !== ''
                    ? trim($onlineStoreUrl)
                    : $storeUrl
                        . '/products/'
                        . rawurlencode($handle);

            /*
            |--------------------------------------------------------------------------
            | Add affiliate reference
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | /products/russian-salad-300g?ref=SEFood10
            |
            */

            if ($affiliateRef !== '') {
                /*
                 * URL fragment temporarily separate karo.
                 */
                $fragment = '';

                $fragmentPosition = strpos(
                    $productUrl,
                    '#'
                );

                if ($fragmentPosition !== false) {
                    $fragment = substr(
                        $productUrl,
                        $fragmentPosition
                    );

                    $productUrl = substr(
                        $productUrl,
                        0,
                        $fragmentPosition
                    );
                }

                /*
                 * Agar ref already exist karta hai,
                 * uski value replace karo.
                 */
                if (
                    preg_match(
                        '/([?&])ref=[^&#]*/i',
                        $productUrl
                    ) === 1
                ) {
                    $productUrl = preg_replace(
                        '/([?&])ref=[^&#]*/i',
                        '$1ref='
                            . rawurlencode($affiliateRef),
                        $productUrl
                    );
                } else {
                    /*
                     * Existing query string ho to &,
                     * otherwise ? use karo.
                     */
                    if (strpos($productUrl, '?') !== false) {
                        $lastCharacter = substr(
                            $productUrl,
                            -1
                        );

                        $separator = in_array(
                            $lastCharacter,
                            ['?', '&'],
                            true
                        )
                            ? ''
                            : '&';
                    } else {
                        $separator = '?';
                    }

                    $productUrl .= $separator
                        . http_build_query(
                            [
                                'ref' => $affiliateRef,
                            ],
                            '',
                            '&',
                            PHP_QUERY_RFC3986
                        );
                }

                /*
                 * Fragment wapas URL ke end mein add karo.
                 */
                $productUrl .= $fragment;
            }

            return [
                'id' => $product['id'] ?? null,

                'title' => $title,

                'handle' => $handle,

                'description' => trim(
                    (string) (
                        $product['description'] ?? ''
                    )
                ),

                'available' => (bool) (
                    $product['availableForSale']
                    ?? false
                ),

                'image' => is_string($imageUrl)
                    && $imageUrl !== ''
                        ? $imageUrl
                        : null,

                'image_alt' => is_string($imageAlt)
                    && $imageAlt !== ''
                        ? $imageAlt
                        : $title,

                'price_label' => $hasPriceRange
                    ? 'From '
                        . $this->formatStorefrontMoney(
                            $minimumAmount,
                            $currencyCode
                        )
                    : $this->formatStorefrontMoney(
                        $minimumAmount,
                        $currencyCode
                    ),

                'compare_at_price' => $isOnSale
                    ? $this->formatStorefrontMoney(
                        $compareAtAmount,
                        $currencyCode
                    )
                    : null,

                'on_sale' => $isOnSale,

                /*
                 * Shopify URL with affiliate reference.
                 */
                'url' => $productUrl,
            ];
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Collection data
    |--------------------------------------------------------------------------
    */

    $collectionTitle = trim(
        (string) (
            $rawCollection['title'] ?? ''
        )
    );

    $collectionDescription = trim(
        (string) (
            $rawCollection['description'] ?? ''
        )
    );

    $collectionImage = data_get(
        $rawCollection,
        'image.url'
    );

    $collectionImageAlt = data_get(
        $rawCollection,
        'image.altText'
    );

    $seoTitle = trim(
        (string) data_get(
            $rawCollection,
            'seo.title',
            ''
        )
    );

    $seoDescription = trim(
        (string) data_get(
            $rawCollection,
            'seo.description',
            ''
        )
    );

    return [
        'id' => $rawCollection['id'] ?? null,

        'title' => $collectionTitle,

        'handle' => trim(
            (string) (
                $rawCollection['handle'] ?? ''
            )
        ),

        'description' => $collectionDescription,

        'image' => is_string($collectionImage)
            && $collectionImage !== ''
                ? $collectionImage
                : null,

        'image_alt' =>
            is_string($collectionImageAlt)
            && $collectionImageAlt !== ''
                ? $collectionImageAlt
                : $collectionTitle,

        'seo_title' => $seoTitle !== ''
            ? $seoTitle
            : $collectionTitle,

        'seo_description' =>
            $seoDescription !== ''
                ? $seoDescription
                : $collectionDescription,

        'products' => $products,

        'products_count' => $products->count(),
    ];
}
    /**
     * Validate Australia-only region.
     */
    private function validateRegion(
        string $region
    ): string {
        $region = strtolower(
            trim($region)
        );

        abort_unless(
            $region === 'au',
            404
        );

        return $region;
    }

    /**
     * Format Shopify money.
     */
    private function formatStorefrontMoney(
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

        return $prefix . number_format(
            $amount,
            2
        );
    }

    /**
     * Return public Sizzzlingo store URL.
     */
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
}
