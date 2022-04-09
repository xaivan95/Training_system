<?php

/**
 * Specify group_section object. Group_section table.
 */
class group_section
{
  /**
   * int group_section id
   */
  var $id;

  /**
   * string group_section group
   */
  var $group;

  /**
   * string group_section section
   */
  var $section;

  /**
   * limitation dates
   **/

  var $limited_from;
  var $limited_to;
}

/**
 * Get group sections.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter group_section_filter
 *
 * @return array accesses array
 */
function get_group_sections($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0,
                            $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field, array('id', 'group_name', 'section_name'))) {
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
  $tbl_gs = DB_TABLE_GROUP_SECTION;
  $tbl_group = DB_TABLE_GROUP;
  $tbl_section = DB_TABLE_SECTION;
  $query = "SELECT  `$tbl_gs`.`id`, `$tbl_gs`.`limited_from`,`$tbl_gs`.`limited_to`, 
                    `$tbl_group`.`group_name`, `$tbl_section`.`section_name`
            FROM  `$tbl_gs` ,`$tbl_group`,`$tbl_section` 
            WHERE  `$tbl_gs`.`group_id`=`$tbl_group`.`id` AND  `$tbl_section`.`id` = `$tbl_gs`.`section_id` $tmp 
            ORDER BY $order_str";

  if ($limit_str != '') {
    $query .= ' LIMIT ' . $limit_str;
  }
  return db_query($query);
}

/**
 * Get group_section count by filter.
 *
 * @param $p_filter group_section_filter
 *
 * @return int group_section count
 */
function get_group_sections_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  $query = 'SELECT COUNT(*) AS `_count_`
         FROM ' . DB_TABLE_GROUP_SECTION . ',' . '`' . DB_TABLE_GROUP . '`, ' . DB_TABLE_SECTION . ' WHERE ' .
    DB_TABLE_GROUP_SECTION . '.`group_id`=' . '`' . DB_TABLE_GROUP . '`.`id`
            AND ' . DB_TABLE_SECTION . '.`id` = ' . DB_TABLE_GROUP_SECTION . '.`section_id`' . $tmp;

  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get group sections from group_section id array.
 *
 * @param $p_id_array array of int group section id
 *
 * @return array group sections array
 */
function get_group_sections_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);
  $query =
    'SELECT ' . DB_TABLE_GROUP_SECTION . '.`id`, ' . '`' . DB_TABLE_GROUP . '`.`group_name`,' . DB_TABLE_SECTION . '.`section_name`
          FROM ' . DB_TABLE_GROUP_SECTION . ',' . '`' . DB_TABLE_GROUP . '`, ' . DB_TABLE_SECTION . ' WHERE ' .
    DB_TABLE_GROUP_SECTION . '.`group_id`=' . '`' . DB_TABLE_GROUP . '`.`id`
            AND ' . DB_TABLE_SECTION . '.`id` = ' . DB_TABLE_GROUP_SECTION . '.`section_id`
            AND ' . DB_TABLE_GROUP_SECTION . '.`id` in(' . $tmp . ')
            ORDER BY ' . DB_TABLE_GROUP_SECTION . '.`id` ASC';

  return db_query($query);
}

/**
 * Delete group section.
 *
 * @param $p_id int group section id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_group_section($p_id)
{
  $t_id = db_prepare_int($p_id);

  $query = 'DELETE FROM ' . DB_TABLE_GROUP_SECTION . ' WHERE `id`=' . $t_id;
  db_exec($query);
  return (db_last_error() == '');
}

/**
 * Delete group sections.
 *
 * @param $p_id_array array of int group sections id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_group_sections($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_group_section($id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Get groups count for section id.
 *
 * @param $p_id int section id
 *
 * @return int groups count
 */
function get_groups_count_for_section_id($p_id)
{
  $t_id = db_prepare_int($p_id);

  return db_count(DB_TABLE_GROUP_SECTION, '`section_id` = ' . $t_id);
}

/**
 * Add group section.
 *
 * @param int $p_group_id group id
 * @param $p_sections array of int section id
 * @param string $p_limited_from
 * @param string $p_limited_to
 * @return ADORecordSet result
 */
function add_group_section($p_group_id, $p_sections, $p_limited_from = '', $p_limited_to = '')
{
  $t_group_id = db_prepare_int($p_group_id);
  if ($p_limited_from == '') $p_limited_from = date('Y-m-d');
  if ($p_limited_to == '') $p_limited_to = date('Y-m-d', strtotime('+10 years'));
  $p_limited_from = db_prepare_string($p_limited_from);
  $p_limited_to = db_prepare_string($p_limited_to);
  $values = '';
  $sections_count = count($p_sections);
  for ($i = 0; $i < $sections_count; $i++) {
    $t_section_id = db_prepare_int($p_sections[$i]);
    $values .= "($t_group_id, $t_section_id, $p_limited_from, $p_limited_to),";
  }
  $values = substr($values, 0, -1);

  $query = 'INSERT INTO ' . DB_TABLE_GROUP_SECTION . '(`group_id`, `section_id`, `limited_from`, `limited_to`)
    VALUES ' . $values;

  return db_exec($query);
}

/**
 * Get group section id by ghroup id and section id.
 *
 * @param $p_group_id int group id
 * @param $p_section_id int
 * @return int group section id on success or 0 on failure
 *
 */
function get_group_section_id($p_group_id, $p_section_id)
{
  $t_group_id = db_prepare_int($p_group_id);
  $t_section_id = db_prepare_int($p_section_id);

  $group_sections[] =
    db_extract(DB_TABLE_GROUP_SECTION, '`group_id` = ' . $t_group_id . ' and `section_id`=' . $t_section_id);
  $id = 0;

  if (count($group_sections[0]) > 0) {
    $id = $group_sections[0][0]['id'];
  }

  return $id;
}

/**
 * Get group section.
 *
 * @param integer $p_id group section id
 *
 * @return group_section class
 */
function get_group_section($p_id)
{
  $t_id = db_prepare_int($p_id);
  $tbl_gs = DB_TABLE_GROUP_SECTION;
  $tbl_group = DB_TABLE_GROUP;
  $tbl_section = DB_TABLE_SECTION;
  $query = /** @lang MySQL */
    "SELECT $tbl_gs.`id`, $tbl_gs.`limited_from`, $tbl_gs.`limited_to`, `$tbl_group`.`group_name`,
       $tbl_section.`section_name`
    FROM $tbl_gs,`$tbl_group`, $tbl_section 
    WHERE $tbl_gs.`group_id`=`$tbl_group`.`id` AND $tbl_section.`id` = $tbl_gs.`section_id` 
      AND $tbl_gs.`id` = $t_id";

  $group_sections[] = db_query($query);
  $group_section = new group_section();
  if (count($group_sections[0]) > 0) {
    $group_section->id = $group_sections[0][0]['id'];
    $group_section->group = $group_sections[0][0]['group_name'];
    $group_section->section = $group_sections[0][0]['section_name'];
    $group_section->limited_from = $group_sections[0][0]['limited_from'];
    $group_section->limited_to = $group_sections[0][0]['limited_to'];
  }

  return $group_section;
}

/**
 * Edit group section.
 *
 * @param $p_id int access id
 * @param $p_group_id int new group id
 * @param $p_section_id int new section id
 * @param string $p_limited_from
 * @param string $p_limited_to
 * @return ADORecordSet result
 */
function edit_group_section($p_id, $p_group_id, $p_section_id, $p_limited_from = '', $p_limited_to = '')
{
  $t_id = db_prepare_int($p_id);
  $t_group_id = db_prepare_int($p_group_id);
  $t_section_id = db_prepare_int($p_section_id);
  if ($p_limited_from == '') $p_limited_from = date('Y-m-d');
  if ($p_limited_to == '') $p_limited_to = date('Y-m-d', strtotime('+10 years'));
  $p_limited_from = db_prepare_string($p_limited_from);
  $p_limited_to = db_prepare_string($p_limited_to);

  $query = "UPDATE " . DB_TABLE_GROUP_SECTION . " SET `group_id` =  $t_group_id , `section_id` =  $t_section_id,
    `limited_from` = $p_limited_from, `limited_to`=$p_limited_to 
    WHERE `id` = $t_id";

  return db_exec($query);
}

