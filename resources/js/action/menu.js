
menufile = $(clickedAction).attr("href");
$("#content").html($(generateFromFile("/resources/json/data/" + menufile.replace("#", "") + ".json")).html());