// bs-class-selector.js (or directly in your script)
grapesjs.plugins.add('grapejs-plugin-ai', (editor) => {

    editor.Panels.addButton('options', {
        id: 'aigen',
        className: 'fa fa-lightbulb-o',
        command: 'aiexchange',
        attributes: { title: 'AI tools' },
        active: false,
    });

    editor.Commands.add('aiexchange', {
        run(editor, sender) {
            const selected = editor.getSelected();
            const modal = editor.Modal;

            if (!selected) {
                alert('No element selected');
                return;
            }
            const components = selected.get('components');

            // Check if there are components and the first one is a text node
            if (components.length > 0 && components.at(0).get('type') === 'textnode') {

                modal.setTitle('Wait Please');
                modal.setContent("<div>Content is being made. Please Wait. If it takes too long then try again.</div>");
                modal.open();

                const textContent = components.at(0).get('content');
                $.ajax({
                    url: "/setup/plugins/ai/page.php",
                    data: { saying: textContent },
                    type: "POST",
                    success: function (response) {
                        let theHtmlContent = "";
                        let htmlContent = $(`<div>` + response + `</div>`);

                        // Iterate through each child element with the class .alt_text
                        htmlContent.find('.alt_saying').each(function () {
                            theHtmlContent += '<div class="alt_saying" style="border:1px solid #fff; padding:3px; background-color:#000;">' + $(this).html() + "</div>";  // Get inner HTML of the current .alt_text child
                            theHtmlContent += "<hr>";           // Add a horizontal rule
                        });

                        // Optionally, if you want to wrap it back into a div or do further processing
                        theHtmlContent = `<div>${theHtmlContent}</div>`;


                        // Display the modal with the HTML content
                        modal.setTitle('Select One');
                        modal.setContent(theHtmlContent);

                        console.log("Success");
                    }
                });
            } else {
                alert('Selected element has no text content.');
            }

            $(document).on("click", ".alt_saying", function () {
                const selected = editor.getSelected();
                change = $(this).html();

                html = $(selected.toHTML())
                    .html(change);

                selected.replaceWith(html.prop('outerHTML'));
                editor.Modal.close();
            });


        },


    });


    editor.Panels.addButton('options', {
        id: 'aigenpara',
        className: 'fa fa-paragraph',
        command: 'aigenparacmd',
        attributes: { title: 'AI Paragraph Creation' },
        active: false,
    });

    editor.Commands.add('aigenparacmd', {
        run(editor, sender) {
            const selected = editor.getSelected();
            const modal = editor.Modal;

            if (!selected) {
                alert('No element selected');
                return;
            }

            const components = selected.get('components');

            // Check if there are components and the first one is a text node
            if (components.length > 0 && components.at(0).get('type') === 'textnode') {

                modal.setTitle('Wait Please');
                modal.setContent("<div>Content is being made. Please Wait. If it takes too long then try again.</div>");
                modal.open();

                const textContent = components.at(0).get('content');
                $.ajax({
                    url: "/setup/plugins/ai/content.php",
                    data: { saying: textContent },
                    type: "POST",
                    success: function (response) {
                        let theHtmlContent = "";
                        let htmlContent = $(`<div>` + response + `</div>`);

                        // Iterate through each child element with the class .alt_text
                        htmlContent.find('.alt_saying').each(function () {
                            theHtmlContent += '<div class="alt_saying" style="border:1px solid #fff; padding:3px; background-color:#000;">' + $(this).html() + "</div>";  // Get inner HTML of the current .alt_text child
                            theHtmlContent += "<hr>";           // Add a horizontal rule
                        });

                        // Optionally, if you want to wrap it back into a div or do further processing
                        theHtmlContent = `<div>${theHtmlContent}</div>`;


                        // Display the modal with the HTML content
                        modal.setTitle('Select One');
                        modal.setContent(theHtmlContent);

                        console.log("Success");
                    }
                });
            } else {
                alert('Selected element has no text content.');
            }

            $(document).on("click", ".alt_saying", function () {
                const selected = editor.getSelected();
                change = $(this).html();

                html = $(selected.toHTML())
                    .html(change);

                selected.replaceWith(html.prop('outerHTML'));
                editor.Modal.close();
            });


        },


    });

});