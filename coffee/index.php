<?php error_reporting(-1);
require_once 'config.inc.php';
require_once 'db.inc.php';


?><!DOCTYPE html>
<html>
<head>
    <?php include_once './layout/head.inc.php'; ?>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <?php include_once './layout/header.inc.php'; ?>
            <ul>
                <li><a href="./products">Products</a></li>
            </ul>
            <?php include_once './layout/footer.inc.php'; ?>
        </div>
    </div>
</div>
</body>
</html>