<?php


class group_user
{
  var $id;
  var $group;
  var $user;
}

function add_user_to_group($p_user_id, $p_group_id)
{
  $t_user_id = db_prepare_int($p_user_id);
  $t_group_id = db_prepare_int($p_group_id);
  $query = "INSERT INTO " . DB_TABLE_GROUP_USER . "(`user_id`, `group_id`) VALUES ($t_user_id,  $t_group_id )";
  return db_exec($query);
}

function add_users_to_group($p_users_id, $p_group_id)
{
  $t_group_id = db_prepare_int($p_group_id);
  $values = '';
  $users_count = count($p_users_id);
  for ($i = 0; $i < $users_count; $i++) {
    $t_user_id = db_prepare_int($p_users_id[$i]);
    $values .= "($t_group_id, $t_user_id),";
  }
  $values = substr($values, 0, -1);
  $query = "INSERT INTO " . DB_TABLE_GROUP_USER . "(`group_id`, `user_id`) VALUES $values";
  return db_exec($query);
}

function get_user_groups($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0,
                         $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field, array('id', 'group_name', 'user_name'))) {
    $t_sort_field = 'id';
  }

  $order_str = "`$t_sort_field` $t_sort_order";

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "$limit, $t_count";
  } else {
    $limit_str = '';
  }

  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  $query = 'SELECT ' . DB_TABLE_GROUP_USER . '.`id`, ' . DB_TABLE_USER . '.`user_name`,' . DB_TABLE_GROUP .
    '.`group_name` FROM ' . DB_TABLE_GROUP_USER . ',' . DB_TABLE_USER . ', ' . DB_TABLE_GROUP . ' WHERE ' .
    DB_TABLE_GROUP_USER . '.`user_id`=' . DB_TABLE_USER . '.`id`
            AND ' . DB_TABLE_GROUP . '.`id` = ' . DB_TABLE_GROUP_USER . '.`group_id`' . $tmp . '
            ORDER BY ' . $order_str;

  if ($limit_str != '') {
    $query .= ' LIMIT ' . $limit_str;
  }

  return db_query($query);
}

function get_user_groups_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  $query = 'SELECT COUNT(*) AS `_count_`
         FROM ' . DB_TABLE_GROUP_USER . ',' . DB_TABLE_USER . ', ' . DB_TABLE_GROUP . ' WHERE ' . DB_TABLE_GROUP_USER .
    '.`user_id`=' . DB_TABLE_USER . '.`id`
            AND ' . DB_TABLE_GROUP . '.`id` = ' . DB_TABLE_GROUP_USER . '.`group_id`' . $tmp;

  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

function get_user_groups_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  $query = 'SELECT ' . DB_TABLE_GROUP_USER . '.`id`, ' . DB_TABLE_USER . '.`user_name`,' . DB_TABLE_GROUP . '.`group_name`
     FROM ' . DB_TABLE_GROUP_USER . ',' . DB_TABLE_USER . ', ' . DB_TABLE_GROUP . ' WHERE ' . DB_TABLE_GROUP_USER .
    '.`user_id`=' . DB_TABLE_USER . '.`id` AND ' . DB_TABLE_GROUP . '.`id` = ' . DB_TABLE_GROUP_USER . '.`group_id`
    AND ' . DB_TABLE_GROUP_USER . '.`id` in(' . $tmp . ')
    ORDER BY ' . DB_TABLE_GROUP_USER . '.`id` ASC';

  return db_query($query);
}

function delete_user_group($p_id)
{
  $t_id = db_prepare_int($p_id);
  $query = 'DELETE FROM ' . DB_TABLE_GROUP_USER . ' WHERE id=' . $t_id;
  db_exec($query);
  return (db_last_error() == '');
}

function delete_user_groups($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_user_group($id);
    $tmp = $tmp && $result;
  }
  return $tmp;
}

function get_user_group_id($p_user_id, $p_group_id)
{
  $t_user_id = db_prepare_int($p_user_id);
  $t_group_id = db_prepare_int($p_group_id);
  $user_groups[] = db_extract(DB_TABLE_GROUP_USER, "`user_id` =  $t_user_id  AND `group_id`=$t_group_id");
  $id = 0;
  if (count($user_groups[0]) > 0) {
    $id = $user_groups[0][0]['id'];
  }
  return $id;
}

function get_user_group($p_id)
{
  $t_id = db_prepare_int($p_id);
  $query = /** @lang MySQL */
    'SELECT ' . DB_TABLE_GROUP_USER . '.`id`, ' . DB_TABLE_USER . '.`user_name`,' . DB_TABLE_GROUP . '.`group_name`        
        FROM ' . DB_TABLE_GROUP_USER . ',' . DB_TABLE_USER . ', ' . DB_TABLE_GROUP . ' WHERE ' . DB_TABLE_GROUP_USER .
    '.`user_id`=' . DB_TABLE_USER . '.`id` AND ' . DB_TABLE_GROUP . '.`id` = ' . DB_TABLE_GROUP_USER . '.`group_id`' .
    ' AND ' . DB_TABLE_GROUP_USER . '.`id` = ' . $t_id;

  $user_groups[] = db_query($query);
  $group_user = new group_user();
  if (count($user_groups[0]) > 0) {
    $group_user->id = $user_groups[0][0]['id'];
    $group_user->user = $user_groups[0][0]['user_name'];
    $group_user->group = $user_groups[0][0]['group_name'];
  }

  return $group_user;
}

function edit_user_group($p_id, $p_user_id, $p_group_id)
{
  $t_id = db_prepare_int($p_id);
  $t_user_id = db_prepare_int($p_user_id);
  $t_group_id = db_prepare_int($p_group_id);
  $query = /** @lang MySQL */
    "UPDATE " . DB_TABLE_GROUP_USER . " SET `user_id` =   $t_user_id, `group_id` =  $t_group_id  WHERE `id` = $t_id";
  return db_exec($query);
}