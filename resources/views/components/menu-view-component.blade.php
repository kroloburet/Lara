@props(['menu' => [], 'isTopLevel' => true])

@php
    if ($menu->isEmpty() && $isTopLevel) return;
@endphp

<!--- Menu View Component --->
<ul @if($isTopLevel) class="UI_Menu topNavComponent_menu" @endif>
    @foreach($menu as $item)
        <li>
            @if($item->url)
                <a href="{{ $item->url }}" @if($item->target) target="{{ $item->target }}" @endif>
                    {!! $item->title !!}
                </a>
            @else
                <span>{!! $item->title !!}</span>
            @endif
            @if($item->children && $item->children->isNotEmpty())
                <x-menu-view-component :menu="$item->children" :isTopLevel="false" />
            @endif
        </li>
    @endforeach
</ul>

@pushOnce('startPage')

    <!--
    ########### Menu View Component
    -->

    <style>
        .UI_Menu.topNavComponent_menu {
            --UI_base-active-bg-color: transparent;

            margin: 0;
            background-color: var(--nav-bg-color);
        }

        .UI_Menu-toggle-btn {
            margin-bottom: 0;
            font-size: 1.2em;
            background-color: var(--nav-bg-color);
        }

        .UI_Menu.topNavComponent_menu .UI_Menu-sub-toggle-btn {
            padding: var(--nav-padding-Y) var(--nav-items-gapX);
            font-size: 1em;
            background-color: var(--nav-bg-color);
        }

        .UI_Menu.topNavComponent_menu .UI_Menu-sub-toggle-btn > i:before {
            content: "\f078";
        }

        .UI_Menu.topNavComponent_menu li a,
        .UI_Menu.topNavComponent_menu li span {
            font-size: 1em;
            padding: var(--nav-padding-Y) var(--nav-items-gapX);
        }

        .UI_Menu.topNavComponent_menu li.UI_Menu-mark > a,
        .UI_Menu.topNavComponent_menu li.UI_Menu-mark > span {
            color: var(--UI_link-hover-color);
        }

        .UI_Menu.topNavComponent_menu li a:hover {
            color: var(--UI_link-hover-color);
        }

        .UI_Menu.topNavComponent_menu ul {
            background-color: var(--nav-bg-color);
            left: auto;
            right: 0;
        }

        .UI_Menu.topNavComponent_menu ul ul {
            border-right: none;
            border-top: var(--UI_base-border);
            top: -1px;
            left: auto;
            right: 100%;
        }

        .UI_Menu.topNavComponent_menu ul ul li {
            border-right: var(--UI_base-border);
        }

        .UI_Menu.topNavComponent_menu ul ul li:first-child {
            border-right: none;
        }

        .UI_Menu.topNavComponent_menu ul {
            border: var(--UI_base-border);
            border-top: none;
        }

        .UI_Menu.topNavComponent_menu ul li {
            background-color: var(--nav-bg-color);
        }

        @media (max-width: 768px) {
            .UI_Menu.topNavComponent_menu.UI_Menu-show {
                --nav-padding-Y:var(--layout-gap-2xs);
                --nav-items-gapX:  var(--layout-gap-s);

                position: absolute;
                padding: var(--layout-gap);
                background-color: var(--nav-bg-color);
                border: var(--UI_base-border);
                border-top: none;
                top: 100%;
                right: 0;
                overflow: auto;
                max-height: 80vh;
            }

            .UI_Menu.topNavComponent_menu.UI_Menu-show ul{
                --UI_base-border: none;

                margin-left: calc(var(--nav-items-gapX) * 2);
                left: auto;
                right: auto;
            }

            .UI_Menu.topNavComponent_menu.UI_Menu-show ul ul {
                left: auto;
                right: auto;
            }
        }
    </style>
@endPushOnce
