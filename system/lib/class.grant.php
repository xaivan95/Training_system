<?php

/**
 * Specify grant object. Grant table.
 */
class grant
{
  /**
   * int grant id
   */
  var $id;

  /**
   * string grant name
   */
  var $title;

  /**
   * bool grant hidden
   */
  var $hidden;
}

/**
 * Get grants.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter grant_filter
 *
 * @return array grants array
 */
function get_grants($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0, $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field, array('id', 'grant_title', 'grant_hidden'))) {
    $t_sort_field = 'id';
  }

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "$limit, $t_count";
  } else {
    $limit_str = '';
  }

  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';

  return db_extract(DB_TABLE_GRANT, $tmp, $t_sort_field . ' ' . $t_sort_order, $limit_str);
}

/**
 * Get grants count by filter.
 *
 * @param $p_filter grant_filter
 *
 * @return int grants count
 */
function get_grants_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  if ($tmp != '') {
    $tmp = ' WHERE ' . $tmp;
  }

  $query = 'SELECT COUNT(*) as `_count_`
             FROM `' . DB_TABLE_GRANT . '`' . $tmp;
  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get grants from grants id array.
 *
 * @param $p_id_array array of int grants id
 *
 * @return array grants array
 */
function get_grants_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  return db_extract(DB_TABLE_GRANT, "id IN($tmp)", 'id ASC');
}

/**
 * Delete grant.
 *
 * @param $p_id int grant id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_grant($p_id)
{

  $t_id = db_prepare_int($p_id);

  $count = db_count(DB_TABLE_USER_GRANTS, '`ug_grant_id` = ' . $t_id);
  $count += db_count(DB_TABLE_ACCESS, '`grant_id` = ' . $t_id);

  if ($count == 0) {
    $query = 'DELETE FROM `' . DB_TABLE_GRANT . '` WHERE id=' . $t_id;
    db_exec($query);
    return (db_last_error() == '');
  }

  return FALSE;
}

/**
 * Delete grants.
 *
 * @param $p_id_array  array of int grants id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_grants($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_grant($id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Add grant.
 *
 * @param $p_title string grant title
 * @param $p_hidden int is hidden grant
 *
 * @return ADORecordSet result
 */
function add_grant($p_title, $p_hidden)
{
  $t_title = db_prepare_string($p_title);
  $t_hidden = db_prepare_int($p_hidden);

  $query = 'INSERT INTO `' . DB_TABLE_GRANT . '`(`grant_title`, `grant_hidden`)
        VALUES(' . $t_title . ',' . $t_hidden . ')';

  return db_exec($query);
}

/**
 * Get grant id by grant title.
 *
 * @param $p_title string grant title
 *
 * @return int grant id on success or 0 on failure
 */
function get_grant_id($p_title)
{
  $t_title = db_prepare_string(trim($p_title));

  $grants[] = db_extract(DB_TABLE_GRANT, '`grant_title` = ' . $t_title);
  $id = 0;

  if (count($grants[0]) > 0) {
    $id = $grants[0][0]['id'];
  }

  return $id;
}

/**
 * Get grant.
 *
 * @param integer $p_id grant id
 *
 * @return grant class
 */
function get_grant($p_id)
{
  $t_id = db_prepare_int($p_id);

  $grants[] = db_extract(DB_TABLE_GRANT, '`id`=' . $t_id);
  $grant = new grant();
  if (count($grants[0]) > 0) {
    $grant->id = $grants[0][0]['id'];
    $grant->title = $grants[0][0]['grant_title'];
    $grant->hidden = $grants[0][0]['grant_hidden'];
  }

  return $grant;
}

/**
 * Edit grant.
 *
 * @param $p_id int grant id
 * @param $p_title int new grant title
 * @param $p_hidden int (0, 1) is grant hidden
 *
 * @return ADORecordSet result
 */
function edit_grant($p_id, $p_title, $p_hidden)
{
  $t_id = db_prepare_int($p_id);
  $t_title = db_prepare_string($p_title);
  $t_hidden = db_prepare_string($p_hidden);

  $query = 'UPDATE `' . DB_TABLE_GRANT . '` SET `grant_title` = ' . $t_title . ',
         `grant_hidden` = ' . $t_hidden . '
          WHERE `id` =' . $t_id;

  return db_exec($query);
}

/**
 * Add grant to user.
 *
 * @param $p_user_id int user id
 * @param $p_grant_id int grant id
 */
function add_user_grant($p_user_id, $p_grant_id)
{
  $t_user_id = db_prepare_int($p_user_id);
  $t_grant_id = db_prepare_int($p_grant_id);
  $grant = get_grant($t_grant_id);
  if (isset($grant->id)) {
    db_query('INSERT INTO `' . DB_TABLE_USER_GRANTS . '`(`ug_user_id`, `ug_grant_id`)
            VALUES(' . $t_user_id . ', ' . $t_grant_id . ')');
  }
}

/**
 * Get user grants.
 *
 * @param $p_user_id int user id
 *
 * @return array grants array
 */
function get_grants_by_user_id($p_user_id)
{
  $t_user_id = db_prepare_int($p_user_id);
  return db_extract(DB_TABLE_USER_GRANTS, '`ug_user_id` = ' . $t_user_id);
}

/**
 * Delete user grants.
 *
 * @param $p_user_id int user id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_user_grants($p_user_id)
{
  $t_user_id = db_prepare_int($p_user_id);
  $query = 'DELETE FROM `' . DB_TABLE_USER_GRANTS . '` WHERE `ug_user_id`=' . $t_user_id;
  db_exec($query);

  return (db_last_error() == '');
}

function user_have_grant($p_user_id, $p_grant_title)
{
  $t_user_id = db_prepare_int($p_user_id);
  $where = "`ug_user_id` = $t_user_id AND `ug_grant_id` = " . get_grant_id($p_grant_title);
  $result = db_extract(DB_TABLE_USER_GRANTS, $where);
  return $result[0];
}

function user_have_grant_id($p_user_id, $p_grant_id)
{
  $t_user_id = db_prepare_int($p_user_id);
  $t_grant_id = db_prepare_int($p_grant_id);
  $where = "`ug_user_id` = $t_user_id AND `ug_grant_id` = $t_grant_id";
  $result = db_extract(DB_TABLE_USER_GRANTS, $where);
  return $result[0];
}