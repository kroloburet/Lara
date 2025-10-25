<x-layouts.base
    :title='__("material.list.{$type}.meta_title")'
    :description='__("material.list.{$type}.meta_desc")'
    robots="all"
>
    <x-top-nav-component/>

    <!--- Base Hero --->
    <header class="base-hero-s">
        <h1>{!! __("material.list.{$type}.page_title") !!}</h1>
        <p>{!! __("material.list.{$type}.page_subtitle_desc") !!}</p>
    </header>

    <!--- Base Main --->
    <main class="base-main not-aside">
        <section class="base-main-section">
            <div id="materialList" class="materialList"></div>
            <button id="materialList_more" class="UI_button moreResults  Paginator_more">
                {!! __('base.Show_more') !!}
            </button>
        </section>

        <aside class="base-main-aside"></aside>
    </main>

    @vite('resources/js/front/material-list.js')
    @pushOnce('endPage')

        <!--
        ########### Init Material List and Paginate
        -->

        <script>
            document.addEventListener(`DOMContentLoaded`, () => {
                new MaterialList(`{{ $type }}`);
            });
        </script>
    @endPushOnce

    <x-footer-component/>
</x-layouts.base>
