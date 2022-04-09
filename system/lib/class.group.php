<?php

/**
 * Specify group object. Group table.
 */
class group
{
  /**
   * int group id
   */
  var $id;

  /**
   * string group name
   */
  var $name;

  /**
   * string group description
   */
  var $description;

  /**
   * int group is hidden
   */
  var $login_available;
  /**
   * int user may register myself in this group
   */
  var $registration_available;
}

/**
 * Get groups.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter book_filter
 *
 * @return array groups array
 */
function get_groups($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0, $p_filter = NULL,
                    $p_user_id = NULL)
{
  global $WEB_APP;
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  $t_user_id = db_prepare_int($p_user_id);

  if (isset($p_user_id) && user_have_grant_id($p_user_id, $WEB_APP['settings']['limited_reports_grant_id'])) {
    $query = "SELECT `groups`.id, `groups`.group_name, `groups`.group_description, `groups`.group_hidden, `groups`.group_registration_available, `groups`.group_login_available 
       FROM `" . DB_TABLE_GROUP . "` as groups
    LEFT JOIN `" . DB_TABLE_GROUP_USER . "` as user_groups on groups.`id`=user_groups.`group_id`
    WHERE user_groups.`user_id`= $t_user_id OR groups.id= (SELECT user_group_id FROM `webclass_user` WHERE id=$t_user_id)
    ORDER BY group_name DESC";
    return db_query($query);

  } else {
    if ($t_sort_field == '') $t_sort_field = 'id';
    if (!in_array($t_sort_field,
      array('id', 'group_name', 'group_description', 'group_login_available', 'group_registration_available'))) {
      $t_sort_field = 'id';
    }
    if ($p_count != 0) {
      $limit = ($t_page - 1) * $t_count;
      $limit_str = "$limit, $t_count";
    } else {
      $limit_str = '';
    }
    $where = ($p_filter != NULL) ? $p_filter->query() : '';
    return db_extract(DB_TABLE_GROUP, $where, $t_sort_field . ' ' . $t_sort_order, $limit_str);
  }
}

/**
 * Get unhidden groups.
 *
 * @return array groups array
 */
function get_visible_groups()
{
  return db_extract(DB_TABLE_GROUP, '`group_login_available` = 1', 'group_name');
}

/**
 * Get groups count by filter.
 *
 * @param $p_filter group_filter
 *
 * @return int groups count
 */
function get_groups_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  if ($tmp != '') {
    $tmp = ' WHERE ' . $tmp;
  }

  $query = 'SELECT COUNT(*) AS `_count_`
             FROM `' . DB_TABLE_GROUP . '` ' . $tmp;
  global $adodb;
  $result = $adodb->Execute($query);

  $count = 0;
  if ($adodb->ErrorMsg() == '') {
    if (!$result->EOF) {
      $count = $result->fields['_count_'];
    }
  }
  return $count;
}

/**
 * Get groups from groups id array.
 *
 * @param $p_id_array array of int groups id
 *
 * @return array groups array
 */
function get_groups_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  return db_extract(DB_TABLE_GROUP, "id IN($tmp)", 'id ASC');
}

/**
 * Delete group.
 *
 * @param $p_id int group id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_group($p_id)
{
  global $adodb;

  $t_id = db_prepare_int($p_id);

  $count = db_count(DB_TABLE_USER, '`user_group_id` = ' . $t_id);
  $count += db_count(DB_TABLE_GROUP_COURSE, '`group_id` = ' . $t_id);
  if ($count == 0) {
    $query = 'DELETE FROM `' . DB_TABLE_GROUP . '` WHERE id=' . $t_id;
    $adodb->Execute($query);
    return ($adodb->ErrorMsg() == '');
  } else {
    return FALSE;
  }


}

/**
 * Delete groups.
 *
 * @param $p_id_array array of int groups id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_groups($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_group($id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Add group.
 *
 * @param string $p_name group name
 * @param string $p_description group description
 * @param int $p_hidden group hidden
 * @param int $p_registration_available May user register in this group?
 * @return ADORecordSet result
 */
function add_group($p_name, $p_description, $p_hidden, $p_registration_available)
{
  global $adodb;
  $t_name = db_prepare_string($p_name);
  $t_description = db_prepare_string($p_description);
  $t_hidden = db_prepare_int($p_hidden);
  $t_registration_available = db_prepare_int($p_registration_available);

  $query = "INSERT INTO " . DB_TABLE_GROUP . "
        (`group_name`, `group_description`, `group_login_available`, `group_registration_available`)
        VALUES($t_name, $t_description, $t_hidden, $t_registration_available);";

  return $adodb->Execute($query);
}

/**
 * Get group id by group name.
 *
 * @param $p_name string group name
 *
 * @return int group id on success or 0 on failure
 */
function get_group_id($p_name)
{
  $t_name = db_prepare_string(trim($p_name));

  $groups[] = db_extract(DB_TABLE_GROUP, '`group_name` = ' . $t_name);
  $id = 0;

  if (count($groups[0]) > 0) {
    $id = $groups[0][0]['id'];
  }

  return $id;
}

/**
 * Get group.
 *
 * @param integer $p_id group id
 *
 * @return group class
 */
function get_group($p_id)
{
  $t_id = db_prepare_int($p_id);

  $groups[] = db_extract(DB_TABLE_GROUP, '`id`=' . $t_id);
  $group = new group();
  if (count($groups[0]) > 0) {
    $group->id = $groups[0][0]['id'];
    $group->name = $groups[0][0]['group_name'];
    $group->description = $groups[0][0]['group_description'];
    $group->login_available = $groups[0][0]['group_login_available'];
    $group->registration_available = $groups[0][0]['group_registration_available'];
  }

  return $group;
}

/**
 * Get groups available for registration.
 *
 * @return array
 */
function get_group_for_registration()
{

  return db_extract(DB_TABLE_GROUP, '`group_registration_available` = 1', 'group_name');

}

/**
 * Edit group.
 *
 * @param int $p_id group id
 * @param string $p_name new group name
 * @param string $p_description new group description
 * @param int $p_hidden group is hidden *
 * @param int $p_registration_available
 * @return ADORecordSet result
 */
function edit_group($p_id, $p_name, $p_description, $p_hidden, $p_registration_available)
{
  $t_id = db_prepare_int($p_id);

  $t_name = db_prepare_string(trim($p_name));
  $t_description = db_prepare_string(trim($p_description));
  $t_hidden = db_prepare_int($p_hidden);
  $t_registration_available = db_prepare_int($p_registration_available);

  $query = "UPDATE " . DB_TABLE_GROUP . " SET
            `group_name` =  $t_name,
            `group_description` =  $t_description,
            `group_login_available` = $t_hidden,
            `group_registration_available` = $t_registration_available
          WHERE `id` = $t_id;";

  return db_exec($query);
}

