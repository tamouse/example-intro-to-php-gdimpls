<?php

/**
 * Database setup and functions
 *
 * The functions do basic CRUD operations mapping PHP arrays to / from MySQL data.
 *
 * The tables are assumed to have an ID column called 'id' that is considered immutable
 * outside the database itself. The data inserted, updated, and retrieved will not
 * include the ID column.
 *
 * Data is passed back and forth using associative arrays where the keys are the column names.
 *
 * Data retrieved will be an array of arrays (even in the case of db_findOne) where the outer array
 * keys are the ID column value.
 *
 * Return values will either be a data array or an errors array:
 *
 * data:
 *   array('data' => array(
 *     table/resource => data retrieved, or id of record created / updated / destroyed
 *     ),
 *     'params' => array of input params + sql statement
 *   )
 *
 * errors:
 *   array('errors' => array(
 *     'errno' => mysqli error number,
 *     'error' => mysqli error message
 *     ),
 *     'params' => array of input params + sql statement
 *   )
 */

/**
 * FUNCTION db_connect - establish a connection to the database
 *
 * @param string $host host name where MySQL is running
 * @param string $user user name to connect to database
 * @param string $pass password to connect to database
 * @param string $name database name
 * @return array
 *
 */
function db_connect($host, $user, $pass, $name) {
    $params = array(
        'host' => $host,
        'user' => $user,
        'pass' => $pass,
        'name' => $name
    );

    $link = mysqli_connect($host, $user, $pass, $name);

    if (!$link) {
        return array(
            'errors' => array(
                'errno' => mysqli_connect_errno(),
                'error' => mysqli_connect_error()
            ),
            'params' => $params
        );
    } else {
        return array(
            'data' => array(
                'connected' => TRUE,
                'link' => $link
            ),
            'params' => $params
        );
    }
}


/**
 * FUNCTION db_insert - insert a new row of data into a table
 *
 * @param mysqli $link
 * @param string $table resource name
 * @param array $data keys are column names, values are new data
 * @return array Example:
 *
 * Example:
 * $result = db_insert($db, 'products', array('company'=>'Starbux', 'type'=>'whole bean coffee', 'roast'=>'dark',
 * 'description'=>'Really, really burnt covfefe'));
 */
function db_insert($link, $table, $data)
{
    $params = [
        'table' => $table,
        'data' => $data
    ];

    $sql = "INSERT INTO `$table` ";
    foreach ($data as $key => $value) {
        $columns[] = '`' . $key . '`';
        if (is_string($value)) {
            $values[] = "'" . $value . "'";
        } else {
            $values[] = $value;
        }
    }

    $sql .= " (" . implode(',', $columns) .") ";
    $sql .= " VALUES (" .implode(', ', $values) .") ;";

    $params['sql'] = $sql;

    $res = mysqli_query($link, $sql);
    if (FALSE === $res) {
        return [
            'errors' => [
                'errno' => mysqli_errno($link),
                'error' => mysqli_errno($link)
            ],
            'params' => $params
        ];
    }

    $new_id = mysqli_insert_id($link);

    $new_res = db_findOne($link, $table, $new_id);
    $new_res['params'] = $params;

    return $new_res;
}


/**
 * FUNCTION db_update - update the data in a row of the table
 *
 * @param mysqli $link
 * @param string $table resource name
 * @param int $id row id
 * @param array $data keys are column names, values are new data
 * @return array Example:
 *
 * Example:
 * $result = db_update($db, 'products', 1, array('roast'=>'light',
 * 'description'=>'nice and mild'));
 */
function db_update($link, $table, $id, $data)
{
    $params = [
        'table' => $table,
        'id' => $id,
        'data' => $data
    ];

    $sql = "UPDATE $table SET ";
    foreach ($data as $key => $value) {
        $set_string = "`$key` = ";
        if (is_string($value)) {
            $set_string .= "'" . $value . "'";
        } else {
            $set_string .= $value;
        }
        $columns[] = $set_string;
    }
    $sql .= implode(', ', $columns);
    $sql .= " WHERE `id` = $id";

    $params['sql'] = $sql;

    $res = mysqli_query($link, $sql);
    if (FALSE === $res) {
        return [
            'errors' => [
                'errno' => mysqli_errno($link),
                'error' => mysqli_error($link)
            ],
            'params' => $params
        ];
    }

    $res = db_findOne($link, $table, $id);
    $res['params'] = $params;
    return $res;
}


/**
 * FUNCTION db_destroy - delete a record from the table
 *
 * @param mysqli $link
 * @param string $table resource name
 * @param int $id row id to delete
 * @return array Example:
 *
 * Example:
 * $result = db_destroy($db, 'products', 1);
 */
function db_destroy($link, $table, $id)
{
    $params = [
        'table' => $table,
        'id' => $id
    ];
    $sql = "DELETE FROM `$table` WHERE `id` = $id;";
    $params['sql'] = $sql;

    $res = mysqli_query($link, $sql);
    if (FALSE === $res) {
        return [
            'errors' => [
                'errno' => mysqli_errno($link),
                'error' => mysqli_error($link)
            ],
            'params' => $params
        ];
    }

    return [
        'data' => [
            $table => [
                $id => null
            ]
        ],
        'params' => $params
    ];

}


/**
 * FUNCTION db_findAll - retrieve a set of records from a table
 *
 * @param mysqli $link
 * @param string $table resource name
 * @param array $columns list of column name to retrieve. Default is all columns.
 * @param int $limit limit the number of rows retrieved. Default is 100.
 * @param int $offset offset record to start with. Default is 0.
 * @return array
 *
 * Example:
 * $products = db_findAll($db, 'products');
 */
function db_findAll($link, $table, $columns = [], $limit = 100, $offset = 0)
{
    $params = [
        'table' => $table,
        'columns' => $columns,
        'limit' => $limit,
        'offset' => $offset
    ];

    $sql = "SELECT ";
    if ($columns) {
        $sql .= 'id, ' . implode(', ', $columns);
    } else {
        $sql .= '*';
    }
    $sql .= " FROM $table ";
    if ($offset) {
        $sql .= " OFFSET $offset ";
    }
    $sql .= " LIMIT $limit;";

    $params['sql'] = $sql;

    $results = _db_execQuery($link, $sql);

    if (!array_key_exists('errors', $results)) {
        $results = array(
            'data' => array(
                $table => $results
            )
        );
    }
    $results['params'] = $params;

    return $results;
}


/**
 * FUNCTION db_findOne - retrieve a single row from a table
 *
 * @param mysqli $link
 * @param string $table resource name
 * @param int $id row id
 * @param array $columns list of column names to retrieve. Default is all columns.
 * @return array
 *
 * Example:
 * $sbux = db_findOne($db, 'products', 1);
 */
function db_findOne($link, $table, $id, $columns = [])
{
    $params = array(
        'table' => $table,
        'id' => $id,
        'columns' => $columns
    );

    $sql = "SELECT ";
    if ($columns) {
        $sql .= 'id, ' . implode(',', $columns);
    } else {
        $sql .= '*';
    }
    $sql .= " FROM $table WHERE id = $id;";

    $params['sql'] = $sql;

    $results = _db_execQuery($link, $sql);

    if (!array_key_exists('errors', $results)) {
        $results = array(
            'params' => $params,
            'data' => array(
                $table => $results
            )
        );
    }
    $results['params'] = $params;
    return $results;
}


/**
 * FUNCTION _db_execQuery - execute a query and return the results
 *
 * @param mysqli $link link to database
 * @param string $sql query to run
 * @return array
 */
function _db_execQuery($link, $sql)
{
    $result = mysqli_query($link, $sql);

    if (FALSE === $result) {
        // woops, major error
        return array(
            'errors' => array(
                'errno' => mysqli_errno($link),
                'error' => mysqli_error($link)
            )
        );
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data_from_row = array();
        foreach ($row as $column => $value) {
            if ($column != 'id') {
                $data_from_row[$column] = $value;
            }
        }
        $data[$row['id']] = $data_from_row;
    }

    return $data;
}