<!--- Breadcrumbs Component --->
<ul id="breadcrumbsComponent">
    {!! $chain !!}
</ul>

@pushOnce('startPage')

    <!--
    ########### Breadcrumbs Component
    -->

    <style>
        #breadcrumbsComponent {
            margin: 0 0 1em 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            color: var(--tertiary-color);
        }

        #breadcrumbsComponent > li::after {
            content: "/";
            display: inline-block;
            margin: 0 .4em;
        }

        #breadcrumbsComponent > li:last-of-type::after {
            display: none;
        }
    </style>
@endpushOnce
