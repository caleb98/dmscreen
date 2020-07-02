<?php
declare(strict_types=1);

function createPanelHTML(array $panelObj) : string {
    //Grab panel meta
    $id = $panelObj["id"];
    $name = $panelObj["name"];
    
    //Load html content
    $panelHTMLFile = $panelObj["html_file"];
    $panelFullPath = "panels/" . $panelHTMLFile;
    $htmlFileRef = fopen($panelFullPath, "r");
    $html = fread($htmlFileRef, filesize($panelFullPath));

    return
    "<div id=\"{$id}-panel\" class=\"container-fluid content-panel-container\" content-panel-name=\"{$name}\">
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

?>