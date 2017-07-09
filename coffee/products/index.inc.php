<?php
$res = db_findAll($GLOBALS['db']['link'], 'products');
if (isset($res['errors'])) die("Could not retrieve products. " . $res['errors']['error']);

$products = $res['data']['products'];
include_once './helpers/delete_button.inc.php';
include_once './layout/preamble.inc.php';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-heading">
                <div class="pull-right">
                    <a href="./new" class="btn btn-success">
                        <i class="glyphicons glyphicons-pencil"></i>
                        Add Product
                    </a>
                </div>
                <h1>Products</h1>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>List of products</h2>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Roast</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (count($products) > 0) { ?>
                            <?php foreach ($products as $id => $row) { ?>
                                <tr id="product-<?php echo $id; ?>">
                                    <td><?php echo $row['company']; ?></td>
                                    <td><?php echo $row['type']; ?></td>
                                    <td><?php echo $row['roast']; ?></td>
                                    <td>
                                        <a href="./<?php echo $id; ?>" class="btn btn-sm btn-primary"><i
                                                    class="glyphicon glyphicon-expand" style="font-size: 1.5em"></i></a>
                                        <a href="./<?php echo $id; ?>/edit" class="btn btn-sm btn-info"><i
                                                    class="glyphicon glyphicon-edit" style="font-size: 1.5em"></i></a>
                                        <?php echo delete_button("./$id/delete", $id, ""); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4">No products found</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once './layout/postlude.inc.php';
