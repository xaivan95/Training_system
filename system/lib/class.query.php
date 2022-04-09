<?php

/**
 * Object for statistic sql query info.
 */
class query
{
    /**
     * int sql query counter
     */
    private static $count = 0;

    /**
     * ADOConnection adodb class object
     */
    public static $adodb = NULL;

    /**
     * int queries timer
     */
    private static $time = 0;

    /**
     * string last error message
     */
    private static $error_msg = '';

    /**
     * string array requests
     */
    private static $requests = '';

    /**
     * Execute sql query.
     *
     * @param $query string sql query
     *
     * @return ADORecordSet result
     */
    public static function exec($query)
    {
        // write sql query to log.
        if (CFG_WRITE_SQL_LOG)
        {
            $user = (isset($_SESSION['user_login']) ? $_SESSION['user_login'] : '---');
            $log = log::get_instance();
            $log->write('sql.log', LOG__INFO, $user, $query);
        }

        query::$count++;
        query::$requests .= '<br>'.$query.'<br>';
        $mtime = explode(' ', microtime() );
        $mtime = $mtime[1] + $mtime[0];
        $result = query::$adodb->Execute($query);
        query::$error_msg = query::$adodb->ErrorMsg();
        if (query::$error_msg != '')
        {
            query::$error_msg .= ' Query: '.$query;
        }
        $timeend = explode(' ', microtime() );
        $timeend = $timeend[1] + $timeend[0];
        $timetotal = $timeend-$mtime;
        query::$time += $timetotal;

        return $result;
    }

    /**
     * Return timer.
     *
     * @return string queries timer
     */
    public static function get_all_time()
    {
        return number_format(query::$time, 4);
    }

    /**
     * Return queries count.
     *
     * @return int queries count.
     */
    public static function get_count()
    {
        return query::$count;
    }

    /**
     * Return last error.
     *
     * @return string last error
     */
    public static function error()
    {
        return query::$error_msg;
    }

    /**
     * Get sql requests.
     *
     * @return string array queries requests.
     */
    public static function get_requests()
    {
        return query::$requests;
    }
}

