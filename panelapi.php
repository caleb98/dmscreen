<?php
include "functions.php";

/**
 * name (string) - Name of panel to get
 */
$PANEL_REQUEST_GET_PANEL = "GET_PANEL";

/**
 * id (string) - ID of panel to add tag to
 * tag (string) - tag to add
 * priority (int) - priority this tag gets in search ordering
 */
$PANEL_REQUEST_ADD_TAG = "ADD_TAG";

/**
 * id (string) - ID of panel to remove tag from
 * tag (string) - tag to remove
 */
$PANEL_REQUEST_REMOVE_TAG = "REMOVE_TAG";

$requestType = htmlspecialchars($_GET["function"]);

if(!$requestType) {
    die("No function specified.");
}

$sqlServer = "localhost";
$username = "root";
$password = "choppywavesinsignificantmonkey-";
$dbName = "dmscreen";

$conn = new mysqli($sqlServer, $username, $password, $dbName);

if($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

if($requestType == $PANEL_REQUEST_GET_PANEL) {
    $requestedPanel = htmlspecialchars($_GET["name"]);
    $requestedPanel = str_replace("&amp;", "&", $requestedPanel); //This fixes issues with names that contain "&"

    if(!$requestedPanel) {
        die("No panel name given.");
    }

    $sql = $conn->prepare("SELECT * FROM global_panels WHERE name = ?");
    $sql->bind_param("s", $requestedPanel);
    $sql->execute();
    $result = $sql->get_result();
    $panelObj = $result->fetch_assoc();

    if(!$panelObj) {
        die("Invalid panel name.");
    }

    $previewHtml = createPanelHTML($panelObj);

    $sql = $conn->prepare("
        SELECT tag, priority
        FROM global_panels 
        INNER JOIN panel_tags ON global_panels.id = panel_tags.id
        WHERE global_panels.name = ?
    ");
    $sql->bind_param("s", $requestedPanel);
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

    $return = (object) array(
        "previewHtml" => $previewHtml,
        "tags" => $tags,
        "id" => $panelObj["id"]
    );

    echo json_encode($return);
}
else if($requestType == $PANEL_REQUEST_REMOVE_TAG) {
    $id = htmlspecialchars($_GET["id"]);
    $tag = htmlspecialchars($_GET["tag"]);

    $sql = $conn->prepare("
        DELETE FROM panel_tags
        WHERE id=? AND tag=?
    ");
    $sql->bind_param("ss", $id, $tag);
    $sql->execute();
}
else if($requestType == $PANEL_REQUEST_ADD_TAG) {
    $id = htmlspecialchars($_GET["id"]);
    $tag = htmlspecialchars($_GET["tag"]);

    if(isset($_GET["priority"])) {
        $priority =  htmlspecialchars($_GET["priority"]);
    }
    else {
        $priority = 0;
    }

    $sql = $conn->prepare("
        INSERT INTO panel_tags (id, tag, priority)
        VALUES (?, ?, ?)
    ");
    $sql->bind_param("ssi", $id, $tag, $priority);
    $sql->execute();
}

?>