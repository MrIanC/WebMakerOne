
{
    let allFieldsFilled = true;
    $(clickedAction).closest('form').find('input[required]').each(function () {
        if ($(this).val() === '') {
            allFieldsFilled = false;
            $(this).addClass("is-invalid");
        } else {
            if (allFieldsFilled == true) {
                $(this).addClass("is-valid");
                $(this).removeClass('is-invalid');
            }
        }
    });

    if (allFieldsFilled == true) {
        $.ajax({
            url: $(clickedAction).closest('form').attr("action"),
            type: "POST",
            data: $(clickedAction).closest('form').serialize(),
            success: function (response) {
                var sentVal = $('input[name="sent"]').val();
                var failVal = $('input[name="fail"]').val();

                // Basic validation to ensure input values are relative paths
                function isValidPath(path) {
                    return path.startsWith("/") && !path.includes("http");  // Only allow relative paths
                }

                if (response == "pass" && isValidPath(sentVal)) {
                    window.location.href = sentVal;
                } else if (isValidPath(failVal)) {
                    window.location.href = failVal;
                    console.log(response);
                } else {
                    console.error("Invalid redirect path");
                }
            },

            error: function (xhr, status, error) {
                $(clickedAction).closest('form').load("/resources/parts/pages/" + $('input[name="fail"]').val() + ".html");
                console.error('Form submission failed');
                console.error(error);
            }
        });
    }
}