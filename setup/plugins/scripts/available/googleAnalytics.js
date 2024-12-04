googleTagID = "PUT_YOUR_GOOGLE_TAG_ID_HERE";
scriptPath = $(clickedAction).data("action");
$.getScript("https://www.googletagmanager.com/gtag/js?id=" + googleTagID)
    .done(function () {
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', googleTagID);
    })
    .fail(function () {
        console.log('oops ' + scriptPath);
    });