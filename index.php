<?php
include "functions.php";
?>

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
<div class="row">
    <div class="col searchbar-container">
        <input class="form-control searchbar" type="text" placeholder="Search..."/>
    </div>
</div>
<div class="row" id="page-container">

<?php

$sqlServer = "localhost";
$username = "root";
$password = "choppywavesinsignificantmonkey-";
$dbName = "dmscreen";

$conn = new mysqli($sqlServer, $username, $password, $dbName);

if($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

//Read panels
$sql = "SELECT * FROM global_panels";
$result = $conn->query($sql);

//Loop through and load
$column = 0;
$cols = [[],[],[]];
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        //Create full panel
        $fullPanel = createPanelHTML($row);

        $cols[$column][] = $fullPanel;
        $column = ($column + 1) % 3;

    }
}

$col1HTML = '<div class="col-lg-4">';
for($i = 0; $i < count($cols[0]); $i++) {
    $col1HTML .= $cols[0][$i];
}
$col1HTML .= '</div>';

$col2HTML = '<div class="col-lg-4">';
for($i = 0; $i < count($cols[1]); $i++) {
    $col2HTML .= $cols[1][$i];
}
$col2HTML .= '</div>';

$col3HTML = '<div class="col-lg-4">';
for($i = 0; $i < count($cols[2]); $i++) {
    $col3HTML .= $cols[2][$i];
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