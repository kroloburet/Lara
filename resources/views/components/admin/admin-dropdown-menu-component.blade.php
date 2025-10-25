@php
    $admin = auth('admin')->user();
    if (! $admin) return;
@endphp

<!--- Admin Dropdown Menu Component --->
<div id="adminDropdownMenuComponent" class="dropdownMenu">

    <!--- Trigger --->
    <div class="trigger UI_angle-down" title="{{ $admin->email }}">
        <img src="/images/admin-pic.svg"
             class="avatar-circle"
             alt="{{ $admin->email }}">
        <span class="name">{{ $admin->email }}</span>
    </div>

    <!--- Dropdown --->
    <div class="dropdown" hidden>

        <div class="dropdown-columns">
            <section class="admin-menu">
                <h5 class="ellipsis-overflow">
                    {{ __('component.dropdown_menu.admin.admin_menu') }}
                </h5>

                <x-aside-menu-component class="items" />
            </section>

            <section class="quick-links">
                <h5 class="ellipsis-overflow">
                    {{ __('component.dropdown_menu.admin.quick_links') }}
                </h5>

                <div class="items">
                    <a href="{{ route('admin.help') }}">
                        <span class="ellipsis-overflow">
                            {{ __('admin.nav.help') }}
                        </span>
                    </a>

                    <a href="{{ route('admin.logout') }}">
                        <span class="ellipsis-overflow">
                            {{ __('base.Logout') }}
                        </span>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>

@pushonce('startPage')

    <!--
    ########### Admin Dropdown Menu Component
    -->

    <style>
        #adminDropdownMenuComponent .quick-links {
            order: 2;
        }

        #adminDropdownMenuComponent .admin-menu {
            order: 1;
        }

        @media (max-width: 600px) {
            #adminDropdownMenuComponent .quick-links {
                order: 1;
            }

            #adminDropdownMenuComponent .admin-menu {
                order: 2;
            }
        }
    </style>
@endpushonce

