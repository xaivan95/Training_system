<?php

/**
 * Specify language object. Language table.
 */
class language
{
  /**
   * int language id
   */
  var $id;

  /**
   * string language name
   */
  var $name;

  /**
   * string language short name
   */
  var $short_name;

  var $hidden;
}

/**
 * Get languages.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter language_filter
 *
 * @return array languages array
 */
function get_languages($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0, $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field, array('id', 'name', 'short_name', 'hidden'))) {
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
  return db_extract(DB_TABLE_LANGUAGES, $tmp, $order_str, $limit_str);
}

function get_unhidden_languages()
{
  return db_extract(DB_TABLE_LANGUAGES, 'hidden=0', 'name');
}

/**
 * Get languages count by filter.
 *
 * @param $p_filter language_filter
 *
 * @return int languages count
 */
function get_languages_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  if ($tmp != '') {
    $tmp = ' WHERE ' . $tmp;
  }


  $query = 'SELECT COUNT(*) AS `_count_`
             FROM ' . DB_TABLE_LANGUAGES . $tmp;
  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get languages from languages id array.
 *
 * @param $p_id_array array of int languages id
 *
 * @return array languages array
 */
function get_languages_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  return db_extract(DB_TABLE_LANGUAGES, "id IN($tmp)", 'id ASC');
}

/**
 * Delete language.
 *
 * @param $p_id int language id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_language($p_id)
{

  $t_id = db_prepare_int($p_id);

  //Delete translations.
  $query = 'DELETE FROM ' . DB_TABLE_TRANSLATION . ' WHERE language_id=' . $t_id;
  db_exec($query);

  if (db_last_error() == '') {
    $error = FALSE;
    $query = 'DELETE FROM ' . DB_TABLE_LANGUAGES . ' WHERE id=' . $t_id;
    db_exec($query);
  } else {
    $error = TRUE;
  }


  return ((db_last_error() == '') && !$error);
}

/**
 * Delete languages.
 *
 * @param $p_id_array array of int languages id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_languages($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_language($id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Add language.
 *
 * @param string $p_name language name
 * @param string $p_short_name language short name
 * @param boolean $p_copy copy english translations
 *
 * @return int language id
 */
function add_language($p_name, $p_short_name, $p_copy = TRUE)
{
  $t_name = db_prepare_string(trim($p_name));
  $t_short_name = db_prepare_string(trim($p_short_name));

  $query = 'INSERT INTO ' . DB_TABLE_LANGUAGES . '(`name`, `short_name`)
        VALUES(' . $t_name . ', ' . $t_short_name . ')';

  db_exec($query);
  $language_id = db_insert_id();
  if ($p_copy) {
    // Add translations.
    $translations = get_translations('id', 'ASC', 1, 0, 1);

    foreach ($translations as $translation) {
      add_translation($language_id, $translation['name'], $translation['text']);
    }
  }
  return $language_id;
}

/**
 * Get language id by language name.
 *
 * @param $p_name string language name
 *
 * @return int language id on success or 0 on failure
 */
function get_language_id($p_name)
{
  $t_name = db_prepare_string(trim($p_name));

  $languages[] = db_extract(DB_TABLE_LANGUAGES, '`name` = ' . $t_name);
  $id = 0;

  if (count($languages[0]) > 0) {
    $id = $languages[0][0]['id'];
  }

  return $id;
}

/**
 * Get language id by language short name.
 *
 * @param $p_short_name string language short name
 *
 * @return int language id on success or 0 on failure
 */
function get_language_id_by_short_name($p_short_name)
{
  $t_short_name = db_prepare_string(trim($p_short_name));
  $languages[] = db_extract(DB_TABLE_LANGUAGES, '`short_name` = ' . $t_short_name);
  $id = 0;
  if (count($languages[0]) > 0) {
    $id = $languages[0][0]['id'];
  }

  return $id;
}

/**
 * Get language short name by language id.
 *
 * @param int $p_lang_id language id
 *
 * @return string  language short name
 */
function get_language_short_name_by_id($p_lang_id, $full_name = FALSE)
{
  if ($full_name) $field = 'name'; else $field = 'short_name';
  $t_lang_id = db_prepare_int($p_lang_id);
  $languages[] = db_extract(DB_TABLE_LANGUAGES, '`id` = ' . $t_lang_id);
  $short_language_name = "";
  if (count($languages[0]) > 0) {
    $short_language_name = $languages[0][0][$field];
  }

  return $short_language_name;
}

/**
 * Get language.
 *
 * @param integer $p_id language id
 *
 * @return language class
 */
function get_language($p_id)
{
  $t_id = db_prepare_int($p_id);
  $languages[] = db_extract(DB_TABLE_LANGUAGES, '`id` = ' . $t_id);
  $language = new language();
  if (count($languages[0]) > 0) {
    $language->id = $languages[0][0]['id'];
    $language->name = $languages[0][0]['name'];
    $language->short_name = $languages[0][0]['short_name'];
    $language->hidden = $languages[0][0]['hidden'];
  }

  return $language;
}

/**
 * Edit language.
 *
 * @param $p_id int language id
 * @param $p_name string new language name
 * @param $p_short_name string new language short name
 *
 * @return ADORecordSet result
 */
function edit_language($p_id, $p_name, $p_short_name, $p_hidden = 0)
{
  $t_id = db_prepare_int($p_id);
  $t_name = db_prepare_string(trim($p_name));
  $t_short_name = db_prepare_string(trim($p_short_name));
  $t_hidden = db_prepare_bool($p_hidden);

  $query = "UPDATE " . DB_TABLE_LANGUAGES .
    " SET `name` = $t_name, `short_name` = $t_short_name, `hidden`=$t_hidden  WHERE `id` =$t_id;";
  return db_exec($query);
}

/**
 * Update language.
 *
 * @param string $p_name language name
 * @param string $p_short_name language short name
 *
 * @return int language id
 */
function update_language($p_name, $p_short_name)
{
  $id = get_language_id($p_name);

  if ($id == 0) {
    $id = add_language($p_name, $p_short_name, FALSE);
  }

  return $id;
}

function copy_language($p_language_id)
{
  global $adodb;
  $language_id = db_prepare_int($p_language_id);
  $query = " SELECT * FROM " . DB_TABLE_LANGUAGES . " WHERE id=$language_id";
  $result = db_query($query);
  $new_language_name = $result[0]['name'] . '_1';
  $new_language_short_name = $result[0]['short_name'] . '_1';
  $new_language_name_hidden = db_prepare_int($result[0]['hidden']);
  $query = "INSERT INTO " . DB_TABLE_LANGUAGES .
    " (name, short_name, hidden) VALUES ('$new_language_name', '$new_language_short_name', $new_language_name_hidden)";
  db_exec($query);
  $new_language_id = $adodb->insert_Id();
  if ($new_language_id > 0) {
    $query = "INSERT INTO " . DB_TABLE_TRANSLATION . " (`language_id`, `name`, `text`)  SELECT '$new_language_id', `name`, `text` 
    FROM webclass_translation 
    WHERE language_id=$language_id";
    db_exec($query);
  }
}

function export_language($p_language_id)
{
  $t_language_id = db_prepare_int($p_language_id);
  $language_name = get_language_short_name_by_id($t_language_id, TRUE);
  $language_short_name = get_language_short_name_by_id($t_language_id);
  $file_name = $language_name . ".php";
  $handle = fopen($file_name, "w");
  fwrite($handle, "<?php\n\n");
  fwrite($handle, "\$language_id = update_language(\"" . $language_name . "\", \"" . $language_short_name . "\");\n");
  $translations = get_translations('name', 'ASC', 1, 0, $t_language_id);
  foreach ($translations as $translation) {
    fwrite($handle,
      "update_translation(\$language_id, \"" . $translation['name'] . "\", \"" . addslashes($translation['text']) .
      "\");\n");
  }
  fwrite($handle, "\n?>");
  fclose($handle);
  header('Content-Type: application/octet-stream');
  header("Content-Transfer-Encoding: Binary");
  header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
  readfile($file_name);
  unlink($file_name);
}
