<?php

/**
 * Specify resume object. Resume table.
 */
class resume
{
    /**
     * int resume id
     */
    var $id;

    /**
     * int test id
     */
    var $test;

    /**
     * int resume number
     */
    var $number;

    /**
     * int resume top
     */
    var $top;

    /**
     * int resume low
     */
    var $low;

    /**
     * string resume text
     */
    var $text;
}

/**
 * Add resume.
 *
 * @param $p_resume resume class
 */
function add_resume($p_resume)
{
    $t_resume_test_id = db_prepare_int($p_resume->test);
    $t_resume_number = db_prepare_int($p_resume->number);
    $t_resume_top = db_prepare_float($p_resume->top);
    $t_resume_low = db_prepare_float($p_resume->low);
    $t_resume_text = db_prepare_string($p_resume->text);

    $query = "INSERT INTO ".DB_TABLE_RESUME."(
            `resume_test_id`,
            `resume_number`,
            `resume_top`,
            `resume_low`,
            `resume_text`)
         VALUES(
            $t_resume_test_id,
            $t_resume_number,
            $t_resume_top,
            $t_resume_low,
            $t_resume_text)";

    db_exec($query);
}

/**
 * Delete resume for test id.
 *
 * @param $p_test_id int test id
 *
 * @return ADORecordSet or false
 */
function delete_resume_for_test_id($p_test_id)
{
    $t_test_id = db_prepare_int($p_test_id);
    $query = 'DELETE FROM '.DB_TABLE_RESUME.
         ' WHERE `resume_test_id` = '.$t_test_id;

    return db_exec($query);
}

/**
 * Get resumes for user_result_id.
 *
 * @param $p_user_result_id int user_result id
 *
 * @return string
 */
function get_resume_text_for_user_result_id($p_user_result_id)
{
    $t_user_result_id = db_prepare_int($p_user_result_id);

    $query = 'SELECT '.DB_TABLE_RESUME.'.`resume_text`
        FROM    '.DB_TABLE_RESUME.','.
            DB_TABLE_USER_RESULTS.
        ' WHERE '.DB_TABLE_RESUME.'.`resume_test_id` = '.
            DB_TABLE_USER_RESULTS.'.`user_result_test_id`
        AND '.DB_TABLE_USER_RESULTS.'.`id` = '.
            $t_user_result_id.'
        AND '.DB_TABLE_USER_RESULTS.'.`user_result_score` >= '.
            DB_TABLE_RESUME.'.`resume_low`
        AND '.DB_TABLE_USER_RESULTS.'.`user_result_score` <= '.
            DB_TABLE_RESUME.'.`resume_top`';

    $result = db_query($query);

    return isset($result[0]['resume_text']) ? $result[0]['resume_text'] : '';
}

/**
 * Get resume text for user result id by max score.
 *
 * @param $p_user_result_id int user_result id
 * @param $p_max_score int max score
 *
 * @return string resume text
 */
function get_resume_text_for_user_result_id_by_max_score(
                                    $p_user_result_id,
                                    $p_max_score)
{

    $t_user_result_id = db_prepare_int($p_user_result_id);
    $t_max_score = db_prepare_float($p_max_score);

    $query = 'SELECT '.DB_TABLE_RESUME.'.`resume_text`
        FROM    '.DB_TABLE_RESUME.', '.
            DB_TABLE_USER_RESULTS.
        ' WHERE '.DB_TABLE_RESUME.'.`resume_test_id` = '.
            DB_TABLE_USER_RESULTS.'.`user_result_test_id`
        AND '.DB_TABLE_USER_RESULTS.'.`id` = '.
            $t_user_result_id.'
        AND '.DB_TABLE_USER_RESULTS.'.`user_result_score`/'.
            $t_max_score.'*100 >= '.DB_TABLE_RESUME.'.`resume_low`
        AND '.DB_TABLE_USER_RESULTS.'.`user_result_score`/'.
            $t_max_score.'*100 <= '.DB_TABLE_RESUME.'.`resume_top`';

    $result = db_query($query);

    return isset($result[0]['resume_text']) ? $result[0]['resume_text'] : '';
}

