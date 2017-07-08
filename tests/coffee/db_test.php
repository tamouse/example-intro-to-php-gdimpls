<?php
require_once 'simpletest/autorun.php';
require_once 'coffee/db.inc.php';

$dbname = 'test';
$dbuser = 'test';
$dbpass = 'test';
$dbhost = 'localhost';
$dbtable = 'test_table';
$dblink = FALSE;

class TestDBFunctions extends UnitTestCase
{
    function setUp()
    {
        global $dbhost, $dbuser, $dbpass, $dbname, $dbtable, $dblink;
        $res = db_connect($dbhost, $dbuser, $dbpass, $dbname);
        if (array_key_exists('errors', $res)) {
            die("could not connect to $dbname: " . $res['errors']['errno'] . ': ' . $res['errors']['error']);
        }

        $this->assertTrue(array_key_exists('data', $res), "connect to $dbname");
        $this->assertTrue(array_key_exists('link', $res['data']), "link established");
        $dblink = $res['data']['link'];

        $drop_sql = "DROP TABLE IF EXISTS `$dbtable`;";
        $res = mysqli_query($dblink, $drop_sql);
        if (!$res) {
            die("could not drop $dbtable: " . mysqli_error($dblink));
        }

        $create_sql = "CREATE TABLE `$dbname`.`$dbtable` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(50) NULL , `quantity` INT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        $res = mysqli_query($dblink, $create_sql);
        if (!$res) {
            die("could not create $dbtable: " . mysqli_error($dblink));
        }
    }

    function tearDown()
    {
        global $dblink, $dbtable;
        $drop_sql = "DROP TABLE IF EXISTS `$dbtable`;";
        $res = mysqli_query($dblink, $drop_sql);
        $this->assertTrue($res, "table $dbtable is dropped");

        $res = mysqli_close($dblink);
        $this->assertTrue($res, 'database is closed');
    }

    function testDbFindOne()
    {
        global $dblink, $dbtable;
        $sql = "INSERT INTO $dbtable (`name`, `quantity`) VALUES ('blah blah blah', 7)";
        $res = mysqli_query($dblink, $sql);
        $this->assertTrue($res, 'insert worked');
        $new_id = mysqli_insert_id($dblink);

        $res = db_findOne($dblink, $dbtable, $new_id);

        $this->assertTrue(array_key_exists('data', $res), "retrieved some data");
        $this->assertTrue(array_key_exists($dbtable, $res['data']), "resource returned");
        $this->assertEqual(count($res['data'][$dbtable]), 1, "one row returned");
        $this->assertTrue(array_key_exists($new_id, $res['data'][$dbtable]), "row id returned");
        $this->assertFalse(array_key_exists('id', $res['data'][$dbtable][$new_id]), "no id column returned in row");
        $this->assertTrue(array_key_exists('name', $res['data'][$dbtable][$new_id]), "name column returned");
        $this->assertTrue(array_key_exists('quantity', $res['data'][$dbtable][$new_id]), "quantity column returned");

        $this->assertEqual($res['data'][$dbtable][$new_id]['name'], 'blah blah blah', "returned correct name data");
        $this->assertEqual($res['data'][$dbtable][$new_id]['quantity'], 7, "returned correct quantity data");
    }

    function testDbFindOneNotFound()
    {
        global $dblink, $dbtable;
        $res = db_findOne($dblink, $dbtable, 999);
        $this->assertTrue(array_key_exists('data', $res), "retrieved some data. " . var_export($res, true));
        $this->assertTrue(array_key_exists($dbtable, $res['data']),
            "resource returned. " . var_export($res, true));
        $this->assertEqual(count($res['data'][$dbtable]), 0,
            "no rows returned. " . var_export($res, true));
    }

    function testDbFindAll()
    {
        global $dblink, $dbtable;
        $sql = "INSERT INTO $dbtable (`name`, `quantity`) VALUES ('one', 1),('two', 2),('three', 3)";
        $res = mysqli_query($dblink, $sql);
        $this->assertTrue($res, 'insert worked');

        $res = db_findAll($dblink, $dbtable);

        $this->assertTrue(array_key_exists('data', $res), "retrieved some data");
        $this->assertTrue(array_key_exists($dbtable, $res['data']), "resource returned");
        $this->assertEqual(count($res['data'][$dbtable]), 3, "three rows returned");

        foreach ($res['data'][$dbtable] as $id => $row) {
            $this->assertFalse(array_key_exists('id', $row), "no id column returned in row");
            $this->assertTrue(array_key_exists('name', $row), "name column returned");
            $this->assertTrue(array_key_exists('quantity', $row), "quantity column returned");
        }
    }

    function testDbInsert()
    {
        global $dblink, $dbtable;
        $sql = "SELECT COUNT(`id`) as num_rows FROM `$dbtable`;";
        $res = mysqli_query($dblink, $sql);
        if (!$res) {
            die("failed to count rows: " . mysqli_error($dblink));
        }
        $this->assertEqual(mysqli_fetch_assoc($res)['num_rows'], 0, "the table is empty");

        $data = [
            'name' => 'a new record',
            'quantity' => 42
        ];
        $res = db_insert($dblink, $dbtable, $data);

        $this->assertFalse(array_key_exists('errors', $res));
        $this->assertTrue(array_key_exists('data', $res));
        $this->assertTrue(array_key_exists($dbtable, $res['data']));
        $this->assertEqual(count($res['data'][$dbtable]), 1);

        $keys = array_keys($res['data'][$dbtable]);
        $id = array_pop($keys);

        $new_res = db_findOne($dblink, $dbtable, $id);

        $this->assertTrue(array_key_exists('data', $new_res));
        $this->assertTrue(array_key_exists($dbtable, $new_res['data']));
        $this->assertTrue(array_key_exists($id, $new_res['data'][$dbtable]), var_export($res, true));
        $this->assertTrue(array_key_exists('name', $new_res['data'][$dbtable][$id]));
        $this->assertEqual($new_res['data'][$dbtable][$id]['name'], 'a new record');
        $this->assertTrue(array_key_exists('quantity', $new_res['data'][$dbtable][$id]));
        $this->assertEqual($new_res['data'][$dbtable][$id]['quantity'], 42);
    }

    function testDbUpdate()
    {
        global $dblink, $dbtable;
        $sql = "INSERT INTO $dbtable (`name`, `quantity`) VALUES ('old name', 1);";
        $res = mysqli_query($dblink, $sql);
        if (!$res) {
            die("failed to insert data" . mysqli_error($dblink));
        }
        $id = mysqli_insert_id($dblink);
        $res = db_findOne($dblink, $dbtable, $id);
        if (array_key_exists('errors', $res)) {
            die("could not retrieve row just inserted: " . var_export($res, true));
        }
        $this->assertTrue(array_key_exists($id, $res['data'][$dbtable]));
        $old_row = $res['data'][$dbtable][$id];

        $new_data = [
            'name' => 'new name'
        ];

        $res = db_update($dblink, $dbtable, $id, $new_data);
        if (array_key_exists('errors', $res)) {
            die("could not update row: " . var_export($res, true));
        }
        $this->assertTrue(array_key_exists('data', $res),
            "data block returned. " . var_export($res, true));
        $this->assertTrue(array_key_exists($dbtable, $res['data']),
            "resource returned. " . var_export($res, true));
        $this->assertTrue(array_key_exists($id, $res['data'][$dbtable]),
            "row returned. " . var_export($res, true));
        $this->assertTrue(array_key_exists('name', $res['data'][$dbtable][$id]),
            "name returned. " . var_export($res, true));
        $this->assertTrue(array_key_exists('quantity', $res['data'][$dbtable][$id]),
            "quantity returned. " . var_export($res, true));
        $this->assertFalse(array_key_exists('id', $res['data'][$dbtable][$id]),
            "id is not returned. " . var_export($res, true));

        $this->assertEqual($res['data'][$dbtable][$id]['name'], 'new name',
            "name is changed. " . var_export($res, true));
        $this->assertEqual($res['data'][$dbtable][$id]['quantity'], 1,
            "quantity is unchanged. " . var_export($res, true));
    }

    function testDbDestroy()
    {
        global $dblink, $dbtable;
        $sql = "INSERT INTO $dbtable (`name`, `quantity`) VALUES ('one', 1);";
        $res = mysqli_query($dblink, $sql);
        if (!$res) {
            die("failed to insert data. " . mysqli_error($dblink));
        }
        $id = mysqli_insert_id($dblink);
        $res = db_findOne($dblink, $dbtable, $id);
        if (array_key_exists('errors', $res)) {
            die("could not retrieve data for $id. " . var_export($res, true));
        }

        $res = db_destroy($dblink, $dbtable, $id);
        if (array_key_exists('errors', $res)) {
            die("failure destorying $id. " . var_export($res, true));
        }
        $this->assertTrue(array_key_exists('data', $res),
            "data block returned. " . var_export($res, true));
        $this->assertTrue(array_key_exists($dbtable, $res['data']),
            "resource returned. " . var_export($res, true));
        $this->assertTrue(array_key_exists($id, $res['data'][$dbtable]),
            "id record in resource. " . var_export($res, true));
        $this->assertEqual($res['data'][$dbtable][$id], null,
            "row is null. " . var_export($res, true));

        $res = db_findOne($dblink, $dbtable, $id);
        if (array_key_exists('errors', $res)) {
            die("failure to query $id. " . var_export($res, true));
        }

        $this->assertTrue(array_key_exists('data', $res));
        $this->assertTrue(array_key_exists($dbtable, $res['data']));
        $this->assertEqual(count($res['data'][$dbtable]), 0);
    }
}