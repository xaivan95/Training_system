<?php

/**
 * Specify book_course object. Book_course table.
 */
class book_course
{
    /**
     * int book course id
     */
    var $id;

    /**
     * string book
     */
    var $book;

    /**
     * string course
     */
    var $course;

    /**
     * int hidden (0 - unhidden, 1 - hidden)
     */
    var $hidden;
}

/**
 * Add book course
 *
 * @param $p_book_id int book_id
 * @param $p_course_id int course id
 * @param $p_hidden int hidden book_course
 *
 * @return ADORecordSet result
 */
function add_book_course($p_book_id, $p_course_id, $p_hidden)
{

    $t_book_id = db_prepare_int($p_book_id);
    $t_course_id = db_prepare_int($p_course_id);
    $t_hidden = db_prepare_int($p_hidden);

    $query = 'INSERT INTO ' . DB_TABLE_BOOK_COURSE .
        '(`book_id`, `course_id`, `hidden`)
         VALUES (' . $t_book_id . ', ' . $t_course_id . ', ' . $t_hidden . ')';

    return db_exec($query);
}

/**
 * Add books to course
 *
 * @param $p_books_id array int book_id
 * @param $p_course_id int course id
 * @param $p_hidden int hidden book_course
 *
 * @return ADORecordSet result
 */
function add_books_course($p_books_id, $p_course_id, $p_hidden)
{
    $t_course_id = db_prepare_int($p_course_id);
    $t_hidden = db_prepare_int($p_hidden);
    $values = '';
    $books_count = count($p_books_id);
    for ($i = 0; $i < $books_count; $i++) {
        $t_book_id = db_prepare_int($p_books_id[$i]);
        $values .= "($t_course_id, $t_book_id, $t_hidden),";
    }
    $values = substr($values, 0, -1);

    $query = "INSERT INTO " . DB_TABLE_BOOK_COURSE .
        "(`course_id`, `book_id`, `hidden`) VALUES $values";

    return db_exec($query);
}

/**
 * Get book_courses.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter book_course_filter
 *
 * @return array book_course array
 */
function get_book_courses($p_sort_field = 'id',
                          $p_sort_order = DEFAULT_ORDER,
                          $p_page = 1,
                          $p_count = 0,
                          $p_filter = NULL
)
{
    $t_sort_field = db_escape_string($p_sort_field);
    $t_sort_order = db_prepare_sort_order($p_sort_order);
    $t_page = db_prepare_int($p_page);
    $t_count = db_prepare_int($p_count);

    if ($t_sort_field == '')
        $t_sort_field = 'id';

    if (!in_array($t_sort_field,
        array('id', 'book_title', 'title', 'hidden'))
    ) {
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
    $query = 'SELECT ' . DB_TABLE_BOOK_COURSE . '.`id`, ' .
        DB_TABLE_BOOK . '.`book_title`,' .
        DB_TABLE_COURSE . '.`title`, ' .
        DB_TABLE_BOOK_COURSE . '.`hidden`
          FROM ' .
        DB_TABLE_BOOK_COURSE . ',' .
        DB_TABLE_BOOK . ', ' .
        DB_TABLE_COURSE .
        ' WHERE ' .
        DB_TABLE_BOOK_COURSE . '.`book_id`=' . DB_TABLE_BOOK . '.`id`
            AND ' . DB_TABLE_COURSE . '.`id` = ' .
        DB_TABLE_BOOK_COURSE . '.`course_id`' . $tmp . '
            ORDER BY ' . $order_str;

    if ($limit_str != '') {
        $query .= ' LIMIT ' . $limit_str;
    }
  return db_query($query);
}

/**
 * Get book courses count by filter.
 *
 * @param $p_filter book_course_filter
 *
 * @return int book courses count
 */
function get_book_courses_count($p_filter)
{
    $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
    $query = 'SELECT COUNT(*) AS `_count_`
         FROM ' .
        DB_TABLE_BOOK_COURSE . ',' .
        DB_TABLE_BOOK . ', ' .
        DB_TABLE_COURSE .
        ' WHERE ' .
        DB_TABLE_BOOK_COURSE . '.`book_id`=' . DB_TABLE_BOOK . '.`id`
            AND ' . DB_TABLE_COURSE . '.`id` = ' .
        DB_TABLE_BOOK_COURSE . '.`course_id`' . $tmp;

    $result = db_query($query);

    return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get book courses from book course id array.
 *
 * @param $p_id_array array of int book course id
 *
 * @return array book_course array
 */
function get_book_courses_from_array($p_id_array)
{
    $tmp = '';
    $array = array_values($p_id_array);
    $size = sizeof($array);

    if ($size == 0)
        return NULL;

    for ($i = 0; $i < $size - 1; $i++)
        $tmp .= db_prepare_int($array[$i]) . ', ';
    $tmp .= db_prepare_int($array[$size - 1]);

    $query = 'SELECT ' . DB_TABLE_BOOK_COURSE . '.`id`, ' .
        DB_TABLE_BOOK . '.`book_title`,' .
        DB_TABLE_COURSE . '.`title`,' .
        DB_TABLE_BOOK_COURSE . '.`hidden`
         FROM ' .
        DB_TABLE_BOOK_COURSE . ',' .
        DB_TABLE_BOOK . ', ' .
        DB_TABLE_COURSE .
        ' WHERE ' .
        DB_TABLE_BOOK_COURSE . '.`book_id`=' . DB_TABLE_BOOK . '.`id`
            AND ' . DB_TABLE_COURSE . '.`id` = ' .
        DB_TABLE_BOOK_COURSE . '.`course_id`
            AND ' . DB_TABLE_BOOK_COURSE . '.`id` in(' . $tmp . ')
            ORDER BY ' . DB_TABLE_BOOK_COURSE . '.`id` ASC';

  return db_query($query);
}

/**
 * Delete book course.
 *
 * @param $p_id int book_course id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_book_course($p_id)
{
    $t_id = db_prepare_int($p_id);

    $query = 'DELETE FROM ' . DB_TABLE_BOOK_COURSE . ' WHERE id=' . $t_id;
    db_exec($query);
    return (db_last_error() == '');
}

/**
 * Delete book courses.
 *
 * @param $p_id_array array of int book_course id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_book_courses($p_id_array)
{
    $tmp = TRUE;
    foreach ($p_id_array as $id) {
        $result = delete_book_course($id);
        $tmp = $tmp && $result;
    }

    return $tmp;
}

/**
 * Get book course id by book id and course id.
 *
 * @param $p_book_id int book id
 * @param $p_course_id int course id
 *
 * @return int book_course id on success or 0 on failure
 */
function get_book_course_id($p_book_id, $p_course_id)
{
    $t_book_id = db_prepare_int($p_book_id);
    $t_course_id = db_prepare_int($p_course_id);

    $book_courses[] = db_extract(DB_TABLE_BOOK_COURSE,
        '`book_id` = ' . $t_book_id . ' and `course_id`=' . $t_course_id);
    $id = 0;

    if (count($book_courses[0]) > 0) {
        $id = $book_courses[0][0]['id'];
    }

    return $id;
}

/**
 * Get book course by book_course id.
 *
 * @param integer $p_id book_course id
 *
 * @return book_course class
 */
function get_book_course($p_id)
{
    $t_id = db_prepare_int($p_id);
    $query = 'SELECT ' . DB_TABLE_BOOK_COURSE . '.`id`, ' .
        DB_TABLE_BOOK . '.`book_title`,' .
        DB_TABLE_COURSE . '.`title`, ' .
        DB_TABLE_BOOK_COURSE . '.`hidden`
         FROM ' .
        DB_TABLE_BOOK_COURSE . ',' .
        DB_TABLE_BOOK . ', ' .
        DB_TABLE_COURSE .
        ' WHERE ' .
        DB_TABLE_BOOK_COURSE . '.`book_id`=' . DB_TABLE_BOOK . '.`id`
            AND ' . DB_TABLE_COURSE . '.`id` = ' .
        DB_TABLE_BOOK_COURSE . '.`course_id`' .
        ' AND ' . DB_TABLE_BOOK_COURSE . '.`id` = ' . $t_id;

    $book_courses[] = db_query($query);
    $book_course = new book_course();
    if (count($book_courses[0]) > 0) {
        $book_course->id = $book_courses[0][0]['id'];
        $book_course->book = $book_courses[0][0]['book_title'];
        $book_course->course = $book_courses[0][0]['title'];
        $book_course->hidden = $book_courses[0][0]['hidden'];
    }

    return $book_course;
}

/**
 * Edit book_course.
 *
 * @param $p_id int book_course id
 * @param $p_book_id int new book id
 * @param $p_course_id int new course id
 * @param $p_hidden int new hidden
 *
 * @return ADORecordSet result
 */
function edit_book_course($p_id, $p_book_id, $p_course_id, $p_hidden)
{
    $t_id = db_prepare_int($p_id);
    $t_book_id = db_prepare_int($p_book_id);
    $t_course_id = db_prepare_int($p_course_id);
    $t_hidden = db_prepare_int($p_hidden);

    $query = 'UPDATE ' . DB_TABLE_BOOK_COURSE .
        ' SET `book_id` = ' . $t_book_id . ',
         `course_id` = ' . $t_course_id . ',
         `hidden` = ' . $t_hidden . '
          WHERE `id` =' . $t_id;

    return db_exec($query);
}

