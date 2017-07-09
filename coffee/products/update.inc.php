<?php
error_log("--------------------");
error_log("UPDATE /products/:id");

if (isset($_POST['commit'])) {
    $id = isset($params['id']) ? intval($params['id']) : 0;
    $data = [];
    foreach (['company', 'type', 'roast', 'description'] as $field) {
        if (isset($_POST[$field])) {
            $data[$field] = $_POST[$field];
        }
    }
    $res = db_update($GLOBALS['db']['link'], 'products', $id, $data);
    if (isset($res['errors'])) {
        die("Unable to update product. " . $res['errors']['error']);
    }

    error_log("Updated product: " . var_export($res['data']['products'], true));

    header("Location: /coffee/index.php/products/$id");
    exit;
}

$id = isset($params['id']) ? intval($params['id']) : 0;
$res = db_findOne($GLOBALS['db']['link'], 'products', $id);
if (isset($res['errors'])) die("Unable to retrieve product $id. " . $res['errors']['error']);

$ids = array_keys($res['data']['products']);
$product_id = array_pop($ids);
$product_data = $res['data']['products'][$product_id];


error_log("product_id: $product_id");
error_log("product_data: " . var_export($product_data, true));

$form_data = [];
$form_data['id'] = $product_id;
foreach(['company', 'type', 'roast', 'description'] as $key) {
    $form_data[$key] = isset($product_data[$key]) ? $product_data[$key] : null;
}

$form_path="/coffee/index.php/products/$product_id";
$form_submit_label="Save product";

include_once './layout/preamble.inc.php';
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="page-heading">
                    <div class="pull-right">
                        <a href="." class="btn btn-primary">
                            <i class="glyphicons glyphicons-th-list"></i>
                            List Products
                        </a>
                    </div>
                    <h1>Products</h1>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2>Edit product</h2>
                    </div>
                    <div class="panel-body">
                        <?php include_once 'form.inc.php'; ?>
                    </div>
                    <div class="panel-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include_once './layout/postlude.inc.php';