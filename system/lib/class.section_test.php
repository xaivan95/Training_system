<?php

/**
 * Specify section_test object. Section_test table.
 */
class section_test
{
  /**
   * int section_test id
   */
  var $id;

  /**
   * int section id
   */
  var $section;

  /**
   * int test id
   */
  var $test;

  var $author;

  /**
   * int (0, 1) hide section_test
   */
  var $hidden;
}

/**
 * Get section_tests.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter section_test_filter object
 *
 * @return array section_tests array
 */
function get_section_tests($p_sort_field = "id", $p_sort_order = "ASC", $p_page = 1, $p_count = 0, $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';

  $query =
    'SELECT ' . DB_TABLE_SECTION_TEST . '.`id` AS `id`,' . DB_TABLE_SECTION . '.`section_name` AS `section_name`,' .
    DB_TABLE_TESTS . '.`test_name` AS `test_name`,' . DB_TABLE_TESTS . '.`test_author` AS `test_author`,' .
    DB_TABLE_SECTION_TEST . '.`test_is_hidden`
          FROM  ' . DB_TABLE_SECTION_TEST . ',' . DB_TABLE_SECTION . ', ' . DB_TABLE_TESTS . ' WHERE ' .
    DB_TABLE_SECTION_TEST . '.`section_id` = ' . DB_TABLE_SECTION . '.`id`
        AND ' . DB_TABLE_SECTION_TEST . '.`test_id` = ' . DB_TABLE_TESTS . '.`id` ' . $tmp;

  if ($t_sort_field == "") $t_sort_field = "id";
  $query .= " ORDER BY `$t_sort_field` $t_sort_order";

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $query .= " LIMIT  $limit, $t_count";
  }

  return db_query($query);
}

/**
 * Get section_test array from section_test id array.
 *
 * @param $p_id_array array of int section_test id
 *
 * @return array section_test array
 */
function get_section_tests_from_array($p_id_array)
{
  $tmp = "";
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ", ";
  $tmp .= db_prepare_int($array[$size - 1]);

  $query =
    'SELECT ' . DB_TABLE_SECTION_TEST . '.`id` as `id`,' . DB_TABLE_SECTION . '.`section_name` as `section_name`,' .
    DB_TABLE_TESTS . '.`test_name` as `test_name`,' . DB_TABLE_SECTION_TEST . '.`test_is_hidden`
          FROM  ' . DB_TABLE_SECTION_TEST . ',' . DB_TABLE_SECTION . ', ' . DB_TABLE_TESTS . ' WHERE ' .
    DB_TABLE_SECTION_TEST . '.`section_id` = ' . DB_TABLE_SECTION . '.`id`
        AND ' . DB_TABLE_SECTION_TEST . '.`test_id` = ' . DB_TABLE_TESTS . '.`id`
        AND ' . DB_TABLE_SECTION_TEST . '.`id` IN(' . $tmp . ')
        ORDER BY ' . DB_TABLE_SECTION_TEST . '.`id` ASC';

  return db_query($query);
}

/**
 * Get section_tests count.
 *
 * @param $p_filter section_test_filter object
 *
 * @return int section_test count
 */
function get_section_tests_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  $query = 'SELECT COUNT(*) AS `_count_`
          FROM  ' . DB_TABLE_SECTION_TEST . ',' . DB_TABLE_SECTION . ', ' . DB_TABLE_TESTS . ' WHERE ' .
    DB_TABLE_SECTION_TEST . '.`section_id` = ' . DB_TABLE_SECTION . '.`id`
        AND ' . DB_TABLE_SECTION_TEST . '.`test_id` = ' . DB_TABLE_TESTS . '.`id` ' . $tmp;

  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Add section_test.
 *
 * @param $p_section_id int section id
 * @param $p_test_id int test id
 * @param $p_hidden int (0, 1) is section_test hidden
 *
 * @return ADORecordSet or false
 */
function add_section_test($p_section_id, $p_test_id, $p_hidden)
{
  $t_section_id = db_prepare_int($p_section_id);
  $t_test_id = db_prepare_int($p_test_id);
  $t_hidden = db_prepare_int($p_hidden);

  $query = 'INSERT INTO ' . DB_TABLE_SECTION_TEST . '(`section_id`, `test_id`, `test_is_hidden`)
        VALUES(' . $t_section_id . ', ' . $t_test_id . ', ' . $t_hidden . ')';

  return db_exec($query);
}

/**
 * Add section_test.
 *
 * @param $p_section_id int section id
 * @param $p_tests_id array int test id
 * @param $p_test_hidden boolean
 * @return ADORecordSet or false
 */
function add_section_tests($p_section_id, $p_tests_id, $p_test_hidden)
{
  global $WEB_APP;
  $t_section_id = db_prepare_int($p_section_id);
  $p_test_hidden = db_prepare_bool($p_test_hidden);

  $values = '';
  $tests_count = count($p_tests_id);
  for ($i = 0; $i < $tests_count; $i++) {
    $test_section_exists = get_section_test_id($t_section_id, $p_tests_id[$i]);
    if ($test_section_exists !== 0) {
      $WEB_APP['errorstext'] .= text('txt_section_test_already_exist_insert_another_section_test') . "<br>";
    } else {
      $t_tests_id = db_prepare_int($p_tests_id[$i]);
      $values .= "($t_section_id , $t_tests_id, $p_test_hidden),";
    }
  }
  $values = substr($values, 0, -1);

  $query = "INSERT INTO " . DB_TABLE_SECTION_TEST . "(`section_id`, `test_id`, `test_is_hidden`) VALUES $values";

  return db_exec($query);
}

/**
 * Delete section_test.
 *
 * @param $p_id int section_test id
 *
 * @return boolean
 */
function delete_section_test($p_id)
{
  $t_id = db_prepare_int($p_id);

  $query = 'DELETE FROM ' . DB_TABLE_SECTION_TEST . ' WHERE `id`=' . $t_id;

  db_exec($query);

  return (db_last_error() == '');
}


/**
 * Delete test from all sections.
 *
 * @param $p_id int test_id
 *
 * @return boolean
 */
function delete_test_from_all_sections($p_id)
{
  $t_id = db_prepare_int($p_id);

  $query = 'DELETE FROM ' . DB_TABLE_SECTION_TEST . ' WHERE `test_id`=' . $t_id;

  db_exec($query);

  return (db_last_error() == '');
}

/**
 * Delete section_tests.
 *
 * @param $p_id_array array of int section_tests id
 *
 * @return boolean
 */
function delete_section_tests($p_id_array)
{
  $tmp = "";
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return FALSE;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ", ";
  $tmp .= db_prepare_int($array[$size - 1]);

  $query = 'DELETE FROM ' . DB_TABLE_SECTION_TEST . ' WHERE `id` IN (' . $tmp . ')';

  db_exec($query);

  return (db_last_error() == '');
}

/**
 * Get section_test.
 *
 * @param $p_id int section_test id
 *
 * @return section_test class
 */
function get_section_test($p_id)
{
  $t_id = db_prepare_int($p_id);

  $query =
    'SELECT ' . DB_TABLE_SECTION_TEST . '.`id` as `id`,' . DB_TABLE_SECTION . '.`section_name` as `section_name`,' .
    DB_TABLE_TESTS . '.`test_name` as `test_name`,' . DB_TABLE_TESTS . '.`test_author` as `test_author`,' .
    DB_TABLE_SECTION_TEST . '.`test_is_hidden`
        FROM  ' . DB_TABLE_SECTION_TEST . ', ' . DB_TABLE_SECTION . ', ' . DB_TABLE_TESTS . '
        WHERE ' . DB_TABLE_SECTION_TEST . '.`section_id` = ' . DB_TABLE_SECTION . '.`id`
        AND ' . DB_TABLE_SECTION_TEST . '.`test_id` = ' . DB_TABLE_TESTS . '.`id`
        AND ' . DB_TABLE_SECTION_TEST . '.`id`=' . $t_id;

  $section_test = new section_test();
  $result = db_query($query);
  if (isset($result[0])) {
    $section_test->id = $result[0]['id'];
    $section_test->section = $result[0]['section_name'];
    $section_test->test = $result[0]['test_name'];
    $section_test->author = $result[0]['test_author'];
    $section_test->hidden = $result[0]['test_is_hidden'];
  }

  return $section_test;
}

/**
 * Edit section_test.
 *
 * @param $p_id int section_test id
 * @param $p_new_section_id int new section id
 * @param $p_new_test_id int new test id
 * @param $p_new_hidden int (0, 1) new hide section_test
 *
 * @return boolean
 */
function edit_section_test($p_id, $p_new_section_id, $p_new_test_id, $p_new_hidden)
{
  $t_id = db_prepare_int($p_id);
  $t_new_section_id = db_prepare_int($p_new_section_id);
  $t_new_test_id = db_prepare_int($p_new_test_id);
  $t_new_hidden = db_prepare_int($p_new_hidden);

  $query = 'UPDATE ' . DB_TABLE_SECTION_TEST . ' SET `section_id` = ' . $t_new_section_id . ',
        `test_id` = ' . $t_new_test_id . ',
        `test_is_hidden` = ' . $t_new_hidden . '
        WHERE `id` = ' . $t_id;

  db_exec($query);

  return (db_last_error() == '');
}

/**
 * Get section_test id.
 *
 * @param $p_section_id int section id
 * @param $p_test_id int test id
 *
 * @return int section_test id
 */
function get_section_test_id($p_section_id, $p_test_id)
{
  $t_section_id = db_prepare_int($p_section_id);
  $t_test_id = db_prepare_int($p_test_id);

  $query = 'SELECT `id` FROM ' . DB_TABLE_SECTION_TEST . ' WHERE `section_id` = ' . $t_section_id . '
         AND `test_id` = ' . $t_test_id;

  $result = db_query($query);

  return isset($result[0]['id']) ? $result[0]['id'] : 0;
}

/**
 * Get sections count for test id.
 *
 * @param $p_id int test id
 *
 * @return int sections count
 */
function get_sections_count_for_test_id($p_id)
{
  $t_id = db_prepare_int($p_id);

  return db_count(DB_TABLE_SECTION_TEST, '`test_id` = ' . $t_id);
}

/**
 * Change section for section_test id.
 *
 * @param $p_section_id int new section id
 * @param $p_id int section_test id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function move_test($p_section_id, $p_id)
{
  $t_section_id = db_prepare_int($p_section_id);
  $t_id = db_prepare_int($p_id);

  $query = 'UPDATE ' . DB_TABLE_SECTION_TEST . ' SET `section_id` = ' . $t_section_id . ' WHERE `id` = ' . $t_id;

  db_exec($query);

  return (db_last_error() == '');
}

/**
 * Change section for section_test id array.
 *
 * @param $p_section_id int new section id
 * @param $p_id_array section_test id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function move_tests($p_section_id, $p_id_array)
{
  $tmp = TRUE;

  foreach ($p_id_array as $p_id) {
    $result = move_test($p_section_id, $p_id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

