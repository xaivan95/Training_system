<?php

/**
 * Specify course object. Course table.
 */
class course
{
  /**
   * int course id
   */
  var $id;

  /**
   * string course name
   */
  var $title;

  /**
   * int course hidden
   */
  var $hidden;
}

/**
 * Get courses.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter course_filter
 *
 * @return array courses array
 */
function get_courses($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0, $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field, array('id', 'title', 'hidden'))) {
    $t_sort_field = 'id';
  }

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "$limit, $t_count";
  } else {
    $limit_str = '';
  }

  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';

  return db_extract(DB_TABLE_COURSE, $tmp, $t_sort_field . ' ' . $t_sort_order, $limit_str);
}

/**
 * Get courses count by filter.
 *
 * @param $p_filter course_filter
 *
 * @return int courses count
 */
function get_courses_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  if ($tmp != '') {
    $tmp = ' WHERE ' . $tmp;
  }


  $query = 'SELECT COUNT(*) as `_count_` FROM ' . DB_TABLE_COURSE . $tmp;

  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get courses from courses id array.
 *
 * @param $p_id_array array of int courses id
 *
 * @return array courses array
 */
function get_courses_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  return db_extract(DB_TABLE_COURSE, "id IN($tmp)", 'id ASC');
}

/**
 * Delete course.
 *
 * @param $p_id int course id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_course($p_id)
{
  $t_id = db_prepare_int($p_id);

  $count = db_count(DB_TABLE_GROUP_COURSE, '`course_id` = ' . $t_id) +
    db_count(DB_TABLE_BOOK_COURSE, '`course_id` = ' . $t_id);
  if ($count == 0) {
    $query = 'DELETE FROM ' . DB_TABLE_COURSE . ' WHERE id=' . $t_id;
    db_exec($query);
    return (db_last_error() == '');
  } else {
    return FALSE;
  }


}

/**
 * Delete courses.
 *
 * @param $p_id_array array of int courses id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_courses($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_course($id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Add course.
 *
 * @param $p_title string course title
 * @param $p_hidden int course hidden
 *
 * @return ADORecordSet result
 */
function add_course($p_title, $p_hidden)
{
  $t_title = db_prepare_string($p_title);
  $t_hidden = db_prepare_int($p_hidden);

  $query = 'INSERT INTO ' . DB_TABLE_COURSE . '(`title`, `hidden`)
        VALUES(' . $t_title . ',' . $t_hidden . ')';

  return db_exec($query);
}

/**
 * Get course id by course title.
 *
 * @param $p_title string course title
 *
 * @return int course id on success or 0 on failure
 */
function get_course_id($p_title)
{
  $t_title = db_prepare_string(trim($p_title));

  $courses[] = db_extract(DB_TABLE_COURSE, '`title` = ' . $t_title);
  $id = 0;

  if (count($courses[0]) > 0) {
    $id = $courses[0][0]['id'];
  }

  return $id;
}

/**
 * Get course.
 *
 * @param integer $p_id course id
 *
 * @return course class
 */
function get_course($p_id)
{
  $t_id = db_prepare_int($p_id);

  $courses[] = db_extract(DB_TABLE_COURSE, '`id`=' . $t_id);
  $course = new course();
  if (count($courses[0]) > 0) {
    $course->id = $courses[0][0]['id'];
    $course->title = $courses[0][0]['title'];
    $course->hidden = $courses[0][0]['hidden'];
  }

  return $course;
}

/**
 * Edit course.
 *
 * @param $p_id int course id
 * @param $p_title int new course title
 * @param $p_hidden int course hidden
 *
 * @return ADORecordSet result
 */
function edit_course($p_id, $p_title, $p_hidden)
{
  $t_id = db_prepare_int($p_id);
  $t_title = db_prepare_string($p_title);
  $t_hidden = db_prepare_string($p_hidden);

  $query = 'UPDATE ' . DB_TABLE_COURSE . ' SET `title` = ' . $t_title . ',
         `hidden` = ' . $t_hidden . '
          WHERE `id` =' . $t_id;

  return db_exec($query);
}

///**
// * Get unhidden courses.
// *
// * @return array courses array
// */
//function get_unhidden_courses()
//{
//    $query = 'SELECT * FROM '.DB_TABLE_COURSE. ' WHERE `hidden` = 0 ORDER BY `title` ';
//
//    return db_query($query);
//}

/**
 * Get unhidden courses by user login and user password.
 * @param $p_login string user login
 * @return array courses array
 */
function get_unhidden_courses_by_login($p_login)
{
  $user_id = get_user_id($p_login);
  $query = /** @lang MySQL */
    'SELECT DISTINCT ' . DB_TABLE_COURSE . '.*
        FROM ' . DB_TABLE_COURSE . ', ' . DB_TABLE_USER . ', ' . DB_TABLE_GROUP_COURSE . ' 
        WHERE ' . DB_TABLE_COURSE . '.`hidden` = 0
        AND (' . DB_TABLE_USER . '.`user_group_id` = ' . DB_TABLE_GROUP_COURSE . '.`group_id` 
        OR ' . DB_TABLE_GROUP_COURSE . '.`group_id` IN 
        (SELECT `group_id` FROM ' . DB_TABLE_GROUP_USER . ' WHERE ' . DB_TABLE_GROUP_USER . '.`user_id`=' . $user_id . ') )        
        AND ' . DB_TABLE_GROUP_COURSE . '.`course_id` = ' . DB_TABLE_COURSE . '.`id`
        AND CURDATE() BETWEEN ' . DB_TABLE_GROUP_COURSE . '.`limited_from` AND ' . DB_TABLE_GROUP_COURSE . '.`limited_to`   
        AND ' . DB_TABLE_USER . '.`id` = ' . $user_id . ' ORDER BY `title`';

  return db_query($query);
}

