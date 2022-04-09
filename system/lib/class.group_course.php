<?php

/**
 * Specify group_course object. Group_course table.
 */
class group_course
{
  /**
   * int group_course id
   */
  var $id;

  /**
   * string group_course group
   */
  var $group;

  /**
   * string group_course course
   */
  var $course;

  /**
   * limitation dates
   **/

  var $limited_from;
  var $limited_to;
}

/**
 * Get group courses.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter group_course_filter
 *
 * @return array accesses array
 */
function get_group_courses($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0,
                           $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field, array('id', 'group_name', 'title'))) {
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
  $query = 'SELECT ' . DB_TABLE_GROUP_COURSE . '.`id`, ' . DB_TABLE_GROUP_COURSE . '.`limited_from`, ' .
    DB_TABLE_GROUP_COURSE . '.`limited_to`, ' . '`' . DB_TABLE_GROUP . '`.`group_name`,' . DB_TABLE_COURSE . '.`title`
          FROM ' . DB_TABLE_GROUP_COURSE . ',' . '`' . DB_TABLE_GROUP . '`, ' . DB_TABLE_COURSE . ' WHERE ' .
    DB_TABLE_GROUP_COURSE . '.`group_id`=' . '`' . DB_TABLE_GROUP . '`.`id`
            AND ' . DB_TABLE_COURSE . '.`id` = ' . DB_TABLE_GROUP_COURSE . '.`course_id`' . $tmp . '
            ORDER BY ' . $order_str;

  if ($limit_str != '') {
    $query .= ' LIMIT ' . $limit_str;
  }
  return db_query($query);
}

/**
 * Get group_course count by filter.
 *
 * @param $p_filter group_course_filter
 *
 * @return int group_course count
 */
function get_group_courses_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  $query = 'SELECT COUNT(*) AS `_count_`
         FROM ' . DB_TABLE_GROUP_COURSE . ',' . '`' . DB_TABLE_GROUP . '`, ' . DB_TABLE_COURSE . ' WHERE ' .
    DB_TABLE_GROUP_COURSE . '.`group_id`=' . '`' . DB_TABLE_GROUP . '`.`id`
            AND ' . DB_TABLE_COURSE . '.`id` = ' . DB_TABLE_GROUP_COURSE . '.`course_id`' . $tmp;

  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get group courses from group_course id array.
 *
 * @param $p_id_array array of int group course id
 *
 * @return array group courses array
 */
function get_group_courses_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  $query = 'SELECT ' . DB_TABLE_GROUP_COURSE . '.`id`, ' . '`' . DB_TABLE_GROUP . '`.`group_name`,' . DB_TABLE_COURSE . '.`title`
          FROM ' . DB_TABLE_GROUP_COURSE . ',' . '`' . DB_TABLE_GROUP . '`, ' . DB_TABLE_COURSE . ' WHERE ' .
    DB_TABLE_GROUP_COURSE . '.`group_id`=' . '`' . DB_TABLE_GROUP . '`.`id`
            AND ' . DB_TABLE_COURSE . '.`id` = ' . DB_TABLE_GROUP_COURSE . '.`course_id`
            AND ' . DB_TABLE_GROUP_COURSE . '.`id` in(' . $tmp . ')
            ORDER BY ' . DB_TABLE_GROUP_COURSE . '.`id` ASC';

  return db_query($query);
}

/**
 * Delete group course.
 *
 * @param $p_id int group course id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_group_course($p_id)
{
  $t_id = db_prepare_int($p_id);

  $query = 'DELETE FROM ' . DB_TABLE_GROUP_COURSE . ' WHERE id=' . $t_id;
  db_exec($query);
  return (db_last_error() == '');


}

/**
 * Delete group courses.
 *
 * @param $p_id_array array of int group courses id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_group_courses($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_group_course($id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Add group course.
 *
 * @param int $p_group_id group id
 * @param $p_courses
 * @param string $p_limited_from
 * @param string $p_limited_to
 * @return ADORecordSet result
 * @internal param int $p_course_id course id
 */
function add_group_course($p_group_id, $p_courses, $p_limited_from = '', $p_limited_to = '')
{
  $t_group_id = db_prepare_int($p_group_id);
  if ($p_limited_from == '') $p_limited_from = date('Y-m-d');
  if ($p_limited_to == '') $p_limited_to = date('Y-m-d', strtotime('+10 years'));
  $p_limited_from = db_prepare_string($p_limited_from);
  $p_limited_to = db_prepare_string($p_limited_to);
  $values = '';
  $courses_count = count($p_courses);
  for ($i = 0; $i < $courses_count; $i++) {
    $t_course_id = db_prepare_int($p_courses[$i]);
    $values .= "($t_group_id, $t_course_id, $p_limited_from, $p_limited_to),";
  }
  $values = substr($values, 0, -1);

  $query = 'INSERT INTO ' . DB_TABLE_GROUP_COURSE . '(`group_id`, `course_id`, `limited_from`, `limited_to`)
        VALUES ' . $values;

  return db_exec($query);
}

/**
 * Get group course id by ghroup id and course id.
 *
 * @param integer $p_group_id group id
 * @param integer $p_course_id
 * @return integer group course id on success or 0 on failure
 */
function get_group_course_id($p_group_id, $p_course_id)
{
  $t_group_id = db_prepare_int($p_group_id);
  $t_course_id = db_prepare_int($p_course_id);

  $group_courses[] =
    db_extract(DB_TABLE_GROUP_COURSE, '`group_id` = ' . $t_group_id . ' and `course_id`=' . $t_course_id);
  $id = 0;

  if (count($group_courses[0]) > 0) {
    $id = $group_courses[0][0]['id'];
  }

  return $id;
}

/**
 * Get group course.
 *
 * @param integer $p_id group course id
 *
 * @return group_course class
 */
function get_group_course($p_id)
{
  $t_id = db_prepare_int($p_id);
  $query =
    'SELECT ' . DB_TABLE_GROUP_COURSE . '.`id`, ' . '`' . DB_TABLE_GROUP . '`.`group_name`,' . DB_TABLE_GROUP_COURSE .
    '.`limited_from`, ' . DB_TABLE_GROUP_COURSE . '.`limited_to`, ' . DB_TABLE_COURSE . '.`title`
          FROM ' . DB_TABLE_GROUP_COURSE . ',' . '`' . DB_TABLE_GROUP . '`, ' . DB_TABLE_COURSE . ' WHERE ' .
    DB_TABLE_GROUP_COURSE . '.`group_id`=' . '`' . DB_TABLE_GROUP . '`.`id`
            AND ' . DB_TABLE_COURSE . '.`id` = ' . DB_TABLE_GROUP_COURSE . '.`course_id`' . ' AND ' .
    DB_TABLE_GROUP_COURSE . '.`id` = ' . $t_id;

  $group_courses[] = db_query($query);
  $group_course = new group_course();
  if (count($group_courses[0]) > 0) {
    $group_course->id = $group_courses[0][0]['id'];
    $group_course->group = $group_courses[0][0]['group_name'];
    $group_course->course = $group_courses[0][0]['title'];
    $group_course->limited_from = $group_courses[0][0]['limited_from'];
    $group_course->limited_to = $group_courses[0][0]['limited_to'];
  }

  return $group_course;
}

/**
 * Edit group course.
 *
 * @param $p_id int access id
 * @param $p_group_id int new group id
 * @param $p_course_id int new course id
 * @param string $p_limited_from
 * @param string $p_limited_to
 * @return ADORecordSet
 */
function edit_group_course($p_id, $p_group_id, $p_course_id, $p_limited_from = '', $p_limited_to = '')
{
  $t_id = db_prepare_int($p_id);
  $t_group_id = db_prepare_int($p_group_id);
  $t_course_id = db_prepare_int($p_course_id);
  if ($p_limited_from == '') $p_limited_from = date('Y-m-d');
  if ($p_limited_to == '') $p_limited_to = date('Y-m-d', strtotime('+10 years'));
  $p_limited_from = db_prepare_string($p_limited_from);
  $p_limited_to = db_prepare_string($p_limited_to);

  $query = "UPDATE " . DB_TABLE_GROUP_COURSE . " SET `group_id` = $t_group_id,
         `course_id` = $t_course_id , `limited_from` = $p_limited_from, `limited_to`=$p_limited_to 
          WHERE `id` =" . $t_id;

  return db_exec($query);
}

