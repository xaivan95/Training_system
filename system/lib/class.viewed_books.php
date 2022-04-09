<?php

class viewed_books
{
  var $id;

  var $book_id;

  var $chapter_id;

  var $view_date;
}

/**
 * @param int $p_user_id
 * @param int $p_book_id
 * @param int $p_chapter_id
 * @return int
 */
function add_viewed_chapter($p_user_id, $p_book_id, $p_chapter_id)
{
  global $WEB_APP;
  if ($WEB_APP['settings']['write_book_view_log'] == 1) {
    if (($p_user_id != '') || ($p_book_id != '') || ($p_chapter_id != '')) {
      $t_user_id = db_prepare_int($p_user_id);
      $t_book_guid = db_prepare_int($p_book_id);
      $t_chapter_guid = db_prepare_int($p_chapter_id);
      $query = "INSERT INTO " . DB_TABLE_USER_BOOK_VIEWS . "(`user_id`, `book_id`, `chapter_id`)
        VALUES ($t_user_id, $t_book_guid, $t_chapter_guid)";
      db_exec($query);
      return db_insert_id();
    } else return -1;
  } else return -1;
}

function get_all_viewed_chapters($p_user_id)
{
  $t_user_id = db_prepare_int($p_user_id);
  $query = "SELECT view_date, book_title, chap_title FROM " . DB_TABLE_USER_BOOK_VIEWS . " as book_views
    LEFT JOIN `webclass_book` as books on  book_views.book_id=books.id
    LEFT JOIN `webclass_chapter` as chapters on  book_views.chapter_id=chapters.id
    WHERE user_id=$t_user_id ORDER BY view_date DESC;";
  return db_query($query);

}

function get_viewed_chapters_count($p_user_id, $p_filter)
{
  $t_user_id = db_prepare_int($p_user_id);
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  if ($tmp != '') {
    $tmp = ' WHERE ' . $tmp;
  }
  $query = "SELECT COUNT(*) as `_count_` FROM " . DB_TABLE_USER_BOOK_VIEWS . " WHERE user_id=$t_user_id" . $tmp;
  $result = db_query($query);
  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

function get_viewed_chapters($p_user_id, $p_sort_field = 'view_date', $p_sort_order = DEFAULT_ORDER, $p_page = 1,
                             $p_count = 0, $p_filter = NULL)
{
  $t_user_id = db_prepare_int($p_user_id);
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($t_sort_field == '') $t_sort_field = 'view_date';
  if (!in_array($t_sort_field, array('view_date', 'book_id', 'chapter_id', 'user_id'))) {
    $t_sort_field = 'view_date';
  }

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = " LIMIT $limit, $t_count";
  } else {
    $limit_str = '';
  }

  $filter = ($p_filter != NULL) ? $p_filter->query() : '';
  $query = "SELECT view_date, book_title, chap_title, books.id as book_id, chapters.id as chapter_id 
    FROM " . DB_TABLE_USER_BOOK_VIEWS . " as book_views
    LEFT JOIN `webclass_book` as books on book_views.book_id=books.id
    LEFT JOIN `webclass_chapter` as chapters on book_views.chapter_id=chapters.id
    WHERE user_id=$t_user_id $filter ORDER BY $t_sort_field $t_sort_order  $limit_str;";
  return db_query($query);
}

function get_viewed_chapters_for_book($p_user_id, $p_book_id)
{
  $t_user_id = db_prepare_int($p_user_id);
  $t_book_guid = db_prepare_int($p_book_id);
  $query = "SELECT * FROM " . DB_TABLE_USER_BOOK_VIEWS . " WHERE  `user_id`=$t_user_id AND `book_id`=$t_book_guid";
  return db_query($query);
}

/**
 * @param bool $return_just_count
 * @param array $p_group_array
 * @param array $p_book_array
 * @param string $p_view_date_from
 * @param string $p_view_date_to
 * @param string $p_sort_field
 * @param string $p_sort_order
 * @param int $p_page
 * @param int $p_count
 * @return array|null|int
 */
function get_book_group_report($return_just_count, $p_group_array, $p_book_array, $p_view_date_from, $p_view_date_to,
                               $p_sort_field = "id", $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  if (isset($t_view_date_from)) $t_view_date_from = db_prepare_date($p_view_date_from); else $t_view_date_from =
    '2020-01-01';
  if (isset($t_view_date_to)) $t_view_date_to = db_prepare_date($p_view_date_to); else $t_view_date_to = 'CURDATE()';

  $groups_id = "";
  $array = array_values($p_group_array);
  $size = sizeof($array);
  if ($size == 0) return NULL;
  for ($i = 0; $i < $size - 1; $i++) $groups_id .= db_prepare_int($array[$i]) . ", ";
  $groups_id .= db_prepare_int($array[$size - 1]);

  $books_id = "";
  $array = array_values($p_book_array);
  $size = sizeof($array);
  if ($size == 0) return NULL;
  for ($i = 0; $i < $size - 1; $i++) $books_id .= db_prepare_int($array[$i]) . ", ";
  $books_id .= db_prepare_int($array[$size - 1]);

  if ($return_just_count) {
    $query = "SELECT COUNT(*) 
    FROM `" . DB_TABLE_USER_BOOK_VIEWS . "` as book_views
    LEFT JOIN `" . DB_TABLE_BOOK . "` as books on  book_views.book_id=books.id
    LEFT JOIN `" . DB_TABLE_CHAPTER . "` as chapters on  book_views.chapter_id=chapters.id
    LEFT JOIN `" . DB_TABLE_USER . "` as users on users.id=book_views.`user_id`
    LEFT JOIN `" . DB_TABLE_GROUP . "` as groups on groups.id = users.user_group_id
    WHERE users.user_group_id in ($groups_id) AND books.id IN ($books_id) 
    AND view_date BETWEEN $t_view_date_from AND $t_view_date_to ";
  } else {
    $query = "SELECT `groups`.group_name, `users`.user_name, `book_views`.view_date, 
       `books`.book_title, `chapters`.chap_title, books.id as book_id, chapters.id as chapter_id 
    FROM `" . DB_TABLE_USER_BOOK_VIEWS . "` as book_views
    LEFT JOIN `" . DB_TABLE_BOOK . "` as books on  book_views.book_id=books.id
    LEFT JOIN `" . DB_TABLE_CHAPTER . "` as chapters on  book_views.chapter_id=chapters.id
    LEFT JOIN `" . DB_TABLE_USER . "` as users on users.id=book_views.`user_id`
    LEFT JOIN `" . DB_TABLE_GROUP . "` as groups on groups.id = users.user_group_id
    WHERE users.user_group_id in ($groups_id) AND books.id IN ($books_id) 
    AND view_date BETWEEN $t_view_date_from AND $t_view_date_to ";
  }

  if ($t_sort_field == '') $t_sort_field = 'view_date';
  $fields_array = array('group_name', 'user_name', 'user_name', 'book_title', 'chap_title', 'view_date');
  if (!in_array($t_sort_field, $fields_array)) $t_sort_field = 'view_date';
  $query .= " ORDER BY $t_sort_field $t_sort_order ";

  if (!$return_just_count and ($p_count != 0)) {
    $limit = ($t_page - 1) * $t_count;
    $query .= " LIMIT  $limit, $t_count";
  }

  $results = db_query($query);
  if ($return_just_count) return $results[0][0]; else return $results;
}