<?php

/**
 * Specify hint object. Hints table.
 */
class hint
{
    /**
     * int hint id.
     */
    var $id;

    /**
     * int test id.
     */
    var $test;

    /**
     * int hint number.
     */
    var $number;

    /**
     * string hint title.
     */
    var $title;

    /**
     * string hint text.
     */
    var $text;

    /**
     * string hint html text.
     */
    var $html_text;
}

/**
 * Add hint.
 *
 * @param $p_hint hint class
 *
 * @return ADORecordSet or false
 */
function add_hint($p_hint)
{
    $t_test_id = db_prepare_int($p_hint->test);
    $t_number = db_prepare_int($p_hint->number);
    $t_title = db_prepare_string($p_hint->title);
    $t_text = db_prepare_string($p_hint->text);
    $t_html_text = db_prepare_string($p_hint->html_text);

    $query =  'INSERT INTO '.DB_TABLE_HINTS.
                '(
                `hint_test_id`,
                `hint_number`,
                `hint_title`,
                `hint_text`,
                `hint_html_text`)
            VALUES (
                '.$t_test_id.',
                '.$t_number.',
                '.$t_title.',
                '.$t_text.',
                '.$t_html_text.')';

    return db_exec($query);
}

/**
 * Delete hints for test id.
 *
 * @param $p_test_id int test id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_hints_for_test_id($p_test_id)
{
    $t_test_id = db_prepare_int($p_test_id);

    $query =  'DELETE FROM '.DB_TABLE_HINTS.
          ' WHERE `hint_test_id` = '.$t_test_id;

    db_exec($query);

    return (db_last_error() == '');
}

/**
 * Get hint.
 *
 * @param $p_test_id int test id
 * @param $p_hint_number int hint number
 *
 * @return hint class
 */
function get_hint($p_test_id, $p_hint_number)
{
    $t_test_id = db_prepare_int($p_test_id);
    $t_hint_number = db_prepare_int($p_hint_number);

    $hints[] = db_extract(DB_TABLE_HINTS, '`hint_test_id` = '.$t_test_id.
                ' AND `hint_number` = '.$t_hint_number);
    $hint = new hint();
    if (count($hints[0]) > 0)
    {
        $hint->id = $hints[0][0]['hint_id'];
        $hint->test = $hints[0][0]['hint_test_id'];
        $hint->number = $hints[0][0]['hint_number'];
        $hint->title = $hints[0][0]['hint_title'];
        $hint->text = $hints[0][0]['hint_text'];
        $hint->html_text = $hints[0][0]['hint_html_text'];
    }
	return	$hint;
}

