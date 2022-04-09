<?php

/**
 * Specify theme object. Themes table.
 */
class theme
{
    /**
     * int theme id
     */
    var $id;

    /**
     * int test id
     */
    var $test;

    /**
     * int theme number
     */
    var $number;

    /**
     * string theme caption
     */
    var $caption;

    /**
     * int theme numexam
     */
    var $numexam;

    /**
     * int conclusions count
     */
    var $conclusions_count;

    /**
     * int (0, 1) show in results
     */
    var $show_in_results;

    /** int for import only */
    var $original_id;
}

/**
 * Add theme.
 *
 * @param $p_theme theme class
 *
 * @return ADORecordSet or false
 */
function add_theme($p_theme)
{
    $t_theme_number = db_prepare_int($p_theme->number);
    $t_theme_test_id = db_prepare_int($p_theme->test);
    $t_theme_caption = db_prepare_string($p_theme->caption);
    $t_theme_numexam = db_prepare_int($p_theme->numexam);
    $t_theme_conclusions_count = db_prepare_int($p_theme->conclusions_count);
    $t_theme_show_in_results = db_prepare_int($p_theme->show_in_results);

    $query = 'INSERT INTO ' . DB_TABLE_THEMES . ' (
                `theme_number`,
                `theme_test_id`,
                `theme_caption`,
                `theme_numexam`,
                `theme_conclusions_count`,
                `theme_show_in_results`)
            VALUES (
                ' . $t_theme_number . ',
                ' . $t_theme_test_id . ',
                ' . $t_theme_caption . ',
                ' . $t_theme_numexam . ',
                ' . $t_theme_conclusions_count . ',
                ' . $t_theme_show_in_results . ')';
    return db_exec($query);
}

/**
 * Get themes for test id.
 *
 * @param $p_test_id int test id
 *
 * @return array theme class
 */
function get_themes_for_test_id($p_test_id)
{
    $t_test_id = db_prepare_int($p_test_id);

    return db_extract(DB_TABLE_THEMES,
        '`theme_test_id` = ' . $t_test_id,
        '`theme_number` ASC');
}

/**
 * Delete themes for test id.
 *
 * @param $t_id int test id
 */
function delete_themes_for_test_id($t_id)
{
    $themes = get_themes_for_test_id($t_id);
    $id_array = array();
    foreach ($themes as $theme) {
        delete_conclusions_for_theme_id($theme['theme_id']);
        delete_questions_for_theme_id($theme['theme_id']);
        $id_array[] = $theme['theme_id'];
    }

    $tmp = "";
    $array = array_values($id_array);
    $size = sizeof($array);

    if ($size == 0)
        exit;

    for ($i = 0; $i < $size - 1; $i++)
        $tmp .= db_prepare_int($array[$i]) . ", ";
    $tmp .= db_prepare_int($array[$size - 1]);

    $query = 'DELETE FROM ' . DB_TABLE_THEMES .
        ' WHERE `theme_id` IN (' . $tmp . ')';
    db_exec($query);
}

/**
 * Edit theme.
 *
 * @param $p_id int test id
 * @param $p_theme theme class
 *
 * @return ADORecordSet or false
 */
function edit_theme($p_id, $p_theme)
{
    $t_id = db_prepare_int($p_id);

    $t_theme_number = db_prepare_int($p_theme['theme_number']);
    $t_theme_test_id = db_prepare_int($p_theme['theme_test_id']);
    $t_theme_caption = db_prepare_string($p_theme['theme_caption']);
    $t_theme_numexam = db_prepare_int($p_theme['theme_numexam']);
    $t_theme_conclusions_count =
        db_prepare_int($p_theme['theme_conclusions_count']);
    $t_theme_show_in_results = db_prepare_int($p_theme['theme_show_in_results']);

    $query = 'UPDATE ' . DB_TABLE_THEMES .
        ' SET  `theme_number` = ' . $t_theme_number . ',
            `theme_test_id` = ' . $t_theme_test_id . ',
            `theme_caption` = ' . $t_theme_caption . ',
            `theme_numexam` = ' . $t_theme_numexam . ',
            `theme_conclusions_count` = ' . $t_theme_conclusions_count . ',
            `theme_show_in_results` = ' . $t_theme_show_in_results . '
         WHERE  `theme_id` = ' . $t_id;

    return db_exec($query);
}

/**
 * Get scores by themes for user_result_id.
 *
 * @param $p_user_result_id int user_result id
 *
 * @return array of int  scores array
 */
function get_score_by_theme($p_user_result_id)
{
    $t_user_result_id = db_prepare_int($p_user_result_id);

    $query = 'SELECT    ' . DB_TABLE_THEMES . '.`theme_id`,
            SUM(' . DB_TABLE_USER_ANSWERS . '.`user_answer_score`) AS `score`
            FROM    ' . DB_TABLE_THEMES . ','
        . DB_TABLE_USER_ANSWERS . ','
        . DB_TABLE_QUESTIONS .
        ' WHERE ' . DB_TABLE_USER_ANSWERS . '.`user_answer_user_result_id` = ' . $t_user_result_id . '
            AND     ' . DB_TABLE_USER_ANSWERS . '.`user_answer_question_id` = ' . DB_TABLE_QUESTIONS . '.`question_id`
            AND     ' . DB_TABLE_QUESTIONS . '.`question_theme_id` = ' . DB_TABLE_THEMES . '.`theme_id`
            GROUP BY ' . DB_TABLE_THEMES . '.`theme_id`';

    $result = db_query($query);

    $scores = array();

    foreach ($result as $theme_result) {
        $scores[$theme_result['theme_id']] = $theme_result['score'];
    }

    return $scores;
}
