<?php error_reporting(-1);
error_log("=============================================================");
error_log("Top of index.php");

session_start();
$flash = null;
$redirected = null;

if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
if (isset($_SESSION['redirected'])) {
    $redirected = $_SESSION['redirected'];
    unset($_SESSION['redirected']);
}

require_once 'config.inc.php';
require_once 'secrets.inc.php';
require_once 'db.inc.php';
require_once 'routes.php';

$res = db_connect(
    $GLOBALS['db']['host'],
    $GLOBALS['db']['user'],
    $GLOBALS['db']['pass'],
    $GLOBALS['db']['name']
);
if (isset($res['errors'])) {
    die("Could not connect to database. " . $res['errors']['error']);
}
$GLOBALS['db']['link'] = $res['data']['link'];

$path = '/';
$method = 'get';

if (! $redirected) {
    $redirected = false;
    if (isset($_SERVER['PATH_INFO'])) $path = $_SERVER['PATH_INFO'];
    if (isset($_SERVER['REQUEST_METHOD'])) $method = strtolower($_SERVER['REQUEST_METHOD']);
}

error_log("index.php: path: $path");
error_log("index.php: method: $method");


if (! $route = $GLOBALS['routes']->get_route($method, $path)) {
    $_SESSION['flash'] = "Unknown route $path";
    $_SESSION['redirected'] = true;
    header("Location: /coffee/index.php/");
    exit;
}

$params = $route['params'];
$component = $route['component'];

error_log("params: " . var_export($params, true));
error_log("component: $component");

include_once $component;