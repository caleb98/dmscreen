var panels = new Object();

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

    //Store panel ids->name
    $(".content-panel-container").each(function() {
        var panelId = $(this).attr("id").replace("-panel", "");
        var panelName = $(this).attr("content-panel-name");
        panels[panelId] = panelName;
    });

    //Setup panel selection dropdown buttons
    $(".panel-select-button").click(panelDropdownButtonClick);

    //Setup panel dropdown items
    $(".panel-select-content").each(function() {
        var currentDropdown = $(this);
        var thisId = $(this).attr("id").replace("-panel-select-content", "");
        Object.keys(panels).forEach(function(key) {
            if (key !== thisId) {
                currentDropdown.append(`<div class=\"panel-swapper\" panel-from=\"${thisId}\" panel-to=\"${key}\">${panels[key]}</div>`);
            }
        });
    });

    //Setup panel dropdown item clicks
    $(".panel-swapper").click(function(event) {
        var currentId = $(this).attr("panel-from");
        var nextId = $(this).attr("panel-to");

        //Swap html inside
        var currentPanel = $(`#${currentId}-panel`);
        var nextPanel = $(`#${nextId}-panel`);

        var currentChildren = $(`#${currentId}-panel > *`).detach(); //Detach keeps listeners, etc.
        var nextChildren = $(`#${nextId}-panel > *`).detach();

        currentChildren.appendTo(nextPanel);
        nextChildren.appendTo(currentPanel);

        //Swap ids and names of overarching panels
        var tempName = currentPanel.attr("content-panel-name");
        currentPanel.attr({
            "id": `${nextId}-panel`,
            "content-panel-name": `${nextPanel.attr("content-panel-name")}`
        });
        nextPanel.attr({
            "id": `${currentId}-panel`,
            "content-panel-name": `${tempName}`
        });

        closePanelSwapDropdowns();
        event.stopPropagation();
    })

});

//Close panel selection windows if a click occurs outside of one that is open
$(window).click(function(event) {
    if (!event.target.matches(".panel-select-button")) {
        closePanelSwapDropdowns();
    }
});

$(document).keydown(function(event) {
    var sb = $(".searchbar");
    if (event.which === 27) {
        sb.val("");
    }
    sb.focus();
});

function closePanelSwapDropdowns() {
    $(".panel-select-content").removeClass("show");
}

function panelDropdownButtonClick(event) {
    var id = $(this).attr("id");
    id = id.replace("-panel-select-button", "-panel-select-content");
    $(`#${id}`).toggleClass("show");
    event.stopPropagation();
}