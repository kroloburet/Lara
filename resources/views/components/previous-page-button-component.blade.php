
<!--- Previous Page Button Component --->
<a {{ $attributes->class(['previousPageButtonComponent', 'dashboard-panel-item', 'UI_arrow-left']) }} onclick="history.back()">
    {!! __('component.previous_page_button.title') !!}
</a>

<style>
    .previousPageButtonComponent {
        display: block;
        margin-bottom: var(--layout-gap);
        text-align: center;
    }
</style>
