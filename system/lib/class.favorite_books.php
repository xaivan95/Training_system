<?php

class favorite_books
{
  /**
   * @var int
   */
  var $id;
  /**
   * @var int User ID
   */
  var $user_id;
  /**
   * @var int Book ID
   */
  var $book_id;
}

/**
 * @param $user_id int
 * @param $book_id int
 * @return int
 */
function add_book_to_favorites($user_id, $book_id)
{
  $user_id = db_prepare_int($user_id);
  $book_id = db_prepare_int($book_id);
  $query = "SELECT count(*) FROM " . DB_TABLE_FAVORITE_BOOKS . " WHERE user_id=$user_id AND book_id=$book_id";
  $favorites = db_query($query);
  if ($favorites[0][0] == 0) {
    $query = "INSERT INTO " . DB_TABLE_FAVORITE_BOOKS . "(user_id, book_id) VALUES ($user_id, $book_id)";
    db_exec($query);
    if (db_last_error() != '') {
      return 0;
    }
    return db_insert_id();
  } else return 0;
}

/**
 * @param $user_id int
 * @param $book_id int
 * @return bool
 */
function remove_book_from_favorites($user_id, $book_id)
{
  $user_id = db_prepare_int($user_id);
  $book_id = db_prepare_int($book_id);
  $query = "DELETE FROM " . DB_TABLE_FAVORITE_BOOKS . " WHERE user_id=$user_id AND book_id=$book_id";

  db_exec($query);
  return (db_last_error() == '');
}

/**
 * @param $id int
 * @return bool
 */
function remove_book_from_favorites_by_id($id)
{
  $id = db_prepare_int($id);
  $query = "DELETE FROM " . DB_TABLE_FAVORITE_BOOKS . " WHERE id=$id";

  db_exec($query);
  return (db_last_error() == '');
}

/**
 * Get Favorite books.
 * @param integer $p_id favorite id
 * @return favorite_books class
 */
function get_favorite_book($p_id)
{
  $t_id = db_prepare_int($p_id);

  $favorites[] = db_extract(DB_TABLE_FAVORITE_BOOKS, '`id`=' . $t_id);
  $favorite_book = new favorite_books();
  if (count($favorites[0]) > 0) {
    $favorite_book->id = $favorites[0][0]['id'];
    $favorite_book->book_id = $favorites[0][0]['book_id'];
    $favorite_book->user_id = $favorites[0][0]['user_id'];
  }

  return $favorite_book;
}

/**
 * @param $p_id_array array of int courses id
 * @return array courses array
 */
function get_favorite_books_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);
  if ($size == 0) return NULL;
  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);
  return db_extract(DB_TABLE_FAVORITE_BOOKS, "id IN($tmp)", 'id ASC');
}

/**
 * @param $p_filter favorie_books_filter
 * @return int courses count
 */
function get_favorite_books_count($p_filter)
{
  $user_id = get_user_id($_SESSION['user_login']);
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  if ($tmp != '') {
    $tmp = " WHERE `user_id`=$user_id AND $tmp";
  } else {
    $tmp = " WHERE `user_id`=$user_id";
  }

  $query = "SELECT COUNT(*) as `_count_` 
    FROM " . DB_TABLE_FAVORITE_BOOKS . " as fav_books
    JOIN " . DB_TABLE_BOOK . "  as books on fav_books.`book_id`=books.`id` 
    $tmp";
  $result = db_query($query);
  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get favorites.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter favorie_books_filter
 *
 * @return array favorites array
 */
function get_favorite_books($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0,
                            $p_filter = NULL)
{
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  $user_id = get_user_id($_SESSION['user_login']);

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "$limit, $t_count";
  } else {
    $limit_str = '';
  }

  if ($p_sort_field == '') $p_sort_field = 'id';

  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  $query = "SELECT fav_books.id, fav_books.user_id, fav_books.book_id, books.`book_title`
            FROM " . DB_TABLE_FAVORITE_BOOKS . " as fav_books
            JOIN `" . DB_TABLE_BOOK . "`  as books on fav_books.`book_id`=books.`id` 
            WHERE fav_books.`user_id`=$user_id ORDER BY $p_sort_field $p_sort_order";
  if ($tmp !== "") $query = $query . " AND " . $tmp;

  if ($limit_str != '') {
    $query .= ' LIMIT ' . $limit_str;
  }
  return db_query($query);
}
