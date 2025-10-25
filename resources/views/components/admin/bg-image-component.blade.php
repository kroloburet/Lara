@props(['materialType', 'material' => null])

@php
    $bgImageUrl = materialBgImageUrl($material);
    $dependenceOnForm = !$material;
    $layoutSettings = !$material ? appSettings("layout.default.{$materialType}") : $material->layout;
@endphp

<!--- Bg Image Component --->
<span class="form_field-label">{!! __('component.bg_image.label') !!}</span>
<i class="base_hint-icon" data-hint="this"></i>
<span class="UI_Hint">{!! __('component.bg_image.hint') !!}</span>

@if(empty($layoutSettings['header']))
    <div class="UI_notice-warning mini-notice">
        {!! __('component.bg_image.header_disable_notice') !!}
    </div>
@endif

<div id="bgImageComponent" {{ $attributes }}>
    <div class="bgImageComponent_preview">
        <img class="cropper-img" src="" alt="Cropper image">
    </div>

    <div class="UI_form-component">
        <input type="text" class="UI_input new-image-url" placeholder="{{ __('component.bg_image.url_placeholder') }}">

        <input type="file" id="new_image_file" accept="image/*" hidden>

        <label for="new_image_file" class="UI_form-component-control fa-solid fa-arrow-up-from-bracket"
               title="{{ __('component.bg_image.Choose') }}"></label>

        <i class="UI_form-component-control fa-solid fa-trash-can delete" title="{{ __('component.bg_image.Delete') }}"></i>
        <i class="UI_form-component-control fa-solid fa-check save" title="{{ __('component.bg_image.Upload') }}"></i>
    </div>

    @if($dependenceOnForm)
        <input type="hidden" name="bg_image">
    @endif
</div>

@pushOnce('startPage')

    <!--
    ########### Bg Image Component
    -->

    <style>
        #bgImageComponent {
            margin-top: var(--UI_form-gap-top);
        }

        #bgImageComponent .bgImageComponent_preview {
            margin: 0;
            display: block;
            width: 100%;
            aspect-ratio: var(--layout-header-aspect-ratio);
            border: var(--UI_base-border-width) solid var(--UI_form-border-color);
            border-bottom: none;
            border-radius: var(--UI_form-border-radius) var(--UI_form-border-radius) 0 0;
            position: relative;
            overflow: hidden;
            background-size: 100% auto;
            background-repeat: no-repeat;
            background-image: url("{{ $bgImageUrl }}");
        }

        #bgImageComponent .cropper-img,
        #bgImageComponent #new_image_file {
            display: none;
        }

        #bgImageComponent.error * {
            border-color: var(--UI_form-invalid-border-color);
        }

        #bgImageComponent .UI_form-component {
            margin-top: 0;
        }

        #bgImageComponent .UI_form-component > :first-child {
            border-top-left-radius: 0;
        }

        #bgImageComponent .UI_form-component > :last-child {
            border-top-right-radius: 0;
        }
    </style>
@endPushOnce

@pushonce('endPage')

    <!--
    ########### Bg Image Component
    -->

    <script>
        {
            class BgImageComponent {
                constructor() {
                    this.component = document.getElementById(`bgImageComponent`);
                    this.preview = this.component.querySelector(`.bgImageComponent_preview`);
                    this.newUrlInput = this.component.querySelector(`.new-image-url`);
                    this.fileInput = this.component.querySelector(`#new_image_file`);
                    this.saveButton = this.component.querySelector(`.save`);
                    this.deleteButton = this.component.querySelector(`.delete`);
                    this.img = this.preview.querySelector(`.cropper-img`);
                    this.cropper = null;

                    this.init();
                }

                /**
                 * Initializes event listeners
                 */
                init() {
                    this.newUrlInput.addEventListener(`input`, () => this.handleNewUrlInput());
                    this.newUrlInput.addEventListener(`paste`, (e) => this.handlePaste(e));
                    this.fileInput.addEventListener(`change`, () => this.handleFileInput());
                    this.deleteButton.addEventListener(`click`, () => this.deleteImage());
                    this.saveButton.addEventListener(`click`, () => this.saveImage());
                }

                /**
                 * Handles paste events, checking for image files in clipboard data
                 */
                handlePaste(e) {
                    const clipboardData = e.clipboardData || window.clipboardData;
                    if (clipboardData.files.length > 0) {
                        e.preventDefault();
                        const file = clipboardData.files[0];
                        if (file && file.type.startsWith(`image/`)) {
                            this.loadImageFromFile(file);
                        }
                    }
                }

                /**
                 * Processes input in the URL field and loads the image if a URL is provided
                 */
                handleNewUrlInput() {
                    const newUrl = this.newUrlInput.value.trim();
                    if (newUrl) {
                        this.loadImageFromUrl(newUrl);
                    }
                }

                /**
                 * Handles file selection from the file input
                 */
                handleFileInput() {
                    const file = this.fileInput.files[0];
                    if (file) {
                        this.loadImageFromFile(file);
                    }
                }

                /**
                 * Loads an image from a file by converting it to a data URL
                 */
                loadImageFromFile(file) {
                    this.readFileAsDataUrl(file).then((dataUrl) => {
                        this.setImageSrc(dataUrl);
                        this.newUrlInput.value = ``;
                        this.fileInput.value = ``;
                        this.clearError();
                    });
                }

                /**
                 * Loads an image from a provided URL after validating it
                 */
                loadImageFromUrl(url) {
                    this.isValidImageUrl(url).then((isValid) => {
                        if (isValid) {
                            this.setImageSrc(url);
                            this.fileInput.value = ``;
                            this.clearError();
                        } else {
                            this.setError();
                        }
                    });
                }

                /**
                 * Sets the image source for the cropper and initializes cropping
                 */
                setImageSrc(src) {
                    this.img.src = src;
                    this.img.style.display = `block`;
                    this.initCropper();
                }

                /**
                 * Initializes the Cropper.js instance with specified settings and checks image width
                 */
                initCropper() {
                    this.destroyCropper();

                    loadCropper(() => {

                        // Get the CSS value of the --layout-header-aspect-ratio variable
                        const aspectRatio = parseFloat(
                            getComputedStyle(document.documentElement).getPropertyValue(`--layout-header-aspect-ratio`).trim()
                        ) || 3.5; // Fallback to 3.5 if the variable is not defined

                        // Init cropper
                        this.cropper = new Cropper(this.img, {
                            aspectRatio: aspectRatio,
                            viewMode: 1,
                            autoCropArea: 1,
                            dragMode: `move`,
                            zoomable: true,
                            rotatable: false,
                            scalable: false,
                            maxCropBoxWidth: 3500,
                            ready: () => {
                                const imageData = this.cropper.getImageData();
                                if (imageData.naturalWidth < 2050) {
                                    UI.Alert(`{!! __('component.bg_image.small_image_alert') !!}`);
                                }
                            }
                        });
                    });
                }

                /**
                 * Deletes the current background image via an API call with confirmation
                 */
                async deleteImage() {
                    UI.Confirm(`{!! __('component.bg_image.delete_confirm') !!}`, async () => {
                        try {
                            this.deleteButton.classList.add(UI.css.process);
                            this.component.classList.add(UI.css.disabled);

                            @if($dependenceOnForm)
                                const bgImageField = this.component.querySelector(`[name="bg_image"]`);
                                bgImageField.value = ``;

                                this.destroyCropper();
                                this.img.style.display = `none`;
                                this.preview.style.backgroundImage = `url({{ materialBgImageUrl() }})`;
                                this.clearError();
                            @else
                                const data = await fetchActionData(
                                    `/xhr/bg-image/delete`,
                                    JSON.stringify({
                                        material_type: `{{ $material->type }}`,
                                        material_id: `{{ $material->id }}`
                                    })
                                );

                                if (data && data.url) {
                                    this.destroyCropper();
                                    this.img.style.display = `none`;
                                    this.preview.style.backgroundImage = `url(${data.url})`;
                                    this.clearError();
                                }
                            @endif
                        } catch (e) {
                            console.error(`[bgImageComponent]: Error deleting image.\n`, e);
                        } finally {
                            this.deleteButton.classList.remove(UI.css.process);
                            this.component.classList.remove(UI.css.disabled);
                        }
                    });
                }

                /**
                 * Saves the cropped image to the server as a PNG data URL
                 */
                async saveImage() {
                    if (!this.cropper) {
                        UI.Alert(`{!! __('component.bg_image.choose_file_alert') !!}`);
                        return;
                    }

                    try {
                        this.saveButton.classList.add(UI.css.process);
                        this.component.classList.add(UI.css.disabled);

                        const canvas = this.cropper.getCroppedCanvas();
                        const dataUrl = canvas.toDataURL(`image/png`);

                        @if($dependenceOnForm)
                            const bgImageField = this.component.querySelector(`[name="bg_image"]`);
                            bgImageField.value = dataUrl;

                            this.destroyCropper();
                            this.img.style.display = `none`;
                            this.preview.style.backgroundImage = `url(${dataUrl})`;
                            this.clearError();
                        @else
                            const data = await fetchActionData(
                                `/xhr/bg-image/upload`,
                                JSON.stringify({
                                    material_type: `{{ $material->type }}`,
                                    material_id: `{{ $material->id }}`,
                                    bg_image: dataUrl
                                })
                            );

                            if (data && data.url) {
                                const newUrl = data.url || dataUrl;
                                this.destroyCropper();
                                this.img.style.display = `none`;
                                this.preview.style.backgroundImage = `url(${newUrl})`;
                                this.clearError();
                            }
                        @endif
                    } catch (e) {
                        console.error(`[bgImageComponent]: Error saving image.\n`, e);
                    } finally {
                        this.saveButton.classList.remove(UI.css.process);
                        this.component.classList.remove(UI.css.disabled);
                    }
                }

                /**
                 * Destroys the Cropper.js instance if it exists
                 */
                destroyCropper() {
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                }

                /**
                 * Validates if a provided URL points to a valid image
                 */
                async isValidImageUrl(url) {
                    return new Promise((resolve) => {
                        const img = new Image();
                        img.onload = () => resolve(true);
                        img.onerror = () => resolve(false);
                        img.src = url;
                    });
                }

                /**
                 * Converts a file to a data URL
                 */
                async readFileAsDataUrl(file) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = (e) => resolve(e.target.result);
                        reader.onerror = (e) => reject(e);
                        reader.readAsDataURL(file);
                    });
                }

                /**
                 * Sets error state and displays validation message
                 */
                setError() {
                    this.component.classList.add(`error`);
                    this.newUrlInput.setCustomValidity(`{{ __('component.bg_image.Load_image_error') }}`);
                    this.newUrlInput.reportValidity();
                }

                /**
                 * Clears error state and resets validation
                 */
                clearError() {
                    this.component.classList.remove(`error`);
                    this.newUrlInput.setCustomValidity(``);
                    this.newUrlInput.reportValidity();
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                new BgImageComponent();
            });
        }
    </script>
@endpushonce
