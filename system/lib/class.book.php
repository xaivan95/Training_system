<?php

/**
 * Specify book object. Book table.
 */
class book
{
  /**
   * int book id
   */
  var $id;

  /**
   * double Engine Version
   */

  var $engine_version;

  /**
   * string book title
   */
  var $title;

  /**
   * string book author
   */
  var $author;

  /**
   * string book copyright
   */
  var $copyright;

  /**
   * string book description
   */
  var $description;

  /**
   * string book header
   */
  var $header;

  /**
   * string book footer
   */
  var $footer;

  /**
   * string book theme
   */
  var $theme;

  /**
   * string book contents
   */
  var $contents;

  /**
   * string book mediastorage folder
   */
  var $mediastorage;

  /**
   * string book HTML HEADER section
   */
  var $html_header;

  /**
   * string book CSS
   */
  var $css;

  /**
   * string book GUID
   */
  var $guid;

  /**
   * TOC settings
   */
  var $toc_width;
  var $toc_width_measure;
  var $toc_responsive;
  var $toc_show_search;
  var $toc_show_dots;
  var $toc_show_icons;
  var $toc_show_stripes;

  /**
   * User id of book's author
   */
  var $author_id;
}

/**
 * Get books.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter book_filter
 *
 * @return array books array
 */
function get_books($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0, $p_filter = NULL,
                   $p_author_id = NULL)
{
  global $WEB_APP;
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  $t_author_id = db_prepare_int($p_author_id);

  if (isset($p_author_id) && user_have_grant_id($p_author_id, $WEB_APP['settings']['limited_books_grant_id'])) {
    $tmp = " `book_author_id`=$t_author_id OR `book_author_id`=0 ";
    if ($p_filter->query() !== '') $tmp .= " AND " . $p_filter->query();
  } else {
    $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  }

  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field, array('id', 'book_title', 'book_guid', 'book_mediastorage'))) {
    $t_sort_field = 'id';
  }

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "$limit, $t_count";
  } else {
    $limit_str = '';
  }

  return db_extract(DB_TABLE_BOOK, $tmp, $t_sort_field . ' ' . $t_sort_order, $limit_str);
}

/**
 * Get books from books id array.
 *
 * @param $p_id_array array of int books id
 *
 * @return array books array
 */
function get_books_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  $query = 'SELECT *
		 FROM ' . DB_TABLE_BOOK . ' WHERE ' . DB_TABLE_BOOK . '.`id` IN (' . $tmp . ')
		ORDER BY ' . DB_TABLE_BOOK . '.`id` ';

  return db_query($query);
}

/**
 * Move book to course_id.
 *
 * @param $p_course_id int new course id
 * @param $p_book_course_id int book_course id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function move_book($p_course_id, $p_book_course_id)
{
  $t_course_id = db_prepare_int($p_course_id);
  $t_book_course_id = db_prepare_int($p_book_course_id);

  $query =
    'UPDATE ' . DB_TABLE_BOOK_COURSE . ' SET `course_id` = ' . $t_course_id . ' WHERE `id` = ' . $t_book_course_id;

  db_exec($query);

  return (db_last_error() == '');
}

/**
 * Move books to over course.
 *
 * @param $p_course_id int course id
 * @param $p_id_array array of int books id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function move_books($p_course_id, $p_id_array)
{
  $tmp = TRUE;

  foreach ($p_id_array as $p_id) {
    $result = move_book($p_course_id, $p_id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Get books count by filter.
 *
 * @param $p_filter book_filter
 *
 * @return int books count
 */
function get_books_count($p_filter, $p_author_id = NULL)
{
  global $WEB_APP;

  if (isset($p_author_id) && user_have_grant_id($p_author_id, $WEB_APP['settings']['limited_books_grant_id'])) {
    $t_author_id = db_prepare_int($p_author_id);
    $tmp = " WHERE `book_author_id`=$t_author_id OR `book_author_id`=0 ";
    if ($p_filter->query() !== '') $tmp .= " AND " . $p_filter->query();
  } else {
    $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
    if ($tmp != '') {
      $tmp = ' WHERE ' . $tmp;
    }
  }

  $query = 'SELECT COUNT(*) AS `_count_`
			 FROM ' . DB_TABLE_BOOK . $tmp;
  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get book id by title.
 *
 * @param string $p_title book title
 *
 * @return integer book id on success or 0 on failure
 */
function get_book_id($p_title)
{
  $t_title = db_prepare_string($p_title);

  $query = 'SELECT `id`
		FROM ' . DB_TABLE_BOOK . ' WHERE `book_title`=' . $t_title;

  $result = db_query($query);

  return isset($result[0]['id']) ? $result[0]['id'] : 0;
}

/**
 * Get book by book id or guid.
 *
 * @param integer $p_id book id
 *
 * @return book class
 */
function get_book($p_id)
{
  $t_id = db_prepare_int($p_id);
  $books[] = db_extract(DB_TABLE_BOOK, '`id` = ' . $t_id);
  if (count($books[0]) == 0) {
    unset($books);
    $t_id = db_prepare_string($p_id);
    $books[] = db_extract(DB_TABLE_BOOK, '`book_guid` = ' . $t_id);
  }
  if (count($books[0]) == 0) return null; else
    return get_book_by_array($books);
}

/**
 * Get book by book multimedia id (media storage).
 *
 * @param $p_id string book multimedia id
 *
 * @return book class
 */
function get_book_by_multimedia_id($p_id)
{
  $t_id = db_prepare_string($p_id);
  $books[] = db_extract(DB_TABLE_BOOK, '`book_mediastorage` = ' . $t_id);
  if (count($books[0]) == 0) return null; else
    return get_book_by_array($books);
}

/**
 * @param string $p_id
 * @return int
 */
function get_book_id_by_multimedia_id($p_id)
{
  $t_id = db_prepare_string($p_id);
  $query = "SELECT id FROM " . DB_TABLE_BOOK . " WHERE book_mediastorage=" . $t_id;
  $book_id = db_query($query);
  if (isset($book_id[0])) return $book_id[0][0]; else return 0;
}

/**
 * @param string $p_id
 * @return int
 */
function get_book_id_by_guid($p_id)
{
  $t_id = db_prepare_string($p_id);
  $query = "SELECT id FROM " . DB_TABLE_BOOK . " WHERE book_guid=" . $t_id;
  $book_id = db_query($query);
  if (isset($book_id[0])) return $book_id[0][0]; else return 0;
}

function remove_book_from_all_courses($p_id)
{
  global $adodb;
  $b_id = db_prepare_int($p_id);
  $query = "DELETE FROM " . DB_TABLE_BOOK_COURSE . " WHERE `book_id`=$b_id";
  return $adodb->Execute($query);
}

/**
 * Delete book id with/without book courses.
 *
 * @param $p_id int book id
 * @param $must bool TRUE - with book courses
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_book($p_id, $must = FALSE)
{
  $t_id = db_prepare_int($p_id);
  if ($must) $need_to_delete = TRUE; else
    $need_to_delete = remove_book_from_all_courses($p_id) != FALSE;

  if ($need_to_delete) {
    $b = delete_chapters($t_id);
    if ($b) {
      $query = "DELETE FROM " . DB_TABLE_BOOK . " WHERE `id`= $t_id";
      db_exec($query);
      return (db_last_error() == '');
    }

    return $b;

  } else {
    return FALSE;
  }
}

/**
 * Delete books.
 *
 * @param $p_id_array array of int book id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_books($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_book($id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Add book.
 *
 * @param $book book object
 * @param $p_book_id int book id, NULL -don't use book id
 *
 * @return int book id on success or 0 on failure
 */
function add_book($book, $p_book_id = NULL)
{
  $book = prepare_book($book);
  if ($p_book_id != NULL) {
    delete_book($p_book_id, TRUE);
  }
  if ($p_book_id == NULL) {
    $t_book_id = 'NULL';
  } else {
    $t_book_id = db_prepare_int($p_book_id);
  }

  $author_id = get_user_id($_SESSION['user_login']);

  $query = "INSERT INTO  " . DB_TABLE_BOOK . "
        (
			`id`,
			`book_engine_version`,
			`book_title`,
			`book_author`,
			`book_copyright`,
			`book_description`,
			`book_header`,
			`book_footer`,
			`book_theme`,
			`book_contents`,
			`book_mediastorage`,
			`book_html_header`,
			`book_css`,
			`book_guid`,
			`toc_width`,
			`toc_width_measure`,
			`toc_responsive`,
			`toc_show_search`,
			`toc_show_dots`,
			`toc_show_icons`,
			`toc_show_stripes`,
			`book_author_id`
			)
		VALUES
			(
        $t_book_id,
        $book->engine_version,
        $book->title,
        $book->author,
        $book->copyright,
        $book->description,
        $book->header,
        $book->footer,
        $book->theme,
        $book->contents,
        $book->mediastorage,
        $book->html_header,
        $book->css,
        $book->guid,
        $book->toc_width,
        $book->toc_width_measure,
        $book->toc_responsive,
        $book->toc_show_search,
        $book->toc_show_dots,
        $book->toc_show_icons,
        $book->toc_show_stripes,
        $author_id
        )";

  db_query($query);

  if (db_last_error() != '') {
    return 0;
  }

  if ($p_book_id == NULL) {
    return db_insert_id();
  } else {
    return $p_book_id;
  }
}

/**
 * Import book from xml file.
 *
 * @param $p_file string xml file
 * @param $p_book_id int book id, NULL -don't use book id
 *
 * @return int book id
 */
function import_book($p_file, $p_book_id = NULL)
{
  $file = CFG_BOOKS_DIR . $p_file;
  $items = get_xml_data($file);

  $book = new book();

  $book->engine_version = get_value_array($items, 'sunravbook/info/engine_version');
  $book->title = get_value_array($items, 'sunravbook/info/title');
  $book->author = get_value_array($items, 'sunravbook/info/author');
  $book->copyright = get_value_array($items, 'sunravbook/info/copyright');
  $book->description = get_value_array($items, 'sunravbook/info/description');
  $book->header = get_value_array($items, 'sunravbook/info/header');
  $book->footer = get_value_array($items, 'sunravbook/info/footer');
  $book->theme = get_value_array($items, 'sunravbook/info/theme');
  $book->contents = get_value_array($items, 'sunravbook/info/contents');
  $book->mediastorage = get_value_array($items, 'sunravbook/info/mediastorage');
  $book->html_header = get_value_array($items, 'sunravbook/info/html_header');
  $book->css = get_value_array($items, 'sunravbook/info/css');
  $book->guid = get_value_array($items, 'sunravbook/info/guid');
  $book->toc_width = get_value_array($items, 'sunravbook/info/contents/width');
  $book->toc_width_measure = get_value_array($items, 'sunravbook/info/contents/width_measure');
  $book->toc_responsive = ((get_value_array($items, 'sunravbook/info/contents/responsive', 'T') == 'T') ? 1 : 0);
  $book->toc_show_search = ((get_value_array($items, 'sunravbook/info/contents/show_search', 'T') == 'T') ? 1 : 0);
  $book->toc_show_dots = ((get_value_array($items, 'sunravbook/info/contents/show_dots', 'T') == 'T') ? 1 : 0);
  $book->toc_show_icons = ((get_value_array($items, 'sunravbook/info/contents/show_icons', 'T') == 'T') ? 1 : 0);
  $book->toc_show_stripes = ((get_value_array($items, 'sunravbook/info/contents/show_stripes', 'T') == 'T') ? 1 : 0);

  $book_id = add_book($book, $p_book_id);
  import_chapters($book_id, $items);

  return $book_id;
}

/**
 * Edit book.
 *
 * @param $book book class
 *
 * @return ADORecordSet
 */
function edit_book($book)
{
  $book = prepare_book($book);
  $query = "UPDATE  " . DB_TABLE_BOOK . "
         SET
		 `book_engine_version`= $book->engine_version,
		 `book_title`		      = $book->title,
		 `book_author` 		    = $book->author,
		 `book_copyright`	    = $book->copyright,
		 `book_description`   = $book->description,
		 `book_header` 		    = $book->header,
		 `book_footer` 		    = $book->footer,
		 `book_theme` 		    = $book->theme,
		 `book_mediastorage`  = $book->mediastorage,
		 `book_html_header`	  = $book->html_header,
		 `book_css`	          = $book->css,
		 `book_guid`	        = $book->guid,
		 `toc_width`	        = $book->toc_width,
		 `toc_width_measure`  = $book->toc_width_measure,
		 `toc_responsive`	    = $book->toc_responsive,
		 `toc_show_search`	  = $book->toc_show_search,
		 `toc_show_dots`	    = $book->toc_show_dots,
		 `toc_show_icons`	    = $book->toc_show_icons,
		 `toc_show_stripes`	  = $book->toc_show_stripes
		  WHERE `id` 		= $book->id";

  return db_exec($query);
}

/**
 * Prepare book to query.
 *
 * @param $book book object
 *
 * @return book object
 */
function prepare_book($book)
{
  $book->id = db_prepare_int($book->id);
  $book->engine_version = db_prepare_float($book->engine_version);
  $book->title = db_prepare_string($book->title);
  $book->author = db_prepare_string($book->author);
  $book->copyright = db_prepare_string($book->copyright);
  $book->description = db_prepare_string($book->description);
  $book->header = db_prepare_string($book->header);
  $book->footer = db_prepare_string($book->footer);
  $book->theme = db_prepare_string($book->theme);
  $book->mediastorage = db_prepare_string($book->mediastorage);
  $book->html_header = db_prepare_string($book->html_header);
  $book->css = db_prepare_string($book->css);
  $book->contents = db_prepare_string($book->contents);
  $book->guid = db_prepare_string($book->guid);
  $book->toc_width = db_prepare_int($book->toc_width);
  $book->toc_width_measure = db_prepare_string($book->toc_width_measure);
  $book->toc_responsive = db_prepare_bool($book->toc_responsive);
  $book->toc_show_search = db_prepare_bool($book->toc_show_search);
  $book->toc_show_dots = db_prepare_bool($book->toc_show_dots);
  $book->toc_show_icons = db_prepare_bool($book->toc_show_icons);
  $book->toc_show_stripes = db_prepare_bool($book->toc_show_stripes);
  return $book;
}

/**
 * Get books by user login and user password.
 *
 * @param $p_login string user login
 * @param $p_password string user password
 *
 * @return array books array
 */
function get_books_by_login_password($p_login, $p_password)
{
  $t_login = db_prepare_string($p_login);
  $t_password = db_prepare_string(md5($p_password));

  $query = 'SELECT DISTINCT `' . DB_TABLE_BOOK . '`.*
		FROM `' . DB_TABLE_BOOK . '`, `' . DB_TABLE_USER . '`, `' . DB_TABLE_GROUP . '`, `' . DB_TABLE_GROUP_COURSE .
    '`, `' . DB_TABLE_COURSE . '`, `' . DB_TABLE_BOOK_COURSE . '` WHERE	`' . DB_TABLE_USER . '`.`user_login` = ' .
    $t_login . ' AND `' . DB_TABLE_USER . '`.`user_password` = ' . $t_password . ' AND `' . DB_TABLE_USER .
    '`.`user_group_id` = `' . DB_TABLE_GROUP . '`.`id`' . ' AND `' . DB_TABLE_GROUP_COURSE . '`.`group_id` = `' .
    DB_TABLE_GROUP . '`.`id`' . ' AND `' . DB_TABLE_GROUP_COURSE . '`.`course_id` = `' . DB_TABLE_COURSE . '`.`id`' .
    ' AND `' . DB_TABLE_BOOK_COURSE . '`.`course_id` = `' . DB_TABLE_COURSE . '`.`id`' . ' AND `' .
    DB_TABLE_BOOK_COURSE . '`.`book_id` = `' . DB_TABLE_BOOK . '`.`id`';
  if (HIDDEN_IS_DISABLED == TRUE) $query .= ' AND `' . DB_TABLE_BOOK_COURSE . '`.`hidden` = 0';

  return db_query($query);
}

/**
 * Get book by BookID and user login and user password.
 *
 * @param $p_login string user login
 * @param $p_book_id integer
 * @return array books array
 */
function get_book_by_login($p_login, $p_book_id)
{
  $t_book_id = db_prepare_int($p_book_id);
  $user_id = get_user_id($p_login);

  $query = 'SELECT DISTINCT `' . DB_TABLE_BOOK . '`.*
		FROM `' . DB_TABLE_BOOK . '`, `' . DB_TABLE_USER . '`, `' . DB_TABLE_GROUP . '`, `' . DB_TABLE_GROUP_COURSE .
    '`, `' . DB_TABLE_COURSE . '`, `' . DB_TABLE_BOOK_COURSE . '` WHERE	`' . DB_TABLE_BOOK . '`.`id`=' . $t_book_id .
    ' AND `' . DB_TABLE_USER . '`.`id` = ' . $user_id . ' AND `' . DB_TABLE_USER . '`.`user_group_id` = `' .
    DB_TABLE_GROUP . '`.`id`' . ' AND (`' . DB_TABLE_GROUP_COURSE . '`.`group_id` = `' . DB_TABLE_GROUP . '`.`id`' . ' OR
`' . DB_TABLE_GROUP_COURSE . '`.`group_id` in (SELECT `group_id` FROM `' . DB_TABLE_GROUP_USER . '`  WHERE `' .
    DB_TABLE_GROUP_USER . '`.`user_id`=' . $user_id . '))
 AND `' . DB_TABLE_GROUP_COURSE . '`.`course_id` = `' . DB_TABLE_COURSE . '`.`id`' . ' AND `' . DB_TABLE_BOOK_COURSE .
    '`.`course_id` = `' . DB_TABLE_COURSE . '`.`id`' . ' AND `' . DB_TABLE_BOOK_COURSE . '`.`book_id` = `' .
    DB_TABLE_BOOK . '`.`id`';
  if (HIDDEN_IS_DISABLED == TRUE) $query .= ' AND `' . DB_TABLE_BOOK_COURSE . '`.`hidden` = 0';
  return db_query($query);
}

/**
 * @param $p_login string
 * @param $p_book_id int
 * @return bool
 */
function book_available_for_user($p_login, $p_book_id)
{
  $t_book_id = db_prepare_int($p_book_id);
  $user_id = get_user_id($p_login);
  $user_group_id = get_user_groupid($p_login);
  $query = "SELECT  `" . DB_TABLE_BOOK . "`.id  FROM `" . DB_TABLE_BOOK . "` WHERE  `" . DB_TABLE_BOOK .
    "`.`id`=$t_book_id AND `" . DB_TABLE_BOOK . "`.`id` IN 
    (SELECT `" . DB_TABLE_BOOK_COURSE . "`.`book_id` FROM `" . DB_TABLE_BOOK_COURSE . "` WHERE  `" .
    DB_TABLE_BOOK_COURSE . "`.`course_id` IN 
        (SELECT `" . DB_TABLE_GROUP_COURSE . "`.`course_id` FROM `" . DB_TABLE_GROUP_COURSE . "` 
        WHERE (`" . DB_TABLE_GROUP_COURSE . "`.`group_id`=$user_group_id OR `" . DB_TABLE_GROUP_COURSE . "`.`group_id` IN 
            (SELECT `" . DB_TABLE_GROUP_USER . "`.`group_id` FROM `" . DB_TABLE_GROUP_USER . "` 
            WHERE `" . DB_TABLE_GROUP_USER . "`.`user_id`=$user_id)) AND 
            CURDATE() BETWEEN `" . DB_TABLE_GROUP_COURSE . "`.`limited_from` AND `" . DB_TABLE_GROUP_COURSE .
    "`.`limited_to` ))";
  $result = db_exec($query);
  return $result->RowCount() > 0;
}

/**
 * Get books by course id.
 *
 * @param $p_course_id int course id
 *
 * @return array books array
 */
function get_books_by_course_id($p_course_id)
{
  $t_course_id = db_prepare_int($p_course_id);

  $query = 'SELECT ' . DB_TABLE_BOOK . '.*
		  FROM ' . DB_TABLE_BOOK . ', ' . DB_TABLE_BOOK_COURSE . ' WHERE ' . DB_TABLE_BOOK . '.`id` = ' .
    DB_TABLE_BOOK_COURSE . '.`book_id`
		  AND ' . DB_TABLE_BOOK_COURSE . '.`course_id` = ' . $t_course_id . ' AND ' . DB_TABLE_BOOK_COURSE .
    '.`hidden` = 0';

  return db_query($query);
}

/**
 * Get books by course id.
 *
 * @param $p_course_id int course id
 *
 * @return array books array
 */
function get_all_books_by_course_id($p_course_id)
{
  $t_course_id = db_prepare_int($p_course_id);

  $query =
    "SELECT " . DB_TABLE_BOOK . ".* FROM " . DB_TABLE_BOOK . ", " . DB_TABLE_BOOK_COURSE . " WHERE " . DB_TABLE_BOOK .
    ".`id` = " . DB_TABLE_BOOK_COURSE . ".`book_id`  AND " . DB_TABLE_BOOK_COURSE .
    ".`course_id` = $t_course_id ORDER BY `book_title`";

  return db_query($query);
}

/**
 * Get books by course id, user login, and user password.
 *
 * @param $p_course_id int course id
 * @param $p_login string user login
 * @param $p_password string user password
 *
 * @return array books array
 */
function get_books_by_course_id_and_login_password($p_course_id, $p_login, $p_password)
{
  $t_course_id = db_prepare_int($p_course_id);
  $t_login = db_prepare_string($p_login);
  $t_password = db_prepare_string(md5($p_password));

  $query = 'SELECT DISTINCT `' . DB_TABLE_BOOK . '`.*
		FROM `' . DB_TABLE_BOOK . '`, `' . DB_TABLE_USER . '`, `' . DB_TABLE_GROUP . '`, `' . DB_TABLE_GROUP_COURSE .
    '`, `' . DB_TABLE_COURSE . '`, `' . DB_TABLE_BOOK_COURSE . '` WHERE	`' . DB_TABLE_USER . '`.`user_login` = ' .
    $t_login . ' AND `' . DB_TABLE_USER . '`.`user_password` = ' . $t_password . ' AND `' . DB_TABLE_USER .
    '`.`user_group_id` = `' . DB_TABLE_GROUP . '`.`id`' . ' AND `' . DB_TABLE_GROUP_COURSE . '`.`group_id` = `' .
    DB_TABLE_GROUP . '`.`id`' . ' AND `' . DB_TABLE_GROUP_COURSE . '`.`course_id` = `' . DB_TABLE_COURSE . '`.`id`' .
    ' AND `' . DB_TABLE_BOOK_COURSE . '`.`course_id` = `' . DB_TABLE_COURSE . '`.`id`' . ' AND `' .
    DB_TABLE_BOOK_COURSE . '`.`book_id` = `' . DB_TABLE_BOOK . '`.`id`
		  AND `' . DB_TABLE_BOOK_COURSE . '`.`hidden` = 0
		  AND `' . DB_TABLE_COURSE . '`.`id` = ' . $t_course_id . ' ORDER BY book_title';

  return db_query($query);
}

/**
 * @param $book_file_name string
 * @return string|bool
 */
function unpack_book($book_file_name)
{
  $zip = new ZipArchive;
  if ($zip->open($book_file_name) === TRUE) {
    $zip->extractTo(CFG_BOOKS_DIR, basename($book_file_name));
    $zip->extractTo(CFG_PATH . DIRECTORY_SEPARATOR . 'media/', basename($book_file_name));
    $zip->close();
    return TRUE;
  } else {
    return 'Can not unpack file';
  }
}

/**
 * Create Book object from array.
 *
 * @param $book_array array
 *
 * @return book class
 */
function get_book_by_array($book_array)
{
  $book = new book();
  if (count($book_array[0]) > 0) {
    $book->id = $book_array[0][0]['id'];
    $book->engine_version = $book_array[0][0]['book_engine_version'];
    $book->title = $book_array[0][0]['book_title'];
    $book->author = $book_array[0][0]['book_author'];
    $book->copyright = $book_array[0][0]['book_copyright'];
    $book->description = $book_array[0][0]['book_description'];
    $book->header = $book_array[0][0]['book_header'];
    $book->footer = $book_array[0][0]['book_footer'];
    $book->theme = $book_array[0][0]['book_theme'];
    $book->contents = $book_array[0][0]['book_contents'];
    $book->mediastorage = $book_array[0][0]['book_mediastorage'];
    $book->html_header = $book_array[0][0]['book_html_header'];
    $book->css = $book_array[0][0]['book_css'];
    $book->guid = $book_array[0][0]['book_guid'];
    $book->toc_width = $book_array[0][0]['toc_width'];
    $book->toc_width_measure = $book_array[0][0]['toc_width_measure'];
    $book->toc_responsive = $book_array[0][0]['toc_responsive'];
    $book->toc_show_search = $book_array[0][0]['toc_show_search'];
    $book->toc_show_dots = $book_array[0][0]['toc_show_dots'];
    $book->toc_show_icons = $book_array[0][0]['toc_show_icons'];
    $book->toc_show_stripes = $book_array[0][0]['toc_show_stripes'];
    $book->author_id = $book_array[0][0]['book_author_id'];
  }

  return $book;
}

function replace_completed_tests_links($user_id, $html)
{
  $new_html = $html;
  define('AREG_PATTERN', '/<a\shref="\?module=testing&amp;tmid=\w.*\<\/a>/mU');
  define('MEDIA_ID_PATTERN_1', '/tmid(\w*.*?)&/');
  define('MEDIA_ID_PATTERN_2', '/tmid(\w*.*?)"/');
  define('BOOK_MEDIA_ID_PATTERN', '/src="media\/(w*.*)ex\.svg"/');

  if (strpos($html, '?module=testing') !== FALSE) {
    preg_match_all(AREG_PATTERN, $html, $matches);
    $matches_count = count($matches[0]);
    if ($matches_count > 0) {
      for ($i = 0; $i < $matches_count; $i++) {
        if (preg_match(MEDIA_ID_PATTERN_1, $matches[0][$i], $media_id_arr) == 1) {
          $test_media_id = ltrim($media_id_arr[1], '=');
          if (preg_match(BOOK_MEDIA_ID_PATTERN, $matches[0][$i], $media_book_id_arr) == 1) {
            $book_media_id = rtrim($media_book_id_arr[1], '/');
            if (user_complete_succesfully_test_by_media_id($user_id, $test_media_id)) {
              $matches[1][$i] =
                str_replace('media/' . $book_media_id . '/' . TEST_LINK_IMG, CFG_IMG_DIR . TEST_COMPLETED_LINK_IMG,
                  $matches[0][$i]);
              $new_html = str_replace($matches[0][$i], $matches[1][$i], $new_html);
            }
          }
        }
      }
      return $new_html;
    } else return $html;
  } else return $html;
}