// bs-class-selector.js (or directly in your script)
grapesjs.plugins.add('bs-class-selector', (editor) => {
    editor.Panels.addButton('options', {
        id: 'bs-class-button',
        className: 'fa fa-css3',
        command: 'bootstrap-class-list',
        attributes: { title: 'Select Bootstrap Class' },
        active: false,
    });
    editor.Commands.add('bootstrap-class-list', {
        run(editor, sender, options) {
            const selected = editor.getSelected();
            if (!selected) {
                alert('No element selected');
                return;
            }

            // Get the classes from options or use a default array
            const classes = options.classes || [
                // **Text Alignment**
                'sep-Text Alignment',
                'text-start',      // Align text to the left
                'text-center',     // Center text
                'text-end',        // Align text to the right

                // **Wrapping and Overflow**
                'sep-Wrapping and Overflow',
                'text-nowrap',     // Prevent text from wrapping
                'text-wrap',       // Allow text to wrap
                'text-break',      // Break words to fit within container
                'overflow-auto',   // Auto overflow
                'overflow-hidden', // Hide overflow
                'overflow-visible',// Show overflow
                'overflow-scroll', // Scroll overflow

                // **Transformation**
                'sep-Transformation',
                'text-lowercase',  // Lowercase text
                'text-uppercase',  // Uppercase text
                'text-capitalize', // Capitalize first letter of each word

                // **Font Size**
                'sep-Font Size',
                'fs-1',            // Font size 1
                'fs-2',            // Font size 2
                'fs-3',            // Font size 3
                'fs-4',            // Font size 4
                'fs-5',            // Font size 5
                'fs-6',            // Font size 6

                // **Font Weight and Italics**
                'sep-Font Weight and Italics',
                'fw-bold',         // Bold text
                'fw-bolder',       // Bolder text
                'fw-normal',       // Normal weight text
                'fw-light',        // Light weight text
                'fst-italic',      // Italic text
                'fst-normal',      // Normal text (non-italic)

                // **Text Colors**
                'text-Text Colors',
                'text-custom-dark',
                'text-custom-light',
                'text-primary',    // Primary text color
                'text-secondary',  // Secondary text color
                'text-success',    // Success text color
                'text-danger',     // Danger text color
                'text-warning',    // Warning text color
                'text-info',       // Info text color
                'text-light',      // Light text color
                'text-dark',       // Dark text color
                'text-body',       // Body text color
                'text-muted',      // Muted text color
                'text-white',      // White text color

                // **Text Decoration**
                'sep-Text Decoration',
                'text-decoration-none',   // No text decoration
                'text-decoration-underline', // Underline text
                'text-decoration-line-through', // Line through text
                'text-decoration-overline', // Overline text

                // **Background Color**
                'sep-Background Color',
                'bg-custom-dark',
                'bg-custom-light',
                'bg-primary',      // Primary background color
                'bg-secondary',    // Secondary background color
                'bg-success',      // Success background color
                'bg-danger',       // Danger background color
                'bg-warning',      // Warning background color
                'bg-info',         // Info background color
                'bg-light',        // Light background color
                'bg-dark',         // Dark background color
                'bg-white',        // White background color

                // **Padding**
                'sep-Padding',
                'p-0',             // Padding 0
                'p-1',             // Padding 1
                'p-2',             // Padding 2
                'p-3',             // Padding 3
                'p-4',             // Padding 4
                'p-5',             // Padding 5
                'pt-0',            // Padding top 0
                'pt-1',            // Padding top 1
                'pt-2',            // Padding top 2
                'pt-3',            // Padding top 3
                'pt-4',            // Padding top 4
                'pt-5',            // Padding top 5
                'pb-0',            // Padding bottom 0
                'pb-1',            // Padding bottom 1
                'pb-2',            // Padding bottom 2
                'pb-3',            // Padding bottom 3
                'pb-4',            // Padding bottom 4
                'pb-5',            // Padding bottom 5
                'pl-0',            // Padding left 0
                'pl-1',            // Padding left 1
                'pl-2',            // Padding left 2
                'pl-3',            // Padding left 3
                'pl-4',            // Padding left 4
                'pl-5',            // Padding left 5
                'pr-0',            // Padding right 0
                'pr-1',            // Padding right 1
                'pr-2',            // Padding right 2
                'pr-3',            // Padding right 3
                'pr-4',            // Padding right 4
                'pr-5',            // Padding right 5

                // **Margins**
                'sep-Margins',
                'm-0',             // Margin 0
                'm-1',             // Margin 1
                'm-2',             // Margin 2
                'm-3',             // Margin 3
                'm-4',             // Margin 4
                'm-5',             // Margin 5
                'mt-0',            // Margin top 0
                'mt-1',            // Margin top 1
                'mt-2',            // Margin top 2
                'mt-3',            // Margin top 3
                'mt-4',            // Margin top 4
                'mt-5',            // Margin top 5
                'mb-0',            // Margin bottom 0
                'mb-1',            // Margin bottom 1
                'mb-2',            // Margin bottom 2
                'mb-3',            // Margin bottom 3
                'mb-4',            // Margin bottom 4
                'mb-5',            // Margin bottom 5
                'ml-0',            // Margin left 0
                'ml-1',            // Margin left 1
                'ml-2',            // Margin left 2
                'ml-3',            // Margin left 3
                'ml-4',            // Margin left 4
                'ml-5',            // Margin left 5
                'mr-0',            // Margin right 0
                'mr-1',            // Margin right 1
                'mr-2',            // Margin right 2
                'mr-3',            // Margin right 3
                'mr-4',            // Margin right 4
                'mr-5',            // Margin right 5

                // **Borders**
                'sep-Borders',
                'border',          // Default border
                'border-0',        // No border
                'border-top',      // Top border
                'border-end',      // Right border
                'border-bottom',   // Bottom border
                'border-start',    // Left border
                'border-primary',  // Primary border color
                'border-secondary',// Secondary border color
                'border-success',  // Success border color
                'border-danger',   // Danger border color
                'border-warning',  // Warning border color
                'border-info',     // Info border color
                'border-light',    // Light border color
                'border-dark',     // Dark border color
                'border-white',    // White border color

                // **Shadow**
                'sep-Shadow',
                'shadow-sm',       // Small shadow
                'shadow',          // Default shadow
                'shadow-lg',       // Large shadow
                'shadow-none',      // No shadow

                'sep-Buttons',
                'btn',             // Default button
                'btn-custom-dark',     // Primary button
                'btn-custom-light',     // Primary button
                'btn-primary',     // Primary button
                'btn-secondary',   // Secondary button
                'btn-success',     // Success button
                'btn-danger',      // Danger button
                'btn-warning',     // Warning button
                'btn-info',        // Info button
                'btn-light',       // Light button
                'btn-dark',        // Dark button
                'btn-white',       // White button
                'btn-link',        // Link button
                'btn-lg',          // Large button
                'btn-sm',          // Small button
                'btn-block',       // Block level button
                'btn-outline-primary',   // Outline primary button
                'btn-outline-secondary', // Outline secondary button
                'btn-outline-success',   // Outline success button
                'btn-outline-danger',    // Outline danger button
                'btn-outline-warning',   // Outline warning button
                'btn-outline-info',      // Outline info button
                'btn-outline-light',     // Outline light button
                'btn-outline-dark',      // Outline dark button
                'btn-outline-white'      // Outline white button
            ];


            // Build the HTML for the select and button
            let htmlContent = `
            <div>
              ${classes.map(className => {
                f = className.split("-");

                if (f[0] == "sep") {
                    return `<div class="gjs-sm-sector-label">` + f[1] + `</div>`
                } else {
                    return `<button style="margin:3px" class="selectStyle gjs-btn-prim" value="${className}">${className}</button>`
                }
            }).join('')}
              
            </div>
          `;

            // Display the modal with the HTML content
            const modal = editor.Modal;
            modal.setTitle('Choose a Class');
            modal.setContent(htmlContent);
            modal.open();

            // Add event listener for the button
            $(document).on("click", ".selectStyle", function () {
                selected.addClass($(this).val());
                editor.Modal.close();
            });
        }
    });
});