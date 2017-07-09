<form class="form-horizontal" method="POST" action="<?php echo $form_path; ?>">
    <?php if (isset($form_data['id'])) {?><input type="hidden" name="id" value="<?php echo $form_data['id'] ?>"><?php } ?>
    <div class="form-group">
        <label for="company" class="col-xs-3">Company:</label>
        <div class="col-xs-9"><input type="text" name="company" value="<?php echo $form_data['company']; ?>"></div>
    </div>
    <div class="form-group">
        <label for="type" class="col-xs-3">Type:</label>
        <div class="col-xs-9"><input type="text" name="type" value="<?php echo $form_data['type']; ?>"></div>
    </div>
    <div class="form-group">
        <div class="col-xs-3">Roast:</div>
        <div class="col-xs-9">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="roast" id="roastLight" value="light"
                        <?php if ($form_data['roast'] == 'light') echo "checked" ?>>
                    Light
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="roast" id="roastLight" value="medium"
                        <?php if ($form_data['roast'] == 'medium') echo "checked" ?>>
                    Medium
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="roast" id="roastLight" value="dark"
                        <?php if ($form_data['roast'] == 'dark') echo "checked" ?>>
                    Dark
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">Description:</div>
            <div class="panel-body">
                <textarea name="description" id="description" rows="3" class="form-control"><?php echo $form_data['description']; ?></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-3 col-xs-9">
            <input class="btn btn-success" type="submit" name="commit" value="<?php echo $form_submit_label; ?>">
        </div>
    </div>
</form>
