<?php

/**
 * Specify module object. Module table.
 */
class module
{
  /**
   * int module id
   */
  var $id;

  /**
   * string module title
   */
  var $name;

  /**
   * string module name
   */
  var $module;

  /**
   * strting module image file
   */
  var $image;

  /**
   * int (0, 1) is module hidden
   */
  var $hidden;
}

/**
 * Get modules.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter module_filter
 *
 * @return array modules array
 */
function get_modules($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0, $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field, array('id', 'module_name', 'module', 'image', 'hidden'))) {
    $t_sort_field = 'id';
  }

  $order_str = "`$t_sort_field` $t_sort_order";

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "LIMIT $limit, $t_count";
  } else {
    $limit_str = '';
  }
  global $WEB_APP;
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';

  $query = 'SELECT ' . DB_TABLE_MODULE . '.*, ' . DB_TABLE_TRANSLATION . '.`text` as `module_name`
            FROM ' . DB_TABLE_MODULE . ', ' . DB_TABLE_TRANSLATION . ' WHERE ' . DB_TABLE_MODULE . '.`name` = ' .
    DB_TABLE_TRANSLATION . '.`name`' . ' AND ' . DB_TABLE_TRANSLATION . '.`language_id`= ' .
    $WEB_APP['settings']['language_id'] . $tmp . '
            ORDER BY ' . $order_str . ' ' . $limit_str;

  return db_query($query);
}

/**
 * Get modules count by filter.
 *
 * @param $p_filter module_filter
 *
 * @return int modules count
 */
function get_modules_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  global $WEB_APP;
  $query = 'SELECT COUNT(*) as `_count_`
         FROM ' . DB_TABLE_MODULE . ', ' . DB_TABLE_TRANSLATION . ' WHERE ' . DB_TABLE_MODULE . '.`name` = ' .
    DB_TABLE_TRANSLATION . '.`name`
        AND ' . DB_TABLE_TRANSLATION . '.`language_id`= ' . $WEB_APP['settings']['language_id'] . $tmp;
  global $adodb;
  $result = $adodb->Execute($query);

  $count = 0;
  if ($adodb->ErrorMsg() == '') {
    if (!$result->EOF) {
      $count = $result->fields['_count_'];
    }
  }
  return $count;
}

/**
 * Get modules from modules id array.
 *
 * @param $p_id_array array of int modules id
 *
 * @return array modules array
 */
function get_modules_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  global $WEB_APP;
  $query = 'SELECT ' . DB_TABLE_MODULE . '.*, ' . DB_TABLE_TRANSLATION . '.`text` as `module_name`
        FROM ' . DB_TABLE_MODULE . ', ' . DB_TABLE_TRANSLATION . ' WHERE ' . DB_TABLE_MODULE . '.`name` = ' .
    DB_TABLE_TRANSLATION . '.`name`
        AND ' . DB_TABLE_TRANSLATION . '.`language_id`= ' . $WEB_APP['settings']['language_id'] . '
        AND ' . DB_TABLE_MODULE . '.`id` in (' . $tmp . ')
        ORDER BY ' . DB_TABLE_MODULE . '.`id` ASC';


  return db_query($query);
}

/**
 * Get module by module id.
 *
 * @param integer $p_id module id
 *
 * @return module class
 */
function get_module($p_id)
{
  $t_id = db_prepare_int($p_id);

  global $WEB_APP;
  $query = 'SELECT ' . DB_TABLE_MODULE . '.*, ' . DB_TABLE_TRANSLATION . '.`text` as `category_name`
        FROM ' . DB_TABLE_MODULE . ',' . DB_TABLE_TRANSLATION . ' WHERE ' . DB_TABLE_TRANSLATION . '.`language_id`= ' .
    $WEB_APP['settings']['language_id'] . '
        AND ' . DB_TABLE_MODULE . '.`id` =' . $t_id;

  $modules[] = db_query($query);
  $module = new module();

  if (count($modules[0]) > 0) {
    $module->id = $modules[0][0]['id'];
    $module->name = $modules[0][0]['name'];
    $module->module = $modules[0][0]['module'];
    $module->image = $modules[0][0]['image'];
    $module->hidden = $modules[0][0]['hidden'];
  }

  return $module;
}

/**
 * Delete module.
 *
 * @param $p_id int module id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_module($p_id)
{
  global $adodb;

  $t_id = db_prepare_int($p_id);

  $count = db_count(DB_TABLE_CATEGORY_MODULE, '`module_id` = ' . $t_id) + get_module_actions_count_for_module_id($t_id);

  if ($count == 0) {
    $query = 'DELETE FROM ' . DB_TABLE_MODULE . ' WHERE id=' . $t_id;
    $adodb->Execute($query);
    return ($adodb->ErrorMsg() == '');
  } else {
    return FALSE;
  }


}

/**
 * Delete modules.
 *
 * @param $p_id_array array of int modules id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_modules($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_module($id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Add module.
 *
 * @param $p_module string module name
 * @param $p_name string module title
 * @param $p_image string module image file name
 * @param $p_hidden int (0, 1) is module hidden
 *
 * @return ADORecordSet result
 */
function add_module($p_module, $p_name, $p_image, $p_hidden)
{
  global $adodb;
  $t_module = db_prepare_string($p_module);
  $t_name = db_prepare_string($p_name);
  $t_image = db_prepare_string($p_image);
  $t_hidden = db_prepare_int($p_hidden);

  $query = 'INSERT INTO ' . DB_TABLE_MODULE . '(`module`, `name`, `image`, `hidden`)
        VALUES(' . $t_module . ',' . $t_name . ',' . $t_image . ',' . $t_hidden . ')';

  return $adodb->Execute($query);
}

/**
 * Get module id by title.
 *
 * @param $p_name string module title
 *
 * @return int module id on success or 0 on failure
 */
function get_module_id_by_name($p_name)
{
  $t_name = db_prepare_string(trim($p_name));

  $modules[] = db_extract(DB_TABLE_MODULE, '`name` = ' . $t_name);
  $id = 0;

  if (count($modules[0]) > 0) {
    $id = $modules[0][0]['id'];
  }

  return $id;
}

/**
 * Get module id by name.
 *
 * @param $p_module string module name
 *
 * @return int module id on success or 0 on failure
 */
function get_module_id_by_module($p_module)
{
  $t_module = db_prepare_string(trim($p_module));

  $modules[] = db_extract(DB_TABLE_MODULE, '`module` = ' . $t_module);
  $id = 0;

  if (count($modules[0]) > 0) {
    $id = $modules[0][0]['id'];
  }

  return $id;
}


/**
 * Edit module.
 *
 * @param $p_id int module id
 * @param $p_module string new module name
 * @param $p_name string new module title
 * @param $p_image string new module image file name
 * @param $p_hidden int module is hidden
 *
 * @return ADORecordSet result
 */
function edit_module($p_id, $p_module, $p_name, $p_image, $p_hidden)
{
  global $adodb;
  $t_id = db_prepare_int($p_id);
  $t_module = db_prepare_string($p_module);
  $t_name = db_prepare_string($p_name);
  $t_image = db_prepare_string($p_image);
  $t_hidden = db_prepare_int($p_hidden);

  $query = 'UPDATE ' . DB_TABLE_MODULE . ' SET `module` = ' . $t_module . ',
         `name` = ' . $t_name . ',
         `image` = ' . $t_image . ',
         `hidden` = ' . $t_hidden . '
          WHERE `id` =' . $t_id;

  return $adodb->Execute($query);
}