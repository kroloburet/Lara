<x-top-nav-component />
<main class="base-main right-aside not-aside-adaptive">
    <section class="base-main-section">
        {{ $slot }}
    </section>

    <aside class="base-main-aside">
        <section class="rollupWrapper">
            <nav class="rollupSection">
                <x-aside-menu-component />
            </nav>
            <div class="rollupToggler rollupTogglerIcon"></div>
        </section>
    </aside>
</main>

<footer class="base-footer">
    <div class="container">
        <div class="copyright">
            System installed at: {{ consumerDateFormat(appSettings('installedAt'), 'admin') }}<br>
            Powered by:
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAASCAYAAABvqT8MAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAEPSURBVHgBtVJNzgFBEH3tm+8nn80spsWy3YAbcAJuwA24AU7gCBzBDRyBG2BNQsKwMu1VM8kIMr1RSaX/XtV7VdUKHrYLYX6/MebWBDnA8O8H3cSix2Mod+od+BCh/qVuWemru9cLr+hPGjOCZwJmxsn/BhULrOX9QVJcQgvWZQ2txUIpVAnsnEtYUZazBwaVuIz7i0WjuEUtAWoihcED3rfha3GEQayxo1TrH6QxkYBACmSvTF4AZbm2BiTZ4xOWSir4gNnWPpfm08MpQo9Zlmd9a6FMm+e5ZE79icHK0ODo3bS5Nyx4yIfpa/oyTKr37qNsDW8/31GjQ3rRbiDT5g8guIo8kylna4CPZWQur8dHciy9sUimAAAAAElFTkSuQmCC" alt="Lara CMS">
            Lara CMS<br>
            Development by:
            <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEBLAEsAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wgALCAASABIBAREA/8QAGQABAAIDAAAAAAAAAAAAAAAACQMEBQgK/9oACAEBAAAAAQlwlRpGLNLQTrxk/8QAHRAAAQQCAwAAAAAAAAAAAAAABAMFBggABwITMv/aAAgBAQABBQJuaHN3LRRVIVypUslCjiJo+CNk8Lpn2FVsLKF2Hx85/8QAJRAAAgICAQMEAwEAAAAAAAAAAgMBBAUREgATIQYQFTEUMnGx/9oACAEBAAY/Aq1HG0bV21cJkVk10mw3dlZufIajyKEra5xfqlQMYziIlPQIQBuc0oBa1jJmZF9CAD5KZ9sv6NpY+hm8bTrR6kSrKXrKJxWracXk5xICi0j8i9VyzZNZDW7xK4nZjeuk+ucTgcbQf8ber2KSqy4qKyT7lO3VzWPq8Sr0siiFWkE1HaHt2PAdWTVnbJLOw4llJVhkgJhSJSPGeMyOpkdzr630Q1rNiuNjENW+EOYqHL+Rxs9tsLIe4G/PE9xvzrqP5H+e3//EABwQAQEAAgIDAAAAAAAAAAAAAAEhABEQMUFR0f/aAAgBAQABPyG2MigEM7A9g9ATE+4g+LA4kIKb9mSujiAKq60CEnLl6jvErjlujdVAtNbyMtPvyhrxe8Ze2/Bx/9oACAEBAAAAEH5//8QAHBABAQACAgMAAAAAAAAAAAAAAREAQSExEFGR/9oACAEBAAE/EE3I8gXGPNw9ZXiBC6kNsqPJq5H0/HBKB8Nsly/aK4vuECC9GFcT2bQjOndKkLd0KzDZQBqASIOAONUlNlatpV7u/H//2Q==" alt="Web Development by Kroloburet">
            Kroloburet<br>
        </div>
    </div>
</footer>

@pushOnce('endPage')
    @vite('resources/js/admin/base.js')
@endPushOnce
