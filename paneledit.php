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
    <script src="scripts/paneledit.js"></script>

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
<div class="container">
    <input id="panel-id-input" class="form-control searchbar" type="text" placeholder="panel id"/>
    <div class="row">
        <div id="panel-preview" style="margin-top: 12px;" class="col-8">
            
        </div>
        <div style="margin-top: 12px;" class="col-4">
            <div>
                <div class="content-panel-header container-fluid">
                    Tags
                </div>
                <div class="content-panel-inner">
                    <div class="content-panel-content">
                        <input id="tag-input" class="form-control inputbar" style="font-size: 16px;" type="text" placeholder="add tag"/>
                        <div id="tags-list" style="margin-top: 12px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
</body>
</html>