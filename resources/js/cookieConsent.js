$(document).ready(function () {
    $.getScript("https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js", function () {
        console.log("js-cookie library loaded!");
        // Now you can use the js-cookie library

        console.log("Cookie 'user' set.");

        console.log("Cookie Consent");
        if (Cookies.get("acceptCookie")) {
            console.log("Accept Cookies");
            //Cookies.set("acceptCookie", '', { expires: -1, path: '/' }); // Testing - unset cookie to show banner

        } else {
            $("<div>")
                .attr("id", "cookieConsent")
                .css("position", "fixed")
                .css("top", "0px")
                .css("bottom", "0px")
                .css("left", "0px")
                .css("right", "0px")
                .addClass("bg-dark bg-opacity-50")
                .css("z-index", "9000000")
                .append(
                    $("<div>")
                        .addClass("m-3 p-3 border-top bg-custom-light shadow rounded")
                        .append(
                            $("<div>")
                                .addClass("container")
                                .append(
                                    $("<div>")
                                        .addClass("d-flex justify-content-between align-items-center")
                                        .append(
                                            $("<div>").append(
                                                $("<div>")
                                                    .addClass("fw-bold")
                                                    .html("Consent To Cookies"),
                                                $("<ul>").append(
                                                    $("<li>").html("We use cookies to remember your preferences."),
                                                    $("<li>").html("We utilize Google Analytics to understand user behavior."),
                                                    $("<li>").html("Local storage will be used for the shopping cart management."),
                                                ),
                                                $("<i>").html("By clicking 'Accept', you consent to our use of cookies and local storage.")
                                            ),
                                            $("<div>").append(
                                                $("<div>")
                                                    .addClass("btn btn-custom-dark mx-1")
                                                    .html("Accept")
                                                    .click(function () {
                                                        Cookies.set('acceptCookie', 'Yes', { expires: (1 / 48), path: '/' }); // 1/48 is 30 minutes (1 day = 24 hours)

                                                        $("#cookieConsent").remove();
                                                    }),
                                                $("<div>")
                                                    .addClass("btn btn-warning mx-1")
                                                    .html("Don't accept and leave site")
                                                    .click(function () {
                                                        window.history.back();
                                                    })
                                            )
                                        )
                                )
                        )
                )
                .appendTo("body");
        }
    });
});