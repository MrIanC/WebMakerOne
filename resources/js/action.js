console.log("Action Script Loaded")
$(document).on('click', '.action', function (event) {
    event.preventDefault();
    clickedAction = this;
    scriptPath = $(clickedAction).data("action");
    $.getScript('/resources/js/action/' + scriptPath + ".js")
        .done(function () {
            console.log('Script Executed: ' + scriptPath);
        })
        .fail(function () {
            console.log('No Script Found: ' + scriptPath);
        });
});
