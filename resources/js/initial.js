$(document).ready(function () {
    $.ajaxSetup({ cache: false });
    currentPath = window.location.pathname;
    uri = currentPath;
    if (currentPath == "/") 
        currentPath = "/home/";
    $.ajax({
        url: "/resources/parts/navigation.html",
        datatype: "html",
        success: function (response) {
            $r = $(response);
            styles = $("<div>").html(response).find("style");
            $("head").append($("<style>", { id: "gs" }));
            $("#gs").append(
                styles.html()
            );

            $normal = $r.find("#template").clone();
            $active = $r.find("#templateActive").clone();
            $normal.removeAttr("id");
            $active.removeAttr("id");

            $r.find("#template").remove();
            $r.find("#templateActive").remove();
            $("#navigation").replaceWith($r);

            $.ajax({
                url: "/resources/json/navigation.json",
                datatype: "json",
                success: function (items) {
                    $.each(items['links'], function (i, item) {

                        if (item != "/") {
                            fn = item + "/";
                        } else {
                            fn = item;
                        }

                        if (fn == uri) {
                            $ac = $($active).clone();
                            $ac.find("a").html(i).attr("href", item);
                            $("#menuItems").append(
                                $ac
                            );
                        } else {
                            $no = $($normal).clone();
                            $no.find("a").html(i).attr("href", item);
                            $("#menuItems").append(
                                $no
                            );

                        }
                    });
                }
            })
        }
    });
    $.ajax({
        url: "/resources/parts/pages" + currentPath.replace(/\/+$/, '') + ".html",
        datatype: "html",
        success: function (response) {
            page = $("<main>").html(response);
            $("#gs").append(page.find("style").remove().html());
            $("#content").replaceWith(page);

            const title = page.find('h1').first().text().trim() || "NO H1 HEADING!";
            $("head").append($("<title>").text(title));
            const description = page.find('h1, h2, h3, h4, h5, h6')
                .map((_, el) => $(el).text().trim())
                .get().join(", ");
            jsonLd = {
                "@context": "https://schema.org",
                "@type": "BreadcrumbList",
                "itemListElement": [{
                    "@type": "ListItem",
                    "position": 1,
                    "name": currentPath.replace(/\//g, '').replace(/^./, m => m.toUpperCase()),
                    "item": "https://s8.techbit.co.za/"
                }]
            };
            page.find('section').each((index, section) => {
                const sectionId = $(section).attr('id') || `section-${index + 1}`;
                const headingText = $(section).find('h1, h2, h3, h4, h5, h6').first().text().trim();
                if (headingText) {
                    jsonLd.itemListElement.push({
                        "@type": "ListItem",
                        "position": index + 2,
                        "name": headingText,
                        "item": `${window.location.href}#${sectionId}`
                    });
                }
            });
            $("head").append(
                $("<meta>", { name: "description", content: description }),
                $("<meta>", { rel: "canonical", href: window.location.href }),
                $("<link>", { rel: "icon", href: "/uploads/favicon.png", type: "image/x-icon" }),
                $("<script>", { type: "application/ld+json" }).html(JSON.stringify(jsonLd, null, 2))
            );
            $.ajax({
                url: "/resources/json/business.json",
                datatype: "json",
                success: function (jsonLd) {
                    jsonLd.url = window.location.href;
                    $("head").append($("<script>", { type: "application/ld+json" }).text(JSON.stringify(jsonLd, null, 2)));
                },
                error: function () {
                    console.log("Json Business failure");
                }
            });
        }
    });
    $.ajax({
        url: "/resources/parts/footer.html",
        datatype: "html",
        success: function (response) {
            page = $("<footer>").html(response);
            $("#gs").append(page.find("style").remove().html());
            $("#footer").replaceWith(page);

        }
    });
});

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


