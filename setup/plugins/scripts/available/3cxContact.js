$(document).ready(function () {
    $("<call-us-selector>")
        .attr("phonesystem-url", "#PROVISIONINGURL#")
        .attr("party", "#CHATID#")
        .appendTo("body");
    $("<script>")
        .attr("src", "https://downloads-global.3cx.com/downloads/livechatandtalk/v1/callus.js")
        .attr("id", "tcx-callus-js")
        .attr("charset", "utf-8")
        .appendTo("body");
});
