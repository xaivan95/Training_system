<?php

/**
 * Specify user_answer object. User_answers table.
 */
class user_answer
{
  /**
   * int user_answer id
   */
  var $id;

  /**
   * int user_result id
   */
  var $user_result_id;

  /**
   * int question number
   */
  var $qnumber;

  /**
   * string question
   */
  var $question;

  /**
   * string answer
   */
  var $answer;

  /**
   * string time
   */
  var $time;

  /**
   * char T or F is right answer
   */
  var $is_right;

  /**
   * float score
   */
  var $score;

  /**
   * int (0, 1) is answered
   */
  var $answered;

  /**
   * int question id
   */
  var $question_id;

  /**
   * int theme id
   */
  var $theme_id;

  /**
   * string correct answer
   */
  var $correct_answer;

  /**
   * string answer fields
   */
  var $answer_fields;

  /**
   * string explanation from question
   */
  var $explanation;

  function __construct()
  {
    $this->answer_fields = '';
  }
}

/**
 * Add user_answer.
 *
 * @param $p_user_answer user_answer class
 */
function add_user_answer($p_user_answer)
{
  $t_user_result_id = db_prepare_int($p_user_answer->user_result_id);
  $t_qnumber = db_prepare_int($p_user_answer->qnumber);
  $t_question = db_prepare_string($p_user_answer->question);
  $t_answer = db_prepare_string($p_user_answer->answer);
  $t_time = db_prepare_string($p_user_answer->time);
  $t_is_right = db_prepare_int($p_user_answer->is_right);
  $t_score = db_prepare_float($p_user_answer->score);
  $t_answered = db_prepare_int($p_user_answer->answered);
  $t_question_id = db_prepare_int($p_user_answer->question_id);
  $t_theme_id = db_prepare_int($p_user_answer->theme_id);
  $t_correct_answer = db_prepare_string($p_user_answer->correct_answer);
  $t_answer_fields = db_prepare_string($p_user_answer->answer_fields);
  $t_explanation = db_prepare_string($p_user_answer->explanation);

  $query = "INSERT INTO " . DB_TABLE_USER_ANSWERS . " (
            `user_answer_user_result_id`,
            `user_answer_qnumber`,
            `user_answer_question`,
            `user_answer_answer`,
            `user_answer_time`,
            `user_answer_is_right`,
            `user_answer_score`,
            `user_answer_answered`,
            `user_answer_question_id`,
            `user_answer_user_result_theme_id`,
            `user_answer_correct_answer`,
            `user_answer_answer_fields`,
            `user_answer_explanation`)
        VALUES  (
            $t_user_result_id ,
            $t_qnumber,
            $t_question,
            $t_answer,
            $t_time,
            $t_is_right,
            $t_score,
            $t_answered,
            $t_question_id,
            $t_theme_id,
            $t_correct_answer,
            $t_answer_fields,
            $t_explanation)";

  db_exec($query);
}

/**
 * Is showed question.
 *
 * @param $p_user_result_id int user_result id
 * @param $p_question_id int question id
 *
 * @return bool
 */
function is_showed_question($p_user_result_id, $p_question_id)
{
  $t_user_result_id = db_prepare_int($p_user_result_id);
  $t_question_id = db_prepare_int($p_question_id);

  $query = 'SELECT *
        FROM ' . DB_TABLE_USER_ANSWERS . ' WHERE `user_answer_user_result_id` = ' . $t_user_result_id . '
        AND `user_answer_question_id` = ' . $t_question_id . '
        LIMIT 1';

  $result = db_query($query);

  return isset($result[0]);
}

/**
 * Edit user_answer.
 *
 * @param $p_user_answer user_answer class
 */
function edit_user_answer($p_user_answer)
{
  $t_user_result_id = db_prepare_int($p_user_answer->user_result_id);
  $t_qnumber = db_prepare_int($p_user_answer->qnumber);
  $t_question = db_prepare_string($p_user_answer->question);
  $t_answer = db_prepare_string($p_user_answer->answer);
  $t_time = db_prepare_string($p_user_answer->time);
  $t_is_right = db_prepare_int($p_user_answer->is_right);
  $t_score = db_prepare_float($p_user_answer->score);
  $t_answered = db_prepare_int($p_user_answer->answered);
  $t_question_id = db_prepare_int($p_user_answer->question_id);
  $t_theme_id = db_prepare_int($p_user_answer->theme_id);
  $t_correct_answer = db_prepare_string($p_user_answer->correct_answer);
  $t_answer_answer_fields = db_prepare_string($p_user_answer->answer_fields);
  $t_explanation = db_prepare_string($p_user_answer->explanation);

  $query = "UPDATE " . DB_TABLE_USER_ANSWERS . " SET `user_answer_qnumber` =  $t_qnumber ,
            `user_answer_question` =  $t_question,
            `user_answer_answer` = $t_answer,
            `user_answer_time` = $t_time,
            `user_answer_is_right` = $t_is_right,
            `user_answer_score` = $t_score,
            `user_answer_answered` = $t_answered,
            `user_answer_user_result_theme_id` = $t_theme_id,
            `user_answer_correct_answer` = $t_correct_answer,
            `user_answer_answer_fields` = $t_answer_answer_fields,
            `user_answer_explanation` = $t_explanation
        WHERE   `user_answer_user_result_id` =  $t_user_result_id
        AND `user_answer_question_id` =  $t_question_id";

  db_exec($query);
}

/**
 * Get right questions count.
 *
 * @param $p_user_result_id int user_result id
 *
 * @return int right questions count
 */
function get_right_questions($p_user_result_id)
{
  $t_user_result_id = db_prepare_int($p_user_result_id);

  return db_count(DB_TABLE_USER_ANSWERS, '`user_answer_user_result_id` = ' . $t_user_result_id . '
            AND `user_answer_is_right` = 1');
}

/**
 * Get answered questions count.
 *
 * @param $p_user_result_id int user_result id
 *
 * @return int answered questions count
 */
function get_answered_questions($p_user_result_id)
{
  $t_user_result_id = db_prepare_int($p_user_result_id);

  return db_count(DB_TABLE_USER_ANSWERS, '`user_answer_user_result_id` = ' . $t_user_result_id . '
            AND `user_answer_answered` = 1');
}

/**
 * Get user_answers array for user_result id.
 *
 * @param $p_user_result_id int user_result id
 *
 * @return array user_answers array
 */
function get_user_answers_for_user_result_id($p_user_result_id)
{
  global $WEB_APP;
  $t_user_result_id = db_prepare_int($p_user_result_id);
  $t_admset_dateformat = db_prepare_string($WEB_APP['settings']['admset_dateformat']);
  $t_timezone = db_prepare_string(date('P'));

  $query = 'SELECT    `user_answer_id`,
            `user_answer_user_result_id`,
            `user_answer_qnumber`,
            `user_answer_question`,
            `user_answer_answer`,
            DATE_FORMAT(CONVERT_TZ(`user_answer_time`, \'+00:00\',' . $t_timezone . '), ' . $t_admset_dateformat . ')
                AS `user_answer_time`,
            `user_answer_is_right`,
            `user_answer_score`,
            `user_answer_answered`,
            `user_answer_question_id`,
            `user_answer_correct_answer`,
            `user_answer_answer_fields`,
            `user_answer_explanation`
        FROM    ' . DB_TABLE_USER_ANSWERS . ' WHERE `user_answer_user_result_id` = ' . $t_user_result_id . '
        ORDER BY `user_answer_id` ASC';

  return db_query($query);
}

/**
 * Delete all user_answers for user_result id.
 *
 * @param $p_user_result_id int user_result id
 */
function delete_user_answers_for_user_result($p_user_result_id)
{
  $t_user_result_id = db_prepare_int($p_user_result_id);

  $query = 'DELETE FROM ' . DB_TABLE_USER_ANSWERS . ' WHERE `user_answer_user_result_id` = ' . $t_user_result_id;

  db_exec($query);
}

/**
 * Delete all user_answers for user_results id array.
 *
 * @param $p_user_result_id_array array id array
 *
 * @return ADORecordSet|string result
 */
function delete_user_answers_for_user_results($p_user_result_id_array)
{
  $tmp = "";
  $array = array_values($p_user_result_id_array);
  $size = sizeof($array);

  if ($size == 0) return "";

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ", ";
  $tmp .= db_prepare_int($array[$size - 1]);

  $query = 'DELETE FROM ' . DB_TABLE_USER_ANSWERS . ' WHERE `user_answer_user_result_id` IN (' . $tmp . ')';

  return db_exec($query);
}


/**
 * Delete all user records for user_results id array.
 * @param array $p_user_result_id_array id array
 * @return boolean result
 */
function delete_user_records_for_user_results($p_user_result_id_array)
{
  $array = array_values($p_user_result_id_array);
  $size = sizeof($array);
  if ($size == 0) return TRUE;
  $deleted = TRUE;
  for ($i = 0; $i < $size; $i++) {
    foreach (glob("records/*/" . $array[$i] . "_*.mp3") as $filename) {
      $deleted = $deleted && unlink($filename);
    }
  }
  return $deleted;
}

