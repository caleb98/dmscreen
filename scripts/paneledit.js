var currentPanelId;
var currentPanelName;
var tempName;

$(document).ready(function() {

    $("#panel-id-input").keydown(function(event) {
        if (event.which === 13) {
            requestPanel($(this).val())
        }
    });

    $("#tag-input").keydown(function(event) {
        if (event.which === 13 && currentPanelId !== null) {
            var tag = $(this).val();
            var priority = 0;
            if (tag.includes(":")) {
                var split = tag.split(":");
                tag = split[0];
                priority = split[1];
            }
            $.ajax({
                    url: "panelapi.php",
                    method: "GET",
                    data: {
                        function: "ADD_TAG",
                        id: currentPanelId,
                        tag: tag,
                        priority: priority
                    }
                })
                .done(function() {
                    requestPanel(currentPanelName);
                });
            $(this).val("");
        }
    })

});

function requestPanel(panelName) {
    $.ajax({
            url: "panelapi.php",
            method: "GET",
            data: {
                function: "GET_PANEL",
                name: panelName
            }
        })
        .done(updatePanel);
    tempName = panelName;
}

function updatePanel(jsonString) {
    var val = JSON.parse(jsonString);
    currentPanelId = val.id;
    currentPanelName = tempName;

    $("#panel-preview").html(val.previewHtml);

    var tagsDisplay = $("#tags-list");
    tagsDisplay.html("");
    val.tags.forEach(function(tagObj) {
        tagsDisplay.append(
            `<div>
                ${tagObj.tag} : ${tagObj.priority}
                <div class="remove-tag-button" remove-tag="${tagObj.tag}" remove-id="${val.id}" style="float: right;">
                    remove
                </div>
            <div>`
        );
    });

    $(".remove-tag-button").click(function(event) {

        $.ajax({
                url: "panelapi.php",
                method: "GET",
                data: {
                    function: "REMOVE_TAG",
                    id: $(this).attr("remove-id"),
                    tag: $(this).attr("remove-tag")
                }
            })
            .done(function() {
                requestPanel(currentPanelName);
            })

    })
}