<!doctype html>
<html>
<head>

    <!-- Info -->
    <title>DM-Screen</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="scripts/full.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- Fonts  -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;700&display=swap" rel="stylesheet">

    <!-- Style -->
    <link rel="stylesheet" href="styles/default.css"/>

</head>
<body>
<div class="container-fluid">
<div class="row" id="page-container">

<?php

//Read panels
$panelsDir = "panels";
$panels = scandir($panelsDir);

//Loop through and load
$column = 1;
$col1 = [];
$col2 = [];
$col3 = [];
for($i = 2; $i < count($panels); $i++) {
    $pfileName = $panelsDir . "\\" . $panels[$i];
    $pfile = fopen($pfileName, "r") or die("Error Loading Panels");
    $json = fread($pfile, filesize($pfileName));
    fclose($pfile);

    $panelInfo = json_decode($json);
    $panelName = $panelInfo->name;
    $panelHTML = $panelInfo->html;
    
    $panel = 
    '<button class="content-panel-header container-fluid" type="button">' .
        $panelName .
    '</button>
    <div class="content-panel-inner">
        <div class="content-panel-content">' . $panelHTML . '</div>
    </div>';

    switch($column) {

        case 1:
            $col1[] = $panel;
            $column = 2;
            break;

        case 2:
            $col2[] = $panel;
            $column = 3;
            break;

        case 3:
            $col3[] = $panel;
            $column = 1;
            break;

    }
}

$col1HTML = '<div class="col-lg-4">';
for($i = 0; $i < count($col1); $i++) {
    $col1HTML .= "<div class=\"container-fluid content-panel-container\">{$col1[$i]}</div>";
}
$col1HTML .= '</div>';

$col2HTML = '<div class="col-lg-4">';
for($i = 0; $i < count($col2); $i++) {
    $col2HTML .= "<div class=\"container-fluid content-panel-container\">{$col2[$i]}</div>";
}
$col2HTML .= '</div>';

$col3HTML = '<div class="col-lg-4">';
for($i = 0; $i < count($col3); $i++) {
    $col3HTML .= "<div class=\"container-fluid content-panel-container\">{$col3[$i]}</div>";
}
$col3HTML .= '</div>';

echo $col1HTML;
echo $col2HTML;
echo $col3HTML;

?>

</div>
</div>
</body>
</html>