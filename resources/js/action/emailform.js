
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
                if (response == "pass") {
                    window.location.href = "/" + $('input[name="sent"]').val();
                } else {
                    window.location.href = "/" + $('input[name="fail"]').val();
                    console.log(response);
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