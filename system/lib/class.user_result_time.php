<?php

/**
 * Specify user_result_time object. User_result_time table.
 */
class user_result_time
{
    /**
     * int user result time id
     */
    var $id;

    /**
     * int user_result id
     */
    var $user_result_id;

    /**
     * int question id
     */
    var $question_id;

    /**
     * string date time begin
     */
    var $time_begin;

    /**
     * string date time end
     */
    var $time_end;
}

/**
 * Add user result time to user_result_time table.
 *
 * @param $p_user_result_id int user_result id
 * @param $p_question_id int question id
 *
 * @return int user_result_time id
 */
function add_user_result_time($p_user_result_id, $p_question_id)
{
    $t_user_result_id = db_prepare_int($p_user_result_id);
    $t_question_id = db_prepare_int($p_question_id);
    $t_time = db_prepare_string(get_utc_time());

    $query = 'INSERT INTO ' . DB_TABLE_USER_RESULT_TIME . '(
		`user_result_id`,
		`question_id`,
		`time_begin`,
		`time_end`)
		VALUES (' . $t_user_result_id . ', ' . $t_question_id . ',' . $t_time . ', ' . $t_time . ')';
    db_exec($query);

    return db_insert_id();
}

/**
 * Update time for user_result_time id.
 *
 * @param $p_user_result_time_id int user_result_time id
 */
function update_time_user_result_time($p_user_result_time_id)
{
    $t_user_result_time_id = db_prepare_int($p_user_result_time_id);
    $t_time_end = db_prepare_string(get_utc_time());
    $query = 'UPDATE ' . DB_TABLE_USER_RESULT_TIME . '
              SET `time_end`=' . $t_time_end . '
              WHERE `id`=' . $t_user_result_time_id;
    db_exec($query);
}

/**
 * Get user_result_test time for user_result id.
 *
 * @param $p_user_result_id int user_result_id
 *
 * @return int user_result time sec on success or 0 on falure
 */
function get_user_result_test_time($p_user_result_id)
{
    $t_user_result_id = db_prepare_int($p_user_result_id);

    /* $query = 'SELECT sum(unix_timestamp(time_end) - unix_timestamp(time_begin)) AS `time`
              FROM '.DB_TABLE_USER_RESULT_TIME.'
              WHERE `user_result_id`='.$t_user_result_id; */

    $query = 'SELECT sum(unix_timestamp(user_result_time_end) - unix_timestamp(user_result_time_begin)) AS `time`
              FROM ' . DB_TABLE_USER_RESULTS . '
              WHERE `id`=' . $t_user_result_id;

    $result = db_query($query);

    if (isset($result[0]['time'])) {
        if ($result[0]['time'] < 0) $result[0]['time'] = 0;
        return $result[0]['time'];
    }

    return 0;
}

/**
 * Delete user_result_time data for user_result id.
 *
 * @param $p_user_result_id int user_result id
 */
function delete_user_result_time_for_user_result_id($p_user_result_id)
{
    $t_user_result_id = db_prepare_int($p_user_result_id);

    $query = 'DELETE FROM ' . DB_TABLE_USER_RESULT_TIME . '
              WHERE `user_result_id`=' . $t_user_result_id;

    db_exec($query);
}

/**
 * Delete user_result_time data for user_results.
 *
 * @param $p_user_result_id_array array of int user_result_id
 * @return ADORecordSet|string
 */
function delete_user_result_times_for_user_results($p_user_result_id_array)
{
    $tmp = "";
    $array = array_values($p_user_result_id_array);
    $size = sizeof($array);

    if ($size == 0)
        return "";

    for ($i = 0; $i < $size - 1; $i++)
        $tmp .= db_prepare_int($array[$i]) . ", ";
    $tmp .= db_prepare_int($array[$size - 1]);

    $query = 'DELETE FROM ' . DB_TABLE_USER_RESULT_TIME .
        ' WHERE `user_result_id` IN (' . $tmp . ')';

    return db_exec($query);
}
