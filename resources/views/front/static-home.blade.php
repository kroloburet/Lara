<x-layouts.base
    :title="$material->content()->title . ' | ' . env('APP_NAME')"
    :description="$material->content()->description"
    :preview_image="materialBgImageUrl($material)"
    :robots="$material->robots"
    :css="$material->css"
    :js="$material->js"
    :seo="$material"
    :layout="$material"
>

    <x-top-nav-component/>

    <x-header-component :materialType="$material->type" :$material>
        <!--
        ########### Hero
        -->

        <section class="base-hero container">
            <div class="hero-content">
                <h1>{{ __('base.default_meta_title') }}</h1>
                <p class="hero-desc">
                    {{ __('base.default_meta_desc') }}
                </p>
            </div>
        </section>
    </x-header-component>

    <div class="base-wrapper {{ markIfBlocked($material) }}">
        <!--- Base Main --->
        <main class="base-main {{ collect($layoutSettings['classes'])->join(' ') }}">
            <section class="base-main-section">
                <section class="material-content">
                    <h2>{!! $material->content()->title !!}</h2>

                    @if($material->content()->content)
                        {!! $material->content()->content !!}
                    @endif

                    <x-media-view-component path="general" :$material />
                </section>
            </section>

            <aside class="base-main-aside">
                <x-content-tools-component class="verbal" :model="$material" verbal />

                <x-admin.quick-admin-material-actions :$material />
            </aside>
        </main>

        <x-footer-component/>
    </div>
</x-layouts.base>
