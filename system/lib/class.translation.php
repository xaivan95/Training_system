<?php

/**
 * Specify translation object. Translation table.
 */
class translation
{
    /**
     * int translation id
     */
    var $id;

    /**
     * int language id
     */
    var $language;

    /**
     * string translation name
     */
    var $name;

    /**
     * string translation text
     */
    var $text;
}

/**
 * Get translations.
 *
 * @param $p_sort_field string sort field
 * @param string $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param int $p_language_id
 * @param $p_filter translation_filter
 * @return array translations array
 */
function get_translations($p_sort_field = 'id',
                          $p_sort_order = DEFAULT_ORDER,
                          $p_page = 1,
                          $p_count = 0,
                          $p_language_id = 0,
                          $p_filter = NULL
)
{
    $t_sort_field = db_escape_string($p_sort_field);
    $t_sort_order = db_prepare_sort_order($p_sort_order);
    $t_page = db_prepare_int($p_page);
    $t_count = db_prepare_int($p_count);
    $t_language_id = db_prepare_int($p_language_id);

    if ($t_sort_field == '')
        $t_sort_field = 'id';

    if (!in_array($t_sort_field, array('id', 'language', 'name', 'text'))) {
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
    if ($tmp != '') {
        $tmp = ' AND ' . $tmp;
    }
    $query = 'SELECT ' . DB_TABLE_TRANSLATION . '.`id`, ' .
        DB_TABLE_LANGUAGES . '.`name` AS `language`, ' .
        DB_TABLE_TRANSLATION . '.`name`, ' .
        DB_TABLE_TRANSLATION . '.`text`
          FROM ' . DB_TABLE_TRANSLATION . ',' . DB_TABLE_LANGUAGES .
        ' WHERE ' . DB_TABLE_TRANSLATION . '.`language_id` = ' .
        DB_TABLE_LANGUAGES . '.`id` ' . $tmp;

    if ($t_language_id != 0) {
        $query .= ' AND ' . DB_TABLE_LANGUAGES . '.id =' . $t_language_id;
    }

    $query .= ' ORDER BY ' . $order_str;

    if ($limit_str != '') {
        $query .= ' LIMIT ' . $limit_str;
    }

  return db_query($query);
}

/**
 * Get translations from translations id array.
 *
 * @param $p_id_array array of int translations id
 *
 * @return array translations array
 */
function get_translations_from_array($p_id_array)
{
    $tmp = '';
    $array = array_values($p_id_array);
    $size = sizeof($array);

    if ($size == 0)
        return NULL;

    for ($i = 0; $i < $size - 1; $i++)
        $tmp .= db_prepare_int($array[$i]) . ', ';
    $tmp .= db_prepare_int($array[$size - 1]);

    $query = 'SELECT ' . DB_TABLE_TRANSLATION . '.id, ' .
        DB_TABLE_LANGUAGES . '.name AS `language`, ' .
        DB_TABLE_TRANSLATION . '.name, ' .
        DB_TABLE_TRANSLATION . '.text
          FROM ' . DB_TABLE_TRANSLATION . ',' . DB_TABLE_LANGUAGES .
        ' WHERE ' . DB_TABLE_TRANSLATION . '.language_id = ' .
        DB_TABLE_LANGUAGES . '.id
           AND ' . DB_TABLE_TRANSLATION . '.id IN(' . $tmp . ') ORDER BY ' .
        DB_TABLE_TRANSLATION . '.`id` ASC';

    return db_query($query);
}

/**
 * Delete translation.
 *
 * @param $p_id int translation id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_translation($p_id)
{
    $t_id = db_prepare_int($p_id);
    $query = 'DELETE FROM ' . DB_TABLE_TRANSLATION . ' WHERE id=' . $t_id;
    db_exec($query);

    return (db_last_error() == '');
}

/**
 * Delete translations.
 *
 * @param $p_id_array array of int translations id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_translations($p_id_array)
{
    $tmp = TRUE;
    foreach ($p_id_array as $id) {
        $result = delete_translation($id);
        $tmp = $tmp && $result;
    }

    return $tmp;
}

/**
 * Add translation.
 *
 * @param integer $p_language int translation language id
 * @param string $p_name translation name
 * @param string $p_text translation text
 *
 * @return bool TRUE on success or FALSE on failure
 */
function add_translation($p_language, $p_name, $p_text)
{
    $t_language = db_prepare_int($p_language);
    $t_name = db_prepare_string($p_name);
    $t_text = db_prepare_string($p_text);

    $query = 'INSERT INTO ' . DB_TABLE_TRANSLATION . '(`language_id`,`name`, `text`)
        VALUES(' . $t_language . ', ' . $t_name . ', ' . $t_text . ')';

    db_exec($query);

    return (db_last_error() == '');
}

/**
 * Get translation id by language id and translation name.
 *
 * @param $p_language_id int language id
 * @param $p_name string translation name
 *
 * @return int translation id on success or 0 on failure
 */
function get_translation_id($p_language_id, $p_name)
{
    $t_language_id = db_prepare_int($p_language_id);
    $t_name = db_prepare_string(trim($p_name));

    $translations[] = db_extract(DB_TABLE_TRANSLATION,
        "`language_id` = $t_language_id and `name` = $t_name");
    $id = 0;

    if (count($translations[0]) > 0) {
        $id = $translations[0][0]['id'];
    }

    return $id;
}

/**
 * Get translation.
 *
 * @param $p_id int translation id
 *
 * @return translation class
 */
function get_translation($p_id)
{
    $t_id = db_prepare_int($p_id);
    $translations[] = db_extract(DB_TABLE_TRANSLATION, '`id` = ' . $t_id);
    $translation = new translation();
    if (count($translations[0]) > 0) {
        $translation->id = $translations[0][0]['id'];
        $translation->language = $translations[0][0]['language_id'];
        $translation->name = $translations[0][0]['name'];
        $translation->text = $translations[0][0]['text'];
    }

    return $translation;
}

/**
 * Edit translation.
 *
 * @param $p_id int translation id
 * @param $p_language_id int new language id
 * @param $p_name string new translation name
 * @param $p_text string new translation text
 *
 * @return ADORecordSet result
 */
function edit_translation($p_id, $p_language_id, $p_name, $p_text)
{
    $t_id = db_prepare_int($p_id);
    $t_language_id = db_prepare_int($p_language_id);
    $t_name = db_prepare_string(trim($p_name));
    $t_text = db_prepare_string(trim($p_text));

    $query = 'UPDATE ' . DB_TABLE_TRANSLATION .
        ' SET `language_id` = ' . $t_language_id . ',
         `name` = ' . $t_name . ',
         `text` = ' . $t_text .
        ' WHERE `id` =' . $t_id;

    return db_exec($query);
}

/**
 * Get translations count for language id
 *
 * @param $p_language_id int language id
 * @param $p_filter translation_filter
 *
 * @return int translations count
 */
function get_translations_count($p_language_id, $p_filter)
{
    $t_language_id = db_prepare_int($p_language_id);
    $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
    if ($tmp != '') {
        $tmp = ' AND ' . $tmp;
    }
    $query = 'SELECT COUNT(*) AS `_count_`
             FROM ' . DB_TABLE_TRANSLATION . ',' . DB_TABLE_LANGUAGES .
        ' WHERE ' . DB_TABLE_TRANSLATION . '.`language_id` = ' .
        DB_TABLE_LANGUAGES . '.`id` ' . $tmp;

    if ($t_language_id != 0) {
        $query .= ' AND ' . DB_TABLE_LANGUAGES . '.id =' . $t_language_id;
    }

    $result = db_query($query);

    return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;

}

/**
 * Update translation by language id and name.
 * Remove current translation and add new.
 *
 * @param int  $p_language_id int translation language id
 * @param string $p_name  translation name
 * @param string $p_text translation text
 *
 * @return bool TRUE on success or FALSE on failure
 */
function update_translation($p_language_id, $p_name, $p_text)
{
    $id = get_translation_id($p_language_id, $p_name);

    if ($id != 0) {
        $b = delete_translation($id);

        if (!$b) return FALSE;
    }

    return add_translation($p_language_id, $p_name, $p_text);
}

