<?php

/**
 * Specify chapter object. Chapter table.
 */
class chapter
{
  /**
   * int chapter id
   */
  var $id;

  /**
   * int chapter book id
   */
  var $book;

  /**
   * int chapter number
   */
  var $number;

  /**
   * int chapter index
   */
  var $index;

  /**
   * int chapter level
   */
  var $level;

  /**
   * int (0, 1) is chapter visible
   */
  var $visible;

  /**
   * string chapter title
   */
  var $title;

  /**
   * string chapter icon
   */
  var $icon;

  /**
   * string chapter keywords
   */
  var $keywords;

  /**
   * string chapter text
   */
  var $text;

  /**
   * string chapter css
   */
  var $css;

  /**
   * string chapter GUID
   */
  var $guid;
}

/**
 * Import chapters for book_id from xml data file.
 *
 * @param $book_id int book id
 * @param $xml_data array
 *
 * @see get_xml_data()
 */
function import_chapters($book_id, $xml_data)
{
  $count = get_value_array($xml_data, 'sunravbook/chapters/count', 0);
  for ($i = 0; $i < $count; $i++) {
    $chapter = new chapter();
    $chapter->book = $book_id;
    $chapter->number = get_value_array($xml_data, 'sunravbook/chapters/chapter_' . $i . '/number', 0);
    $chapter->index = get_value_array($xml_data, 'sunravbook/chapters/chapter_' . $i . '/index', 0);
    $chapter->level = get_value_array($xml_data, 'sunravbook/chapters/chapter_' . $i . '/level', 0);
    $chapter->visible =
      ((get_value_array($xml_data, 'sunravbook/chapters/chapter_' . $i . '/visible', 'T') == 'T') ? 1 : 0);
    $chapter->title = get_value_array($xml_data, 'sunravbook/chapters/chapter_' . $i . '/title');
    $chapter->icon = get_value_array($xml_data, 'sunravbook/chapters/chapter_' . $i . '/icon');
    $chapter->keywords = get_value_array($xml_data, 'sunravbook/chapters/chapter_' . $i . '/keywords');
    $chapter->text = get_value_array($xml_data, 'sunravbook/chapters/chapter_' . $i . '/text');
    $chapter->css = get_value_array($xml_data, 'sunravbook/chapters/chapter_' . $i . '/css');
    $chapter->guid = get_value_array($xml_data, 'sunravbook/chapters/chapter_' . $i . '/guid');
    add_chapter($chapter);
  }
}

/**
 * Add chapter.
 *
 * @param $chapter chapter class
 *
 * @return int chapter id on success or 0 on failure
 */
function add_chapter($chapter)
{
  $chapter->book = db_prepare_int($chapter->book);
  $chapter->number = db_prepare_int($chapter->number);
  $chapter->index = db_prepare_int($chapter->index);
  $chapter->level = db_prepare_int($chapter->level);
  $chapter->visible = db_prepare_int($chapter->visible);
  $chapter->title = db_prepare_string($chapter->title);
  $chapter->icon = db_prepare_string($chapter->icon);
  $chapter->keywords = db_prepare_string($chapter->keywords);
  $chapter->text = db_prepare_string($chapter->text);
  $chapter->css = db_prepare_string($chapter->css);
  $chapter->guid = db_prepare_string($chapter->guid);

  $query = "INSERT INTO `" . DB_TABLE_CHAPTER . "`
            (
            `chap_book_id`,
            `chap_number`,
            `chap_index`,
            `chap_level`,
            `chap_visible`,
            `chap_title`,
            `chap_icon`,
            `chap_keywords`,
            `chap_text`,
            `chap_css`,
            `chap_guid`
            )
        VALUES
            (
            $chapter->book,
            $chapter->number,
            $chapter->index,
            $chapter->level,
            $chapter->visible,
            $chapter->title,
            $chapter->icon,
            $chapter->keywords,
            $chapter->text,
            $chapter->css,
            $chapter->guid)";

  db_exec($query);

  if (db_last_error() != '') {
    return 0;
  }

  return db_insert_id();
}

/**
 * Delete chapters by book id.
 *
 * @param $p_book_id int book id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_chapters($p_book_id)
{
  $t_book_id = db_prepare_int($p_book_id);

  $query = 'DELETE FROM ' . DB_TABLE_CHAPTER . ' WHERE `chap_book_id`=' . $t_book_id;

  db_exec($query);
  return (db_last_error() == '');
}

/**
 * Get chapter by book id and chapter index.
 *
 * @param $p_book_id int book id
 * @param $p_chapter_index int chapter index or chapter GUID
 *
 * @return chapter class
 */
function get_chapter_by_index($p_book_id, $p_chapter_index)
{
  $t_book_id = db_prepare_int($p_book_id);
  $t_chapter_index = db_prepare_int($p_chapter_index);
  $chapter[] = db_extract(DB_TABLE_CHAPTER, '`chap_book_id`=' . $t_book_id . ' and `chap_index` =' . $t_chapter_index);
  return get_chapter_by_array($chapter);
}

/**
 * Get chapter by book id and chapter GUID.
 *
 * @param int book id
 * @param string chapter  GUID
 *
 * @return chapter class
 */
function get_chapter_by_guid($p_book_id, $p_chapter_guid)
{
  $t_book_id = db_prepare_int($p_book_id);
  $t_chapter_guid = db_prepare_string($p_chapter_guid);
  $chapter[] = db_extract(DB_TABLE_CHAPTER, '`chap_book_id`=' . $t_book_id . ' and `chap_guid` =' . $t_chapter_guid);
  return get_chapter_by_array($chapter);
}

/**
 * Get chapter by book id and chapter index.
 *
 * @param int book id
 * @param int chapter id
 *
 * @return chapter class
 */
function get_chapter_by_id($p_book_id, $p_chapter_id)
{
  $t_book_id = db_prepare_int($p_book_id);
  $t_chapter_id = db_prepare_string($p_chapter_id);
  $chapter[] = db_extract(DB_TABLE_CHAPTER, '`chap_book_id`=' . $t_book_id . ' and `id` =' . $t_chapter_id);
  if (count($chapter[0]) == 0) {
    unset($chapter);
    $chapter[] = db_extract(DB_TABLE_CHAPTER, '`chap_book_id`=' . $t_book_id . ' and `chap_guid` =' . $t_chapter_id);
    if (count($chapter[0]) == 0) {
      unset($chapter);
      $chapter[] = db_extract(DB_TABLE_CHAPTER, '`chap_book_id`=' . $t_book_id . ' and `chap_index` =' . $t_chapter_id);
    }
  }
  return get_chapter_by_array($chapter);
}

/**
 * Get first chapter by book id.
 *
 * @param $p_book_id int book id
 *
 * @return chapter class
 */
function get_first_chapter_by_book_id($p_book_id)
{
  $t_book_id = db_prepare_int($p_book_id);
  $chapter_id = db_query("SELECT min(id) FROM " . DB_TABLE_CHAPTER . " WHERE chap_book_id=$t_book_id");
  $chapters[] = db_extract(DB_TABLE_CHAPTER, '`id`=' . $chapter_id[0][0]);
  return get_chapter_by_array($chapters);
}

/**
 * Create Chapter object from array.
 *
 * @param $chapter_array array
 *
 * @return chapter class
 */
function get_chapter_by_array($chapter_array)
{
  $chapter = new chapter();
  if (count($chapter_array[0]) > 0) {
    $chapter->id = $chapter_array[0][0]['id'];
    $chapter->book = $chapter_array[0][0]['chap_book_id'];
    $chapter->number = $chapter_array[0][0]['chap_number'];
    $chapter->index = $chapter_array[0][0]['chap_index'];
    $chapter->level = $chapter_array[0][0]['chap_level'];
    $chapter->visible = $chapter_array[0][0]['chap_visible'];
    $chapter->title = $chapter_array[0][0]['chap_title'];
    $chapter->icon = $chapter_array[0][0]['chap_icon'];
    $chapter->keywords = $chapter_array[0][0]['chap_keywords'];
    $chapter->text = $chapter_array[0][0]['chap_text'];
    $chapter->css = $chapter_array[0][0]['chap_css'];
    $chapter->guid = $chapter_array[0][0]['chap_guid'];
  }

  return $chapter;
}

