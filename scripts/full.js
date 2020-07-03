var panels = new Object();
var isSearchMode = false;

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
        var panelTags = JSON.parse($(this).attr("content-panel-tags"));
        panels[panelId] = {
            name: panelName,
            tags: panelTags
        };
    });

    //Setup panel selection dropdown buttons
    $(".panel-select-button").click(panelDropdownButtonClick);

    //Setup panel dropdown items
    $(".panel-select-content").each(function() {
        var currentDropdown = $(this);
        var thisId = $(this).attr("id").replace("-panel-select-content", "");
        Object.keys(panels).forEach(function(key) {
            if (key !== thisId) {
                currentDropdown.append(`<div class=\"panel-swapper\" panel-from=\"${thisId}\" panel-to=\"${key}\">${panels[key].name}</div>`);
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

    $("#panel-searchbar").keyup(function(event) {
        var search = $(this).val();
        if (search == "") {
            if (isSearchMode) {
                stopSearchMode();
            }
        } else {
            if (!isSearchMode) {
                startSearchMode();
            }
            doPanelSearch(search);
        }
    });

});

//Close panel selection windows if a click occurs outside of one that is open
$(window).click(function(event) {
    if (!event.target.matches(".panel-select-button")) {
        closePanelSwapDropdowns();
    }
});

$(document).keydown(function(event) {
    var sb = $("#panel-searchbar");
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

var col1Content, col2Content, col3Content;
var searchContent = [];

function startSearchMode() {
    console.log("Starting search mode.");

    col1Content = $("#panel-column-1 > *").detach();
    col2Content = $("#panel-column-2 > *").detach();
    col3Content = $("#panel-column-3 > *").detach();

    col1Content.appendTo($("#panel-column-2"));
    col2Content.appendTo($("#panel-column-2"));
    col3Content.appendTo($("#panel-column-2"));

    searchContent = $("#panel-column-2 > *").detach();

    isSearchMode = true;
}

function stopSearchMode() {
    console.log("Ending search mode.");

    //Dump search content
    $("#panel-column-2 > *").detach();

    col1Content.appendTo($("#panel-column-1"));
    col2Content.appendTo($("#panel-column-2"));
    col3Content.appendTo($("#panel-column-3"));

    isSearchMode = false;
}

function doPanelSearch(searchTerm) {
    searchTerm = searchTerm.replace(/[^a-zA-Z ]/gi, '');
    searchTerm = searchTerm.toLowerCase();
    var tokens = searchTerm.split(" ");

    $("#panel-column-2 > *").detach();

    var searchScores = [];
    Object.keys(panels).forEach(panelId => {
        searchScores.push({
            panelId: panelId,
            score: scorePanelForSearch(tokens, panelId)
        });
    });

    searchScores.sort(function(a, b) {
        return b.score - a.score; //Use b - a so that we sort high->low
    });

    var searchContentCopy = searchContent.slice();

    searchScores.forEach(scoredPanel => {
        console.log(panels[scoredPanel.panelId].name + ":\t" + scoredPanel.score);
        if (scoredPanel.score > 0) {
            searchContentCopy.each(function(index, element) {
                if (scoredPanel.panelId + "-panel" === $(this).attr("id")) {
                    var panel = searchContentCopy.splice(index, 1);
                    $(this).appendTo("#panel-column-2");
                    return;
                }
            })
        }
    });
    console.log("---------------")
}

function scorePanelForSearch(tokens, panelId) {
    var score = 0;
    var tags = panels[panelId].tags;
    tokens.forEach(token => {
        tags.forEach(tagObj => {
            var index = tagObj.tag.indexOf(token);
            if (index >= 0) {
                var percentLength = token.length / tagObj.tag.length;
                score += 100 * Math.pow(0.8, tagObj.priority) * percentLength * Math.max(0, 1 - 0.2 * index);
            }
        });
    });
    return score;
}