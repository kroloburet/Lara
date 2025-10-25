@props(['left', 'right'])

@if(! $attributes->has('withoutMenu'))
    <x-top-nav-component />
@else
    <div class="top-nav-replacer">
        <div class="container">
            <a href="{{ route('home') }}" class="logo" title="{{ __('base.default_meta_title') }}"></a>

            <x-lang-switcher-component />
        </div>
    </div>
@endif

<main {{ $attributes->class(['two-column', 'container']) }}>

    <!--
    ########### Left column
    -->

    <aside class="two-column_left">
        <!--- Content --->
        <section {{ $left->attributes->class(['two-column_left-content']) }}>
            {{ $left }}
        </section>
    </aside>

    <!--
    ########### Right column
    -->

    <aside {{ $right->attributes->class(['two-column_right']) }}>
        <!--- Content --->
        {{ $right }}
    </aside>
</main>

@vite('resources/css/two-column.css')
