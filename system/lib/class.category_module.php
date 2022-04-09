<?php

/**
 * Specify category_module object. Category_module table.
 */
class category_module
{
    /**
     * int category_module id
     */
    var $id;

    /**
     * string category name
     */
    var $category;

    /**
     * string module name
     */
    var $module;

    /**
     * int (0, 1) is hidden
     */
    var $hidden;

    /**
     * int category position
     */
    var $position;
}

/**
 * Get category modules.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter category_module_filter
 *
 * @return array category_module array
 */
function get_category_modules($p_sort_field = 'id',
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

    if (!in_array($t_sort_field, array('id', 'category_name',
                    'module_name', 'position', 'hidden')))
    {
        $t_sort_field = 'id';
    }

    $order_str = "`$t_sort_field` $t_sort_order";

    if ($p_count != 0)
    {
        $limit = ($t_page-1)*$t_count;
        $limit_str = "$limit, $t_count";
    }
    else
    {
        $limit_str = '';
    }

    global $WEB_APP;
    $tmp =  ($p_filter != NULL) ? $p_filter->query() : '';
    $query = 'SELECT '.DB_TABLE_CATEGORY_MODULE.'.`id`,'.
            DB_TABLE_CATEGORY_MODULE.'.`hidden`,'.
            DB_TABLE_CATEGORY_MODULE.'.`position`,'.
            DB_TABLE_TRANSLATION.'.`text` as `category_name`,
            `module_translation`.`text` as `module_name`
            FROM '.DB_TABLE_CATEGORY.', '.DB_TABLE_MODULE.', '.
                DB_TABLE_CATEGORY_MODULE.', '.
                DB_TABLE_TRANSLATION.', '.
                DB_TABLE_TRANSLATION.' as `module_translation`
            WHERE '.DB_TABLE_CATEGORY.'.`id` = '.
                DB_TABLE_CATEGORY_MODULE.'.`category_id`
            AND '.DB_TABLE_TRANSLATION.'.`name` = '.
                DB_TABLE_CATEGORY.'.`name`
            AND '.DB_TABLE_TRANSLATION.'.`language_id` = '.
                $WEB_APP['settings']['language_id'].
            ' AND '.DB_TABLE_MODULE.'.`id` = '.
                DB_TABLE_CATEGORY_MODULE.'.`module_id`
            AND `module_translation`.`language_id` = '.
                $WEB_APP['settings']['language_id'].
            ' AND `module_translation`.`name` = '.
                DB_TABLE_MODULE.'.`name` '.
            $tmp.'
            ORDER BY '.$order_str;

    if ($limit_str != '')
    {
        $query .= ' LIMIT '. $limit_str;
    }
  return db_query($query);
}

/**
 * Get category modules count by filter.
 *
 * @param $p_filter category_module_filter
 *
 * @return int category modules count
 */
function get_category_modules_count($p_filter)
{
    global $WEB_APP;
    $tmp =  ($p_filter != NULL) ? $p_filter->query() : '';
    $query = 'SELECT count('.DB_TABLE_CATEGORY_MODULE.'.`id`) as `_count_`
            FROM '.DB_TABLE_CATEGORY.', '.DB_TABLE_MODULE.', '.
                DB_TABLE_CATEGORY_MODULE.', '.
                DB_TABLE_TRANSLATION.', '.
                DB_TABLE_TRANSLATION.' as `module_translation`
            WHERE '.DB_TABLE_CATEGORY.'.`id` = '.
                DB_TABLE_CATEGORY_MODULE.'.`category_id`
            AND '.DB_TABLE_TRANSLATION.'.`name` = '.
                DB_TABLE_CATEGORY.'.`name`
            AND '.DB_TABLE_TRANSLATION.'.`language_id` = '.
                $WEB_APP['settings']['language_id'].
            ' AND '.DB_TABLE_MODULE.'.`id` = '.
                DB_TABLE_CATEGORY_MODULE.'.`module_id`
            AND `module_translation`.`language_id` = '.
                $WEB_APP['settings']['language_id'].
            ' AND `module_translation`.`name` = '.
                DB_TABLE_MODULE.'.`name` '.
            $tmp;

    $result = db_query($query);

    return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get category modules from category modules id array.
 *
 * @param $p_id_array array of int category modules id
 *
 * @return array category_module array
 */
function get_category_modules_from_array($p_id_array)
{
    $tmp = '';
    $array = array_values($p_id_array);
    $size = sizeof($array);

    if ($size == 0)
        return NULL;

    for($i = 0; $i < $size - 1; $i++)
        $tmp .=  db_prepare_int($array[$i]).', ';
    $tmp .= db_prepare_int($array[$size-1]);

    global $WEB_APP;

    $query = 'SELECT '.DB_TABLE_CATEGORY_MODULE.'.`id`,'.
            DB_TABLE_CATEGORY_MODULE.'.`hidden`,'.
            DB_TABLE_CATEGORY_MODULE.'.`position`,'.
            DB_TABLE_TRANSLATION.'.`text` as `category_name`,
            `module_translation`.`text` as `module_name`
            FROM '.DB_TABLE_CATEGORY.', '.DB_TABLE_MODULE.', '.
                DB_TABLE_CATEGORY_MODULE.', '.
                DB_TABLE_TRANSLATION.', '.
                DB_TABLE_TRANSLATION.' as `module_translation`
            WHERE '.DB_TABLE_CATEGORY.'.`id` = '.
                DB_TABLE_CATEGORY_MODULE.'.`category_id`
            AND '.DB_TABLE_TRANSLATION.'.`name` = '.
                DB_TABLE_CATEGORY.'.`name`
            AND '.DB_TABLE_TRANSLATION.'.`language_id` = '.
                $WEB_APP['settings']['language_id'].
            ' AND '.DB_TABLE_MODULE.'.`id` = '.
                DB_TABLE_CATEGORY_MODULE.'.`module_id`
            AND `module_translation`.`language_id` = '.
                $WEB_APP['settings']['language_id'].
            ' AND `module_translation`.`name` = '.
                DB_TABLE_MODULE.'.`name`
            AND '.DB_TABLE_CATEGORY_MODULE.'.`id` in('.$tmp.')
            ORDER BY '.DB_TABLE_CATEGORY.'.`id` ASC';

  return db_query($query);
}

/**
 * Add category module.
 *
 * @param $p_category_id int category id
 * @param $p_module_id int module id
 * @param $p_position int category position
 * @param $p_hidden int category is hidden
 *
 * @return ADORecordSet result
 */
function add_category_module($p_category_id, $p_module_id,
                $p_position, $p_hidden)
{
    $t_category_id = db_prepare_int($p_category_id);
    $t_module_id = db_prepare_int($p_module_id);
    $t_position = db_prepare_int($p_position);
    $t_hidden = db_prepare_int($p_hidden);

    $query = 'INSERT INTO '.DB_TABLE_CATEGORY_MODULE.
        '(`category_id`, `module_id`, `position`, `hidden`)
        VALUES('.$t_category_id.','.$t_module_id.','.
            $t_position.', '.$t_hidden.')';

    return db_exec($query);
}

/**
 * Get category module.
 *
 * @param integer $p_id category module id
 *
 * @return category_module class
 */
function get_category_module($p_id)
{
    $t_id = db_prepare_int($p_id);
    global $WEB_APP;
    $query = 'SELECT '.DB_TABLE_CATEGORY_MODULE.'.`id`,'.
            DB_TABLE_CATEGORY_MODULE.'.`hidden`,'.
            DB_TABLE_CATEGORY_MODULE.'.`position`,'.
            DB_TABLE_TRANSLATION.'.`text` as `category_name`,
            `module_translation`.`text` as `module_name`
            FROM '.DB_TABLE_CATEGORY.', '.DB_TABLE_MODULE.', '.
                DB_TABLE_CATEGORY_MODULE.', '.
                DB_TABLE_TRANSLATION.', '.
                DB_TABLE_TRANSLATION.' as `module_translation`
            WHERE '.DB_TABLE_CATEGORY.'.`id` = '.
                DB_TABLE_CATEGORY_MODULE.'.`category_id`
            AND '.DB_TABLE_TRANSLATION.'.`name` = '.
                DB_TABLE_CATEGORY.'.`name`
            AND '.DB_TABLE_TRANSLATION.'.`language_id` = '.
                $WEB_APP['settings']['language_id'].
            ' AND '.DB_TABLE_MODULE.'.`id` = '.
                DB_TABLE_CATEGORY_MODULE.'.`module_id`
            AND `module_translation`.`language_id` = '.
                $WEB_APP['settings']['language_id'].
            ' AND `module_translation`.`name` = '.
                DB_TABLE_MODULE.'.`name`
            AND '.DB_TABLE_CATEGORY_MODULE.'.`id` = '.$t_id;

    $category_modules[] = db_query($query);
    $category_module = new category_module();
    if (count($category_modules[0]) > 0)
    {
        $category_module->id = $category_modules[0][0]['id'];
        $category_module->category =
            $category_modules[0][0]['category_name'];
        $category_module->module =
            $category_modules[0][0]['module_name'];
        $category_module->position =
            $category_modules[0][0]['position'];
        $category_module->hidden = $category_modules[0][0]['hidden'];
    }

    return $category_module;
}

/**
 * Get category module id by category id and module id.
 *
 * @param $p_category_id int category id
 * @param $p_module_id int module id
 *
 * @return int category module id on success or 0 on failure
 */
function get_category_module_id($p_category_id, $p_module_id)
{
    $t_category_id = db_prepare_string($p_category_id);
    $t_module_id = db_prepare_string($p_module_id);

    $id = 0;
    $query = 'SELECT '.DB_TABLE_CATEGORY_MODULE.'.`id`
            FROM '.DB_TABLE_CATEGORY_MODULE.'
            WHERE '.DB_TABLE_CATEGORY_MODULE.'.`category_id` = '.
                $t_category_id.'
            AND '.DB_TABLE_CATEGORY_MODULE.'.`module_id` = '.
                $t_module_id;

    $category_modules[] = db_query($query);
    if (count($category_modules[0]) > 0)
    {
        $id = $category_modules[0][0]['id'];
    }

    return $id;
}

/**
 * Delete category module.
 *
 * @param $p_id int category module id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_category_module($p_id)
{
    $t_id = db_prepare_int($p_id);

    $query =  'DELETE FROM '.DB_TABLE_CATEGORY_MODULE.' WHERE id='.$t_id;
    db_exec($query);

    return (db_last_error() == '');
}

/**
 * Delete category modules.
 *
 * @param $p_id_array array of int category modules id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_category_modules($p_id_array)
{
    $tmp = TRUE;
    foreach($p_id_array as $id)
    {
        $result =  delete_category_module($id);
        $tmp = $tmp && $result;
    }

    return $tmp;
}

/**
 * Edit category module.
 *
 * @param $p_id int category module id
 * @param $p_category_id int category id
 * @param $p_module_id int module id
 * @param $p_position int category position
 * @param $p_hidden int category is hidden
 *
 * @return ADORecordSet result
 */
function edit_category_module($p_id, $p_category_id, $p_module_id,
            $p_position, $p_hidden)
{
    $t_id = db_prepare_int($p_id);
    $t_category_id = db_prepare_int($p_category_id);
    $t_module_id = db_prepare_int($p_module_id);
    $t_position = db_prepare_int($p_position);
    $t_hidden = db_prepare_int($p_hidden);

    $query = 'UPDATE '.DB_TABLE_CATEGORY_MODULE.
         ' SET `category_id` = '.$t_category_id.',
         `module_id` = '.$t_module_id.',
         `position` = '.$t_position.',
         `hidden` = '.$t_hidden.'
          WHERE `id` ='.$t_id;

    return db_exec($query);
}

