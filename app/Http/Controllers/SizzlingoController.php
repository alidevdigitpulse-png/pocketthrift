<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class SizzlingoController extends Controller
{
    /**
     * Display the Sizzzlingo landing page.
     */
    public function index(string $region)
    {
        /*
        |--------------------------------------------------------------------------
        | Australia region only
        |--------------------------------------------------------------------------
        */

        abort_unless(strtolower($region) === 'au', 404);

        $collectionsUnavailable = false;
        $collectionsError = null;

        try {
            $collections = $this->getCollections();
        } catch (Throwable $exception) {
            report($exception);

            /*
            |--------------------------------------------------------------------------
            | API fallback
            |--------------------------------------------------------------------------
            |
            | Shopify request fail ho to last successful cached response use karo.
            |
            */

            $backupCollections = Cache::get(
                'sizzlingo.storefront.collections.backup',
                []
            );

            $collections = $this->formatCollections(
                is_array($backupCollections)
                    ? $backupCollections
                    : []
            );

            $collectionsUnavailable = $collections->isEmpty();

            /*
             * Local environment mein actual error available rahegi.
             * Production par visitor ko technical error show nahi hogi.
             */
            if (app()->environment('local')) {
                $collectionsError = $exception->getMessage();
            }
        }

        return view('sizzlingo.index', [
            'collections' => $collections,
            'collectionsUnavailable' => $collectionsUnavailable,
            'collectionsError' => $collectionsError,
            'sizzlingoBaseUrl' => $this->getStoreUrl(),
        ]);
    }

    /**
     * Fetch collections from Shopify Storefront GraphQL API.
     */
    private function getCollections(): Collection
    {
        $rawCollections = Cache::remember(
            'sizzlingo.storefront.collections.v3',
            now()->addHours(6),
            function (): array {
                $query = <<<'GRAPHQL'
query GetCollections {
    collections(first: 50) {
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

                $data = $this->storefrontRequest($query);

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

                /*
                 * Last successful raw response ko seven days backup rakho.
                 */
                Cache::put(
                    'sizzlingo.storefront.collections.backup',
                    $collections,
                    now()->addDays(7)
                );

                return $collections;
            }
        );

        return $this->formatCollections(
            is_array($rawCollections)
                ? $rawCollections
                : []
        );
    }

    /**
     * Send a request to Shopify Storefront GraphQL API.
     */

/**
 * Send request to Shopify Storefront GraphQL API.
 */
private function storefrontRequest(
    string $query,
    array $variables = []
): array {
    $storeDomain = trim(
        (string) config('services.sizzlingo.store_domain')
    );

    $apiVersion = trim(
        (string) config(
            'services.sizzlingo.api_version',
            '2026-07'
        )
    );

    $accessToken = trim(
        (string) config('services.sizzlingo.storefront_token')
    );

    /*
    |--------------------------------------------------------------------------
    | Validate configuration
    |--------------------------------------------------------------------------
    */

    if ($storeDomain === '') {
        throw new \RuntimeException(
            'Sizzzlingo Shopify store domain is missing.'
        );
    }

    if (
        $accessToken === ''
        || $accessToken === 'PASTE_STOREFRONT_ACCESS_TOKEN_HERE'
    ) {
        throw new \RuntimeException(
            'Sizzzlingo Storefront API token is missing.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Storefront GraphQL endpoint
    |--------------------------------------------------------------------------
    */

    $endpoint = sprintf(
        'https://%s/api/%s/graphql.json',
        $storeDomain,
        $apiVersion
    );

    /*
    |--------------------------------------------------------------------------
    | Guzzle options
    |--------------------------------------------------------------------------
    |
    | Local Windows WAMP par SSL verification temporarily disable hogi.
    | Live production server par SSL verification enabled rahegi.
    |
    */

    $requestOptions = [
        'connect_timeout' => 10,
    ];

    if (
        app()->environment('local')
        && PHP_OS_FAMILY === 'Windows'
    ) {
        $requestOptions['verify'] = false;
    }

    /*
    |--------------------------------------------------------------------------
    | Send Shopify request
    |--------------------------------------------------------------------------
    */

    $request = \Illuminate\Support\Facades\Http::withHeaders([
        'X-Shopify-Storefront-Access-Token' => $accessToken,
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'User-Agent' => 'PocketThrift/1.0',
    ])
        ->timeout(30)
        ->withOptions($requestOptions)
        ->retry(2, 500);

    $response = $request->post($endpoint, [
        'query' => $query,

        /*
         * GraphQL variables should be an object when empty.
         */
        'variables' => empty($variables)
            ? new \stdClass()
            : $variables,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Handle HTTP errors
    |--------------------------------------------------------------------------
    */

    if (!$response->successful()) {
        throw new \RuntimeException(
            'Shopify Storefront API returned HTTP '
            . $response->status()
            . '. Response: '
            . \Illuminate\Support\Str::limit(
                $response->body(),
                700
            )
        );
    }

    $responseData = $response->json();

    if (!is_array($responseData)) {
        throw new \RuntimeException(
            'Shopify returned an invalid JSON response.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Handle GraphQL errors
    |--------------------------------------------------------------------------
    |
    | GraphQL HTTP 200 ke bawajood errors return kar sakta hai.
    |
    */

    if (!empty($responseData['errors'])) {
        throw new \RuntimeException(
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
        throw new \RuntimeException(
            'Shopify GraphQL response does not contain valid data.'
        );
    }

    return $data;
}



    /**
     * Format collections for the landing-page Blade.
     */
    private function formatCollections(
        array $rawCollections
    ): Collection {
        $storeUrl = $this->getStoreUrl();

        return collect($rawCollections)
            ->filter(function ($collection): bool {
                return is_array($collection)
                    && !empty($collection['title'])
                    && !empty($collection['handle']);
            })
            ->reject(function ($collection): bool {
                $handle = strtolower(
                    trim(
                        (string) ($collection['handle'] ?? '')
                    )
                );

                /*
                 * Shopify default/general collections hide karo.
                 */
                return in_array(
                    $handle,
                    [
                        'frontpage',
                        'all',
                    ],
                    true
                );
            })
            ->map(function ($collection) use ($storeUrl): array {
                $title = trim(
                    (string) ($collection['title'] ?? '')
                );

                $handle = trim(
                    (string) ($collection['handle'] ?? ''),
                    '/'
                );

                $description = trim(
                    (string) ($collection['description'] ?? '')
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
                        ? Str::limit($description, 105)
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
                     * Abhi direct Shopify collection link.
                     * Baad mein internal collection page route laga sakte hain.
                     */
                    'url' => $storeUrl
                        . '/collections/'
                        . $handle,
                ];
            })

            /*
             * Reference design mein six collection cards hain.
             * Sab collections dikhani hon to take(6) remove kar dena.
             */
            ->take(6)
            ->values();
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
