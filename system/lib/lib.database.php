<?php

require_once CFG_LIB_DIR.'class.query.php';

/**
 * Set table name for sql query. Use table prefix. Not used from version 4.
 *
 * @param $name string table name
 */
function define_db_table($name)
{
    $tmp = strtoupper($name);
    define('DB_TABLE_'.$tmp, CFG_DB_PREFIX.$name);
}

/**
 * Create adodb class, connect to database
 *
 * @param string $server
 * @param string $user_name string
 * @param string $password string
 * @param string $db string database name
 * @param string $db_driver  database driver
 *
 * @return ADOConnection adodb class
 */
function open_database($server = CFG_DB_SERVER,
                       $user_name = CFG_DB_USER,
                       $password = CFG_DB_PASSWORD,
                       $db = CFG_DB_DBNAME,
                       $db_driver=CFG_DB_DRIVER
                      )
{
    $adodb = NewADOConnection($db_driver);
    if (!$adodb)
        die("Connection failed");

    if (!(@$adodb->Connect($server, $user_name, $password, $db)))
        die(iconv(mb_detect_encoding($adodb->ErrorMsg(), mb_detect_order(), true), "UTF-8", $adodb->ErrorMsg()));
    query::$adodb = $adodb;

    // set collation utf8
    $adodb->Execute("SET NAMES 'utf8';");
    $adodb->Execute("SET CHARACTER SET 'utf8';");
    $adodb->Execute("SET SESSION collation_connection = 'utf8_unicode_ci';");

    return $adodb;
}

/**
 * Extract data from table
 *
 * @param $table_name string table name
 * @param $where string sql condition
 * @param $order string sql order
 * @param $limit string sql limit
 *
 * @return array result array
 */
function db_extract($table_name, $where = '', $order = '', $limit = '')
{
    global $adodb;

    $query = "SELECT * FROM `".$table_name."`";

    if ($where != '')
    {
        $query .= ' WHERE '.$where;
    }

    if ($order != '')
    {
        $query .= ' ORDER BY '.$order;
    }

    if ($limit != '')
    {
        $query .= ' LIMIT '.$limit;
    }

    $result = query::exec($query);

    $items = array();
    if ($adodb->ErrorMsg() == '')
    {
        while (!$result->EOF)
        {
            $items[] = $result->fields;
            $result->MoveNext();
        }
    }
    return $items;
}

/**
 * Execute sql query and receive result data.
 *
 * @param $query string sql query
 *
 * @return array result array
 */
function db_query($query)
{
    global $adodb;
    $result = query::exec($query);

    $items = array();
    if ($adodb->ErrorMsg() == '')
    {
        while (!$result->EOF)
        {
            $items[] = $result->fields;
            $result->MoveNext();
        }
    }
    return $items;
}

/**
 * Execute sql query.
 *
 * @param $query string sql query
 *
 * @return ADORecordSet (ADOConnection->Execute)
 */
function db_exec($query)
{
    return query::exec($query);
}

/**
 * Return last db error message.
 *
 * @return string last db error message
 */
function db_last_error()
{
    global $adodb;

    return $adodb->ErrorMsg();
}

/**
 * Return last insert id.
 *
 * @return integer last insert id.
 */
function db_insert_id()
{
    global $adodb;

    return $adodb->Insert_ID();
}

/**
 * Get rows count for table by condition
 *
 * @param $table_name string table name
 * @param $where string sql condition
 *
 * @return int rows count
 */
function db_count($table_name, $where = '')
{
    global $adodb;

    $query = 'SELECT COUNT(*) as `_count_` FROM `'.$table_name.'`';

    if ($where != '')
    {
        $query .= ' WHERE '.$where;
    }

    $result = query::exec($query);

    $count = 0;
    if ($adodb->ErrorMsg() == '')
    {
        if (!$result->EOF)
        {
            $count = $result->fields['_count_'];
        }
    }
    return $count;
}

/**
 * Get mysql version.
 *
 * @return string current mysql version or 0 on failure
 */
function get_mysql_version()
{
    $query = 'SELECT version() AS `_version_`';
    $version = db_query($query);

    if (query::error() == '')
    {
        if (isset($version[0]))
        {
            return $version[0]['_version_'];
        }
    }

    return 0;
}

/**
 * Check database version is correct.
 * @param $version float
 * @return bool TRUE on success or FALSE on failure
 */
function check_db_version($version)
{
    $ver = preg_split("/\.|-/", $version);
    $tmp = $ver[0].sprintf('%02s', $ver[1]).sprintf('%02s', $ver[2]);

    $min_version = preg_split("/\.|-/", CFG_MIN_MYSQL_VERSION);
    $min_tmp = $min_version[0].sprintf('%02s', $min_version[1]).sprintf('%02s', $min_version[2]);

    return ($tmp >= $min_tmp);
}
