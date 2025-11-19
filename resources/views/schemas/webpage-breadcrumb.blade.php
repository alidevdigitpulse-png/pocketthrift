<!--@include('schemas._helpers', ['region' => $region ?? null, 'regionCode' => $regionCode ?? null, 'meta' => $meta ?? []])-->

<!--@php-->
<!--$title = $meta['title'] ?? ($pageTitle ?? ($page->title ?? ''));-->
<!--$description = $meta['description'] ?? ($page->meta_description ?? null);-->
<!--$path = $meta['path'] ?? $path;-->
<!--$breadcrumbs = $meta['breadcrumbs'] ?? [['name'=>'Home','url'=>'/'], ['name'=>$title,'url'=>$path]];-->

<!--$webpage = [-->
<!--  "@context"=>"https://schema.org/",-->
<!--  "@type"=>"WebPage",-->
<!--  "name"=>$title,-->
<!--  "description"=>$description,-->
<!--  "url"=>$fullUrl,-->
<!--  "publisher"=>["@type"=>"Organization","name"=>"PocketThrift","logo"=>["@type"=>"ImageObject","url"=>$baseUrl . '/images/og-image.webp']]-->
<!--];-->

<!--$crumbItems = [];-->
<!--$pos = 1;-->
<!--foreach($breadcrumbs as $b){-->
<!--  $url = strpos($b['url'],'http') === 0 ? $b['url'] : rtrim($baseUrl,'/').'/'.ltrim($b['url'],'/');-->
<!--  $crumbItems[] = ["@type"=>"ListItem","position"=>$pos++,"name"=>$b['name'],"item"=>$url];-->
<!--}-->
<!--$breadcrumbSchema = ["@context"=>"https://schema.org/","@type"=>"BreadcrumbList","itemListElement"=>$crumbItems];-->
<!--@endphp-->

<!--<script type="application/ld+json">{!! json_encode($webpage, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>-->
<!--<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>-->
