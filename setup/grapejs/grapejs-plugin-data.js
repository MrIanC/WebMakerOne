// bs-class-selector.js (or directly in your script)
grapesjs.plugins.add('grapejs-plugin-data', (editor) => {
    // Add button to the panel
    editor.Panels.addButton('options', {
        id: 'theelementdata',
        className: 'fa fa-database',
        command: 'showData',
        attributes: { title: 'Element Data' },
        active: false,
    });

    // Add custom command to show the modal with the element's data attributes
    editor.Commands.add('showData', {
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
                if (attr == "data-action") {
                    dataAction = value;
                }
                if (attr == "class") {
                    classes = value;
                }
            });

            classarray = classes.split(" ");
            console.log(classarray);

            $.each(classarray, function (index, classvalue) {
                if (classvalue == 'action') {
                    gotClass = true;
                }
            });

            $.ajax({
                url: "/setup/plugins/actions/plist.php",
                data: {da: dataAction},
                success: function (response) {
                    modal.setTitle('Clicked Action Script Name: ');
                    modal.setContent(response);
                    modal.open();
                }
            })

            $(document).on("click", "#setDataAction", function () {
                if (gotClass == false) {
                    attributes['class'] = attributes['class'] + " action";
                }
                attributes['data-action'] = $("#dataActionValue").val();
                selectedElement.setAttributes(attributes);
                modal.close();
            });

        }
    });


});