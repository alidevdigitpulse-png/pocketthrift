<!--@include('schemas._helpers', ['region' => $region ?? null, 'regionCode' => $regionCode ?? null, 'meta' => $meta ?? []])-->

<!--@php-->
<!--$org = [-->
<!--  "@context"=>"https://schema.org/",-->
<!--  "@type"=>"Organization",-->
<!--  "name"=>"PocketThrift",-->
<!--  "url"=>$baseUrl . '/',-->
<!--  "logo"=>$baseUrl . '/images/og-image.webp',-->
<!--  "contactPoint"=>["@type"=>"ContactPoint","telephone"=>"+61 414 573 000","contactType"=>"customer service","areaServed"=> ($region->name ?? ($meta['region_name'] ?? strtoupper($regionCode ?? 'US')) ),"availableLanguage"=>"en-US"],-->
<!--  "sameAs"=>["https://www.facebook.com/people/Pocket-Thrift/61562541071877/?mibextid=ZbWKwL","https://x.com/Pocketthrift","https://www.instagram.com/pocketthrift1/","https://www.linkedin.com/in/pocket-thrift-40336131b","https://www.pinterest.com/pocketthrift1/"]-->
<!--];-->

<!--$website = ["@context"=>"https://schema.org/","@type"=>"WebSite","name"=>"PocketThrift","url"=>$baseUrl . '/',"potentialAction"=>["@type"=>"SearchAction","target"=>$baseUrl . "/search?q={search_term_string}","query-input"=>"required name=search_term_string"]];-->

<!--// FAQs: pass meta['faqs'] = [ ['question'=>'','answer'=>''], ... ]-->
<!--$faqSchema = null;-->
<!--if(!empty($meta['faqs']) && is_array($meta['faqs'])){-->
<!--  $entities = [];-->
<!--  foreach($meta['faqs'] as $f){-->
<!--    $entities[] = ["@type"=>"Question","name"=>$f['question'],"acceptedAnswer"=>["@type"=>"Answer","text"=>$f['answer']]];-->
<!--  }-->
<!--  if(count($entities)) $faqSchema = ["@context"=>"https://schema.org/","@type"=>"FAQPage","mainEntity"=>$entities];-->
<!--}-->
<!--@endphp-->

<!--<script type="application/ld+json">{!! json_encode($org, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>-->
<!--<script type="application/ld+json">{!! json_encode($website, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>-->

<!--@if($faqSchema)-->
<!--<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>-->
<!--@endif-->
