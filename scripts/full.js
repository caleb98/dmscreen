$(document).ready(function() {

    //Set all content headers to toggle their content containers
    var toggleIndex = 0;
    $(".content-panel-header").attr("data-target", function() {
        var content = $(this).next();
        var contentId = `frame-toggle-${toggleIndex}`;
        content.attr("id", contentId);
        content.addClass("collapse show")
        toggleIndex++;
        return "#" + contentId;
    });
    $(".content-panel-header").attr({
        "data-toggle": "collapse",
        "aria-expanded": true
    });

});