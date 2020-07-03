<?php
declare(strict_types=1);

function createPanelHTML(mysqli $conn, array $panelObj) : string {
    //Grab panel meta
    $id = $panelObj["id"];
    $name = $panelObj["name"];
    
    //Load html content
    $panelHTMLFile = $panelObj["html_file"];
    $panelFullPath = "panels/" . $panelHTMLFile;
    $htmlFileRef = fopen($panelFullPath, "r");
    $html = fread($htmlFileRef, filesize($panelFullPath));

    //Grab tags
    $tags = getTagsForPanelId($conn, $id);
    $tagsJson = htmlspecialchars(json_encode($tags));

    return
    "<div id=\"{$id}-panel\" class=\"container-fluid content-panel-container\" content-panel-name=\"{$name}\" content-panel-tags=\"{$tagsJson}\">
        <button class=\"content-panel-header container-fluid\" type=\"button\">
            <div id=\"{$id}-panel-select-button\" class=\"panel-select-button\">▼</div>
            <div id=\"{$id}-panel-select-content\" class=\"panel-select-content\">
            </div>
            {$name}
        </button>
        <div class=\"content-panel-inner\">
            <div class=\"content-panel-content\">{$html}</div>
        </div>
    </div>";
}

function getTagsForPanelId(mysqli $conn, string $panelId) : array {
    $sql = $conn->prepare("
        SELECT tag, priority
        FROM global_panels 
        INNER JOIN panel_tags ON global_panels.id = panel_tags.id
        WHERE global_panels.id = ?
    ");
    return getTags($sql, $panelId);
}

function getTagsForPanelName(mysqli $conn, string $panelName) : array {
    $sql = $conn->prepare("
        SELECT tag, priority
        FROM global_panels 
        INNER JOIN panel_tags ON global_panels.id = panel_tags.id
        WHERE global_panels.name = ?
    ");
    return getTags($sql, $panelName);
}

//Inner function called by getTagsFor... functions
function getTags(mysqli_stmt $sql, string $arg) : array {
    $sql->bind_param("s", $arg);
    $sql->execute();

    $tags = [];

    if($result = $sql->get_result()) {
        while($row = $result->fetch_assoc()) {
            $tags[] = (object) array(
                "tag" => $row["tag"],
                "priority" => $row["priority"]
            );
        }
    }
    else {
        die("Error getting panel tags from database.");
    }

    return $tags;
}

?>