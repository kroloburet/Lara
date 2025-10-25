@props(['robots' => ''])

<!--- Robots Component --->
<div id="robotsComponent" {{ $attributes }}>
    <select class="UI_Select" name="robots" data-select-placeholder="">
        <option value="all" @selected($robots === 'all')>
            {{ __('form.robots.all') }}
        </option>
        <option value="noindex" @selected($robots === 'noindex')>
            {{ __('form.robots.noindex') }}
        </option>
        <option value="nofollow" @selected($robots === 'nofollow')>
            {{ __('form.robots.nofollow') }}
        </option>
        <option value="noimageindex" @selected($robots === 'noimageindex')>
            {{ __('form.robots.noimageindex') }}
        </option>
        <option value="none" @selected($robots === 'none')>
            {{ __('form.robots.none') }}
        </option>
    </select>
</div>
