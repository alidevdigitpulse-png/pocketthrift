<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'sizzlingo' => [
        'store_domain' => 'sizlingoexpress.myshopify.com',
        'api_version' => '2026-07',

        /*
        |--------------------------------------------------------------------------
        | Storefront API Token
        |--------------------------------------------------------------------------
        |
        | Yahan Storefront API public access token paste karo.
        | Admin API token mat lagana.
        |
        */

        'storefront_token' => '8d6779abaae3356089849bc62613eff6',

        'store_url' => 'https://www.sizzzlingoexpress.com.au',

         'affiliate_ref' => 'SEFood10',
    ],



    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

  'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
    'facebook' => [
        'client_id' => '852525629054617',
        'client_secret' => '6da76aca496e4872f1ffd29bc3933cba',
        'redirect' => 'http://localhost:8000/callback',
    ],

    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
   ],

];
