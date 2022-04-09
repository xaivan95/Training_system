<?php

/**
 * Specify category object. Category table.
 */
class category
{
    /**
     * int category id
     */
    var $id;

    /**
     * string category name
     */
    var $name;

    /**
     * int hidden category
     */
    var $hidden;

    /**
     * int category position
     */
    var $position;
}

/**
 * Get categories.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter category_filter
 *
 * @return array categories array
 */
function get_categories($p_sort_field = 'id',
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

    $fields_array = array('id', 'category_name', 'position', 'hidden');
    if (!in_array($t_sort_field, $fields_array))
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
    $query = 'SELECT '.DB_TABLE_TRANSLATION.'.`text` as `category_name`,'.
            DB_TABLE_CATEGORY.'.*
            FROM '.DB_TABLE_CATEGORY.', '.DB_TABLE_TRANSLATION.
            ' WHERE '.DB_TABLE_CATEGORY.'.`name` = '.
            DB_TABLE_TRANSLATION.'.`name`
            AND '.DB_TABLE_TRANSLATION.'.`language_id`= '.
            $WEB_APP['settings']['language_id']. ' '.$tmp.'
            ORDER BY '.$order_str;

    if ($limit_str != '')
    {
        $query .= ' LIMIT '. $limit_str;
    }
  return db_query($query);
}

/**
 * Get unhidden categories.
 *
 * @return array categories array
 */
function get_unhidden_categories()
{
    global $WEB_APP;

    $query = 'SELECT '.DB_TABLE_TRANSLATION.'.`text` as `category_name`,'.
            DB_TABLE_CATEGORY.'.*
            FROM '.DB_TABLE_CATEGORY.', '.DB_TABLE_TRANSLATION.
            ' WHERE '.DB_TABLE_CATEGORY.'.`name` = '.
            DB_TABLE_TRANSLATION.'.`name`
            AND '.DB_TABLE_TRANSLATION.'.`language_id`= '.
            $WEB_APP['settings']['language_id'].
            ' AND '.DB_TABLE_CATEGORY.'.`hidden` = 0
            ORDER BY '.DB_TABLE_CATEGORY.'.`position` ASC';

  return db_query($query);
}

/**
 * Get categories count by filter.
 *
 * @param $p_filter category_filter
 *
 * @return int categories count
 */
function get_categories_count($p_filter)
{
    $tmp =  ($p_filter != NULL) ? $p_filter->query() : '';
    global $WEB_APP;
    $query = 'SELECT COUNT(*) as `_count_`
             FROM '.DB_TABLE_CATEGORY.', '.DB_TABLE_TRANSLATION.
            ' WHERE '.DB_TABLE_CATEGORY.'.`name` = '.
            DB_TABLE_TRANSLATION.'.`name`
            AND '.DB_TABLE_TRANSLATION.'.`language_id`= '.
            $WEB_APP['settings']['language_id']. ' ' .$tmp;

    $result = db_query($query);

    return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}



/**
 * Get categories from categories id array.
 *
 * @param $p_id_array array of int categories id
 *
 * @return array categories array
 */
function get_categories_from_array($p_id_array)
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

    $query = 'SELECT '.DB_TABLE_TRANSLATION.
            '.`text` as `category_name`,'.
            DB_TABLE_CATEGORY.'.*
            FROM '.DB_TABLE_CATEGORY.', '.DB_TABLE_TRANSLATION.
            ' WHERE '.DB_TABLE_CATEGORY.'.`id` in ('.$tmp.')
            AND '.DB_TABLE_CATEGORY.'.`name` = '.
            DB_TABLE_TRANSLATION.'.`name`
            AND '.DB_TABLE_TRANSLATION.'.`language_id`= '.
            $WEB_APP['settings']['language_id'].'
            ORDER BY '.DB_TABLE_CATEGORY.'.`id` ASC';

  return db_query($query);
}

/**
 * Delete category.
 *
 * @param $p_id int category id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_category($p_id)
{
    $t_id = db_prepare_int($p_id);

    $count = db_count(DB_TABLE_CATEGORY_MODULE, '`category_id` = '.$t_id);
    if ($count == 0)
    {
        $query =  'DELETE FROM '.DB_TABLE_CATEGORY.' WHERE id='.$t_id;
        db_query($query);
        return (db_last_error() == '');
    }
    else
    {
        return FALSE;
    }
}

/**
 * Delete categories.
 *
 * @param $p_id_array array of int categories id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_categories($p_id_array)
{
    $tmp = TRUE;
    foreach($p_id_array as $id)
    {
        $result =  delete_category($id);
        $tmp = $tmp && $result;
    }

    return $tmp;
}

/**
 * Add category.
 *
 * @param $p_name string category name
 * @param $p_position int category position
 * @param $p_hidden int category is hidden
 *
 * @return ADORecordSet result
 */
function add_category($p_name, $p_position, $p_hidden)
{
    $t_name = db_prepare_string($p_name);
    $t_position = db_prepare_int($p_position);
    $t_hidden = db_prepare_int($p_hidden);

    $query = 'INSERT INTO '.DB_TABLE_CATEGORY.
         '(`name`, `position`, `hidden`)
         VALUES('.$t_name.','.$t_position.','.$t_hidden.')';

    return db_exec($query);
}

/**
 * Get category id by category name.
 *
 * @param $p_name string category name
 *
 * @return int category id on success or 0 on failure
 */
function get_category_id($p_name)
{
    $t_name = db_prepare_string(trim($p_name));

    $categories[] = db_extract(DB_TABLE_CATEGORY, '`name` = '.$t_name);
    $id = 0;

    if (count($categories[0]) > 0)
    {
        $id = $categories[0][0]['id'];
    }

    return $id;
}

/**
 * Get category.
 *
 * @param integer $p_id category id
 *
 * @return category class
 */
function get_category($p_id)
{
    $t_id = db_prepare_int($p_id);

    $query = 'SELECT '.DB_TABLE_CATEGORY.'.*
            FROM '.DB_TABLE_CATEGORY.
            ' WHERE '.DB_TABLE_CATEGORY.'.`id` = '.$t_id;

    $categories[] = db_query($query);
    $category = new category();
    if (count($categories[0]) > 0)
    {
        $category->id = $categories[0][0]['id'];
        $category->name = $categories[0][0]['name'];
        $category->position = $categories[0][0]['position'];
        $category->hidden = $categories[0][0]['hidden'];
    }

    return $category;
}

/**
 * Edit category.
 *
 * @param $p_id int category id
 * @param $p_name string new category name
 * @param $p_position int category position
 * @param $p_hidden int category is hidden
 *
 * @return ADORecordSet result
 */
function edit_category($p_id, $p_name, $p_position, $p_hidden)
{
    $t_id = db_prepare_int($p_id);
    $t_name = db_prepare_string(trim($p_name));
    $t_position = db_prepare_int($p_position);
    $t_hidden = db_prepare_int($p_hidden);

    $query = 'UPDATE '.DB_TABLE_CATEGORY.
         ' SET `name` = '.$t_name.',
         `position` = '.$t_position.',
         `hidden` = '.$t_hidden.'
          WHERE `id` ='.$t_id;

    return db_exec($query);
}

