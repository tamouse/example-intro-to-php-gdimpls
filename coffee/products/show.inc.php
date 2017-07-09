<?php
if (isset($params['id'])) $id = intval($params['id']);
else {
    $_SESSION['flash'] = "No params['id']";
    header("Location: /coffee/index.php");
    exit;
}

$res = db_findOne($GLOBALS['db']['link'], 'products', $id);
if (isset($res['errors'])) die("Query failed. " . $res['errors']['error']);

$ids = array_keys($res['data']['products']);
$product_id = array_pop($ids);
$product_data = array_pop($res['data']['products']);

error_log("product_id: $product_id");
error_log("product_data: " . var_export($product_data, true));

include_once './helpers/delete_button.inc.php';
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
                    <a href="./new" class="btn btn-success">
                        <i class="glyphicons glyphicons-pencil"></i>
                        New Product
                    </a>
                </div>
                <h1>Products</h1>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>Product Detail</h2>
                </div>
                <div class="panel-body">
                    <?php if ($product_data) { ?>
                        <div class="row">
                            <div class="col-xs-4 text-right">
                                <strong>Company:</strong>
                            </div>
                            <div class="col-xs-8">
                                <?php echo isset($product_data['company']) ? $product_data['company'] : '&mdash;'; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 text-right">
                                <strong>Type:</strong>
                            </div>
                            <div class="col-xs-8">
                                <?php echo isset($product_data['type']) ? $product_data['type'] : '&mdash;'; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 text-right">
                                <strong>Roast:</strong>
                            </div>
                            <div class="col-xs-8">
                                <?php echo isset($product_data['roast']) ? $product_data['roast'] : '&mdash;'; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 text-right">
                                <strong>Description:</strong>
                            </div>
                            <div class="col-xs-8">
                                <?php echo isset($product_data['description']) ? $product_data['description'] : '&mdash;'; ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <em>Product not found.</em>
                            </div>
                        </div>
                    <?php } ?>

                </div>
                <div class="panel-footer">
                    <a href="./<?php echo $product_id; ?>/edit"
                       class="btn <?php echo $product_data ? "btn-info" : "btn-default"; ?>"
                        <?php if (!$product_data) echo "disabled"; ?>>
                        <i class="glyphicon glyphicon-pencil"></i>
                        Edit Product
                    </a>
                    <?php echo delete_button("./$product_id/delete", $product_id, 'Delete Product'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once './layout/postlude.inc.php';