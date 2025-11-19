<!--@include('schemas._helpers', ['region' => $region ?? null, 'regionCode' => $regionCode ?? null, 'meta' => $meta ?? []])-->

<!--@php-->
<!--// meta should contain: title, description, path, breadcrumbs, faqs (optional)-->
<!--$title = $meta['title'] ?? ($store->name ?? 'Store');-->
<!--$description = $meta['description'] ?? ($store->meta_description ?? null);-->
<!--$webpage = ["@context"=>"https://schema.org/","@type"=>"WebPage","name"=>$title,"description"=>$description,"url"=>$fullUrl,"publisher"=>["@type"=>"Organization","name"=>"PocketThrift","logo"=>["@type"=>"ImageObject","url"=>$baseUrl . '/images/og-image.webp']]];-->

<!--// build breadcrumbs as in webpage-breadcrumb-->
<!--$breadcrumbs = $meta['breadcrumbs'] ?? [['name'=>'Home','url'=>'/'],['name'=>'Stores','url'=>'/stores/'],['name'=>$title,'url'=>$meta['path'] ?? request()->getPathInfo()]];-->
<!--$items = []; $pos=1;-->
<!--foreach($breadcrumbs as $b){ $u = strpos($b['url'],'http')===0 ? $b['url'] : rtrim($baseUrl,'/').'/'.ltrim($b['url'],'/'); $items[]=["@type"=>"ListItem","position"=>$pos++,"name"=>$b['name'],"item"=>$u]; }-->
<!--$breadcrumbSchema = ["@context"=>"https://schema.org/","@type"=>"BreadcrumbList","itemListElement"=>$items];-->

<!--// FAQ-->
<!--$faqSchema = null;-->
<!--if(!empty($meta['faqs']) && is_array($meta['faqs'])){-->
<!--  $ents = [];-->
<!--  foreach($meta['faqs'] as $f){ $ents[]=["@type"=>"Question","name"=>$f['question'],"acceptedAnswer"=>["@type"=>"Answer","text"=>$f['answer']]]; }-->
<!--  if($ents) $faqSchema = ["@context"=>"https://schema.org/","@type"=>"FAQPage","mainEntity"=>$ents];-->
<!--}-->
<!--@endphp-->

<!--<script type="application/ld+json">{!! json_encode($webpage, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>-->
<!--<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>-->
<!--@if($faqSchema)-->
<!--<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>-->
<!--@endif-->
