<?php
error_reporting(-1); // TURN IT ALL ON

define('PI', 3.14159);
define('COAT_THRESHHOLD', 50);
define('HAT_THRESHHOLD', 30);

function area($radius)
{
    return PI * $radius * $radius;
}

function full_name($first, $last)
{
    return $first . ' ' . $last;
}

function sort_name($first, $last)
{
    return $last . ', ' . $first;
}

function need_coat_p($temp)
{
    if ($temp < COAT_THRESHHOLD) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function need_hat_p($temp)
{
    return $temp < HAT_THRESHHOLD;
}

function what_to_where_p($temp)
{
    if ($temp < 0) {
        return "Stay inside!";
    } elseif ($temp < HAT_THRESHHOLD) {
        return "You need a hat and a coat.";
    } elseif ($temp < COAT_THRESHHOLD) {
        return "You need a coat.";
    } else {
        return "Wear what you want!";
    }
}

function times_array($val)
{
    $row = array();
    for ($i = 0; $i <= 12; $i++) {
        $row[] = $val * $i;
    }
    return $row;
}

function times_table()
{
    $table = [];
    for ($i = 0; $i <= 12; $i++) {
        $table[] = times_array($i);
    }
    return $table;
}

// pizza radius
$z = 6;

// my name
$first_name = "Tamara";
$last_name = "Temple";

// current temperature
$temp = 60;

$friends = array(
    array(
        'name' => 'Tig',
        'food' => 'Greek Yogurt'
    ),
    array(
        'name' => 'Gail',
        'food' => 'chips'
    ),
    array(
        'name' => 'Marina',
        'food' => 'mission figs'
    ),
    array(
        'name' => 'Rallie',
        'food' => 'tacos'
    )
);

?>
<!DOCTYPE html>
<html>
<head>
    <title>First PHP script</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<p>PI * z * z = a: where z is <?php echo $z; ?> is <?php echo area($z); ?> square inch pie.</p>

<p>Welcome, <?php echo full_name($first_name, $last_name); ?></p>
<p>Your sort name is <?php echo sort_name($first_name, $last_name) ?></p>

<h2>Need a coat?</h2>

<p>You <?php echo need_coat_p($temp) ? '' : "don't"; ?> need a coat when it's <?php echo $temp; ?> degrees F</p>

<h2>Hat and Coat, Part 1</h2>

<p>It's <?php echo $temp; ?> out.</p>
<?php if ($temp < 0) { ?>
    <p>Stay inside!</p>
<?php } elseif (need_hat_p($temp)) { ?>
    <p>Wear a hat and a coat.</p>
<?php } elseif (need_coat_p($temp)) { ?>
    <p>Wear a coat.</p>
<?php } else { ?>
    <p>Go outside, wear what you want!</p>
<?php } ?>

<h2>Hat and Coat, Part 2</h2>

<p> It's <?php echo $temp ?>&deg;F. <?php echo what_to_where_p($temp); ?></p>

<h2>9 times table</h2>

<table class="table">
    <thead>
    <tr>
        <th>Multiplicand</th>
        <th>Result</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach (times_array(9) as $mult => $val) { ?>
        <tr>
            <td><?php echo $mult; ?></td>
            <td><?php echo $val; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<h2>Times Tables</h2>

<table class="table">
    <thead>
    <tr>
        <th>&nbsp;</th>
        <?php for ($i = 0; $i <= 12; $i++) { ?>
            <th><?php echo $i; ?></th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach (times_table() as $mult => $row) { ?>
        <tr>
            <td><?php echo $mult; ?></td>
            <?php foreach ($row as $val) { ?>
                <td><?php echo $val; ?></td>
            <?php } ?>
        </tr>
    <?php } ?>
    </tbody>
</table>

<h2>Friends</h2>

<table class="table">
    <thead>
    <tr>
        <th>Name</th>
        <th>Food</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($friends as $friend) { ?>
        <tr>
            <td><?php echo $friend['name']; ?></td>
            <td><?php echo $friend['food']; ?></td>

        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>