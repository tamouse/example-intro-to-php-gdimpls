<?php

// CRUD for coffee product table

require_once 'db.inc.php';


?>
<!DOCTYPE html>
<html>
<head>
    <title>Coffee Products</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include_once 'bootstrap.inc.php'; ?>
    <link rel="stylesheet" href="coffee.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <h1>Products</h1>

            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>New Product</h2>
                        <form class="form-horizontal" method="POST">
                            <div class="form-group">
                                <label for="company" class="col-xs-3">Company:</label>
                                <div class="col-xs-9"><input type="text" name="company"></div>
                            </div>
                            <div class="form-group">
                                <label for="type" class="col-xs-3">Type:</label>
                                <div class="col-xs-9"><input type="text" name="type"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-3">Roast:</div>
                                <div class="col-xs-9">
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="roast" id="roastLight" value="light" checked>
                                            Light
                                        </label>
                                    </div>
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="roast" id="roastLight" value="medium">
                                            Medium
                                        </label>
                                    </div>
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="roast" id="roastLight" value="dark">
                                            Dark
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Description:</div>
                                    <div class="panel-body">
                                        <textarea name="description" id="description" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-default" type="submit" value="Add a new product">
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>

</html>
