@props(['left', 'right', 'withoutMenu' => false])

<x-top-nav-component :without-menu="$withoutMenu" />

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
