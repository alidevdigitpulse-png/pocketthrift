<!--@php-->
<!--// helper to derive base URL for region-->
<!--$regionCode = $region->code ?? ($regionCode ?? 'us');-->
<!--$appUrl = rtrim(config('app.url','https://pocketthrift.com'), '/');-->

<!--function region_base_url_blade($appUrl, $regionCode){-->
<!--    if(strtolower($regionCode) === 'us') return $appUrl;-->
<!--    return $appUrl . '/' . strtolower($regionCode);-->
<!--}-->

<!--$baseUrl = region_base_url_blade($appUrl, $regionCode);-->
<!--$path = $meta['path'] ?? request()->getPathInfo();-->
<!--$path = '/'.ltrim($path, '/');-->
<!--$fullUrl = rtrim($baseUrl, '/') . $path;-->
<!--@endphp-->
