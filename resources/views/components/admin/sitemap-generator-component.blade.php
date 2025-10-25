@php($lastUpdate = (new \App\Services\SitemapService())->lastUpdate())

<!--- Sitemap Generator Component --->
<div id="sitemapGeneratorComponent" class="dashboard-panel-item">
    <h4>{!! __('settings.sitemap.title') !!}</h4>

    <span class="form_field-label">{!! __('settings.sitemap.desc') !!}</span>
    <i class="base_hint-icon" data-hint="this"></i>
    <span class="UI_Hint">{!! __('settings.sitemap.hint') !!}</span>

    <div class="sitemapGeneratorComponent_last-update UI_size-s">
        {!! __('settings.sitemap.last_update') !!}<time>{!! $lastUpdate !!}</time>
    </div>

    <div>
        <button class="UI_button refresh-button">{!! __('settings.sitemap.Refresh') !!}</button>
        <button class="UI_button UI_contour view-button">{!! __('settings.sitemap.View') !!}</button>
    </div>

    <span class="form_field-label">{!! __('form.sitemap.label') !!}</span>
    <i class="base_hint-icon" data-hint="this"></i>
    <span class="UI_Hint">{!! __('form.sitemap.hint') !!}</span>
    <select id="sitemapRefreshSetting" class="UI_Select">
        <option value="auto"
            @selected(appSettings('sitemap.refresh') === 'auto')>
            {{ __('form.sitemap.auto') }}
        </option>
        <option value="manually"
            @selected(appSettings('sitemap.refresh') === 'manually')>
            {{ __('form.sitemap.manually') }}
        </option>
    </select>

    <div class="UI_Popup popup-full popup-xl" id="sitemapGeneratorComponent_pop"></div>
</div>

@pushOnce('startPage')

    <!--
    ########### Sitemap Generator Component
    -->

    <style>
        .sitemapGeneratorComponent_last-update {
            color: var(--tertiary-color);
        }

        .sitemapGeneratorComponent_last-update time {
            color: var(--UI_base-font-color);
        }

        .sitemap-tabs dd {

        }

        .sitemap-tabs textarea {
            min-height: 70vh;
            width: 100%;
            pointer-events: all !important;
        }
    </style>
@endpushonce

@pushOnce('endPage')

    <!--
    ########### Sitemap Generator Component
    -->

    <script>
        {
            const component = document.getElementById(`sitemapGeneratorComponent`);
            const lastUpdateBox = component.querySelector(`.sitemapGeneratorComponent_last-update time`);
            const sitemapRefreshSetting = document.getElementById(`sitemapRefreshSetting`);
            const refreshButton = component.querySelector(`:scope .refresh-button`);
            const viewButton = component.querySelector(`:scope .view-button`);
            const createSitemapView = (sitemapData) => {
                const dl = document.createElement(`dl`);
                dl.classList.add(`UI_Tabs`, `sitemap-tabs`);
                for (const key in sitemapData) {
                    const dt = document.createElement(`dt`);
                    const dd = document.createElement(`dd`);
                    const textarea = document.createElement(`textarea`);
                    textarea.setAttribute(`readonly`, true);
                    textarea.textContent = sitemapData[key];
                    dt.textContent = key;
                    dd.append(textarea);
                    dl.append(dt, dd);
                }

                UI.Popup(`sitemapGeneratorComponent_pop`).insert(dl);
                UI.Tabs({selector: `.sitemap-tabs`});
            }

            refreshButton.addEventListener(`click`, async () => {
                refreshButton.classList.add(UI.css.process);

                try {
                    const data = await fetchActionData(
                        `{{ route('xhr.admin.refresh.sitemap') }}`,
                        JSON.stringify({_token: global.csrfToken})
                    );

                    if (data.ok && data.message && data.lastUpdate) {
                        UI.OkNotice(data.message);
                        lastUpdateBox.innerHTML = data.lastUpdate;
                    }
                } catch (e) {
                    console.error(`[Refresh sitemap]: Failed to refreshing.\n`, e);
                } finally {
                    refreshButton.classList.remove(UI.css.process);
                }
            });

            viewButton.addEventListener(`click`, async () => {
                viewButton.classList.add(UI.css.process);

                try {
                    const data = await fetchActionData(
                        `{{ route('xhr.admin.view.sitemap') }}`,
                        JSON.stringify({_token: global.csrfToken})
                    );

                    if (! data.sitemapData) {
                        UI.Alert(`{!! __('settings.sitemap.Not_found') !!}`);
                        return;
                    }

                    createSitemapView(data.sitemapData);
                } catch (e) {
                    console.error(`[View sitemap]: sitemap.xml not found or is empty.\n`, e);
                } finally {
                    viewButton.classList.remove(UI.css.process);
                }
            });

            sitemapRefreshSetting.addEventListener(`change`, async () =>
                await setAppSettings(`sitemap.refresh`, sitemapRefreshSetting.value, component)
            );
        }
    </script>
@endPushOnce
