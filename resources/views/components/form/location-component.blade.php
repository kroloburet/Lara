@props(['location' => []])

@php
    $uniqId = uniqid('locationComponent_');
@endphp

    <!--- Location Component --->
<div id="{{ $uniqId }}" {{ $attributes->class(['locationComponent']) }}>
    <div class="locationComponent_controls">
        <div class="UI_form-component">
            <input type="text" class="UI_input locationComponent_search-field"
                   placeholder="{{ __('form.location.placeholder') }}"
                   autocomplete="off">

            <i class="UI_form-component-control fa-solid
            fa-location-crosshairs locationComponent_get-current-btn"
               title="{{ __('form.location.get_current') }}"></i>

            <i class="UI_form-component-control fa-solid
            fa-xmark locationComponent_reset-btn"
               title="{{ __('form.location.reset') }}"></i>
        </div>

        <div class="locationComponent_msg"></div>
    </div>

    <div class="locationComponent_map" tabindex="0"></div>
    <textarea type="hidden" name="location" class="locationComponent_data">{!! !empty($location) ? json_encode($location) : null !!}</textarea>
</div>

<script>
    {
        class LocationComponent {
            #component = document.getElementById(`{{ $uniqId }}`);
            #dataField = this.#component.querySelector(`:scope .locationComponent_data`);
            #controls = this.#component.querySelector(`:scope .locationComponent_controls`);
            #searchField = this.#controls.querySelector(`:scope .locationComponent_search-field`);
            #getCurrentBtn = this.#controls.querySelector(`:scope .locationComponent_get-current-btn`);
            #resetBtn = this.#controls.querySelector(`:scope .locationComponent_reset-btn`);
            #msg = this.#controls.querySelector(`:scope .locationComponent_msg`);
            #map = null;
            #marker = null;
            #geocoder = null;
            #autocomplete = null;
            #defaultZoom = 4;
            #defaultCenter = {lat: 49.292506, lng: 15.21009};

            constructor() {
                this.#init();
            }

            async #init() {
                // Load Google Maps API
                // WARNING!!! Language must be transmitted as 'en' or not transmitted
                const google = await UI.loadGoogleMapsAPI(['places']);

                // Create map
                this.#map = new google.maps.Map(
                    this.#component.querySelector(`:scope .locationComponent_map`),
                    {
                        zoom: this.#defaultZoom,
                        center: this.#defaultCenter,
                        mapTypeControl: false,
                        streetViewControl: false,
                        clickableIcons: false,
                        zoomControl: true,
                        scrollwheel: false,
                        fullscreenControl: true,
                        fullscreenControlOptions: {
                            position: google.maps.ControlPosition.BOTTOM_RIGHT,
                        },
                    }
                );

                // Create marker
                this.#marker = new google.maps.Marker({
                    map: this.#map,
                    draggable: true,
                    title: `{{ __('form.location.marker_title') }}`,
                });

                // Create geocoder, autocomplete
                this.#geocoder = new google.maps.Geocoder();
                this.#autocomplete = new google.maps.places.Autocomplete(this.#searchField, {
                    fields: [`geometry`]
                });

                // Invalid dataField event handler
                this.#dataField.oninvalid = () => this.#component.classList.add(UI.css.invalidForm);

                // The place is chosen
                this.#autocomplete.addListener(`place_changed`, async () => {
                    const place = await this.#autocomplete.getPlace();
                    this.#handler(place.geometry.location, 16);
                });

                // Click on the map
                this.#map.addListener(`click`, event => this.#handler(event.latLng));

                // Drag the marker
                this.#marker.addListener(`dragend`, event => this.#handler(event.latLng));

                // Get current location
                this.#getCurrentBtn.addEventListener(`click`, () => this.#getCurrentLocation());

                // Reset Location
                this.#resetBtn.addEventListener(`click`, () => this.#resetLocation());

                try {
                    // The data already exist
                    const data = JSON.parse(this.#dataField.value.trim());
                    this.#handler({lat: data.lat, lng: data.lng}, 16, true);

                    // Listen form reset ObserveFormState to return the marker position
                    this.#dataField.form?.addEventListener(`ObserveFormState.reset`, async () => {
                        const data = JSON.parse(this.#dataField.value.trim());
                        const latLng = {lat: data.lat, lng: data.lng};
                        await this.#handler(latLng, null, true);
                    });
                } catch {
                    // No data. Do nothing.
                }
            }

            /**
             * Get current location
             */
            #getCurrentLocation() {
                this.#getCurrentBtn.classList.add(`process`, UI.css.disabled);

                if (`geolocation` in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        position => {
                            const latLng = {};
                            latLng.lat = position.coords.latitude;
                            latLng.lng = position.coords.longitude;
                            this.#handler(latLng, 14);
                            this.#getCurrentBtn.classList.replace(`process`, `done`);
                            setTimeout(() =>
                                    this.#getCurrentBtn.classList.remove(`done`, UI.css.disabled)
                                , 3000);
                        },
                        () => {
                            this.#getCurrentBtn.classList.remove(`process`, `done`, UI.css.disabled);
                            this.#errorMsg();
                        },
                        {
                            // enableHighAccuracy: true,
                            // timeout: 5000,
                        }
                    );
                }
            }

            /**
             * Component error message
             */
            #errorMsg(
                error = null,
                text = `{{ __('form.location.error') }}`
            ) {
                if (error) console.error(`[locationComponent]: on line: ${error.lineNumber}`, error);
                this.#component.classList.add(UI.css.invalidForm);
                this.#msg.classList.add(`locationComponent_show-msg`);
                this.#msg.innerHTML = text;
            }

            /**
             * Reset component state and hide marker
             */
            #resetLocation() {
                this.#clear();
                this.#searchField.value = '';
                this.#marker.setMap(null); // Hide the marker
                this.#map.setZoom(this.#defaultZoom); // Reset zoom
                this.#map.panTo(this.#defaultCenter); // Reset to initial center
            }

            /**
             * Clear component
             */
            #clear() {
                this.#msg.classList.remove(`locationComponent_show-msg`);
                this.#component.classList.remove(UI.css.invalidForm);
                this.#msg.innerHTML = '';
                this.#dataField.value = '';
            }

            /**
             * Format and return data object from geocoder response
             */
            #formatData({place_id, geometry, address_components}) {
                const country = address_components.find(component => {
                    return component.types.includes(`country`);
                })?.long_name;

                const city = address_components.find(component => {
                    return (
                        component.types.includes(`locality`) ||
                        component.types.includes(`postal_town`) ||
                        component.types.includes(`administrative_area_level_1`)
                    );
                })?.long_name;

                return {
                    place_id: place_id,
                    lat: geometry.location.lat(),
                    lng: geometry.location.lng(),
                    country,
                    city,
                }
            }

            /**
             * Get data from geocoder, set marker, set position
             */
            async #setLocationAndGetData(latLng, zoom = null) {
                this.#clear();
                if (!this.#geocoder || !latLng.lat || !latLng.lng) return;
                try {
                    const response = await this.#geocoder.geocode({location: latLng});
                    const data = this.#formatData(response.results[0]);
                    if (zoom) this.#map.setZoom(zoom);
                    this.#marker.setMap(this.#map); // Restore marker on map
                    this.#marker.setPosition(latLng);
                    this.#map.panTo(latLng);
                    this.#searchField.value = response.results[0].formatted_address;
                    return data;
                } catch (e) {
                    this.#errorMsg(e);
                    return {};
                }
            }

            /**
             * Get data from geocoder, set marker,
             * insert formatted data to the data field
             */
            async #handler(latLng, zoom = null, isInit = false) {
                if (!this.#geocoder || !latLng.lat || !latLng.lng) return;
                try {
                    const data = await this.#setLocationAndGetData(latLng, zoom);
                    this.#dataField.value = JSON.stringify(data);
                    this.validate();
                    if (!isInit) this.#dataField.form?.dispatchEvent(new Event(`change`));
                } catch (e) {
                    this.#errorMsg(e);
                }
            }

            /**
             * Public validation of component
             * @returns {boolean}
             */
            validate() {
                const isValid = this.#dataField.checkValidity();
                this.#component.classList.toggle(UI.css.invalidForm, !isValid);
                return isValid;
            }
        }

        new LocationComponent();
    }
</script>
