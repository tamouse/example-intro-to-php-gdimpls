<!DOCTYPE html>
<html>
<head>
    <?php include_once './layout/head.inc.php'; ?>
</head>
<body>
<?php include_once './layout/header.inc.php'; ?>
<?php if (isset($flash)) { ?>
    <div class="container">
        <div class="row">
            <div class="alert alert-info"><?php echo $flash; ?></div>
        </div>
    </div>
<?php }