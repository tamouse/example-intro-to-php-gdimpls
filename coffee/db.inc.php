<?php

// Database connection
$db_host = 'localhost';
$db_name = 'coffee';
$db_user = 'coffee';
$db_pass = 'coffee';

$db = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$db) {
    echo '<p class="error">Unable to connect to database ' . $db_name . '. ';
    echo "(" . mysqli_connect_errno() . ") " . mysqli_connect_error() . '</p>' . PHP_EOL;
    exit;
}