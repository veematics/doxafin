<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Feature') }}
        </h2>
    </x-slot>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Edit Feature</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('appsetting.appfeature.update', $appfeature) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="featureName">Feature Name</label>
                                    <input class="form-control @error('featureName') is-invalid @enderror" 
                                           id="featureName" 
                                           type="text" 
                                           name="featureName" 
                                           value="{{ old('featureName', $appfeature->featureName) }}"
                                           required>
                                    @error('featureName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="featureIcon">Feature Icon</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i id="selectedIconPreview" class="{{ $appfeature->featureIcon }}"></i>
                                        </span>
                                        <input class="form-control @error('featureIcon') is-invalid @enderror" 
                                               id="featureIcon" 
                                               type="text" 
                                               name="featureIcon" 
                                               value="{{ old('featureIcon', $appfeature->featureIcon) }}"
                                               placeholder="e.g., cil-user"
                                               required
                                               readonly>
                                        <button class="btn btn-outline-secondary" type="button" data-coreui-toggle="modal" data-coreui-target="#iconPickerModal">
                                            <i class="cil-grid"></i> Select Icon
                                        </button>
                                        @error('featureIcon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Click 'Select Icon' to browse available icons</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="featurePath">Feature Path</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="cil-link"></i>
                                        </span>
                                        <input class="form-control @error('featurePath') is-invalid @enderror" 
                                               id="featurePath" 
                                               type="text" 
                                               name="featurePath" 
                                               value="{{ old('featurePath', $appfeature->featurePath) }}"
                                               placeholder="e.g., /admin/users"
                                               required>
                                        @error('featurePath')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add after featurePath input -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="featureActive">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="featureActive" 
                                               name="featureActive" 
                                               value="1" 
                                               {{ $appfeature->featureActive ? 'checked' : '' }}>
                                        <label class="form-check-label" for="featureActive">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Special Permission -->
                        <div class="form-group">
                            <label for="custom_permission">Custom Permission</label>
                            <textarea 
                                class="form-control @error('custom_permission') is-invalid @enderror" 
                                id="custom_permission" 
                                name="custom_permission" 
                                rows="4" 
                                placeholder="Enter custom permissions (e.g., view_access:Global, Own, Territories)"
                            >{{ old('custom_permission', $appfeature->custom_permission ?? '') }}</textarea>
                            <small class="form-text text-muted">
                                Format: [PERMISSION_NAME]:[Permission options]. Leave empty for no custom permissions.
                                Each permission on a new line.
                            </small>
                            @error('custom_permission')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('appsetting.appfeature.index') }}" 
                                       class="btn btn-secondary">
                                        <i class="cil-x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="cil-save"></i> Update Feature
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Icon Picker Modal -->
    <div class="modal fade" id="iconPickerModal" tabindex="-1" role="dialog" aria-labelledby="iconPickerModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="iconPickerModalLabel">Select Icon</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="sticky-top bg-white pt-2 pb-2">
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="cil-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   id="iconSearch" 
                                   placeholder="Search icons..."
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="icon-grid-container" style="max-height: 60vh; overflow-y: auto;">
                        <div class="row g-3" id="iconGrid">
                            <!-- Icons will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CoreUI Free Icons array
              // CoreUI Free Icons
        const coreUIFreeIcons = [
            'cil-3d', 'cil-4k', 'cil-account-logout', 'cil-action-redo', 'cil-action-undo',
    'cil-address-book', 'cil-airplane-mode', 'cil-airplane-mode-off', 'cil-airplay',
    'cil-alarm', 'cil-album', 'cil-align-center', 'cil-align-left', 'cil-align-right',
    'cil-american-football', 'cil-animal', 'cil-aperture', 'cil-apple', 'cil-applications',
    'cil-applications-settings', 'cil-apps', 'cil-apps-settings', 'cil-arrow-bottom',
    'cil-arrow-circle-bottom', 'cil-arrow-circle-left', 'cil-arrow-circle-right',
    'cil-arrow-circle-top', 'cil-arrow-left', 'cil-arrow-right', 'cil-arrow-thick-bottom',
    'cil-arrow-thick-from-bottom', 'cil-arrow-thick-from-left', 'cil-arrow-thick-from-right',
    'cil-arrow-thick-from-top', 'cil-arrow-thick-left', 'cil-arrow-thick-right',
    'cil-arrow-thick-to-bottom', 'cil-arrow-thick-to-left', 'cil-arrow-thick-to-right',
    'cil-arrow-thick-to-top', 'cil-arrow-thick-top', 'cil-arrow-top',
    'cil-assistive-listening-system', 'cil-asterisk', 'cil-asterisk-circle', 'cil-at',
    'cil-audio', 'cil-audio-description', 'cil-audio-spectrum', 'cil-av-timer', 'cil-baby',
    'cil-baby-carriage', 'cil-backspace', 'cil-badge', 'cil-balance-scale', 'cil-ban',
    'cil-bank', 'cil-bar-chart', 'cil-barcode', 'cil-baseball', 'cil-basket',
    'cil-basketball', 'cil-bath', 'cil-bathroom', 'cil-battery-0', 'cil-battery-3',
    'cil-battery-5', 'cil-battery-alert', 'cil-battery-empty', 'cil-battery-full',
    'cil-battery-slash', 'cil-beach-access', 'cil-beaker', 'cil-bed', 'cil-bell',
    'cil-bell-exclamation', 'cil-bike', 'cil-birthday-cake', 'cil-blind', 'cil-bluetooth',
    'cil-blur', 'cil-blur-circular', 'cil-blur-linear', 'cil-boat-alt', 'cil-bold',
    'cil-bolt', 'cil-bolt-circle', 'cil-book', 'cil-bookmark', 'cil-border-all',
    'cil-border-bottom', 'cil-border-clear', 'cil-border-horizontal', 'cil-border-inner',
    'cil-border-left', 'cil-border-outer', 'cil-border-right', 'cil-border-style',
    'cil-border-top', 'cil-border-vertical', 'cil-bowling', 'cil-braille', 'cil-briefcase',
    'cil-brightness', 'cil-british-pound', 'cil-browser', 'cil-brush', 'cil-brush-alt',
    'cil-bug', 'cil-building', 'cil-bullhorn', 'cil-burger', 'cil-burn', 'cil-bus-alt',
    'cil-calculator', 'cil-calendar', 'cil-calendar-check', 'cil-camera',
    'cil-camera-control', 'cil-camera-roll', 'cil-car-alt', 'cil-caret-bottom',
    'cil-caret-left', 'cil-caret-right', 'cil-caret-top', 'cil-cart', 'cil-cash',
    'cil-casino', 'cil-cast', 'cil-cat', 'cil-cc', 'cil-center-focus', 'cil-chart',
    'cil-chart-line', 'cil-chart-pie', 'cil-chat-bubble', 'cil-check', 'cil-check-alt',
    'cil-check-circle', 'cil-chevron-bottom', 'cil-chevron-circle-down-alt',
    'cil-chevron-circle-left-alt', 'cil-chevron-circle-right-alt',
    'cil-chevron-circle-up-alt', 'cil-chevron-double-down', 'cil-chevron-double-left',
    'cil-chevron-double-right', 'cil-chevron-double-up', 'cil-chevron-left',
    'cil-chevron-right', 'cil-chevron-top', 'cil-child', 'cil-child-friendly', 'cil-circle',
    'cil-clear-all', 'cil-clipboard', 'cil-clock', 'cil-clone', 'cil-closed-captioning',
    'cil-cloud', 'cil-cloud-download', 'cil-cloud-upload', 'cil-cloudy', 'cil-code',
    'cil-coffee', 'cil-cog', 'cil-color-border', 'cil-color-fill', 'cil-color-palette',
    'cil-columns', 'cil-command', 'cil-comment-bubble', 'cil-comment-square', 'cil-compass',
    'cil-compress', 'cil-contact', 'cil-contrast', 'cil-control', 'cil-copy', 'cil-couch',
    'cil-credit-card', 'cil-crop', 'cil-crop-rotate', 'cil-cursor', 'cil-cursor-move',
    'cil-cut', 'cil-data-transfer-down', 'cil-data-transfer-up', 'cil-deaf', 'cil-delete',
    'cil-description', 'cil-devices', 'cil-dialpad', 'cil-diamond', 'cil-dinner',
    'cil-disabled', 'cil-dog', 'cil-dollar', 'cil-door', 'cil-double-quote-sans-left',
    'cil-double-quote-sans-right', 'cil-drink', 'cil-drink-alcohol', 'cil-drop', 'cil-eco',
    'cil-education', 'cil-elevator', 'cil-envelope-closed', 'cil-envelope-letter',
    'cil-envelope-open', 'cil-equalizer', 'cil-ethernet', 'cil-euro', 'cil-excerpt',
    'cil-exit-to-app', 'cil-expand-down', 'cil-expand-left', 'cil-expand-right',
    'cil-expand-up', 'cil-exposure', 'cil-external-link', 'cil-eyedropper', 'cil-face',
    'cil-face-dead', 'cil-factory', 'cil-factory-slash', 'cil-fastfood', 'cil-fax',
    'cil-featured-playlist', 'cil-file', 'cil-filter', 'cil-filter-frames',
    'cil-filter-photo', 'cil-filter-square', 'cil-filter-x', 'cil-find-in-page',
    'cil-fingerprint', 'cil-fire', 'cil-flag-alt', 'cil-flight-takeoff', 'cil-flip',
    'cil-flip-to-back', 'cil-flip-to-front', 'cil-flower', 'cil-folder', 'cil-folder-open',
    'cil-font', 'cil-football', 'cil-fork', 'cil-fridge', 'cil-frown', 'cil-fullscreen',
    'cil-fullscreen-exit', 'cil-functions', 'cil-functions-alt', 'cil-gamepad', 'cil-garage',
    'cil-gem', 'cil-gif', 'cil-gift', 'cil-globe-alt', 'cil-golf', 'cil-golf-alt',
    'cil-gradient', 'cil-grain', 'cil-graph', 'cil-grid', 'cil-grid-slash', 'cil-group',
    'cil-hamburger-menu', 'cil-hand-point-down', 'cil-hand-point-left',
    'cil-hand-point-right', 'cil-hand-point-up', 'cil-happy', 'cil-hd', 'cil-hdr',
    'cil-header', 'cil-headphones', 'cil-healing', 'cil-heart', 'cil-highlighter',
    'cil-highligt', 'cil-history', 'cil-home', 'cil-hospital', 'cil-hot-tub', 'cil-house',
    'cil-https', 'cil-image', 'cil-image-broken', 'cil-image-plus', 'cil-inbox',
    'cil-indent-decrease', 'cil-indent-increase', 'cil-industry', 'cil-industry-slash',
    'cil-infinity', 'cil-info', 'cil-input', 'cil-input-hdmi', 'cil-input-power',
    'cil-institution', 'cil-italic', 'cil-justify-center', 'cil-justify-left',
    'cil-justify-right', 'cil-keyboard', 'cil-lan', 'cil-language', 'cil-laptop',
    'cil-layers', 'cil-leaf', 'cil-lemon', 'cil-level-down', 'cil-level-up', 'cil-library',
    'cil-library-add', 'cil-library-building', 'cil-life-ring', 'cil-lightbulb',
    'cil-line-spacing', 'cil-line-style', 'cil-line-weight', 'cil-link', 'cil-link-alt',
    'cil-link-broken', 'cil-list', 'cil-list-filter', 'cil-list-high-priority',
    'cil-list-low-priority', 'cil-list-numbered', 'cil-list-numbered-rtl', 'cil-list-rich',
    'cil-location-pin', 'cil-lock-locked', 'cil-lock-unlocked', 'cil-locomotive', 'cil-loop',
    'cil-loop-1', 'cil-loop-circular', 'cil-low-vision', 'cil-magnifying-glass', 'cil-map',
    'cil-media-eject', 'cil-media-pause', 'cil-media-play', 'cil-media-record',
    'cil-media-skip-backward', 'cil-media-skip-forward', 'cil-media-step-backward',
    'cil-media-step-forward', 'cil-media-stop', 'cil-medical-cross', 'cil-meh', 'cil-memory',
    'cil-menu', 'cil-mic', 'cil-microphone', 'cil-minus', 'cil-mobile',
    'cil-mobile-landscape', 'cil-money', 'cil-monitor', 'cil-mood-bad', 'cil-mood-good',
    'cil-mood-very-bad', 'cil-mood-very-good', 'cil-moon', 'cil-mouse', 'cil-mouth-slash',
    'cil-move', 'cil-movie', 'cil-mug', 'cil-mug-tea', 'cil-music-note', 'cil-newspaper',
    'cil-note-add', 'cil-notes', 'cil-object-group', 'cil-object-ungroup', 'cil-opacity',
    'cil-opentype', 'cil-options', 'cil-paint', 'cil-paint-bucket', 'cil-paper-plane',
    'cil-paperclip', 'cil-paragraph', 'cil-paw', 'cil-pen', 'cil-pen-alt', 'cil-pen-nib',
    'cil-pencil', 'cil-people', 'cil-phone', 'cil-pin', 'cil-pizza', 'cil-plant',
    'cil-playlist-add', 'cil-plus', 'cil-pool', 'cil-power-standby', 'cil-pregnant',
    'cil-print', 'cil-pushchair', 'cil-puzzle', 'cil-qr-code', 'cil-rain', 'cil-rectangle',
    'cil-recycle', 'cil-reload', 'cil-report-slash', 'cil-resize-both', 'cil-resize-height',
    'cil-resize-width', 'cil-restaurant', 'cil-room', 'cil-router', 'cil-rowing', 'cil-rss',
    'cil-ruble', 'cil-running', 'cil-sad', 'cil-satelite', 'cil-save', 'cil-school',
    'cil-screen-desktop', 'cil-screen-smartphone', 'cil-scrubber', 'cil-search', 'cil-send',
    'cil-settings', 'cil-share', 'cil-share-all', 'cil-share-alt', 'cil-share-boxed',
    'cil-shield-alt', 'cil-short-text', 'cil-shower', 'cil-sign-language',
    'cil-signal-cellular-0', 'cil-signal-cellular-3', 'cil-signal-cellular-4', 'cil-sim',
    'cil-sitemap', 'cil-smile', 'cil-smile-plus', 'cil-smoke', 'cil-smoke-free',
    'cil-smoke-slash', 'cil-smoking-room', 'cil-snowflake', 'cil-soccer', 'cil-sofa',
    'cil-sort-alpha-down', 'cil-sort-alpha-up', 'cil-sort-ascending', 'cil-sort-descending',
    'cil-sort-numeric-down', 'cil-sort-numeric-up', 'cil-spa', 'cil-space-bar', 'cil-speak',
    'cil-speaker', 'cil-speech', 'cil-speedometer', 'cil-spreadsheet', 'cil-square', 'cil-star',
    'cil-star-half', 'cil-storage', 'cil-stream', 'cil-strikethrough', 'cil-sun',
    'cil-swap-horizontal', 'cil-swap-vertical', 'cil-swimming', 'cil-sync', 'cil-tablet',
    'cil-tag', 'cil-tags', 'cil-task', 'cil-taxi', 'cil-tennis', 'cil-tennis-ball',
    'cil-terminal', 'cil-terrain', 'cil-text', 'cil-text-shapes', 'cil-text-size',
    'cil-text-square', 'cil-text-strike', 'cil-thumb-down', 'cil-thumb-up', 'cil-toggle-off',
    'cil-toggle-on', 'cil-toilet', 'cil-touch-app', 'cil-transfer', 'cil-translate', 'cil-trash',
    'cil-triangle', 'cil-truck', 'cil-tv', 'cil-underline', 'cil-usb', 'cil-user',
    'cil-user-female', 'cil-user-follow', 'cil-user-plus', 'cil-user-unfollow', 'cil-user-x',
    'cil-vector', 'cil-vertical-align-bottom', 'cil-vertical-align-center',
    'cil-vertical-align-top', 'cil-video', 'cil-videogame', 'cil-view-column',
    'cil-view-module', 'cil-view-quilt', 'cil-view-stream', 'cil-voice',
    'cil-voice-over-record', 'cil-volume-high', 'cil-volume-low', 'cil-volume-off', 'cil-walk',
    'cil-wallet', 'cil-wallpaper', 'cil-warning', 'cil-watch', 'cil-wc', 'cil-weightlifitng',
    'cil-wheelchair', 'cil-wifi-signal-0', 'cil-wifi-signal-1', 'cil-wifi-signal-2',
    'cil-wifi-signal-3', 'cil-wifi-signal-4', 'cil-wifi-signal-off', 'cil-window',
    'cil-window-maximize', 'cil-window-minimize', 'cil-window-restore', 'cil-wrap-text',
    'cil-x', 'cil-x-circle', 'cil-yen', 'cil-zoom', 'cil-zoom-in', 'cil-zoom-out'
];

            const iconGrid = document.getElementById('iconGrid');
            const iconSearch = document.getElementById('iconSearch');
            const featureIconInput = document.getElementById('featureIcon');
            const selectedIconPreview = document.getElementById('selectedIconPreview');
            const modalEl = document.getElementById('iconPickerModal');
        
            function renderIcons(icons) {
                if (!iconGrid) {
                    console.error('iconGrid element not found');
                    return;
                }
                iconGrid.innerHTML = '';
                icons.forEach(icon => {
                    const div = document.createElement('div');
                    div.className = 'col-4 col-sm-3 col-md-2';
                    div.innerHTML = `
                        <div class="icon-preview text-center p-2 border rounded cursor-pointer" 
                             data-icon="${icon}"
                             role="button"
                             style="cursor: pointer;">
                            <i class="${icon} h3 mb-2"></i>
                            <div class="small text-muted text-truncate px-1">${icon}</div>
                        </div>
                    `;
                    iconGrid.appendChild(div);
                });
            }

            // Initial render
            document.querySelector('[data-coreui-toggle="modal"]').addEventListener('click', function() {
                renderIcons(coreUIFreeIcons);
            });
            
            // Backup render on modal show
            modalEl.addEventListener('shown.coreui.modal', function () {
                if (iconGrid.children.length === 0) {
                    renderIcons(coreUIFreeIcons);
                }
            });
        
            // Search functionality
            iconSearch.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const filteredIcons = coreUIFreeIcons.filter(icon => 
                    icon.toLowerCase().includes(searchTerm)
                );
                renderIcons(filteredIcons);
            });
        
            // Icon selection
            iconGrid.addEventListener('click', (e) => {
                const iconPreview = e.target.closest('.icon-preview');
                if (iconPreview) {
                    const selectedIcon = iconPreview.dataset.icon;
                    featureIconInput.value = selectedIcon;
                    selectedIconPreview.className = selectedIcon;
                    const modal = coreui.Modal.getInstance(modalEl);
                    if (modal) {
                        modal.hide();
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>