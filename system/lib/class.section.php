<?php

/**
 * Specify section object. Section table.
 */
class section
{
  /**
   * Section id.
   */
  var $id;

  /**
   * Section name.
   */
  var $name;

  /**
   * Hide section.
   */
  var $hidden;
}

/**
 * Get sections.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter section_filter
 *
 * @return array sections array
 */
function get_sections($p_sort_field = "id", $p_sort_order = "ASC", $p_page = 1, $p_count = 0, $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field, array('id', 'section_name', 'section_hidden'))) {
    $t_sort_field = 'id';
  }

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "$limit, $t_count";
  } else {
    $limit_str = '';
  }

  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';

  return db_extract(DB_TABLE_SECTION, $tmp, $t_sort_field . ' ' . $t_sort_order, $limit_str);
}

/**
 * Get sections from section id array.
 *
 * @param $p_id_array array section id array
 *
 * @return array sections array
 */
function get_sections_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  $query = 'SELECT *
         FROM ' . DB_TABLE_SECTION . ' WHERE ' . DB_TABLE_SECTION . '.`id` IN (' . $tmp . ')
        ORDER BY ' . DB_TABLE_SECTION . '.`id` ';

  return db_query($query);
}

/**
 * Get sections count.
 *
 * @param $p_filter section_filter
 *
 * @return int sections count
 */
function get_sections_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  if ($tmp != '') {
    $tmp = ' WHERE ' . $tmp;
  }

  $query = 'SELECT COUNT(`id`) AS `_count_`
             FROM ' . DB_TABLE_SECTION . $tmp;

  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;

}

/**
 * Add section.
 *
 * @param $p_name string section name
 * @param $p_hidden int course hidden
 *
 * @return ADORecordSet or false
 */
function add_section($p_name, $p_hidden)
{
  $t_name = db_prepare_string($p_name);
  $t_hidden = db_prepare_int($p_hidden);

  $query = 'INSERT INTO ' . DB_TABLE_SECTION . '(`section_name`, `section_hidden`) 
    VALUES(' . $t_name . ',' . $t_hidden . ')';
  return db_exec($query);
}

/**
 * Delete section.
 *
 * @param $p_id int section id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_section($p_id)
{
  $t_id = db_prepare_int($p_id);
  $tests_count = get_tests_count_for_section_id($t_id);
  $groups_count = get_groups_count_for_section_id($t_id);

  if (($tests_count == 0) && ($groups_count == 0)) {
    $query = 'DELETE FROM ' . DB_TABLE_SECTION . ' WHERE id=' . $t_id;
    db_exec($query);

    return (db_last_error() == "");

  }

  return FALSE;
}

/**
 * Delete sections.
 *
 * @param $p_id_array array of int sections id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_sections($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_section($id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Get section.
 *
 * @param $p_id int section id
 *
 * @return section class
 */
function get_section($p_id)
{
  $t_id = db_prepare_int($p_id);

  $sections[] = db_extract(DB_TABLE_SECTION, '`id`=' . $t_id);
  $section = new section();
  if (count($sections[0]) > 0) {
    $section->id = $sections[0][0]['id'];
    $section->name = $sections[0][0]['section_name'];
    $section->hidden = $sections[0][0]['section_hidden'];
  }

  return $section;
}

/**
 * Edit section.
 *
 * @param $p_id int section id
 * @param $p_name string new section name
 * @param $p_hidden int section hidden
 *
 * @return ADORecordSet or false
 */
function edit_section($p_id, $p_name, $p_hidden)
{
  $t_id = db_prepare_int($p_id);
  $t_name = db_prepare_string($p_name);
  $t_hidden = db_prepare_string($p_hidden);

  $query = 'UPDATE ' . DB_TABLE_SECTION . ' SET `section_name` = ' . $t_name . ',
         `section_hidden` = ' . $t_hidden . ' WHERE `id` =' . $t_id;

  return db_exec($query);
}

/**
 * Get section id.
 *
 * @param $p_name string section name
 *
 * @return int section id. 0- section does not exist
 */
function get_section_id($p_name)
{
  $t_name = db_prepare_string(trim($p_name));

  $sections[] = db_extract(DB_TABLE_SECTION, '`section_name` = ' . $t_name);
  $id = 0;

  if (count($sections[0]) > 0) {
    $id = $sections[0][0]['id'];
  }

  return $id;
}

/**
 * Get unhidden sections for user id.
 *
 * @param $p_user_id int user id
 *
 * @return array sections array
 */
function get_unhidden_sections_for_user_id($p_user_id)
{
  $t_user_id = db_prepare_int($p_user_id);

  $query = /** @lang MySQL */
    'SELECT DISTINCT ' . DB_TABLE_SECTION . '.*
        FROM    ' . DB_TABLE_SECTION . ',' . DB_TABLE_USER . ',' . DB_TABLE_GROUP_SECTION . ' WHERE ' .
    DB_TABLE_SECTION . '.`section_hidden` = 0
         AND    ' . DB_TABLE_USER . '.`id` = ' . $t_user_id . ' AND (' . DB_TABLE_USER . '.`user_group_id` = ' .
    DB_TABLE_GROUP_SECTION . '.`group_id` 
        OR ' . DB_TABLE_GROUP_SECTION . '.`group_id` IN 
        (SELECT `group_id` FROM ' . DB_TABLE_GROUP_USER . ' WHERE ' . DB_TABLE_GROUP_USER . '.`user_id`=' . $t_user_id . ') )  
        AND ' . DB_TABLE_GROUP_SECTION . '.`section_id` = ' . DB_TABLE_SECTION . '.`id`
        AND CURDATE() BETWEEN `limited_from` AND `limited_to`   
        ORDER BY ' . DB_TABLE_SECTION . '.`section_name` ';

  return db_query($query);
}

