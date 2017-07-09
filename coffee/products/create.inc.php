<?php

if (isset($_POST['commit'])) {
    $data = [];
    foreach (['company', 'type', 'roast', 'description'] as $field) {
        $data[$field] = isset($_POST[$field]) ? $_POST[$field] : null;
    }
    $res = db_insert($GLOBALS['db']['link'], 'products', $data);
    if (isset($res['errors'])) {
        die("Unable to save product. " . $res['errors']['error']);
    }

    error_log("Saved new product: " . var_export($res['data']['products'], true));

    header("Location: /coffee/index.php/products/");
    exit;
}

$form_data = [
    'company'=>null,
    'type'=>null,
    'roast'=>'medium',
    'description'=>null
];

$form_path="/coffee/index.php/products/";
$form_submit_label="Save new product";

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
                    <h2>Create new product</h2>
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