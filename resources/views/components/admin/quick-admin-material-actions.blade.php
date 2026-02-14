@props(['material'])

@php
    if (Gate::denies('permits', ['material', 'u']) || empty($material)) return;
    $isStatic = config("app.materials.types.{$material->type}.static");
    $routeToUpdate = $isStatic
        ? route('admin.update.static-material',
            [
            'type' => $material->type,
            'content_locale' => $material->content()->locale
            ]
        )
        : route('admin.update.material',
            [
            'type' => $material->type,
            'alias' => $material->alias ?? null,
            'content_locale' => $material->content()->locale
            ]
        );
@endphp

<!--- Quick Admin Material Actions --->
<div class="quickAdminMaterialActions dashboard-panel-item icons-group">
    {!! __("component.quick_admin_material_actions.title") !!}
    <a href="{{ $routeToUpdate }}"
       class="fa-solid fa-pen-to-square"
       target="_blank"
       title="{{ __("base.Edit") }}"></a>
</div>
