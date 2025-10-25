@props(['path', 'material'])

@php
    $files = materialMedia($material, $path)->get();
    $uniqId = uniqid('mediaViewComponent_');
    if ($files->isEmpty()) return;
@endphp

<!--- Media View Component --->
<div id="{{ $uniqId }}" {{ $attributes->class(['mediaViewComponent']) }}>
    <div class="mediaViewComponent_list UI_no-scrollbar">
        @foreach ($files as $index => $file)
            <div class="mediaViewComponent_item" data-index="{{ $index }}">
                @php
                    // Define icon class once for all types
                    $iconClass = 'fa-file-alt';
                    if ($file['type'] === 'image') {
                        $iconClass = 'fa-image';
                    } elseif ($file['type'] === 'video') {
                        $iconClass = 'fa-video';
                    } elseif ($file['type'] === 'pdf' || Str::endsWith($file['name'], '.pdf')) {
                        $iconClass = 'fa-file-pdf';
                    }
                @endphp

                @if ($file['type'] === 'image')
                    <img src="{{ url($file['url']) }}" alt="{{ $file['name'] }}">
                @elseif ($file['type'] === 'video')
                    <video muted>
                        <source src="{{ url($file['url']) }}#t=0.1" type="video/mp4">
                    </video>
                @else
                    <div class="mediaViewComponent_document-icon fa-solid {{ $iconClass }}"></div>
                @endif

                <div class="mediaViewComponent_item-footer" title="{{ $file['name'] }}">
                    <i class="fa-solid {{ $iconClass }}"></i>
                    <span>{{ pathinfo($file['name'], PATHINFO_FILENAME) }}</span>
                </div>

                <div class="mediaViewComponent_item-overlay">
                    <i class="fa-solid fa-expand"></i>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mediaViewComponent_lightbox">
        <div class="mediaViewComponent_lightbox-backdrop"></div>
        <div class="mediaViewComponent_lightbox-container">
            <div class="mediaViewComponent_lightbox-content"></div>
            <a class="mediaViewComponent_lightbox-close fa-solid fa-xmark"></a>
            <a class="mediaViewComponent_lightbox-prev fa-solid fa-chevron-left"></a>
            <a class="mediaViewComponent_lightbox-next fa-solid fa-chevron-right"></a>
        </div>
    </div>
</div>

<script>
    document.addEventListener(`DOMContentLoaded`, () => {
        new MediaViewComponent(`{{ $uniqId }}`);
    });
</script>

@pushonce('startPage')

    <!--
    ########### Media View Component
    -->

    <style>
        .mediaViewComponent {
            margin-bottom: var(--layout-gap);
            border: var(--UI_base-border);
            border-radius: var(--UI_base-border-radius-l);
        }
        .mediaViewComponent_list {
            display: flex;
            flex-wrap: nowrap;
            gap: var(--layout-gap-s);
            overflow-x: auto;
            padding: var(--layout-gap-s);
        }
        .mediaViewComponent_item {
            position: relative;
            aspect-ratio: 1;
            width: 250px;
            flex-shrink: 0;
            border-radius: var(--UI_base-border-radius);
            border: var(--UI_base-border);
            overflow: hidden;
            cursor: pointer;
            background-color: var(--adaptive-transparent-bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mediaViewComponent_item img, .mediaViewComponent_item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .mediaViewComponent_document-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            font-size: 4rem;
            color: var(--UI_base-font-color);
        }
        .mediaViewComponent_item-overlay {
            position: absolute;
            inset: 0;
            background-color: rgba(0, 0, 0, .4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 2rem;
            opacity: 0;
            transition: opacity .2s ease;
        }
        .mediaViewComponent_item:hover .mediaViewComponent_item-overlay {
            opacity: 1;
        }
        .mediaViewComponent_item-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            max-height: 100%;
            background-color: rgba(0, 0, 0, .7);
            color: #D8D8D8;
            font-size: .8rem;
            padding: var(--layout-gap-s);
            word-wrap: break-word;
        }
        .mediaViewComponent_item-footer i {
            margin-right: .5em;
        }

        /* Lightbox Styles */
        .mediaViewComponent_lightbox {
            display: none;
            inset: 0;
            align-items: center;
            justify-content: center;
            padding: var(--UI_base-gapX);
            position: fixed;
            background-color: var(--UI_base-overlay-color);
            backdrop-filter: var(--UI_base-overlay-backdrop-filter);
            z-index: var(--UI_base-overlay-body-z-index);
        }
        .mediaViewComponent_lightbox.is-active {
            display: flex;
        }
        .mediaViewComponent_lightbox-backdrop {
            position: absolute;
            inset: 0;
        }
        .mediaViewComponent_lightbox-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mediaViewComponent_lightbox-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-height: 100%;
        }
        .mediaViewComponent_lightbox-content img,
        .mediaViewComponent_lightbox-content video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            border-radius: var(--UI_Popup-border-radius);
        }
        .mediaViewComponent_lightbox-title {
            padding: var(--layout-gap-s) 0;
            text-align: center;
            color: #D8D8D8;
        }
        .mediaViewComponent_lightbox-content .document-view {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: var(--UI_Popup-padding);
            max-width: var(--layout-min-width);
            padding: var(--UI_Popup-padding);
            background-color: var(--UI_Popup-bg-color);
            border-radius: var(--UI_Popup-border-radius);
        }
        .mediaViewComponent_lightbox-content .document-view i {
            font-size: 8rem;
        }
        .mediaViewComponent_lightbox-close,
        .mediaViewComponent_lightbox-prev,
        .mediaViewComponent_lightbox-next {
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            background: rgba(0,0,0,.3);
            aspect-ratio: 1;
            font-size: 1.5rem;
            padding: 1em;
            border-radius: 50%;
            line-height: 0;
        }
        .mediaViewComponent_lightbox-close:hover,
        .mediaViewComponent_lightbox-prev:hover,
        .mediaViewComponent_lightbox-next:hover {
            background: rgba(0,0,0,.8);
        }
        .mediaViewComponent_lightbox-close {
            top: 0;
            right: 0;
        }
        .mediaViewComponent_lightbox-prev {
            left: 0;
            top: 50%;
            transform: translateY(-50%);
        }
        .mediaViewComponent_lightbox-next {
            right: 0;
            top: 50%;
            transform: translateY(-50%);
        }
        @media (max-width: 1000px) {
            .mediaViewComponent_lightbox-close { top: .3em; right: .3em; }
            .mediaViewComponent_lightbox-prev { left: .3em; }
            .mediaViewComponent_lightbox-next { right: .3em; }
        }
        @media (max-width: 500px) {
            .mediaViewComponent_lightbox-title {
                padding: var(--layout-gap) 0;
            }
        }
    </style>
@endpushonce

@pushonce('endPage')

    <!--
    ########### Media View Component
    -->

    <script>
        const MediaViewComponent = class {
            /**
             * @param {string} id The unique ID of the component container.
             */
            constructor(id) {
                this.component = document.getElementById(id);
                if (!this.component) return;

                this.files = @json($files->toArray());
                this.currentIndex = 0;
                this.touchStartX = 0;
                this.touchEndX = 0;
                this.touchStartY = 0;
                this.touchEndY = 0;

                this.list = this.component.querySelector(`.mediaViewComponent_list`);
                this.lightbox = this.component.querySelector(`.mediaViewComponent_lightbox`);
                this.lightboxContent = this.lightbox.querySelector(`.mediaViewComponent_lightbox-content`);
                this.closeBtn = this.lightbox.querySelector(`.mediaViewComponent_lightbox-close`);
                this.prevBtn = this.lightbox.querySelector(`.mediaViewComponent_lightbox-prev`);
                this.nextBtn = this.lightbox.querySelector(`.mediaViewComponent_lightbox-next`);

                this.currentVideoElement = null;

                this.bindEvents();
            }

            /**
             * Binds all necessary event listeners.
             */
            bindEvents() {
                this.list.addEventListener(`click`, this.handleGridClick.bind(this));
                this.closeBtn.addEventListener(`click`, this.closeLightbox.bind(this));
                this.lightbox.addEventListener(`click`, (e) => {
                    if (e.target === this.lightbox) this.closeLightbox();
                });
                this.prevBtn.addEventListener(`click`, this.showPrev.bind(this));
                this.nextBtn.addEventListener(`click`, this.showNext.bind(this));

                document.addEventListener(`keydown`, this.handleKeyDown.bind(this));
                document.addEventListener(`visibilitychange`, this.handleVisibilityChange.bind(this));
                this.lightbox.addEventListener(`touchstart`, this.handleTouchStart.bind(this), { passive: true });
                this.lightbox.addEventListener(`touchmove`, this.handleTouchMove.bind(this), { passive: true });
                this.lightbox.addEventListener(`touchend`, this.handleTouchEnd.bind(this));
            }

            /**
             * A helper to get a filename without its extension.
             * @param {string} name - The full filename.
             * @returns {string}
             */
            getFileNameWithoutExtension(name) {
                return name.includes(`.`) ? name.substring(0, name.lastIndexOf(`.`)) : name;
            }

            /**
             * Handles clicks on the grid items to open the lightbox.
             * @param {Event} event
             */
            handleGridClick(event) {
                const item = event.target.closest(`.mediaViewComponent_item`);
                if (!item) return;
                const index = parseInt(item.dataset.index, 10);
                this.openLightbox(index);
            }

            /**
             * Opens the lightbox and shows the selected item.
             * @param {number} index - The index of the file to show.
             */
            openLightbox(index) {
                if (this.files.length === 0) return;
                this.lightbox.classList.add(`is-active`);
                document.body.style.overflow = `hidden`;
                this.showItem(index);
            }

            /**
             * Closes the lightbox.
             */
            closeLightbox() {
                this.lightbox.classList.remove(`is-active`);
                document.body.style.overflow = ``;
                this.lightboxContent.innerHTML = ``;
                this.currentVideoElement = null;
            }

            /**
             * Displays the media item at a specific index in the lightbox.
             * @param {number} index
             */
            showItem(index) {
                this.currentIndex = index;
                const file = this.files[this.currentIndex];
                if (!file) return;

                this.currentVideoElement = null;
                this.lightboxContent.innerHTML = ``;
                let mediaHTML = ``;
                let titleHTML = ``;
                const fileNameWithoutExt = this.getFileNameWithoutExtension(file.name);

                if (file.type === `image`) {
                    mediaHTML = `<img src="${file.url}" alt="${file.name}">`;
                    titleHTML = `<div class="mediaViewComponent_lightbox-title">${fileNameWithoutExt}</div>`;
                } else if (file.type === `video`) {
                    mediaHTML = `<video id="lightbox-video-${this.currentIndex}" src="${file.url}" controls autoplay></video>`;
                    titleHTML = `<div class="mediaViewComponent_lightbox-title">${fileNameWithoutExt}</div>`;
                } else {
                    let iconClass = `fa-file-alt`;
                    if (file.type === `pdf` || file.name.endsWith(`.pdf`)) iconClass = `fa-file-pdf`;
                    mediaHTML = `
                    <div class="document-view">
                        <i class="fa-solid ${iconClass}"></i>
                        <a href="${file.url}" target="_blank">${file.name}</a>
                    </div>
                `;
                }

                this.lightboxContent.innerHTML = mediaHTML + titleHTML;

                if (file.type === `video`) {
                    const videoElement = this.lightboxContent.querySelector(`#lightbox-video-${this.currentIndex}`);
                    if (videoElement) {
                        videoElement.volume = 0.5; // Set volume to 50%
                        videoElement.muted = false;  // Explicitly unmute the video
                        this.currentVideoElement = videoElement; // Link to current video
                    }
                }

                this.updateNavButtons();
            }

            /**
             * Updates the visibility of the prev/next navigation buttons.
             */
            updateNavButtons() {
                this.prevBtn.style.display = (this.currentIndex > 0) ? `flex` : `none`;
                this.nextBtn.style.display = (this.currentIndex < this.files.length - 1) ? `flex` : `none`;
            }

            /**
             * Shows the previous item in the lightbox.
             */
            showPrev() {
                if (this.currentIndex > 0) {
                    this.showItem(this.currentIndex - 1);
                }
            }

            /**
             * Shows the next item in the lightbox.
             */
            showNext() {
                if (this.currentIndex < this.files.length - 1) {
                    this.showItem(this.currentIndex + 1);
                }
            }

            /**
             * Handles keyboard navigation.
             * @param {KeyboardEvent} event
             */
            handleKeyDown(event) {
                if (!this.lightbox.classList.contains(`is-active`)) return;
                if (event.key === `ArrowRight`) this.showNext();
                if (event.key === `ArrowLeft`) this.showPrev();
                if (event.key === `Escape`) this.closeLightbox();
            }

            /**
             * Records the starting position of a touch.
             * @param {TouchEvent} event
             */
            handleTouchStart(event) {
                this.touchStartX = event.touches[0].clientX;
                this.touchEndX = event.touches[0].clientX;
                this.touchStartY = event.touches[0].clientY;
                this.touchEndY = event.touches[0].clientY;
            }

            /**
             * Records the movement of a touch.
             * @param {TouchEvent} event
             */
            handleTouchMove(event) {
                this.touchEndX = event.touches[0].clientX;
                this.touchEndY = event.touches[0].clientY;
            }

            /**
             * Determines if a swipe occurred and navigates or closes accordingly.
             */
            handleTouchEnd() {
                const deltaX = this.touchEndX - this.touchStartX;
                const deltaY = this.touchEndY - this.touchStartY;
                const swipeThreshold = 50; // Minimum pixels for a swipe to register

                // Prioritize vertical swipe for closing
                if (Math.abs(deltaY) > swipeThreshold && Math.abs(deltaY) > Math.abs(deltaX)) {
                    if (deltaY < 0) { // Swipe up
                        this.closeLightbox();
                    }
                }
                // Then check for horizontal swipe for navigation
                else if (Math.abs(deltaX) > swipeThreshold) {
                    if (deltaX < 0) { // Swipe left
                        this.showNext();
                    } else { // Swipe right
                        this.showPrev();
                    }
                }
            }

            /**
             * Handles browser tab visibility changes to pause/play video.
             */
            handleVisibilityChange() {
                if (!this.currentVideoElement) {
                    return;
                }

                if (document.visibilityState === `hidden`) {
                    this.currentVideoElement.pause();
                } else if (document.visibilityState === `visible`) {
                    this.currentVideoElement.play();
                }
            }
        }
    </script>
@endpushonce
