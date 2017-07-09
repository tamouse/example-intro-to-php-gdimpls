<?php

function delete_button($action='', $value='', $label='') {
    $template = <<<EOT
<form style="display: inline" action="$action" method="post">
    <input type="hidden" name="commit" value="$value">
    <button type="submit" class="btn btn-danger">
        <i class="glyphicon glyphicon-remove"></i>
        $label
    </button>
</form>
EOT;
    error_log("delete_button template: " . $template);
    return $template;
}




