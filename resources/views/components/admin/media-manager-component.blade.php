@props(['path', 'material', 'limit' => 0])

@php
    if (empty($material)) return;
    $limit = empty($limit) ? config("app.materials.types.{$material->type}.media.limit") : $limit;
    $files = materialMedia($material, $path)->get();
    $uniqId = uniqid('mediaManagerComponent_');
@endphp

    <!--- Media Selector Component --->
<div id="{{ $uniqId }}" {{ $attributes->class(['mediaManagerComponent']) }}
data-path="{{ $path }}"
     data-limit-alert="{{ __('component.media_selector.limit_alert', ['limit' => $limit]) }}"
     data-delete-confirm="{{ __('component.media_selector.delete_confirm') }}"
     data-limit="{{ $limit }}">

    <div class="mediaManagerComponent_header icons-group">
        <span class="mediaManagerComponent_counter">{{ count($files) }} / {{ $limit }}</span>
        <i class="mediaManagerComponent_process-indicator"></i>
        <a class="mediaManagerComponent_add-btn fa-solid fa-arrow-up-from-bracket"
           title="{{ __('component.media_selector.Add') }}"></a>
        <a class="mediaManagerComponent_remove-all-btn fa-solid fa-broom"
           title="{{ __('component.media_selector.Delete_all') }}"></a>
    </div>

    <div class="mediaManagerComponent_media-list">
        @foreach ($files as $file)
            <div class="mediaManagerComponent_item"
                 data-id="{{ $file['id'] }}"
                 data-name="{{ $file['name'] }}"
                 draggable="true">

                @if ($file['type'] === 'image')
                    <img src="{{ $file['url'] }}" alt="Image">
                @elseif ($file['type'] === 'video')
                    <video controls muted>
                        <source src="{{ $file['url'] }}" type="video/mp4">
                        {!! __('component.media_selector.no_video_tag') !!}
                    </video>
                @else
                    @php
                        $iconClass = 'fa-file-alt'; // Default icon
                        if ($file['type'] === 'pdf' || Str::endsWith($file['name'], '.pdf')) {
                            $iconClass = 'fa-file-pdf';
                        }
                    @endphp
                    <div class="mediaManagerComponent_document fa-solid {{ $iconClass }}"></div>
                @endif

                <div class="mediaManagerComponent_item-footer" title="{{ $file['name'] }}">
                    <span>{{ $file['name'] }}</span>
                </div>

                <div class="mediaManagerComponent_item-controls">
                    <a class="mediaManagerComponent_edit-btn fa-solid fa-pencil"
                       title="{{ __('component.media_selector.Edit') }}"></a>
                    <a class="mediaManagerComponent_remove-btn fa-solid fa-trash-can"
                       title="{{ __('component.media_selector.Delete') }}"></a>
                </div>
                <div class="mediaManagerComponent_overlay">
                    <i class="fa-solid fa-arrow-right-arrow-left mediaManagerComponent_grip-icon"></i>
                </div>
            </div>
        @endforeach

        <label for="mediaManagerComponent_file-input-{{ $uniqId }}" class="mediaManagerComponent_upload-area"
               title="{{ __('component.media_selector.Add') }}">
            <i class="fa-solid fa-arrow-up-from-bracket"></i>
            <input
                type="file"
                id="mediaManagerComponent_file-input-{{ $uniqId }}"
                class="mediaManagerComponent_file-input"
                multiple
                accept=".jpg,.jpeg,.png,.gif,.svg,.webp,video/*,.pdf"
                hidden>
        </label>
    </div>

    <div class="mediaManagerComponent_edit-footer UI_form-component">
        <input type="text" class="mediaManagerComponent_edit-input"
               placeholder="{{ __('component.media_selector.new_name_placeholder') }}">
        <a type="button" class="mediaManagerComponent_save-btn
            UI_form-component-control fa-solid fa-check"
           title="{{ __('component.media_selector.Save') }}"></a>
        <a class="mediaManagerComponent_cancel-btn
            UI_form-component-control fa-solid fa-xmark"
           title="{{ __('component.media_selector.Cancel') }}"></a>
    </div>
</div>

<script>
    document.addEventListener(`DOMContentLoaded`, () => {
        new MediaManagerComponent(`{{ $uniqId }}`);
    });
</script>

@pushOnce('startPage')

    <!--
    ########### Media Manager Component
    -->

    <style>
        .mediaManagerComponent {
            border: var(--UI_form-border-width) solid var(--UI_form-border-color);
            border-radius: var(--UI_form-border-radius);
            margin: var(--UI_form-gap-top) 0 var(--UI_form-gap-bottom) 0;
            overflow: hidden;
        }
        .mediaManagerComponent_header {
            width: 100%;
            justify-content: right;
            padding: var(--UI_form-field-paddingY) var(--UI_form-field-paddingX);
            border-bottom: var(--UI_form-border-width) solid var(--UI_form-border-color);
            background-color: var(--UI_form-bg-color);
        }
        .mediaManagerComponent_process-indicator {
            margin-right: auto;
        }
        .mediaManagerComponent_process-indicator::after {
            margin: 0;
        }
        .mediaManagerComponent_media-list {
            display: flex;
            flex-wrap: nowrap;
            gap: var(--UI_form-field-paddingY);
            padding: var(--UI_form-field-paddingY);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .mediaManagerComponent_item {
            position: relative;
            flex-shrink: 0;
            width: 200px;
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: var(--UI_base-border-radius);
            border: var(--UI_base-border);
            cursor: grab;
            background-color: var(--primary-bg-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .mediaManagerComponent_item.is-editing {
            border: var(--UI_form-border-width) solid var(--UI_form-border-color);
        }
        .mediaManagerComponent_item.dragging {
            opacity: .4;
            transform: scale(0.95);
        }
        .mediaManagerComponent_item img,
        .mediaManagerComponent_item video,
        .mediaManagerComponent_document {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }
        .mediaManagerComponent_item video {
            position: absolute;
            top: 0;
            left: 0;
        }
        .mediaManagerComponent_document {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6rem;
            color: var(--primary-color-light);
        }
        .mediaManagerComponent_item-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: var(--primary-bg-color);
            border-top: var(--UI_base-border);
            color: var(--UI_base-font-color);
            font-size: .75rem;
            padding: .5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            box-sizing: border-box;
        }
        .mediaManagerComponent_item-controls {
            position: absolute;
            right: 0;
            top: 0;
            margin: .4em;
            display: flex;
            gap: .4em;
            z-index: 10;
        }
        .mediaManagerComponent_remove-btn,
        .mediaManagerComponent_edit-btn {
            line-height: 1;
            padding: .4em .5em;
            background-color: var(--primary-bg-color);
            border-radius: var(--UI_base-border-radius-s);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mediaManagerComponent_overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, .3);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            pointer-events: none;
        }
        .mediaManagerComponent_item:hover .mediaManagerComponent_overlay {
            opacity: 1;
            pointer-events: auto;
        }
        .mediaManagerComponent_grip-icon {
            font-size: 2rem;
            line-height: 1;
            color: white;
            cursor: grabbing;
        }
        .mediaManagerComponent_upload-area {
            display: flex;
            flex-shrink: 0;
            width: 200px;
            aspect-ratio: 1;
            padding: 0;
            margin: 0;
            border-radius: var(--UI_base-border-radius);
            border: var(--UI_base-border-width) solid;
            color: var(--UI_link-color);
            cursor: pointer;
        }
        .mediaManagerComponent_upload-area:hover {
            color: var(--UI_link-hover-color);
        }
        .mediaManagerComponent_upload-area > i {
            font-size: 2em;
            margin: auto;
        }
        .mediaManagerComponent_edit-footer {
            display: none;
            margin: 0;
        }
        .mediaManagerComponent_edit-footer * {
            border-radius: 0 !important;
            border-bottom: none !important;
            border-left: none !important;
        }
        .mediaManagerComponent_edit-footer :last-child {
            border-right: none !important;
        }
        .mediaManagerComponent_edit-footer.is-active {
            display: flex;
        }
        .mediaManagerComponent_edit-input {
            width: 100%;
        }
    </style>
@endpushonce

@pushOnce('endPage')

    <!--
    ########### Media Manager Component
    -->

    <script>
        const MediaManagerComponent = class {
            /**
             * Component constructor.
             * @param {string} id
             */
            constructor(id) {
                this.component = document.getElementById(id);
                if (!this.component) return;

                this.path = this.component.dataset.path;
                this.limit = parseInt(this.component.dataset.limit, 10);
                this.mediaList = this.component.querySelector(`.mediaManagerComponent_media-list`);
                this.fileInput = this.component.querySelector(`.mediaManagerComponent_file-input`);
                this.addBtn = this.component.querySelector(`.mediaManagerComponent_add-btn`);
                this.removeAllBtn = this.component.querySelector(`.mediaManagerComponent_remove-all-btn`);
                this.uploadArea = this.component.querySelector(`.mediaManagerComponent_upload-area`);
                this.processIndicator = this.component.querySelector(`.mediaManagerComponent_process-indicator`);
                this.editFooter = this.component.querySelector(`.mediaManagerComponent_edit-footer`);
                this.editInput = this.editFooter.querySelector(`.mediaManagerComponent_edit-input`);
                this.editSaveBtn = this.editFooter.querySelector(`.mediaManagerComponent_save-btn`);
                this.editCancelBtn = this.editFooter.querySelector(`.mediaManagerComponent_cancel-btn`);
                this.counter = this.component.querySelector(`.mediaManagerComponent_counter`);

                this.items = [];
                this.currentDraggingItem = null;
                this.editingItemId = null;
                this.scrollInterval = null;
                this.longPressTimer = null; // Timer for long press detection
                this.isDragging = false; // Flag to indicate drag state

                this.bindEvents();
                this.initializeItems();
                this.updateCounter();
            }

            /**
             * Initializes the component's state from the DOM on page load.
             */
            initializeItems() {
                this.mediaList.querySelectorAll(`.mediaManagerComponent_item`).forEach(item => {
                    const isImage = item.querySelector(`img`);
                    const isVideo = item.querySelector(`video`);
                    this.items.push({
                        id: item.dataset.id,
                        name: item.dataset.name,
                        url: (isImage || isVideo?.querySelector(`source`))?.src,
                        type: this.determineFileType(item.dataset.name),
                        isNew: false
                    });
                });
                this.updateCounter();
            }

            /**
             * Updates the counter display with the current number of items.
             */
            updateCounter() {
                this.counter.textContent = `${this.items.length} / ${this.limit}`;
            }

            /**
             * Binds all necessary event listeners for the component.
             */
            bindEvents() {
                this.addBtn.addEventListener(`click`, () => this.fileInput.click());
                this.removeAllBtn.addEventListener(`click`, this.handleRemoveAll.bind(this));
                this.fileInput.addEventListener(`change`, this.handleFileSelect.bind(this));
                this.mediaList.addEventListener(`click`, this.handleItemControlsClick.bind(this));
                this.editSaveBtn.addEventListener(`click`, this.handleSaveNameClick.bind(this));
                this.editCancelBtn.addEventListener(`click`, this.hideEditFooter.bind(this));

                this.mediaList.addEventListener(`dragstart`, this.handleDragStart.bind(this));
                this.mediaList.addEventListener(`dragover`, this.handleDragOver.bind(this));
                this.mediaList.addEventListener(`drop`, this.handleDrop.bind(this));
                this.mediaList.addEventListener(`dragend`, this.handleDragEnd.bind(this));

                this.mediaList.addEventListener(`touchstart`, this.handleTouchStart.bind(this), { passive: true });
                this.mediaList.addEventListener(`touchmove`, this.handleTouchMove.bind(this), { passive: false });
                this.mediaList.addEventListener(`touchend`, this.handleTouchEnd.bind(this));
            }

            /**
             * Determines the file type based on its name or MIME type.
             * @param {string} fileName
             * @param {string} mimeType
             * @returns {string}
             */
            determineFileType(fileName, mimeType = ``) {
                if (mimeType.startsWith(`image`)) return `image`;
                if (mimeType.startsWith(`video`)) return `video`;
                if (fileName.endsWith(`.pdf`)) return `pdf`;
                return `document`;
            }

            /**
             * Handles delegated clicks on item controls (remove, edit).
             * @param {Event} event
             */
            handleItemControlsClick(event) {
                const removeBtn = event.target.closest(`.mediaManagerComponent_remove-btn`);
                if (removeBtn) {
                    this.handleRemoveClick(removeBtn);
                    return;
                }
                const editBtn = event.target.closest(`.mediaManagerComponent_edit-btn`);
                if (editBtn) {
                    const itemElement = editBtn.closest(`.mediaManagerComponent_item`);
                    const id = itemElement.dataset.id;
                    const name = itemElement.dataset.name;
                    if (this.editingItemId === id) {
                        this.hideEditFooter();
                    } else {
                        this.showEditFooter(id, name);
                    }
                }
            }

            /**
             * Shows the main edit footer and prepares it for a specific item.
             * @param {string} id
             * @param {string} name
             */
            showEditFooter(id, name) {
                this.editingItemId = id;
                this.mediaList.querySelectorAll(`.mediaManagerComponent_item`).forEach(el => {
                    el.classList.toggle(`is-editing`, el.dataset.id === id);
                });
                this.editInput.value = name.includes(`.`) ? name.substring(0, name.lastIndexOf(`.`)) : name;
                this.editFooter.classList.add(`is-active`);
                this.editInput.focus();
                this.editInput.select();
            }

            /**
             * Hides the main edit footer and resets the editing state.
             */
            hideEditFooter() {
                this.editingItemId = null;
                this.mediaList.querySelectorAll(`.mediaManagerComponent_item`).forEach(el => {
                    el.classList.remove(`is-editing`);
                });
                this.editFooter.classList.remove(`is-active`);
                this.editInput.value = ``;
            }

            /**
             * Handles saving the new name from the main edit footer.
             */
            async handleSaveNameClick() {
                if (!this.editingItemId) return;
                const itemInState = this.items.find(i => i.id === this.editingItemId);
                if (!itemInState) return;

                const oldName = itemInState.name;
                const extension = oldName.includes(`.`) ? oldName.substring(oldName.lastIndexOf(`.`)) : ``;
                const newBaseName = this.editInput.value.trim();

                if (!newBaseName) return;

                const newFullName = `${newBaseName}${extension}`;
                if (newFullName === oldName) {
                    this.hideEditFooter();
                    return;
                }

                const isSuccess = await this.sendRenameRequest(this.editingItemId, oldName, newFullName);
                if (isSuccess) {
                    this.hideEditFooter();
                    this.updateCounter();
                }
            }

            /**
             * Handles the selection of new files, creating previews and initiating the upload.
             * @param {Event} event
             */
            async handleFileSelect(event) {
                const files = Array.from(event.target.files);
                if (files.length === 0) return;

                // Check limit
                const newFilesCount = files.length;
                const currentFilesCount = this.items.length;
                if (currentFilesCount + newFilesCount > this.limit) {
                    const message = this.component.dataset.limitAlert ?? `The limit is exceeded. You can add no more than ${this.limit} files.`;
                    UI.Alert(message);
                    event.target.value = ``;
                    return;
                }

                // Process
                const processFile = async (file) => {
                    const originalName = file.name;
                    const tempId = `temp_${Date.now()}_${Math.random().toString(36).substring(2, 9)}`;
                    const type = this.determineFileType(originalName, file.type);
                    const baseItem = { id: tempId, name: originalName, file: file, isNew: true, type: type };
                    return { ...baseItem, url: URL.createObjectURL(file) };
                };
                const newItems = await Promise.all(files.map(processFile));
                const newItemIds = newItems.map(item => item.id);
                this.items.push(...newItems.filter(Boolean));
                this.renderMediaList();
                this.updateCounter();
                event.target.value = ``;
                const isSuccess = await this.sendUpdates();
                if (!isSuccess) {
                    this.items = this.items.filter(item => !newItemIds.includes(item.id));
                    this.renderMediaList();
                    this.updateCounter();
                }
            }

            /**
             * Handles the click on a remove button for a single item.
             * @param {HTMLElement} removeBtn
             */
            async handleRemoveClick(removeBtn) {
                const itemElement = removeBtn.closest(`.mediaManagerComponent_item`);
                const itemId = itemElement.dataset.id;
                const originalItems = [...this.items];
                this.items = this.items.filter(item => item.id !== itemId);
                this.renderMediaList();
                this.updateCounter();
                const isSuccess = await this.sendUpdates();
                if (!isSuccess) {
                    this.items = originalItems;
                    this.renderMediaList();
                    this.updateCounter();
                }
            }

            /**
             * Handles removing all items from the component.
             */
            async handleRemoveAll() {
                const confirmMessage = this.component.dataset.deleteConfirm ?? `Do you really want to delete all media files?`;
                UI.Confirm(confirmMessage, async () => {
                    const originalItems = [...this.items];
                    this.items = [];
                    this.renderMediaList();
                    this.updateCounter();
                    const isSuccess = await this.sendUpdates();
                    if (!isSuccess) {
                        this.items = originalItems;
                        this.renderMediaList();
                        this.updateCounter();
                    }
                });
            }

            /**
             * Prepares data and sends a bulk update (upload, delete, reorder).
             * @returns {Promise<boolean>}
             */
            async sendUpdates() {
                const orderedElements = Array.from(this.mediaList.querySelectorAll(`.mediaManagerComponent_item`));
                const newFilesCount = this.items.filter(item => item.isNew).length;
                const existingFilesCount = this.items.length - newFilesCount;

                if (existingFilesCount + newFilesCount > this.limit) {
                    const message = this.component.dataset.limitAlert ?? `The limit is exceeded. You can add no more than ${this.limit} files.`;
                    UI.Alert(message);
                    this.items = this.items.filter(item => !item.isNew);
                    this.renderMediaList();
                    this.updateCounter();
                    return false;
                }

                const formData = new FormData();
                formData.append(`material_type`, `{{ $material->type }}`);
                formData.append(`material_id`, `{{ $material->id }}`);
                formData.append(`media[path]`, this.path);
                formData.append(`media[limit]`, this.limit);
                const orderArray = orderedElements.map(element => {
                    const id = element.dataset.id;
                    const item = this.items.find(i => i.id === id);
                    if (!item) return null;
                    if (item.isNew && item.file) {
                        formData.append(`media[files][${item.id}]`, item.file);
                    }
                    return { id: item.id, name: item.name };
                }).filter(Boolean);
                formData.append(`media[order]`, JSON.stringify(orderArray));
                return await this._handleRequest(`/xhr/media/set`, formData);
            }

            /**
             * Prepares data and sends a request to rename a file.
             * @param {string} id
             * @param {string} oldName
             * @param {string} newName
             * @returns {Promise<boolean>}
             */
            async sendRenameRequest(id, oldName, newName) {
                const formData = new FormData();
                formData.append(`material_type`, `{{ $material->type }}`);
                formData.append(`material_id`, `{{ $material->id }}`);
                formData.append(`media[path]`, this.path);
                formData.append(`media[id]`, id);
                formData.append(`media[old_name]`, oldName);
                formData.append(`media[new_name]`, newName);
                return await this._handleRequest(`/xhr/media/rename`, formData);
            }

            /**
             * A generic helper to handle AJAX requests, UI updates, and state management.
             * @param {string} endpoint
             * @param {FormData} formData
             * @returns {Promise<boolean>}
             * @private
             */
            async _handleRequest(endpoint, formData) {
                try {
                    this.component.classList.add(UI.css.disabled);
                    this.processIndicator.classList.add(UI.css.process);
                    const response = await fetchActionData(endpoint, formData);
                    this.processIndicator.classList.remove(UI.css.process);
                    this.processIndicator.classList.add(response?.ok ? UI.css.ok : UI.css.err);
                    if (response?.ok && Array.isArray(response.files)) {
                        this.items.forEach(item => {
                            if (item.isNew && item.url) URL.revokeObjectURL(item.url);
                        });
                        this.items = response.files;
                        this.renderMediaList();
                        this.updateCounter();
                        return true;
                    }
                    return false;
                } catch (err) {
                    console.error(`[MediaManagerComponent Request Error]:`, err);
                    this.processIndicator.classList.remove(UI.css.process);
                    this.processIndicator.classList.add(UI.css.err);
                    return false;
                } finally {
                    this.component.classList.remove(UI.css.disabled);
                    setTimeout(() => this.processIndicator.classList.remove(UI.css.ok, UI.css.err), 3000);
                }
            }

            /**
             * Re-draws the entire media list based on the current `this.items` array.
             */
            renderMediaList() {
                this.mediaList.querySelectorAll(`.mediaManagerComponent_item`).forEach(el => el.remove());
                this.items.forEach(item => {
                    this.mediaList.insertBefore(this.createMediaItemElement(item), this.uploadArea);
                });
                this.updateCounter();
            }

            /**
             * Creates a DOM element for a single media item.
             * @param {object} item
             * @returns {HTMLElement}
             */
            createMediaItemElement(item) {
                const itemElement = document.createElement(`div`);
                itemElement.className = `mediaManagerComponent_item`;
                itemElement.dataset.id = item.id;
                itemElement.dataset.name = item.name;
                itemElement.draggable = true;
                let mediaContentHTML = ``;
                const url = item.url || ``;
                if (item.type === `image`) {
                    mediaContentHTML = `<img src="${url}" alt="Image">`;
                } else if (item.type === `video`) {
                    mediaContentHTML = `<video controls muted><source src="${url}"></video>`;
                } else {
                    let iconClass = `fa-file-alt`;
                    if (item.type === `pdf`) {
                        iconClass = `fa-file-pdf`;
                    }
                    mediaContentHTML = `<div class="mediaManagerComponent_document fa-solid ${iconClass}"></div>`;
                }
                const footerHTML = `<div class="mediaManagerComponent_item-footer" title="${item.name}"><span>${item.name}</span></div>`;
                const controlsHTML = `
            <div class="mediaManagerComponent_item-controls">
                <a class="mediaManagerComponent_edit-btn fa-solid fa-pencil" title="Edit"></a>
                <a class="mediaManagerComponent_remove-btn fa-solid fa-trash-can" title="Delete"></a>
            </div>
            <div class="mediaManagerComponent_overlay">
                <i class="fa-solid fa-arrow-right-arrow-left mediaManagerComponent_grip-icon"></i>
            </div>
        `;
                itemElement.innerHTML = mediaContentHTML + footerHTML + controlsHTML;
                return itemElement;
            }

            /**
             * Handles the start of a drag-and-drop operation.
             * @param {DragEvent} event
             */
            handleDragStart(event) {
                const item = event.target.closest(`.mediaManagerComponent_item`);
                if (!item) return;
                this.currentDraggingItem = item;
                item.classList.add(`dragging`);
            }

            /**
             * Handles dragging an item over the list, now with auto-scroll.
             * @param {DragEvent} event
             */
            handleDragOver(event) {
                event.preventDefault();
                this._handleAutoScroll(event.clientX);

                const target = event.target.closest(`.mediaManagerComponent_item:not(.dragging)`);
                if (!target || !this.currentDraggingItem) return;
                const rect = target.getBoundingClientRect();
                const isAfter = event.clientX > rect.left + rect.width / 2;
                if (isAfter) {
                    target.parentNode.insertBefore(this.currentDraggingItem, target.nextSibling);
                } else {
                    target.parentNode.insertBefore(this.currentDraggingItem, target);
                }
            }

            /**
             * Handles dropping an item.
             * @param {DragEvent} event
             */
            async handleDrop(event) {
                event.preventDefault();
                if (this.currentDraggingItem) this.currentDraggingItem.classList.remove(`dragging`);
                this.currentDraggingItem = null;
                clearInterval(this.scrollInterval);
                this.scrollInterval = null;
                await this.sendUpdates();
            }

            /**
             * Cleans up after a drag operation ends.
             * @param {DragEvent} event
             */
            handleDragEnd(event) {
                if (this.currentDraggingItem) this.currentDraggingItem.classList.remove(`dragging`);
                this.currentDraggingItem = null;
                clearInterval(this.scrollInterval);
                this.scrollInterval = null;
            }

            /**
             * Handles the start of a touch gesture, initiating a long-press timer.
             * @param {TouchEvent} event
             */
            handleTouchStart(event) {
                if (event.target.closest(`.mediaManagerComponent_item-controls`)) {
                    return;
                }
                const item = event.target.closest(`.mediaManagerComponent_item`);
                if (!item) return;

                this.currentDraggingItem = item;

                // Start a timer. If the user holds their finger down long enough, start the drag.
                this.longPressTimer = setTimeout(() => {
                    this.isDragging = true;
                    this.currentDraggingItem.classList.add(`dragging`);
                    // Optional: provide haptic feedback if the browser supports it
                    if (navigator.vibrate) {
                        navigator.vibrate(50);
                    }
                }, 300); // 300ms for a long press
            }

            /**
             * Handles movement during a touch gesture.
             * @param {TouchEvent} event
             */
            handleTouchMove(event) {
                // If the user moves their finger, it's a scroll, not a drag.
                // So, we cancel the long-press timer.
                clearTimeout(this.longPressTimer);

                if (!this.isDragging || !this.currentDraggingItem) return;

                // If dragging has started, prevent default scroll behavior.
                event.preventDefault();

                const touch = event.touches[0];
                this._handleAutoScroll(touch.clientX);

                const target = document.elementFromPoint(touch.clientX, touch.clientY)?.closest(`.mediaManagerComponent_item:not(.dragging)`);
                if (!target) return;

                const rect = target.getBoundingClientRect();
                const isAfter = touch.clientX > rect.left + rect.width / 2;
                if (isAfter) {
                    target.parentNode.insertBefore(this.currentDraggingItem, target.nextSibling);
                } else {
                    target.parentNode.insertBefore(this.currentDraggingItem, target);
                }
            }

            /**
             * Handles the end of a touch gesture.
             * @param {TouchEvent} event
             */
            async handleTouchEnd(event) {
                // Always clear the timer and autoscroll when the touch ends.
                clearTimeout(this.longPressTimer);
                clearInterval(this.scrollInterval);
                this.scrollInterval = null;

                // If a drag was in progress, finalize it.
                if (this.isDragging && this.currentDraggingItem) {
                    this.currentDraggingItem.classList.remove(`dragging`);
                    await this.sendUpdates();
                }

                // Reset state for the next interaction.
                this.currentDraggingItem = null;
                this.isDragging = false;
            }

            /**
             * Manages auto-scrolling the list when dragging near the edges.
             * @param {number} cursorX
             * @private
             */
            _handleAutoScroll(cursorX) {
                const scrollZone = 150; // Increased from 60 to 100px for better sensitivity
                const scrollSpeed = 10; // pixels per frame
                const listRect = this.mediaList.getBoundingClientRect();

                clearInterval(this.scrollInterval);
                this.scrollInterval = null;

                if (cursorX > listRect.right - scrollZone) {
                    this.scrollInterval = setInterval(() => {
                        this.mediaList.scrollLeft += scrollSpeed;
                    }, 15);
                } else if (cursorX < listRect.left + scrollZone) {
                    this.scrollInterval = setInterval(() => {
                        this.mediaList.scrollLeft -= scrollSpeed;
                    }, 15);
                }
            }
        }
    </script>
@endpushonce
