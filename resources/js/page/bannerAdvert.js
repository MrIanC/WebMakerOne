$(document).ready(function () {
    $.getScript("https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js", function () {
        if (Cookies.get("bannerShown")) {
            console.log("Banner Already Shown");
            Cookies.set("bannerShown", '', { expires: -1, path: '/' }); 
        }
        else {
            setTimeout(function () {
                rr = $("<div>")
                    .css("position", "fixed")
                    .css("top", "0px")
                    .css("left", "0px")
                    .css("bottom", "0px")
                    .css("right", "0px")
                    .addClass("bg-light bg-opacity-75 p-5 d-flex align-items-center justify-content-center")
                    .attr("z-index", "900101000")
                    .click(function () {
                        $(this).remove();
                    })
                    .load("/resources/parts/banner.html", function (s, e) {
                        if (e == "success") {
                            rr.appendTo("body");
                            Cookies.set('bannerShown', 'Yes', { expires: (1 / 24), path: '/' });
                        }
                    })
                console.log("show Banner");
            }, 10000);
        }
    });
})