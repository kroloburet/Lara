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
                <h1>{!! $material->content()->title !!}</h1>

                <x-breadcrumbs-component :$material/>

                @if($material->type === 'category')
                    <main class="categoryMaterialList">
                        <!--- Categories --->
                        <section class="materialList_section">
                            <h2>{!! __('material.Subcategories') !!}</h2>
                            <div id="materialList_category" class="materialList"></div>
                            <button id="materialList_category_more" class="UI_button  Paginator_more">
                                {!! __('base.Show_more') !!}
                            </button>
                        </section>

                        <!--- Pages --->
                        <section class="materialList_section">
                            <h2>{!! __('material.Subpages') !!}</h2>
                            <div id="materialList_page" class="materialList"></div>
                            <button id="materialList_page_more" class="UI_button  Paginator_more">
                                {!! __('base.Show_more') !!}
                            </button>
                        </section>
                    </main>
                @endif

                <section class="material-content">
                    @if($material->content()->content)
                        {!! $material->content()->content !!}
                    @endif

                    <x-media-view-component path="general" :$material />
                </section>
            </section>

            <aside class="base-main-aside">
                <x-content-tools-component class="dashboard-panel-item" :model="$material" verbal />

                <x-admin.quick-admin-material-actions :$material />
            </aside>
        </main>

        @vite('resources/js/front/material-list.js')

        @pushIf($material->type === 'category', 'endPage')

            <!--
            ########### Get And Paginate Sub Materials of Category
            -->

            <script>
                document.addEventListener(`DOMContentLoaded`, () => {
                    new SubMaterialList({
                        type: `category`,
                        categoryId: {{ $material->id }},
                        resultContainer: `#materialList_category`,
                        moreButton: `#materialList_category_more`,
                    });

                    new SubMaterialList({
                        type: `page`,
                        categoryId: {{ $material->id }},
                        resultContainer: `#materialList_page`,
                        moreButton: `#materialList_page_more`,
                    });
                });
            </script>
        @endpushIf

        <x-footer-component/>
    </div>
</x-layouts.base>
