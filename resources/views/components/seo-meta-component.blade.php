
<!--
########### Seo Meta Component
-->

@if(! empty($canonicalLink))
    <link rel="canonical" href="{{ $canonicalLink['href'] }}" />
@endif

@foreach($alternateLinks as $link)
    <link rel="alternate" href="{{ $link['href'] }}" hreflang="{{ $link['hreflang'] }}" />
@endforeach
