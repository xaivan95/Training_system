<?php

/**
 * Specify user_result_theme object. User_result_themes table.
 */
class user_result_theme
{
  /**
   * int user_result_theme id
   */
  var $id;

  /**
   * int user_result id
   */
  var $user_result_id;

  /**
   * string theme
   */
  var $theme;

  /**
   * string result
   */
  var $result;
}

/**
 * Add new user result theme.
 *
 * @param integer $p_user_result_id user_result id
 * @param string $p_theme_caption theme
 * @param string $p_result theme result
 *
 * @return integer user_result_theme id
 */
function add_user_result_theme($p_user_result_id, $p_theme_caption, $p_result)
{
  $t_user_result_id = db_prepare_int($p_user_result_id);
  $t_theme_caption = db_prepare_string($p_theme_caption);
  $t_result = db_prepare_string($p_result);

  $query = 'INSERT INTO ' . DB_TABLE_USER_RESULT_THEMES . '(`user_result_themes_user_result_id`,
            `user_result_themes_theme_caption`,
            `user_result_themes_result`)
            VALUES
            (' . $t_user_result_id . ',
            ' . $t_theme_caption . ',
            ' . $t_result . ')';

  db_exec($query);

  return db_insert_id();
}

/**
 * Edit user result theme.
 *
 * @param integer $p_user_result_theme_id user_result_theme id
 * @param integer $p_user_result_id user_result id
 * @param string $p_theme_caption theme
 * @param string $p_result theme result
 */
function edit_user_result_theme($p_user_result_theme_id, $p_user_result_id, $p_theme_caption, $p_result)
{
  $t_user_result_theme_id = db_prepare_int($p_user_result_theme_id);
  $t_user_result_id = db_prepare_int($p_user_result_id);
  $t_theme_caption = db_prepare_string($p_theme_caption);
  $t_result = db_prepare_string($p_result);

  $query = 'UPDATE ' . DB_TABLE_USER_RESULT_THEMES . ' SET `user_result_themes_user_result_id` = ' . $t_user_result_id . ',
         `user_result_themes_theme_caption` = ' . $t_theme_caption . ',
         `user_result_themes_result` = ' . $t_result . '
          WHERE `user_result_themes_id` =' . $t_user_result_theme_id;

  db_exec($query);
}

/**
 * Get user result themes for user_result id.
 *
 * @param integer $p_user_result_id int user_result id
 *
 * @return array user_result_themes array
 */
function get_user_result_themes_for_user_result_id($p_user_result_id)
{
  $t_user_result_id = db_prepare_int($p_user_result_id);

  $query = 'SELECT     *
        FROM     ' . DB_TABLE_USER_RESULT_THEMES . ' WHERE `user_result_themes_user_result_id` = ' . $t_user_result_id .
    ' ORDER BY `user_result_themes_id` ';

  return db_query($query);
}

/**
 * Get user result themes for user_result id array.
 *
 * @param array of integer $p_user_result_id_array user_result id array
 *
 * @return array|string user_result_themes array
 */
function get_user_result_themes_for_user_result_id_array($p_user_result_id_array)
{
  $array = array_values($p_user_result_id_array);
  $size = sizeof($array);

  if ($size == 0) return "";
  $tmp = '';
  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ", ";
  $tmp .= db_prepare_int($array[$size - 1]);

  $query = 'SELECT * FROM ' . DB_TABLE_USER_RESULT_THEMES . ' WHERE `user_result_themes_user_result_id` IN (' . $tmp . ')
        ORDER BY `user_result_themes_id` ';

  return db_query($query);
}

/**
 * Delete user result themes for user_result id.
 *
 * @param integer $p_user_result_id user_result id
 */
function delete_user_result_themes_for_user_result($p_user_result_id)
{
  $t_user_result_id = db_prepare_int($p_user_result_id);

  $query =
    'DELETE FROM ' . DB_TABLE_USER_RESULT_THEMES . ' WHERE `user_result_themes_user_result_id`=' . $t_user_result_id;

  db_query($query);
}

/**
 * Delete user result themes for user result id array.
 * @param array of integer $p_user_results   user_result_id array
 */
function delete_user_result_themes_for_user_results($p_user_results)
{
  $tmp = "";
  $array = array_values($p_user_results);
  $size = sizeof($array);
  if ($size > 0) {
    for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ", ";
    $tmp .= db_prepare_int($array[$size - 1]);
    $query =
      'DELETE FROM ' . DB_TABLE_USER_RESULT_THEMES . ' WHERE `user_result_themes_user_result_id` IN (' . $tmp . ')';
    db_query($query);
  }
}

/**
 * Get answers for user_result_theme id.
 *
 * @param integer $p_user_result_theme_id user_result_theme id
 *
 * @param integer $p_user_result_id int
 * @return array answers array
 */
function get_answers_for_user_result_theme($p_user_result_theme_id, $p_user_result_id)
{
  $t_user_result_theme_id = db_prepare_int($p_user_result_theme_id);

  $query = 'SELECT user_answer_is_right, user_answer_score
            FROM ' . DB_TABLE_USER_ANSWERS . ' WHERE `user_answer_user_result_theme_id` = ' . $t_user_result_theme_id .
    ' AND user_answer_user_result_id=' . $p_user_result_id;

  return db_query($query);
}

