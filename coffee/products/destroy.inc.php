<?php
error_log("----------------------");
error_log("DELETE /products/:id/delete");

if (isset($_POST['commit'])) {
    $id = isset($params['id']) ? intval($params['id']) : null;
    $res = db_destroy($GLOBALS['db']['link'], 'products', $id);
    error_log("result of db_destroy: ".var_export($res, true));
    if (isset($res['errors'])) die("Unable to delete product $id. " . $res['errors']['error']);
    $_SESSION['flash'] = "Product $id Deleted";
    header("Location: /coffee/index.php/products/");
}