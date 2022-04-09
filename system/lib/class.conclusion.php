<?php

/**
 * Specify conclusion object. Conclusions table.
 */
class conclusion
{
    /**
     * int conclusion id.
     */
    var $id;

    /**
     * string conclusion theme.
     */
    var $theme;

    /**
     * int conclusion number.
     */
    var $number;

    /**
     * int conclusion top.
     */
    var $top;

    /**
     * int conclusion low.
     */
    var $low;

    /**
     * string conclusion text.
     */
    var $text;
}

/**
 * Add conclusion.
 *
 * @param $p_conclusion conclusion class
 *
 * @return ADORecordSet or false
 */
function add_conclusion($p_conclusion)
{
    $t_conclusion_theme_id = db_prepare_int($p_conclusion->theme);
    $t_conclusion_number = db_prepare_int($p_conclusion->number);
    $t_conclusion_top = db_prepare_int($p_conclusion->top);
    $t_conclusion_low = db_prepare_int($p_conclusion->low);
    $t_conclusion_text = db_prepare_string($p_conclusion->text);

    $query = 'INSERT INTO '.DB_TABLE_CONCLUSIONS.'(
            `conclusion_theme_id`,
            `conclusion_number`,
            `conclusion_top`,
            `conclusion_low`,
            `conclusion_text`)
        VALUES(
            '.$t_conclusion_theme_id.',
            '.$t_conclusion_number.',
            '.$t_conclusion_top.',
            '.$t_conclusion_low.',
            '.$t_conclusion_text.')';

    return db_exec($query);
}

/**
 * Delete conclusions for theme id.
 *
 * @param $p_id int theme id
 *
 * @return ADORecordSet or false
 */
function delete_conclusions_for_theme_id($p_id)
{
    $t_id = db_prepare_int($p_id);

    $query = 'DELETE FROM '.DB_TABLE_CONCLUSIONS.
         ' WHERE `conclusion_theme_id` = '.$t_id;

    return db_exec($query);
}

/**
 * Get conclusions text for user_result id.
 *
 * @param $p_user_result_id int user_result id
 *
 * @return array user_result_theme class array
 */
function get_conclusions_text_for_user_result_id($p_user_result_id)
{
    $scores = get_score_by_theme($p_user_result_id);

    global $adodb;
    $user_result_themes = array();
    foreach($scores as $p_theme_id=>$p_score)
    {
        $t_theme_id = db_prepare_int($p_theme_id);
        $t_score = db_prepare_float($p_score);

        $query = 'SELECT '.DB_TABLE_THEMES.'.`theme_caption`,'.
                DB_TABLE_CONCLUSIONS.'.`conclusion_text`
            FROM    '.DB_TABLE_THEMES.',
                '.DB_TABLE_CONCLUSIONS.
            ' WHERE '.DB_TABLE_THEMES.'.`theme_id` = '.$t_theme_id.'
            AND '.$t_score.' >= '.
                    DB_TABLE_CONCLUSIONS.'.`conclusion_low`
            AND '.$t_score.' <= '.
                    DB_TABLE_CONCLUSIONS.'.`conclusion_top`
            AND     '.DB_TABLE_CONCLUSIONS.'.`conclusion_theme_id` = '.
                    DB_TABLE_THEMES.'.`theme_id`';


        $result = $adodb->Execute($query);

        if ($adodb->ErrorMsg() == "")
        {
            while (!$result->EOF)
            {
                $user_result_theme = new user_result_theme();
                $user_result_theme->theme =
                    $result->fields['theme_caption'];
                $user_result_theme->result =
                    $result->fields['conclusion_text'];
                $user_result_themes[] = $user_result_theme;
                $result->MoveNext();
            }
        }
    }

    return $user_result_themes;
}

/**
 * Get conclusions text for user_result id by max scores.
 *
 * @param $p_user_result_id int user_result id
 * @param $p_max_scores array int array
 *
 * @return array user_result_theme class array
 */
function get_conclusions_text_for_user_result_id_by_max_scores(
                                    $p_user_result_id,
                                    $p_max_scores
                                    )
{
    global $adodb;

    $scores = get_score_by_theme($p_user_result_id);

    //$text = "";
    $user_result_themes = array();
    foreach($scores as $p_theme_id=>$p_score)
    {
        $t_theme_id = db_prepare_int($p_theme_id);
        $t_score = db_prepare_int($p_score);
        $t_max_score = db_prepare_int($p_max_scores[$p_theme_id]);

        $query = 'SELECT    '.DB_TABLE_THEMES.'.`theme_caption`,'.
                DB_TABLE_CONCLUSIONS.'.`conclusion_text`
            FROM    '.DB_TABLE_THEMES.','.
                DB_TABLE_CONCLUSIONS.
            ' WHERE '.DB_TABLE_THEMES.'.`theme_id` = '.$t_theme_id.
            ' AND   '.$t_score.'/'.$t_max_score.'*100 >= '.
                        DB_TABLE_CONCLUSIONS.'.`conclusion_low`
            AND '.$t_score.'/'.$t_max_score.'*100 <= '.
                        DB_TABLE_CONCLUSIONS.'.`conclusion_top`
            AND     '.DB_TABLE_CONCLUSIONS.'.`conclusion_theme_id` = '.
                        DB_TABLE_THEMES.'.`theme_id`';

        $result = $adodb->Execute($query);

        if ($adodb->ErrorMsg() == "")
        {
            while (!$result->EOF)
            {
                $user_result_theme = new user_result_theme();
                $user_result_theme->theme =
                        $result->fields['theme_caption'];
                $user_result_theme->result =
                        $result->fields['conclusion_text'];
                $user_result_themes[] = $user_result_theme;
                $result->MoveNext();
            }
        }
    }

    return $user_result_themes;
}

