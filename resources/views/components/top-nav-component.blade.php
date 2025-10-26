
<!--- Top Navigation Component --->
<div id="topNavComponent">
    <div class="container">
        <a href="{{ route('home') }}" class="topNavComponent_logo" title="{{ __('base.default_meta_title') }}"></a>

        <x-lang-switcher-component/>

        <x-menu-view-component :menu="getMenu(app()->getLocale())" />

        <div class="topNavComponent_user-panel">
            @auth('admin')
                <!--- Admin Menu --->
                <x-admin.admin-dropdown-menu-component />
            @endauth
        </div>
    </div>
</div>

<x-top-notice-component />

@pushOnce('startPage')

    <!--
    ########### Top Nav Component
    -->

    <style>
        #topNavComponent {
            /*--- Top Nav Vars ---*/
            --nav-padding-Y: 1rem;
            --nav-row-min-height: 50px;
            --nav-items-gapX: var(--layout-gap-s);
            --nav-items-gapX-l: var(--layout-gap);
            --nav-user-panel-bg-color: var(--nav-bg-color);
            --nav-user-panel-font-color: var(--UI_base-font-color);
            --UI_form-bg-color: var(--nav-bg-color);
            --UI_form-component-control-font-color: var(--UI_base-font-color);
            --UI_form-component-control-bg-color: transparent;
            --UI_form-component-control-hover-bg-color: transparent;
            --UI_form-focus-border-color: var(--tertiary-bg-color);

            left: 0;
            right: 0;
            top: 0;
            font-size: clamp(1rem, 1.2vw, 1.3rem);
            z-index: var(--UI_base-overlay-elements-z-index);
            min-height: var(--nav-row-min-height);
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            flex-wrap: wrap;
            row-gap: var(--UI_base-gapX);
            padding: 0;
            border-bottom: var(--UI_base-border);
            background: var(--nav-bg-color);
            position: sticky;
        }

        #topNavComponent > .container {
            display: flex;
            column-gap: var(--nav-items-gapX);
            align-items: stretch;
            width: 100%;
        }

        .topNavComponent_logo {
            display: inline-block;
            width: 24px;
            background: var(--base-logo-url) center no-repeat;
            background-size: contain;
            z-index: calc(var(--UI_base-overlay-body-z-index) + 1);
            position: relative;
        }

        #topNavComponent .langSwitcherComponent {
            margin-right: auto;
        }

        #topNavComponent .topNavComponent_menu {
            margin-left: auto;
        }

        .topNavComponent_user-panel {
            display: flex;
            align-items: center;
        }

        .topNavComponent_user-panel {
            z-index: calc(var(--UI_base-overlay-body-z-index) + 1);
        }

        .topNavComponent_user-panel > * {
            align-self: stretch;
            padding: var(--nav-padding-Y) var(--nav-items-gapX);
            text-decoration: none;
        }

        .topNavComponent_user-panel > :last-child {
            padding-right: 0;
        }

        .topNavComponent_user-panel > :first-child {
            padding-left: 0;
        }

        @media (max-width: 1000px) {
            #topNavComponent {
                justify-content: space-between;
                padding: unset;
            }
        }

        @media (max-width: 600px) {
            #topNavComponent {
                --nav-items-gapX: var(--layout-gap);
            }

            .topNavComponent_logo {
                min-width: var(--avatar-circle-size);
            }
        }
    </style>
@endPushOnce

@pushOnce('endPage')

    <!--
    ########### Top Nav Component
    -->

    <script>
        {
            const component = document.getElementById(`topNavComponent`);
            const menu = component.querySelector(`:scope .topNavComponent_menu`);

            /**
             * Mobile Menu
             */
            try {
                document.addEventListener(`click`, event => {
                    isToggler = event.target;

                    if(isToggler.classList.contains(`topNavComponent_toggler`)) {
                        menu.classList.toggle(`showed`);
                        isToggler.classList.toggle(`fa-xmark`);
                        // document.body.classList.toggle(UI.css.bodyHideOverflow);
                    }
                });
            } catch(err) {
                console.error(`[Top Nav Component]: error on line: ${err.lineNumber}`, err);
            }
        }
    </script>
@endPushOnce
