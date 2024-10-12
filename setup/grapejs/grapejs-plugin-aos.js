// bs-class-selector.js (or directly in your script)
grapesjs.plugins.add('grapejs-plugin-aos', (editor) => {
    // Add button to the panel
    editor.Panels.addButton('options', {
        id: 'elementAOS',
        className: 'fa fa-fast-forward',
        command: 'showAOS',
        attributes: { title: 'Element Data' },
        active: false,
    });

    // Add custom command to show the modal with the element's data attributes
    editor.Commands.add('showAOS', {
        run(editor, sender) {
            sender.set('active', false); // Deactivate the button
            const selectedElement = editor.getSelected();
            const modal = editor.Modal;

            // Check if an element is selected
            if (!selectedElement) {
                alert('Please select an element to edit its data attributes.');
                return;
            }
            // Get the element's data-* attributes
            const attributes = selectedElement.getAttributes();
            let dataAction = "";
            // Filter out only data-* attributes
            let html = "";
            let classes = "";
            let gotClass = false;

            $.each(attributes, function (attr, value) {
                if (attr == "data-aos") {
                    dataAction = value;
                }
                if (attr == "class") {
                    classes = value;
                }
            });

            response = '<select id="dataAOSValue">';
            
            response += '<option>'+dataAction+'</option>';
            response += '<option value="">Clear</option>';
            response += '<option value="fade-right">fade-right</option>';
            response += '<option value="fade-left">fade-left</option>';
            response += '<option value="fade-up">fade-up</option>';
            response += '<option value="fade-down">fade-down</option>';
            response += '<option value="fade-up-right">fade-up-right</option>';
            response += '<option value="fade-up-left">fade-up-left</option>';
            response += '<option value="fade-down-right">fade-down-right</option>';
            response += '<option value="fade-down-left">fade-down-left</option>';
            response += '</select>';
            response += '<button id="setAOSAction">Select</button>';
            
            
            modal.setTitle('Clicked Action Script Name: ');
            modal.setContent(response);
            modal.open();

            $(document).on("click", "#setAOSAction", function () {
                attributes['data-aos'] = $("#dataAOSValue").val();
                selectedElement.setAttributes(attributes);
                modal.close();
            });

        }
    });


});