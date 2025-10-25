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

    <x-header-component :materialType="$material->type" :$material />

    <div class="base-wrapper {{ markIfBlocked($material) }}">
        <!--- Base Main --->
        <main class="base-main {{ collect($layoutSettings['classes'])->join(' ') }}">
            <section class="base-main-section">
                <section class="material-content">
                    <h1>{!! $material->content()->title !!}</h1>

                    @if($material->content()->content)
                        {!! $material->content()->content !!}
                    @endif

                    <x-media-view-component path="general" :$material />

                    <h3>{!! __('form.appeal.popup_title') !!}</h3>
                    <p>{!! __('form.appeal.popup_desc') !!}</p>
                    <x-form.appeal-form-component />

                    <x-contacts-view-component />
                </section>
            </section>

            <aside class="base-main-aside"></aside>
        </main>

        <x-footer-component/>
    </div>
</x-layouts.base>
