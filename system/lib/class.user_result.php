<?php

/**
 * Specify user_result object. User_results table.
 */
class user_result
{
  /**
   * int user_result id
   */
  var $id;

  /**
   * string group
   */
  var $group;

  /**
   * string user
   */
  var $user;

  /**
   * int test id
   */
  var $test;

  /**
   * int (0, 1) is completed
   */
  var $completed;

  /**
   * string results
   */
  var $results;

  /**
   * string begin time
   */
  var $time_begin;

  /**
   * string end time
   */
  var $time_end;

  /**
   * int is test out of time
   */
  var $out_of_time;

  /**
   * int completed questions
   */
  var $completed_questions;

  /**
   * int right questions
   */
  var $right_questions;

  /**
   * int score
   */
  var $score;

  /**
   * float percent right answers
   */
  var $percent_right;

  /**
   * int total questions
   */
  var $total_questions;

  /**
   * string test title
   */
  var $test_title;

  /**
   * string test css
   */
  var $test_css;

  /**
   * string test data
   */
  var $test_data;

  /**
   * string test <HEADER> section
   */
  var $test_html_header;

  /**
   * string IP address
   */
  var $ip;
}

/**
 * Add new user result.
 *
 * @param $p_user_id int user id
 * @param $p_questions_count int questions count
 * @param $p_test_id int test id*
 * @param $p_test_data
 * @return int user_result id
 */
function add_user_result($p_user_id, $p_questions_count, $p_test_id, $p_test_data)
{
  global $WEB_APP;

  $test = get_test($p_test_id);
  $t_user_id = db_prepare_int($p_user_id);
  $t_test_id = db_prepare_int($p_test_id);
  $t_questions_count = db_prepare_int($p_questions_count);
  $t_test_name = db_prepare_string($test->name);
  $t_test_css = db_prepare_string($test->css);
  $t_test_data = db_prepare_string($p_test_data);
  $t_time = db_prepare_string(gmdate('Y-m-d H:i:s'));
  $t_test_html_header = db_prepare_string($test->html_header);
  if ($WEB_APP['settings']['tst_collect_ip'] == 1) $t_ip = db_prepare_string(GetIP()); else $t_ip =
    db_prepare_string('');

  $query = "INSERT INTO " . DB_TABLE_USER_RESULTS . "(
        `user_result_user_id`,
        `user_result_test_id`,
        `user_result_completed`,
        `user_result_results`,
        `user_result_time_begin`,
        `user_result_time_end`,
        `user_result_completed_questions`,
        `user_result_righ_questions`,
        `user_result_score`,
        `user_result_percent_right`,
        `user_result_total_questions`,
        `user_result_test_title`,
		    `user_result_test_css`,
        `user_result_test_data`,
        `user_result_html_header`,
        `user_result_ip`)
        VALUES ($t_user_id, $t_test_id, 0, '', $t_time, $t_time, 0, 0, 0, 0,
        $t_questions_count, $t_test_name,  $t_test_css,  $t_test_data, $t_test_html_header, $t_ip)";
  db_exec($query);

  return db_insert_id();
}

/**
 * @param $p_user_id int
 * @return array of int
 */
function get_user_all_results_id($p_user_id)
{
  $t_user_id = db_prepare_int($p_user_id);
  $query = "SELECT id FROM " . DB_TABLE_USER_RESULTS . " WHERE  `user_result_user_id`=$t_user_id";
  $res_array = db_query($query);
  $result_array = array();
  foreach ($res_array as $res_id) {
    $result_array[] = $res_id[0];
  }
  return $result_array;
}

/**
 * Get user_results.
 *
 * @param $p_user_id int user id
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter user_result_filter
 *
 * @return array user_results array
 */
function get_user_results($p_user_id, $p_sort_field = "id", $p_sort_order = "ASC", $p_page = 1, $p_count = 0,
                          $p_filter = NULL)
{
  global $WEB_APP;

  $t_user_id = db_prepare_int($p_user_id);
  $t_admset_dateformat = db_prepare_string($WEB_APP['settings']['admset_dateformat']);
  $t_precision = db_prepare_string($WEB_APP['settings']['admset_percprecision']);
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field,
    array('id', 'user_result_user_id', 'user_result_test_id', 'user_result_completed', 'user_result_results',
      'time_begin', 'time_end', 'user_result_completed_questions', 'user_result_righ_questions', 'user_result_score',
      'user_result_percent_right', 'user_result_total_questions', 'user_result_test_title', 'user_result_ip'))) {
    $t_sort_field = 'id';
  }

  if ($t_sort_field == 'time_begin') {
    $t_sort_field = 'user_result_time_begin';
  }

  if ($t_sort_field == 'time_end') {
    $t_sort_field = 'user_result_time_end';
  }

  $order_str = "`$t_sort_field` $t_sort_order";

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "LIMIT $limit, $t_count";
  } else {
    $limit_str = '';
  }
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  $t_timezone = db_prepare_string(date('P'));

  $query = 'SELECT `id`,
            `user_result_user_id`,
            `user_result_test_id`,
            `user_result_completed`,
            `user_result_results`,
            DATE_FORMAT(CONVERT_TZ(`user_result_time_begin`, \'+0:00\', ' . $t_timezone . '), ' . $t_admset_dateformat . ') AS `time_begin`,
            CONVERT_TZ(`user_result_time_begin`, \'+0:00\', ' . $t_timezone . ') AS `user_result_time_begin`,
            DATE_FORMAT(CONVERT_TZ(`user_result_time_end`, \'+0:00\', ' . $t_timezone . '), ' . $t_admset_dateformat . ') AS `time_end`,
            CONVERT_TZ(`user_result_time_end`, \'+0:00\', ' . $t_timezone . ') AS `user_result_time_end`,
            `user_result_completed_questions`,
            `user_result_righ_questions`,
            `user_result_score`,
            truncate(user_result_percent_right, ' . $t_precision . ') as user_result_percent_right,
            `user_result_total_questions`,
            `user_result_test_title`,
			      `user_result_test_css`,
			      `user_result_html_header`,
			      `user_result_ip`
        FROM    ' . DB_TABLE_USER_RESULTS . ' WHERE     `user_result_user_id` = ' . $t_user_id .
    ' AND `user_result_completed` = 1 ' . $tmp . '
            ORDER BY ' . $order_str . ' ' . $limit_str;

  return db_query($query);
}

/**
 * Edit user result.
 *
 * @param $p_user_result user_result object
 */
function edit_user_result($p_user_result)
{
  $t_completed = db_prepare_int($p_user_result->completed);
  $t_completed_questions = db_prepare_int($p_user_result->completed_questions);
  $t_id = db_prepare_int($p_user_result->id);
  $t_percent_right = db_prepare_string($p_user_result->percent_right);
  $t_results = db_prepare_string($p_user_result->results);
  $t_score = db_prepare_float($p_user_result->score);
  $t_right_questions = db_prepare_int($p_user_result->right_questions);
  $t_test = db_prepare_int($p_user_result->test);
  $t_test_title = db_prepare_string($p_user_result->test_title);
  $t_time_end = db_prepare_string($p_user_result->time_end);
  $t_out_of_time = db_prepare_int($p_user_result->out_of_time);
  $t_total_questions = db_prepare_int($p_user_result->total_questions);
  $t_user = db_prepare_int($p_user_result->user);
  $t_test_data = db_prepare_string($p_user_result->test_data);
  $t_test_html_header = db_prepare_string($p_user_result->test_html_header);

  $query = "UPDATE " . DB_TABLE_USER_RESULTS . " SET
        `user_result_user_id` =  $t_user,
        `user_result_test_id` =  $t_test,
        `user_result_completed` = $t_completed,
        `user_result_results` =  $t_results,
        `user_result_time_end` = $t_time_end,
        `user_result_out_of_time` = $t_out_of_time,
        `user_result_completed_questions` = $t_completed_questions,
        `user_result_righ_questions` = $t_right_questions,
        `user_result_score` = $t_score,
        `user_result_percent_right` = $t_percent_right,
        `user_result_total_questions` = $t_total_questions,
        `user_result_test_title` = $t_test_title,
        `user_result_test_data` = $t_test_data,
        `user_result_html_header` = $t_test_html_header
        WHERE   `id` = $t_id";

  db_exec($query);
}

/**
 * Get user result.
 *
 * @param integer $p_result_id user_result id
 *
 * @return user_result object
 */
function get_user_result($p_result_id)
{
  global $adodb;
  global $WEB_APP;
  $admset_percprecision = $WEB_APP['settings']['admset_percprecision'];
  $t_result_id = db_prepare_int($p_result_id);

  $t_admset_dateformat = db_prepare_string($WEB_APP['settings']['admset_dateformat']);
  $t_timezone = db_prepare_string(date('P'));

  $query = 'SELECT   `id`,
            `user_result_user_id`,
            `user_result_test_id`,
            `user_result_completed`,
            `user_result_results`,
            DATE_FORMAT(CONVERT_TZ(`user_result_time_begin`, \'+00:00\', ' . $t_timezone . '), ' .
    $t_admset_dateformat . ') AS `time_begin`,
            CONVERT_TZ(`user_result_time_begin`, \'+0:00\', ' . $t_timezone . ') AS `user_result_time_begin`,
            DATE_FORMAT(CONVERT_TZ(`user_result_time_end`, \'+00:00\', ' . $t_timezone . '), ' . $t_admset_dateformat . ') AS `time_end`,
            CONVERT_TZ(`user_result_time_end`, \'+0:00\', ' . $t_timezone . ') AS `user_result_time_end`,
            `user_result_out_of_time`,
            `user_result_completed_questions`,
            `user_result_righ_questions`,
            `user_result_score`,
            `user_result_percent_right`,
            `user_result_total_questions`,
            `user_result_test_title`,
			      `user_result_test_css`,
            `user_result_test_data`,
            `user_result_html_header`,
            `user_result_ip`
        FROM    ' . DB_TABLE_USER_RESULTS . ' WHERE     `id` = ' . $t_result_id . '
        LIMIT   1';
  $result = $adodb->Execute($query);

  $user_result = new user_result();

  if (!$result->EOF) {
    $user_result->id = $result->fields['id'];
    $user_result->user = $result->fields['user_result_user_id'];
    $user_result->test = $result->fields['user_result_test_id'];
    $user_result->completed = $result->fields['user_result_completed'];
    $user_result->results = $result->fields['user_result_results'];
    $user_result->time_begin = $result->fields['user_result_time_begin'];
    $user_result->time_end = $result->fields['user_result_time_end'];
    $user_result->out_of_time = $result->fields['user_result_out_of_time'];
    $user_result->completed_questions = $result->fields['user_result_completed_questions'];
    $user_result->right_questions = $result->fields['user_result_righ_questions'];
    $user_result->score = $result->fields['user_result_score'];
    $user_result->percent_right = round($result->fields['user_result_percent_right'], $admset_percprecision);
    $user_result->total_questions = $result->fields['user_result_total_questions'];
    $user_result->test_title = $result->fields['user_result_test_title'];
    $user_result->test_css = $result->fields['user_result_test_css'];
    $user_result->test_data = $result->fields['user_result_test_data'];
    $user_result->test_html_header = $result->fields['user_result_html_header'];
    $user_result->ip = $result->fields['user_result_ip'];
  }

  return $user_result;
}

/**
 * Get test time for user_result id.
 *
 * @param $p_result_id int user_result id
 *
 * @return string test time
 */
function get_test_time_for_user_result_id($p_result_id)
{
  $t_result_id = db_prepare_int($p_result_id);

  $query = 'SELECT    SEC_TO_TIME(TIME_TO_SEC(`user_result_time_end`)-
        TIME_TO_SEC(`user_result_time_begin`))
        AS  `test_time`
        FROM    ' . DB_TABLE_USER_RESULTS . ' WHERE     `id` = ' . $t_result_id;

  $result = db_query($query);

  return isset($result[0]['test_time']) ? $result[0]['test_time'] : 0;
}


/**
 * @return array of int
 */
function get_results_id_for_not_existing_users()
{
  $query = "SELECT id FROM " . DB_TABLE_USER_RESULTS . "
            WHERE user_result_user_id NOT IN (SELECT id FROM " . DB_TABLE_USER . ")";
  $res_array = db_query($query);
  $result_array = array();
  foreach ($res_array as $res_id) {
    $result_array[] = $res_id[0];
  }
  return $result_array;
}

/**
 * Delete user result.
 *
 * @param $p_result_id int user_result id
 */
function delete_user_result($p_result_id)
{
  delete_user_answers_for_user_result($p_result_id);
  delete_user_result_themes_for_user_result($p_result_id);
  delete_user_result_time_for_user_result_id($p_result_id);

  $t_result_id = db_prepare_int($p_result_id);

  $query = 'DELETE FROM ' . DB_TABLE_USER_RESULTS . ' WHERE `user_result_id` = ' . $t_result_id;

  db_query($query);
}

/**
 * Delete user results for user_result id array.
 *
 * @param $p_user_result_id_array array of int user_result id array
 * @param bool $p_delete_results boolean
 * @return ADORecordSet|string|boolean
 */
function delete_user_results($p_user_result_id_array, $p_delete_results = TRUE)
{
  delete_user_answers_for_user_results($p_user_result_id_array);
  delete_user_records_for_user_results($p_user_result_id_array);
  if ($p_delete_results == TRUE) {
    delete_user_result_themes_for_user_results($p_user_result_id_array);
    $tmp = "";
    $array = array_values($p_user_result_id_array);
    $size = sizeof($array);
    if ($size == 0) return "";
    for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ", ";
    $tmp .= db_prepare_int($array[$size - 1]);
    $query = 'DELETE FROM ' . DB_TABLE_USER_RESULTS . ' WHERE `id` IN (' . $tmp . ')';
    return db_exec($query);
  } else
    return TRUE;
}

/**
 * Get groups report.
 *
 * @param $p_group_array array of int group id array
 * @param $p_test_array array of int test id array
 * @param $p_last_result bool show only last result
 * @param $p_testing_period bool show only for testing period
 * @param $p_testing_period_from string datetime testing period from
 * @param $p_testing_period_to string datetime testing period to
 * @param $p_scores bool show only for scores
 * @param $p_scores_from int scores from
 * @param $p_scores_to int scores to
 * @param $p_testing_time
 * @param $p_sort_field string sort field
 * @param $p_sort_order string ('ASC', 'DESC') sort order
 * @param $p_page int page number
 * @param $p_count int items on a page
 *
 * @param bool $is_average
 * @return array results array
 */
function get_groups_report($p_group_array, $p_test_array, $p_last_result, $p_testing_period, $p_testing_period_from,
                           $p_testing_period_to, $p_scores, $p_scores_from, $p_scores_to, $p_testing_time,
                           $p_sort_field = "id", $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0,
                           $is_average = FALSE)
{
  global $WEB_APP;

  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  $t_admset_dateformat = db_prepare_string($WEB_APP['settings']['admset_dateformat']);

  $tmp_groups = "";
  $array = array_values($p_group_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp_groups .= db_prepare_int($array[$i]) . ", ";
  $tmp_groups .= db_prepare_int($array[$size - 1]);

  $tmp_tests = "";
  $array = array_values($p_test_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp_tests .= db_prepare_int($array[$i]) . ", ";
  $tmp_tests .= db_prepare_int($array[$size - 1]);

  $t_testing_period_from = db_escape_string($p_testing_period_from);
  $t_testing_period_to = db_escape_string($p_testing_period_to);

  $t_scores_from = db_prepare_string($p_scores_from);
  $t_scores_to = db_prepare_string($p_scores_to);
  $t_timezone = db_prepare_string(date('P'));

  if ($p_testing_period) {
    $testing_period_query =
      ' CONVERT_TZ(`ur`.`user_result_time_begin`, \'+0:00\', ' . $t_timezone . ') >= "' . $t_testing_period_from . ' 00:00:00"
                                AND CONVERT_TZ(`ur`.`user_result_time_begin`,\'+0:00\', ' . $t_timezone . ') <= "' .
      $t_testing_period_to . ' 23:59:59"';
  } else {
    $testing_period_query = '';
  }

  if ($p_scores) {
    $tmp = ($testing_period_query != '') ? ' AND ' : '';
    $scores_query = $tmp . '`ur`.`user_result_score` >= ' . $t_scores_from . '
                         AND `ur`.`user_result_score` <= ' . $t_scores_to;
  } else {
    $scores_query = '';
  }

  if ($p_testing_time) {
    $testing_time_query_time = 'TIMEDIFF(`ur`.`user_result_time_end`,`ur`.`user_result_time_begin`) AS `test_time`, ';
  } else {
    $testing_time_query_time = '';
  }
  $testing_time_query_join = '';

  if ($p_last_result) {
    $last_result_query_select = 'MAX(`ur`.`id`)';
    $last_result_query_group = ' GROUP BY `ur`.`user_result_user_id` ';
  } else {
    $last_result_query_select = '`ur`.`id`';
    $last_result_query_group = '';
  }
  $add_and = (($testing_period_query == '') && ($scores_query == '')) ? '' : ' AND ';


  if ($is_average == TRUE) {
    $query_average = 'SELECT AVG (`ur`.`user_result_righ_questions`) AS righ_questions, AVG (`ur`.`user_result_score`) AS result_score, AVG(`ur`.`user_result_percent_right`) AS percent_right
              FROM ' . DB_TABLE_USER_RESULTS . ' `ur`
              ' . $testing_time_query_join . '
              INNER JOIN ' . DB_TABLE_USER . ' `u`
                ON `u`.`id`=`ur`.`user_result_user_id`
              INNER JOIN ' . DB_TABLE_GROUP . ' `g`
              ON `g`.`id`=`u`.`user_group_id`
              JOIN (SELECT ' . $last_result_query_select . ' `id`
                    FROM ' . DB_TABLE_USER_RESULTS . ' `ur`
                    INNER JOIN ' . DB_TABLE_USER . ' `u`  ON `u`.`id`=`ur`.`user_result_user_id`
                        AND `u`.`user_group_id` IN (' . $tmp_groups . ')
                    INNER JOIN ' . DB_TABLE_GROUP . ' `g` ON `g`.`id`=`u`.`user_group_id`
                    WHERE `ur`.`user_result_test_id` IN (' . $tmp_tests . ') ' . $add_and . $testing_period_query .
      $scores_query . '
                    ' . $last_result_query_group . ') x ON ur.id=x.id';
    $results_average = db_query($query_average);
  } else {
    $results_average[0]['righ_questions'] = 2;
    $results_average[0]['percent_right'] = 2;
  }

  $query = 'SELECT `g`.`group_name`,
                     `u`.`user_name`,
                     `u`.`user_info`,
                     `u`.`user_login`,
                     `ur`.`id`,
                     `ur`.`user_result_user_id`,
                     `ur`.`user_result_test_id`,
                     `ur`.`user_result_ip`,
                     `ur`.`user_result_completed`,
                     `ur`.`user_result_out_of_time`,
                     `ur`.`user_result_results`,
                     DATE_FORMAT(CONVERT_TZ(`ur`.`user_result_time_begin`, \'+0:00\', ' . $t_timezone . '), ' .
    $t_admset_dateformat . ') AS `time_begin`,
                     CONVERT_TZ(`ur`.`user_result_time_begin`, \'+0:00\', ' . $t_timezone . ') AS `user_result_time_begin`,
                     DATE_FORMAT(CONVERT_TZ(`ur`.`user_result_time_end`, \'+0:00\', ' . $t_timezone . '),' .
    $t_admset_dateformat . ') AS `time_end`,
                     CONVERT_TZ(`ur`.`user_result_time_end`, \'+0:00\', ' . $t_timezone . ') AS `user_result_time_end`,
                     ' . $testing_time_query_time . '
                     `ur`.`user_result_completed_questions`,
                     `ur`.`user_result_righ_questions`,
                     `ur`.`user_result_score`,
                     `ur`.`user_result_percent_right`,
                     `ur`.`user_result_total_questions`,
                     `ur`.`user_result_test_title`
              FROM ' . DB_TABLE_USER_RESULTS . ' `ur`
              ' . $testing_time_query_join . '
              INNER JOIN ' . DB_TABLE_USER . ' `u`
                ON `u`.`id`=`ur`.`user_result_user_id`
              INNER JOIN ' . DB_TABLE_GROUP . ' `g`
              ON `g`.`id`=`u`.`user_group_id`
              JOIN (SELECT ' . $last_result_query_select . ' `id`
                    FROM ' . DB_TABLE_USER_RESULTS . ' `ur`
                    INNER JOIN ' . DB_TABLE_USER . ' `u`  ON `u`.`id`=`ur`.`user_result_user_id`
                        AND `u`.`user_group_id` IN (' . $tmp_groups . ')
                    INNER JOIN ' . DB_TABLE_GROUP . ' `g` ON `g`.`id`=`u`.`user_group_id`
                    WHERE `ur`.`user_result_test_id` IN (' . $tmp_tests . ') ' . $add_and . $testing_period_query .
    $scores_query . '
                    ' . $last_result_query_group . ') x ON ur.id=x.id';

  if ($t_sort_field == '') $t_sort_field = 'id';

  $fields_array =
    array('id', 'group_name', 'user_name', 'user_login', 'user_info', 'user_result_test_title', 'user_result_score',
      'user_result_results', 'user_result_righ_questions', 'time_begin', 'time_end', 'test_time',
      'user_result_percent_right', 'user_result_completed_questions', 'user_result_completed',
      'user_result_out_of_time', 'result_ip');

  if (!in_array($t_sort_field, $fields_array)) {
    $t_sort_field = 'id';
  }
  if ($t_sort_field == 'id') $t_sort_field = '`ur`.`id`';

  if ($t_sort_field == 'time_begin') {
    $t_sort_field = 'user_result_time_begin';
  }

  if ($t_sort_field == 'time_end') {
    $t_sort_field = 'user_result_time_end';
  }

  $query .= " ORDER BY $t_sort_field $t_sort_order";


  if (($p_count != 0) && (!$p_last_result)) {
    $limit = ($t_page - 1) * $t_count;
    $query .= " LIMIT  $limit, $t_count";
  }


  $results = db_query($query);
  $array = array();

  $admset_percprecision = $WEB_APP['settings']['admset_percprecision'];

  foreach ($results as $result) {
    $result['user_result_percent_right'] = round($result['user_result_percent_right'], $admset_percprecision);
    $array[] = $result;
  }
  if (($is_average == TRUE) and (isset($result))) {
    $array[] = $result;
    $array[count($array) - 1]['group_name'] = text('txt_average');
    $array[count($array) - 1]['id'] = '';
    $array[count($array) - 1]['user_name'] = '';
    $array[count($array) - 1]['user_login'] = '';
    $array[count($array) - 1]['user_info'] = '';
    $array[count($array) - 1]['user_result_test_title'] = '';
    $array[count($array) - 1]['user_result_results'] = '';
    $array[count($array) - 1]['time_begin'] = '';
    $array[count($array) - 1]['time_end'] = '';
    $array[count($array) - 1]['test_time'] = '';
    $array[count($array) - 1]['user_result_completed_questions'] = '';
    $array[count($array) - 1]['user_result_completed'] = '';
    $array[count($array) - 1]['user_result_righ_questions'] =
      round($results_average[0]['righ_questions'], $admset_percprecision);
    $array[count($array) - 1]['user_result_score'] = round($results_average[0]['result_score'], $admset_percprecision);
    $array[count($array) - 1]['user_result_percent_right'] =
      round($results_average[0]['percent_right'], $admset_percprecision);
  }

  return $array;

}


function get_groups_report_max($p_group_array, $p_test_array, $p_testing_period, $p_testing_period_from,
                               $p_testing_period_to, $p_sort_field = "id", $p_sort_order = DEFAULT_ORDER, $p_page = 1,
                               $p_count = 0)
{
  global $WEB_APP;

  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  $t_precision = db_prepare_string($WEB_APP['settings']['admset_percprecision']);
  $t_testing_period_from = db_prepare_date($p_testing_period_from);
  if ($p_testing_period) $limit_date =
    "AND user_result_time_begin BETWEEN $t_testing_period_from AND '$p_testing_period_to 23:59:59'"; else $limit_date =
    "";

  $tmp_groups = "";
  $array = array_values($p_group_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp_groups .= db_prepare_int($array[$i]) . ", ";
  $tmp_groups .= db_prepare_int($array[$size - 1]);

  $tmp_tests = "";
  $array = array_values($p_test_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp_tests .= db_prepare_int($array[$i]) . ", ";
  $tmp_tests .= db_prepare_int($array[$size - 1]);

  $query = "SELECT id, user_result_user_id, user_result_test_id, user_result_ip, user_result_completed, 
       user_result_out_of_time, user_result_results, user_result_time_begin time_begin, user_result_time_end, 
       user_result_completed_questions, user_result_righ_questions, user_result_score, 
       truncate(user_result_percent_right, $t_precision) as user_result_percent_right, user_result_total_questions, 
       test_time, user_result_test_title, group_name, user_name, user_info, 
CASE WHEN user_result_score = maxScore
			THEN 'MAX SCORE'
END AS score_status
FROM (
    SELECT ur.id, ur.`user_result_user_id`, ur.`user_result_test_id`, ur.`user_result_ip`, `ur`.`user_result_completed`,
       `ur`.`user_result_out_of_time`, `ur`.`user_result_results`, `ur`.`user_result_time_begin`, 
       `ur`.`user_result_time_end`, `ur`.`user_result_completed_questions`, `ur`.`user_result_righ_questions`, 
       `ur`.`user_result_score`, `ur`.`user_result_percent_right`, `ur`.`user_result_total_questions`,
       `ur`.`user_result_test_title`, TIMEDIFF(`ur`.`user_result_time_end`,`ur`.`user_result_time_begin`) AS `test_time`,
        `g`.group_name group_name, `u`.user_name user_name, `u`.`user_info` user_info,
	      (SELECT max(`user_result_score`) FROM `" . DB_TABLE_USER_RESULTS . "` urm 
		    WHERE (urm.`user_result_test_id`=ur.`user_result_test_id`) AND (urm.`user_result_user_id`=ur.`user_result_user_id`)) 
		    AS maxScore		
    FROM `" . DB_TABLE_USER_RESULTS . "` ur
  INNER JOIN `" . DB_TABLE_USER . "` `u`  ON `u`.`id`=`ur`.`user_result_user_id` AND `u`.`user_group_id` IN ($tmp_groups)
  INNER JOIN `" . DB_TABLE_GROUP . "` `g` ON `g`.`id`=`u`.`user_group_id`
  WHERE `ur`.`user_result_test_id` IN ($tmp_tests) $limit_date) x 
WHERE  user_result_score IN (maxScore) ";

  // Sorting order
  if ($t_sort_field == '') $t_sort_field = 'id';
  $fields_array =
    array('id', 'group_name', 'user_name', 'user_login', 'user_info', 'user_result_test_title', 'user_result_score',
      'user_result_results', 'user_result_righ_questions', 'time_begin', 'time_end', 'test_time',
      'user_result_percent_right', 'user_result_completed_questions', 'user_result_completed',
      'user_result_out_of_time', 'result_ip');
  if (!in_array($t_sort_field, $fields_array)) {
    $t_sort_field = 'id';
  }
  $query .= " ORDER BY $t_sort_field $t_sort_order ";

  // Limit count
  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $query .= " LIMIT  $limit, $t_count";
  }

  return db_query($query);
}


function get_groups_report_max_count($p_group_array, $p_test_array, $p_testing_period, $p_testing_period_from,
                                     $p_testing_period_to)
{
  $t_testing_period_from = db_prepare_date($p_testing_period_from);
  if ($p_testing_period) $limit_date =
    "AND user_result_time_begin BETWEEN $t_testing_period_from AND '$p_testing_period_to 23:59:59'"; else $limit_date =
    "";

  $tmp_groups = "";
  $array = array_values($p_group_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp_groups .= db_prepare_int($array[$i]) . ", ";
  $tmp_groups .= db_prepare_int($array[$size - 1]);

  $tmp_tests = "";
  $array = array_values($p_test_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp_tests .= db_prepare_int($array[$i]) . ", ";
  $tmp_tests .= db_prepare_int($array[$size - 1]);

  $query = "
SELECT count(id) FROM (
SELECT id
FROM (
    SELECT ur.id, ur.`user_result_user_id`, ur.`user_result_test_id`, ur.`user_result_ip`, `ur`.`user_result_completed`,
       `ur`.`user_result_out_of_time`, `ur`.`user_result_results`, `ur`.`user_result_time_begin`, 
       `ur`.`user_result_time_end`, `ur`.`user_result_completed_questions`, `ur`.`user_result_righ_questions`, 
       `ur`.`user_result_score`, `ur`.`user_result_percent_right`, `ur`.`user_result_total_questions`,
       `ur`.`user_result_test_title`,
	      (SELECT max(`user_result_score`) FROM `" . DB_TABLE_USER_RESULTS . "` urm 
		    WHERE (urm.`user_result_test_id`=ur.`user_result_test_id`) AND (urm.`user_result_user_id`=ur.`user_result_user_id`)) 
		    AS maxScore		
    FROM `" . DB_TABLE_USER_RESULTS . "` ur
  INNER JOIN `" . DB_TABLE_USER . "` `u`  ON `u`.`id`=`ur`.`user_result_user_id` AND `u`.`user_group_id` IN ($tmp_groups)
  INNER JOIN `" . DB_TABLE_GROUP . "` `g` ON `g`.`id`=`u`.`user_group_id`
  WHERE `ur`.`user_result_test_id` IN ($tmp_tests) $limit_date) x 
WHERE  user_result_score IN (maxScore) ) c";

  $result = db_query($query);
  return isset($result[0][0]) ? $result[0][0] : 0;
}


/**
 * Get user result count for groups report.
 *
 * @param $p_group_array array of int group id array
 * @param $p_test_array array of int test id array
 * @param $p_last_result bool show only last result
 * @param $p_testing_period bool show only for testing period
 * @param $p_testing_period_from string datetime testing period from
 * @param $p_testing_period_to string datetime testing period to
 * @param $p_scores bool show only for scores
 * @param $p_scores_from int scores from
 * @param $p_scores_to int scores to
 *
 * @return int results count
 */
function get_user_results_count_for_groups_report($p_group_array, $p_test_array, $p_last_result, $p_best_result,
                                                  $p_testing_period, $p_testing_period_from, $p_testing_period_to,
                                                  $p_scores, $p_scores_from, $p_scores_to)
{
  $tmp_groups = "";
  $array = array_values($p_group_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp_groups .= db_prepare_int($array[$i]) . ", ";
  $tmp_groups .= db_prepare_int($array[$size - 1]);

  $tmp_tests = "";
  $array = array_values($p_test_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp_tests .= db_prepare_int($array[$i]) . ", ";
  $tmp_tests .= db_prepare_int($array[$size - 1]);

  $t_testing_period_from = db_escape_string($p_testing_period_from);
  $t_testing_period_to = db_escape_string($p_testing_period_to);

  $t_scores_from = db_prepare_string($p_scores_from);
  $t_scores_to = db_prepare_string($p_scores_to);

  $query = 'SELECT COUNT(*) AS `_count_`
          FROM  `' . DB_TABLE_USER . '`,`' . DB_TABLE_GROUP . '`,`' . DB_TABLE_USER_RESULTS . '` WHERE `' .
    DB_TABLE_USER . '`.`id` = `' . DB_TABLE_USER_RESULTS . '`.`user_result_user_id`
          AND   `' . DB_TABLE_GROUP . '`.`id`  = `' . DB_TABLE_USER . '`.`user_group_id`
          AND   `' . DB_TABLE_USER . '`.`user_group_id` IN (' . $tmp_groups . ')
          AND    `' . DB_TABLE_USER_RESULTS . '`.`user_result_test_id`
            IN (' . $tmp_tests . ')';

  if ($p_testing_period) {
    $query .= ' AND `' . DB_TABLE_USER_RESULTS . '`.`user_result_time_begin` >= "' . $t_testing_period_from . ' 00:00:00"
                AND `' . DB_TABLE_USER_RESULTS . '`.`user_result_time_begin` <= "' . $t_testing_period_to .
      ' 23:59:59"';
  }

  if ($p_scores) {
    $query .= ' AND `' . DB_TABLE_USER_RESULTS . '`.`user_result_score` >= ' . $t_scores_from . ' AND `' .
      DB_TABLE_USER_RESULTS . '`.`user_result_score` <= ' . $t_scores_to;
  }

  if ($p_last_result) {
    $query .= ' ORDER BY `' . DB_TABLE_USER_RESULTS . '`.`id` DESC LIMIT 1';
  } else {
    $query .= ' ORDER BY `' . DB_TABLE_USER_RESULTS . '`.`id` ASC';
  }

  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get user results count for test id.
 *
 * @param $p_user_id int user id
 * @param $p_test_id int test id
 *
 * @return int user results count
 */
function get_user_results_count_for_test_id($p_user_id, $p_test_id)
{
  $t_user_id = db_prepare_int($p_user_id);
  $t_test_id = db_prepare_int($p_test_id);

  return db_count(DB_TABLE_USER_RESULTS, '`user_result_user_id` = ' . $t_user_id . '
            AND `user_result_test_id` = ' . $t_test_id);
}


/**
 * Get user results count by filter.
 *
 * @param $p_user_id int user id
 * @param $p_filter user_result_filter object
 *
 * @return int user results count
 */
function get_user_results_count_for_user_id($p_user_id, $p_filter)
{
  $t_user_id = db_prepare_int($p_user_id);

  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';

  return db_count(DB_TABLE_USER_RESULTS,
    '`user_result_user_id` = ' . $t_user_id . ' AND `user_result_completed` = 1 ' . $tmp);
}

/**
 * Get incomplete tests count by filter for user id.
 *
 * @param $p_user_id int user id
 * @param $p_filter user_result_filter object
 *
 * @return int user results count
 */
function get_incomplete_tests_user_results_count_for_user_id($p_user_id, $p_filter)
{
  $t_user_id = db_prepare_int($p_user_id);

  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';

  return db_count(DB_TABLE_USER_RESULTS,
    '`user_result_user_id` = ' . $t_user_id . ' AND `user_result_completed` = 0 ' . $tmp);
}


/**
 * Get top user results for test id.
 *
 * @param $p_test_id int test id
 *
 * @return array top user results array
 */
function get_top_user_results_for_test_id($p_test_id)
{
  global $WEB_APP;

  $p_count = $WEB_APP['settings']['tst_ratingquantity'];
  $t_test_id = db_prepare_int($p_test_id);
  $t_count = db_prepare_int($p_count);

  $query = 'SELECT    ' . DB_TABLE_USER . '.`user_name`,
            ' . DB_TABLE_USER_RESULTS . '.`user_result_percent_right`
        FROM    ' . DB_TABLE_USER . ',' . DB_TABLE_USER_RESULTS . ' WHERE ' . DB_TABLE_USER_RESULTS . '.`user_result_user_id` =
            ' . DB_TABLE_USER . '.`id`
        AND ' . DB_TABLE_USER_RESULTS . '.`user_result_test_id` = ' . $t_test_id . '
         ORDER BY ' . DB_TABLE_USER_RESULTS . '.`user_result_percent_right` DESC
        LIMIT   ' . $t_count;

  return db_query($query);
}

/**
 * Get group_user_test count.
 *
 * @param $p_filter report_user_results_filter
 * @return int count
 */
function get_group_user_tests_count($p_filter = NULL, $p_user_id = NULL)
{
  global $WEB_APP;
  $t_user_id = db_prepare_int($p_user_id);
  if (($p_filter->field != "") && (trim($p_filter->text != ""))) {
    $tmp = ' ' . $p_filter->query();
    if (trim($tmp) != "") $and = ' AND ';
    if (($p_filter->field == 'group_name') && (trim($p_filter->text != "''"))) $tmp = '`' . DB_TABLE_USER . '`.user_group_id in
				(SELECT `' . DB_TABLE_GROUP . '`.id   FROM `' . DB_TABLE_GROUP . '` WHERE  ' . $tmp . ') ';
  } else {
    $and = ' ';
    $tmp = '';
  }
  if (isset($p_user_id) && user_have_grant_id($p_user_id, $WEB_APP['settings']['limited_reports_grant_id'])) {
    $sql = 'SELECT COUNT(*) AS `_count_`
            FROM `' . DB_TABLE_USER_RESULTS . '`, `' . DB_TABLE_USER . "`
            WHERE `" . DB_TABLE_USER_RESULTS . "`.`user_result_user_id`=`" . DB_TABLE_USER . "`.`id` 
            AND `webclass_user`.`user_group_id`  
            IN (SELECT group_id FROM `webclass_group_user` WHERE user_id=1 
                UNION  SELECT user_group_id FROM `webclass_user` WHERE id=1)" . $and . $tmp;
  } else {
    $sql = 'SELECT COUNT(*) AS `_count_`
            FROM `' . DB_TABLE_USER_RESULTS . '`, `' . DB_TABLE_USER . '`
            WHERE `' . DB_TABLE_USER_RESULTS . '`.`user_result_user_id`=`' . DB_TABLE_USER . '`.`id` ' . $and . $tmp;
  }

  $result = db_query($sql);

  return (isset($result[0]) ? $result[0]['_count_'] : 0);
}

/**
 * Get group_user_test array.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter user_result_filter object
 *
 * @return array group_user_test array
 */
function get_group_user_tests($p_sort_field = "id", $p_sort_order = "ASC", $p_page = 1, $p_count = 0, $p_filter = NULL,
                              $p_user_id = NULL)
{
  global $WEB_APP;

  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  $t_admset_dateformat = db_prepare_string($WEB_APP['settings']['admset_dateformat']);
  $t_user_id = db_prepare_int($p_user_id);

  $tmp = ($p_filter->e_text != NULL) ? "WHERE " . $p_filter->query() : '';
  $t_timezone = db_prepare_string(date('P'));
  if (isset($p_user_id) && user_have_grant_id($p_user_id, $WEB_APP['settings']['limited_reports_grant_id'])) {
    $query = "SELECT `results`.`id`, `group`.`group_name` ,`user`.`user_name`, `results`.`user_result_test_title`,  
       DATE_FORMAT(CONVERT_TZ(`results`.`user_result_time_begin`,'+0:00', $t_timezone), $t_admset_dateformat) AS `time_begin`, 
       `results`.`user_result_ip` 
    FROM `" . DB_TABLE_USER_RESULTS . "`  as results
    LEFT JOIN `" . DB_TABLE_USER . "`  as `user` ON  `results`.`user_result_user_id`=user.`id`
    INNER JOIN `" . DB_TABLE_GROUP . "`  as `group` ON  `group`.`id` IN (
      SELECT group_id FROM `" . DB_TABLE_GROUP_USER . "` WHERE user_id=$t_user_id 
      UNION  SELECT user_group_id FROM `" . DB_TABLE_USER .
      "` WHERE id=$t_user_id) AND `user`.`user_group_id`=`group`.`id` " . $tmp;
  } else {
    $query = "SELECT `results`.`id`, `group`.`group_name` ,`user`.`user_name`, `results`.`user_result_test_title`,  
       DATE_FORMAT(CONVERT_TZ(`results`.`user_result_time_begin`,'+0:00', $t_timezone), $t_admset_dateformat) AS `time_begin`, 
       `results`.`user_result_ip` 
    FROM `" . DB_TABLE_USER_RESULTS . "`  as results
    LEFT JOIN `" . DB_TABLE_USER . "`  as `user` ON  `results`.`user_result_user_id`=user.`id`
    LEFT JOIN `" . DB_TABLE_GROUP . "`  as `group` ON  `user`.`user_group_id`=`group`.`id` " . $tmp;
  }

  if ($t_sort_field == '') $t_sort_field = "id";

  if (!in_array($t_sort_field, array('id', 'group_name', 'user_name', 'user_result_test_title', 'time_begin'))) {
    $t_sort_field = "id";
  }

  if ($t_sort_field == 'time_begin') {
    $t_sort_field = 'user_result_time_begin';
  }


  $order_str = "`$t_sort_field` $t_sort_order";
  $query .= ' ORDER BY ' . $order_str;

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $query .= " LIMIT  $limit, $t_count";
  }


  return db_query($query);
}

/**
 * Get group_user_tests from group_user_test id array.
 *
 * @param $p_id_array array user_result id array
 *
 * @return array|string group_user_test array
 */
function get_group_user_tests_from_array($p_id_array)
{
  global $WEB_APP;

  $t_admset_dateformat = db_prepare_string($WEB_APP['settings']['admset_dateformat']);

  $tmp = "";
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return "";

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ", ";
  $tmp .= db_prepare_int($array[$size - 1]);
  $t_timezone = db_prepare_string(date('P'));

  $query = 'SELECT `' . DB_TABLE_USER_RESULTS . '`.`id`,`' . DB_TABLE_GROUP . '`.`group_name`, `' . DB_TABLE_USER .
    '`.`user_name`, `' . DB_TABLE_USER_RESULTS . '`.`user_result_test_title`,
    DATE_FORMAT(CONVERT_TZ(`' . DB_TABLE_USER_RESULTS . '`.`user_result_time_begin`, \'+0:00\', ' . $t_timezone . '), 
    ' . $t_admset_dateformat . ')  AS `time_begin`,
    CONVERT_TZ(`' . DB_TABLE_USER_RESULTS . '`.`user_result_time_begin`, \'+0:00\', 
    ' . $t_timezone . ') AS `user_result_time_begin` 
    FROM    `' . DB_TABLE_USER . '`, `' . DB_TABLE_GROUP . '`, `' . DB_TABLE_USER_RESULTS . '` WHERE    `' .
    DB_TABLE_USER . '`.`user_group_id` = `' . DB_TABLE_GROUP . '`.`id`
    AND `' . DB_TABLE_USER . '`.`id` = `' . DB_TABLE_USER_RESULTS . '`.`user_result_user_id`
    AND     `' . DB_TABLE_USER_RESULTS . '`.`id`IN(' . $tmp . ')
    ORDER BY `' . DB_TABLE_USER_RESULTS . '`.`id` ASC';

  return db_query($query);
}

/**
 * Get answers matrix.
 *
 * @param $p_group_array array of int group id array
 * @param $p_test_array array of int test id array
 * @param $p_last_result bool show only last result
 * @param $p_testing_period bool show only for testing period
 * @param $p_testing_period_from string datetime testing period from
 * @param $p_testing_period_to string datetime testing period to
 * @param $p_scores bool show only for scores
 * @param $p_scores_from int scores from
 * @param $p_scores_to int scores to
 * @param $p_sort_field string sort field
 * @param $p_sort_order string ('ASC', 'DESC') sort order
 * @param $p_page int page number
 * @param $p_count int items on a page
 *
 * @return array answers array
 */
function get_answers_matrix($p_group_array, $p_test_array, $p_last_result, $p_testing_period, $p_testing_period_from,
                            $p_testing_period_to, $p_scores, $p_scores_from, $p_scores_to, $p_sort_field = "id",
                            $p_sort_order = "ASC", $p_page = 1, $p_count = 0)
{
  global $WEB_APP;

  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  $tmp_groups = "";
  $array = array_values($p_group_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp_groups .= db_prepare_int($array[$i]) . ", ";
  $tmp_groups .= db_prepare_int($array[$size - 1]);

  $tmp_tests = "";
  $array = array_values($p_test_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp_tests .= db_prepare_int($array[$i]) . ", ";
  $tmp_tests .= db_prepare_int($array[$size - 1]);

  $t_testing_period_from = db_escape_string($p_testing_period_from);
  $t_testing_period_to = db_escape_string($p_testing_period_to);

  $t_scores_from = db_prepare_string($p_scores_from);
  $t_scores_to = db_prepare_string($p_scores_to);
  $t_timezone = db_prepare_string(date('P'));

  $query =
    'SELECT `' . DB_TABLE_GROUP . '`.`group_name`, `' . DB_TABLE_USER . '`.`user_name`,`' . DB_TABLE_USER_RESULTS .
    '`.`id`,`' . DB_TABLE_USER_RESULTS . '`.`user_result_user_id`,`' . DB_TABLE_USER_RESULTS .
    '`.`user_result_test_id`,`' . DB_TABLE_USER_RESULTS . '`.`user_result_percent_right`,`' . DB_TABLE_USER_ANSWERS .
    '`.`user_answer_qnumber`,`' . DB_TABLE_USER_ANSWERS . '`.`user_answer_score`
          FROM  `' . DB_TABLE_USER . '`,`' . DB_TABLE_GROUP . '`,`' . DB_TABLE_USER_RESULTS . '`,`' .
    DB_TABLE_USER_ANSWERS . '` WHERE `' . DB_TABLE_USER . '`.`id` = `' . DB_TABLE_USER_RESULTS . '`.`user_result_user_id`
          AND    `' . DB_TABLE_GROUP . '`.`id`  = `' . DB_TABLE_USER . '`.`user_group_id`
          AND   `' . DB_TABLE_USER . '`.`user_group_id` IN (' . $tmp_groups . ')
          AND    `' . DB_TABLE_USER_RESULTS . '`.`user_result_test_id` IN (' . $tmp_tests . ')
          AND  `' . DB_TABLE_USER_ANSWERS . '`.`user_answer_user_result_id` = `' . DB_TABLE_USER_RESULTS . '`.`id`';

  if ($p_testing_period) {
    $query .= ' AND CONVERT_TZ(`' . DB_TABLE_USER_RESULTS . '`.`user_result_time_begin`, \'+0:00\', ' . $t_timezone .
      ') >= "' . $t_testing_period_from . ' 00:00:00"
                    AND CONVERT_TZ(`' . DB_TABLE_USER_RESULTS . '`.`user_result_time_begin`, \'+0:00\', ' .
      $t_timezone . ') <= "' . $t_testing_period_to . ' 23:59:59"';
  }

  if ($p_scores) {
    $query .= ' AND `' . DB_TABLE_USER_RESULTS . '`.`user_result_score` >= ' . $t_scores_from . '
                AND `' . DB_TABLE_USER_RESULTS . '`.`user_result_score` <= ' . $t_scores_to;
  }


  if ($t_sort_field == '') $t_sort_field = 'id';

  $fields_array = array('id', 'user_name', 'user_result_test_title', 'user_result_score', 'user_result_results',
    'user_result_righ_questions', 'user_result_time_begin', 'test_time', 'user_result_percent_right',
    'user_result_completed_questions');
  if (!in_array($t_sort_field, $fields_array)) {
    $t_sort_field = 'id';
  }
  if ($t_sort_field == 'id') $t_sort_field = '`' . DB_TABLE_USER_RESULTS . '`.`id`';

  $query .= " ORDER BY $t_sort_field $t_sort_order";

  if (($p_count != 0) && (!$p_last_result)) {
    $limit = ($t_page - 1) * $t_count;
    $query .= " LIMIT  $limit, $t_count";
  }

  $results = db_query($query);

  $matrix = array();

  if ($p_last_result) {
    $max_id = 0;
    foreach ($results as $result) {
      $max_id = max($max_id, $result['id']);
    }
  }
  foreach ($results as $result) {
    $id = $result['id'];
    if ($p_last_result) {
      if ($max_id != $id) {
        continue;
      }
    }
    if (!isset($matrix[$id])) {
      $matrix[$id] = array();
    }
    $matrix[$id]['id'] = $id;
    $matrix[$id][0] = $result['user_name'];
    $matrix[$id][1 + $result['user_answer_qnumber']] = $result['user_answer_score'];

  }
  $count = count($matrix);
  $answers_count = 0;
  foreach ($matrix as $user_result) {
    $answers_count = max($answers_count, count($user_result) - 2);
  }
  $last_row = array();
  $last_row['id'] = '';
  $last_row[] = text('txt_solvable');
  $admset_percprecision = $WEB_APP['settings']['admset_percprecision'];
  for ($i = 1; $i <= $answers_count; $i++) {
    $tmp = 0;
    foreach ($matrix as $user_result) {
      $tmp += isset($user_result[$i]) ? $user_result[$i] : 0;
    }
    $last_row[$i] = round($tmp / $count, $admset_percprecision);
  }
  $matrix[] = $last_row;

  return $matrix;
}


function finish_user_results($p_id_array)
{
  foreach ($p_id_array as $id) {
    $user_result = get_user_result($id);
    if ($user_result->id != NULL) {
      $user_result->time_end = get_utc_time();
      $user_result->completed = TRUE;

      edit_user_result($user_result);
    }
  }

  return TRUE;
}


/**
 * Get user results from user_results id array.
 *
 * @param $p_id_array array of int user result id
 *
 * @return array user results array
 */
function get_user_results_from_array($p_id_array)
{
  global $WEB_APP;
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  $t_admset_dateformat = db_prepare_string($WEB_APP['settings']['admset_dateformat']);
  $t_timezone = db_prepare_string(date('P'));

  $query = 'SELECT `id`,
            `user_result_user_id`,
            `user_result_test_id`,
            `user_result_completed`,
            `user_result_results`,
            DATE_FORMAT(CONVERT_TZ(`user_result_time_begin`, \'+0:00\', ' . $t_timezone . '), ' . $t_admset_dateformat . ') AS `time_begin`,
            CONVERT_TZ(`user_result_time_begin`, \'+0:00\', ' . $t_timezone . ') AS `user_result_time_begin`,
            DATE_FORMAT(CONVERT_TZ(`user_result_time_end`, \'+0:00\', ' . $t_timezone . '), ' . $t_admset_dateformat . ') AS `time_end`,
            CONVERT_TZ(`user_result_time_end`, \'+0:00\', ' . $t_timezone . ') AS `user_result_time_end`,
            `user_result_completed_questions`,
            `user_result_righ_questions`,
            `user_result_score`,
            `user_result_percent_right`,
            `user_result_total_questions`,
            `user_result_test_title`,
            `user_result_ip`
        FROM    ' . DB_TABLE_USER_RESULTS . ' WHERE `id` IN(' . $tmp . ')
        ORDER BY `id` ASC';

  return db_query($query);
}


/**
 * Get incomplete tests.
 *
 * @param $p_user_id int
 * @param $p_sort_field string  sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int
 * @param $p_count int
 * @param $p_filter user_result_filter
 * @return array user_results array
 */
function get_incomplete_tests($p_user_id, $p_sort_field = "id", $p_sort_order = "ASC", $p_page = 1, $p_count = 0,
                              $p_filter = NULL)
{
  global $WEB_APP;

  $t_user_id = db_prepare_int($p_user_id);
  $t_admset_dateformat = db_prepare_string($WEB_APP['settings']['admset_dateformat']);
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field,
    array('id', 'user_result_user_id', 'user_result_test_id', 'user_result_completed', 'user_result_results',
      'time_begin', 'time_end', 'user_result_completed_questions', 'user_result_righ_questions', 'user_result_score',
      'user_result_percent_right', 'user_result_total_questions', 'user_result_test_title'))) {
    $t_sort_field = 'id';
  }

  if ($t_sort_field == 'time_begin') {
    $t_sort_field = 'user_result_time_begin';
  }

  if ($t_sort_field == 'time_end') {
    $t_sort_field = 'user_result_time_end';
  }

  $order_str = "`$t_sort_field` $t_sort_order";

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "LIMIT $limit, $t_count";
  } else {
    $limit_str = '';
  }
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  $t_timezone = db_prepare_string(date('P'));
  $query = 'SELECT `id`,
            `user_result_user_id`,
            `user_result_test_id`,
            `user_result_completed`,
            `user_result_results`,
            CONVERT_TZ(`user_result_time_begin`, \'+0:00\', ' . $t_timezone . ') AS `user_result_time_begin`,
            DATE_FORMAT(CONVERT_TZ(`user_result_time_begin`, \'+0:00\', ' . $t_timezone . '), ' . $t_admset_dateformat . ') AS `time_begin`,
            CONVERT_TZ(`user_result_time_end`, \'+0:00\', ' . $t_timezone . ') AS `user_result_time_end`,
            DATE_FORMAT(CONVERT_TZ(`user_result_time_end`, \'+0:00\', ' . $t_timezone . '), ' . $t_admset_dateformat . ') AS `time_end`,
            `user_result_completed_questions`,
            `user_result_righ_questions`,
            `user_result_score`,
            `user_result_percent_right`,
            `user_result_total_questions`,
            `user_result_test_title`
        FROM    ' . DB_TABLE_USER_RESULTS . ' WHERE     `user_result_user_id` = ' . $t_user_id . '
         AND `user_result_completed` =0 ' . $tmp . '
            ORDER BY ' . $order_str . ' ' . $limit_str;

  return db_query($query);
}

function get_misapplication($p_group_array, $p_date_from, $p_date_to)
{
  $t_date_from = db_prepare_date($p_date_from);
  $t_date_to = db_prepare_date($p_date_to);
  $groups_id = "";
  $array = array_values($p_group_array);
  $size = sizeof($array);
  if ($size == 0) return NULL;
  for ($i = 0; $i < $size - 1; $i++) $groups_id .= db_prepare_int($array[$i]) . ", ";
  $groups_id .= db_prepare_int($array[$size - 1]);

  $query = "
    SELECT DISTINCT ur.id, ur.user_result_ip, ur.user_result_results, ur.user_result_time_begin, ur.user_result_time_end, 
           ur.user_result_score, ur.user_result_test_title, u.user_name, g.group_name 
    FROM `webclass_user_results` ur
    INNER JOIN `webclass_user_results` urm ON urm.`user_result_user_id`=ur.`user_result_user_id` AND 
                urm.`user_result_ip`!=ur.`user_result_ip` AND 
                ((urm.`user_result_time_begin`>ur.`user_result_time_begin` AND 
                urm.`user_result_time_end`<ur.`user_result_time_end`) OR 
                				(ur.`user_result_time_begin`>urm.`user_result_time_begin` AND 
                ur.`user_result_time_end`<urm.`user_result_time_end`))
    INNER JOIN `webclass_user` `u`  ON `u`.`id`=`ur`.`user_result_user_id` AND `u`.`user_group_id` IN ($groups_id)
    INNER JOIN `webclass_group` `g` ON `g`.`id`=`u`.`user_group_id`
    WHERE ur.user_result_time_begin BETWEEN $t_date_from AND $t_date_to; 
";
  $result = db_query($query);
  return $result;
}

function get_misapplication_count($p_group_array, $p_date_from, $p_date_to)
{
  $t_date_from = db_prepare_date($p_date_from);
  $t_date_to = db_prepare_date($p_date_to);
  $groups_id = "";
  $array = array_values($p_group_array);
  $size = sizeof($array);
  if ($size == 0) return NULL;
  for ($i = 0; $i < $size - 1; $i++) $groups_id .= db_prepare_int($array[$i]) . ", ";
  $groups_id .= db_prepare_int($array[$size - 1]);
  $query = "
    SELECT DISTINCT count(ur.id) 
    FROM `webclass_user_results` ur
    INNER JOIN `webclass_user_results` urm ON urm.`user_result_user_id`=ur.`user_result_user_id` AND 
                urm.`user_result_ip`!=ur.`user_result_ip` AND 
                ((urm.`user_result_time_begin`>ur.`user_result_time_begin` AND 
                urm.`user_result_time_end`<ur.`user_result_time_end`) OR 
                (ur.`user_result_time_begin`>urm.`user_result_time_begin` AND 
                ur.`user_result_time_end`<urm.`user_result_time_end`))

    INNER JOIN `webclass_user` `u`  ON `u`.`id`=`ur`.`user_result_user_id` AND `u`.`user_group_id` IN ($groups_id)
    WHERE ur.user_result_time_begin BETWEEN $t_date_from AND $t_date_to; 
";
  $result = db_query($query);
  return $result[0][0];
}

function get_count_for_best_results($p_group_array, $p_test_array, $p_testing_period_from, $p_testing_period_to)
{
  $t_date_from = db_prepare_date($p_testing_period_from);
  $t_date_to = db_prepare_date($p_testing_period_to. " 23.59.59");

  $groups = "";
  $array = array_values($p_group_array);
  $size = sizeof($array);
  if ($size == 0) return NULL;
  for ($i = 0; $i < $size - 1; $i++) $groups .= db_prepare_int($array[$i]) . ", ";
  $groups .= db_prepare_int($array[$size - 1]);

  $tests = "";
  $array = array_values($p_test_array);
  $size = sizeof($array);
  if ($size == 0) return NULL;
  for ($i = 0; $i < $size - 1; $i++) $tests .= db_prepare_int($array[$i]) . ", ";
  $tests .= db_prepare_int($array[$size - 1]);

  $query = "SELECT test_name FROM `" . DB_TABLE_TESTS . "` WHERE id IN ($tests)";
  $test_names = array_values(db_query($query));
  $test_columns = "";

  for ($i = 0; $i < $size - 1; $i++) $test_columns .= "max(CASE WHEN user_result_test_title='" . $test_names[$i][0] .
    "' THEN user_result_score ELSE NULL END) AS '" . $test_names[$i][0] . "',";
  $test_columns .= "max(CASE WHEN user_result_test_title='" . $test_names[$i][0] .
    "' THEN user_result_score ELSE NULL END) AS '" . $test_names[$i][0] . "'";

  $query = "
SELECT DISTINCT user_name,
                $test_columns
FROM (
    SELECT user_result_score, user_result_test_title, user_name
    FROM (
        SELECT `ur`.id, `ur`.`user_result_user_id`, `ur`.`user_result_test_id`, `ur`.`user_result_score`,
               `ur`.`user_result_test_title`,  `u`.user_name user_name,
            (SELECT count(*) FROM `webclass_user_results` urr 
            WHERE ur.user_result_test_title=urr.user_result_test_title AND ur.`user_result_score`<urr.`user_result_score`),
	          (SELECT max(`user_result_score`) FROM `webclass_user_results` urm 
		        WHERE (urm.`user_result_test_id`=ur.`user_result_test_id`) AND (urm.`user_result_user_id`=ur.`user_result_user_id`)
	              AND user_result_time_begin BETWEEN $t_date_from AND $t_date_to) 
		        AS maxScore		
    FROM `webclass_user_results` ur
  INNER JOIN `webclass_user` `u`  ON `u`.`id`=`ur`.`user_result_user_id` AND `u`.`user_group_id` IN ($groups)
  INNER JOIN `webclass_group` `g` ON `g`.`id`=`u`.`user_group_id`
  WHERE `ur`.`user_result_test_id` IN ($tests) ) x 
WHERE  user_result_score IN (maxScore)) y  GROUP BY user_name ";

  $results = db_query($query);
  return count($results);

}

function get_best_results_report($p_group_array, $p_test_array, $p_testing_period_from, $p_testing_period_to,
                                 $is_average, $p_page = 1, $p_count = 0)
{
  global $WEB_APP;
  $t_date_from = db_prepare_date($p_testing_period_from);
  $t_date_to = db_prepare_date($p_testing_period_to. " 23.59.59");
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  $groups = "";
  $array = array_values($p_group_array);
  $size = sizeof($array);
  if ($size == 0) return NULL;
  for ($i = 0; $i < $size - 1; $i++) $groups .= db_prepare_int($array[$i]) . ", ";
  $groups .= db_prepare_int($array[$size - 1]);

  $tests = "";
  $array = array_values($p_test_array);
  $tests_count = sizeof($array);
  if ($tests_count == 0) return NULL;
  for ($i = 0; $i < $tests_count - 1; $i++) $tests .= db_prepare_int($array[$i]) . ", ";
  $tests .= db_prepare_int($array[$tests_count - 1]);

  $query = "SELECT test_name FROM `" . DB_TABLE_TESTS . "` WHERE id IN ($tests) ORDER BY test_name";
  $test_names = array_values(db_query($query));
  $test_columns = "";

  for ($i = 0; $i < $tests_count - 1; $i++) $test_columns .= "max(CASE WHEN user_result_test_title='" .
    $test_names[$i][0] . "' THEN user_result_score ELSE NULL END) AS '" . $test_names[$i][0] . "',";
  $test_columns .= "max(CASE WHEN user_result_test_title='" . $test_names[$i][0] .
    "' THEN user_result_score ELSE NULL END) AS '" . $test_names[$i][0] . "'";

  $query = "
SELECT DISTINCT user_name,
                $test_columns
FROM (
    SELECT user_result_score, user_result_test_title, user_name
    FROM (
        SELECT `ur`.id, `ur`.`user_result_user_id`, `ur`.`user_result_test_id`, `ur`.`user_result_score`,
               `ur`.`user_result_test_title`,  `u`.user_name user_name,
            (SELECT count(*) FROM `webclass_user_results` urr 
            WHERE ur.user_result_test_title=urr.user_result_test_title AND ur.`user_result_score`<urr.`user_result_score`),
	          (SELECT max(`user_result_score`) FROM `webclass_user_results` urm 
		        WHERE (urm.`user_result_test_id`=ur.`user_result_test_id`) AND (urm.`user_result_user_id`=ur.`user_result_user_id`)
	              AND user_result_time_begin BETWEEN $t_date_from AND $t_date_to) 
		        AS maxScore		
    FROM `webclass_user_results` ur
  INNER JOIN `webclass_user` `u`  ON `u`.`id`=`ur`.`user_result_user_id` AND `u`.`user_group_id` IN ($groups)
  INNER JOIN `webclass_group` `g` ON `g`.`id`=`u`.`user_group_id`
  WHERE `ur`.`user_result_test_id` IN ($tests) ) x 
WHERE  user_result_score IN (maxScore)) y  GROUP BY user_name ";

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $query .= " LIMIT  $limit, $t_count";
  }

  $results = db_query($query);
  $results_count = count($results);


  if ($is_average == TRUE) {
    $array = array();
    $test_sum = array();

    $array[] = $results[0];
    $array[0]['user_name'] = text("txt_test_max_score");
    for ($i = 0; $i < $tests_count; $i++) {
      $array[0][$test_names[$i][0]] = get_test_top_score($p_test_array[$i]);
    }

    foreach ($results as $result) {
      $array[] = $result;
    }
    for ($i = 0; $i < $results_count; $i++) {
      for ($j = 0; $j < $tests_count; $j++) {
        $test_sum[$j] = $test_sum[$j] + $array[$i + 1][$j + 1];
      }
    }

    $array[] = $results[0];
    $array[count($array) - 1]['user_name'] = text("txt_average");
    for ($i = 0; $i < $tests_count; $i++) {
      if ($results_count !== 0) $array[count($array) - 1][$test_names[$i][0]] =
        round($test_sum[$i] / $results_count, $WEB_APP['settings']['admset_percprecision']); else
        $array[count($array) - 1][$test_names[$i][0]] = "";
    }
    return $array;
  };
  return $results;
}