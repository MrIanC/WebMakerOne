// bs-class-selector.js (or directly in your script)
grapesjs.plugins.add('grapejs-plugin-bs-icons', (editor) => {
    editor.Panels.addButton('options', {
        id: 'grapejs-plugin-bs-icons',
        className: 'fa fa-object-group',
        command: 'grapejs-plugin-bs-icons-cmd',
        attributes: { title: 'Select Bootstrap Icons' },
        active: false,
    });
    editor.Commands.add('grapejs-plugin-bs-icons-cmd', {
        run(editor, sender, options) {
            const selected = editor.getSelected();
            if (!selected) {
                alert('No element selected');
                return;
            }
            // Get the classes from options or use a default array
            const classes = options.classes || [
                'bi-alarm', 'bi-bag', 'bi-basket', 'bi-bell', 'bi-book',
                'bi-calendar', 'bi-camera', 'bi-cart', 'bi-chat', 'bi-check',
                'bi-clock', 'bi-cloud', 'bi-download', 'bi-envelope', 'bi-exclamation',
                'bi-eye', 'bi-heart', 'bi-house', 'bi-info', 'bi-key',
                'bi-lightbulb', 'bi-lock', 'bi-map', 'bi-pencil', 'bi-person',
                'bi-phone', 'bi-plus', 'bi-rocket', 'bi-search', 'bi-share',
                'bi-star', 'bi-stop', 'bi-suit-heart', 'bi-tag', 'bi-trash',
                'bi-upload', 'bi-user', 'bi-volume-up', 'bi-wifi', 'bi-zoom-in',
                'bi-gear', 'bi-file-earmark', 'bi-list', 'bi-paint-bucket', 'bi-shield',
                'bi-card-checklist', 'bi-arrow-left', 'bi-arrow-right', 'bi-arrow-up', 'bi-arrow-down',
                'bi-archive', 'bi-broadcast', 'bi-calendar-event', 'bi-calendar-check', 'bi-camera-reels',
                'bi-car', 'bi-chat-dots', 'bi-check-circle', 'bi-check-square', 'bi-chevron-left',
                'bi-chevron-right', 'bi-chevron-up', 'bi-chevron-down', 'bi-clipboard', 'bi-cloud-check',
                'bi-cloud-download', 'bi-cloud-upload', 'bi-code', 'bi-columns', 'bi-compass',
                'bi-cone', 'bi-cup', 'bi-currency-dollar', 'bi-currency-euro', 'bi-currency-pound',
                'bi-currency-yen', 'bi-dash', 'bi-dash-circle', 'bi-dash-square', 'bi-device-desktop',
                'bi-device-laptop', 'bi-device-mobile', 'bi-device-tablet', 'bi-discord', 'bi-display',
                'bi-download', 'bi-envelope-fill', 'bi-exclamation-circle', 'bi-exclamation-square', 'bi-file-earmark-text',
                'bi-file-earmark-pdf', 'bi-file-earmark-word', 'bi-file-earmark-excel', 'bi-file-earmark-image', 'bi-file-earmark-zip',
                'bi-filter', 'bi-flower', 'bi-gift', 'bi-globe', 'bi-heart-fill',
                'bi-heartbreak', 'bi-hourglass', 'bi-hurricane', 'bi-info-circle', 'bi-info-square',
                'bi-inbox', 'bi-journal', 'bi-key-fill', 'bi-laptop', 'bi-lightbulb-fill',
                'bi-link', 'bi-list-check', 'bi-list-ol', 'bi-list-ul', 'bi-lock-fill',
                'bi-magic', 'bi-megaphone', 'bi-mic', 'bi-music-note', 'bi-person-check',
                'bi-person-fill', 'bi-person-lines-fill', 'bi-person-circle', 'bi-person-plus', 'bi-person-x',
                'bi-pie-chart', 'bi-piggy-bank', 'bi-play', 'bi-plus-circle', 'bi-plus-square',
                'bi-postcard', 'bi-printer', 'bi-receipt', 'bi-recycle', 'bi-refresh',
                'bi-satellite', 'bi-save', 'bi-scissors', 'bi-shield-fill', 'bi-skip-backward',
                'bi-skip-forward', 'bi-sliders', 'bi-snow', 'bi-snowflake', 'bi-soundwave',
                'bi-star-fill', 'bi-table', 'bi-tag-fill', 'bi-tags', 'bi-telescope',
                'bi-textarea', 'bi-textarea-resize', 'bi-toggle-on', 'bi-toggle-off', 'bi-tools',
                'bi-trophy', 'bi-ui-checks', 'bi-ui-radios', 'bi-unlock', 'bi-unlock-fill',
                'bi-volume-down', 'bi-volume-mute', 'bi-volume-off', 'bi-volume-up', 'bi-wallet',
                'bi-wind', 'bi-wrench', 'bi-x', 'bi-x-circle', 'bi-x-square',
                'bi-youtube', 'bi-zoom-out', 'bi-skip-start', 'bi-skip-end', 'bi-zipper'
            ]
            
            ;


            // Build the HTML for the select and button
            let htmlContent = `
            <div>
              ${classes.map(className => {
                f = className.split("-");

                if (f[0] == "sep") {
                    return `<div class="gjs-sm-sector-label">` + f[1] + `</div>`
                } else {
                    return `<button style="margin:3px; font-size:2em" class="selectStyle gjs-btn-prim ${className}" value="${className}"></button>`
                }
            }).join('')}
              
            </div>
          `;
          htmlContent += `
          <div>Visit <a href="https://icons.getbootstrap.com/">bootstrap Icons</a> to get more. use the bi-icon-name in the classes to add that icon</div>
          `;
            // Display the modal with the HTML content
            const modal = editor.Modal;
            modal.setTitle('Choose a Class');
            modal.setContent(htmlContent);
            modal.open();

            // Add event listener for the button
            $(document).on("click", ".selectStyle", function () {
                const selected = editor.getSelected();
                selected.addClass($(this).val());
                editor.Modal.close();
            });
        }
    });
});