<?php

/**
 * Specify access object. Access table.
 */
class access
{
    /**
     * int access id
     */
    var $id;

    /**
     * string access grant
     */
    var $grant;

    /**
     * string access module
     */
    var $module;

    /**
     * string access action
     */
    var $action;
}

/**
 * Get accesses.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter access_filter class
 *
 * @return array accesses array
 */
function get_accesses($p_sort_field = 'id',
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

    if (!in_array($t_sort_field, array('id', 'grant_title', 'module_name', 'action')))
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
    $query = 'SELECT '.DB_TABLE_ACCESS.'.`id`, '.
             DB_TABLE_TRANSLATION.'.`text` as `module_name`,'.
             '`'.DB_TABLE_GRANT.'`.`grant_title`,'.
             DB_TABLE_MODULE_ACTION.'.`action`
             FROM '.DB_TABLE_MODULE.','.DB_TABLE_ACCESS.', '.
             DB_TABLE_TRANSLATION.', `'.DB_TABLE_GRANT.'`,'.
             DB_TABLE_MODULE_ACTION.
             ' WHERE '.DB_TABLE_ACCESS.'.`module_action_id`='.
             DB_TABLE_MODULE_ACTION.'.`id`
             AND '.DB_TABLE_MODULE_ACTION.'.`module_id`='.
             DB_TABLE_MODULE.'.`id`
             AND '.DB_TABLE_MODULE.'.`name` = '.
             DB_TABLE_TRANSLATION.'.`name`
             AND '.DB_TABLE_TRANSLATION.'.`language_id`= '.
             $WEB_APP['settings']['language_id'].'
             AND `'.DB_TABLE_GRANT.'`.`id`='.
             DB_TABLE_ACCESS.'.`grant_id` '.$tmp.'
             ORDER BY '.$order_str;

    if ($limit_str != '')
    {
        $query .= ' LIMIT '. $limit_str;
    }

  return db_query($query);
}

/**
 * Get accesses count.
 *
 * @param $p_filter access_filter class
 *
 * @return int accesses count
 */
function get_accesses_count($p_filter)
{
    $tmp =  ($p_filter != NULL) ? $p_filter->query() : '';
    global $WEB_APP;
    $query = 'SELECT COUNT(*) as `_count_`
             FROM '.DB_TABLE_MODULE.','.DB_TABLE_ACCESS.', '.
             DB_TABLE_TRANSLATION.', `'.DB_TABLE_GRANT.'`,'.
             DB_TABLE_MODULE_ACTION.
             ' WHERE '.DB_TABLE_ACCESS.'.`module_action_id`='.
             DB_TABLE_MODULE_ACTION.'.`id`
             AND '.DB_TABLE_MODULE_ACTION.'.`module_id`='.
             DB_TABLE_MODULE.'.`id`
             AND '.DB_TABLE_MODULE.'.`name` = '.
             DB_TABLE_TRANSLATION.'.`name`
             AND '.DB_TABLE_TRANSLATION.'.`language_id`= '.
             $WEB_APP['settings']['language_id'].'
             AND `'.DB_TABLE_GRANT.'`.`id`='.
             DB_TABLE_ACCESS.'.`grant_id` '.$tmp;

    $result = db_query($query);

    return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get accesses array by module.
 *
 * @param $p_module_id int module id
 *
 * @return array accesses array
 */
function get_accesses_by_module_id($p_module_id)
{
    $t_module_id = db_prepare_string($p_module_id);

    global $WEB_APP;

    $query = 'SELECT '.DB_TABLE_ACCESS.'.`id`, '.
            DB_TABLE_TRANSLATION.'.`text` as `module_name`,'.
            '`'.DB_TABLE_GRANT.'`.`grant_title`, '.
            '`'.DB_TABLE_GRANT.'`.`id` as `grant_id`
             FROM '.DB_TABLE_MODULE.','.DB_TABLE_ACCESS.', '.
             DB_TABLE_TRANSLATION.', `'.DB_TABLE_GRANT.'`'.
            ' WHERE '.DB_TABLE_ACCESS.'.`module_id`='.
            DB_TABLE_MODULE.'.`id`
            AND '.DB_TABLE_MODULE.'.`name` = '.
            DB_TABLE_TRANSLATION.'.`name`
            AND '.DB_TABLE_TRANSLATION.'.`language_id`= '.
            $WEB_APP['settings']['language_id'].'
            AND `'.DB_TABLE_GRANT.'`.`id`='.
            DB_TABLE_ACCESS.'.`grant_id`
            AND '.DB_TABLE_MODULE.'.`id` ='.$t_module_id.'
            ORDER BY '.DB_TABLE_ACCESS.'.`id` ASC';

  return db_query($query);
}

/**
 * Get accesses from accesses id array.
 *
 * @param $p_id_array array of int accesses id
 *
 * @return array accesses array
 */
function get_accesses_from_array($p_id_array)
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

    $query = 'SELECT '.DB_TABLE_ACCESS.'.`id`, '.
             DB_TABLE_TRANSLATION.'.`text` as `module_name`,'.
             '`'.DB_TABLE_GRANT.'`.`grant_title`,'.
             DB_TABLE_MODULE_ACTION.'.`action`
             FROM '.DB_TABLE_MODULE.','.DB_TABLE_ACCESS.', '.
             DB_TABLE_TRANSLATION.', `'.DB_TABLE_GRANT.'`,'.
             DB_TABLE_MODULE_ACTION.
             ' WHERE '.DB_TABLE_ACCESS.'.`module_action_id`='.
             DB_TABLE_MODULE_ACTION.'.`id`
             AND '.DB_TABLE_MODULE_ACTION.'.`module_id`='.
             DB_TABLE_MODULE.'.`id`
             AND '.DB_TABLE_MODULE.'.`name` = '.
             DB_TABLE_TRANSLATION.'.`name`
             AND '.DB_TABLE_TRANSLATION.'.`language_id`= '.
             $WEB_APP['settings']['language_id'].'
             AND `'.DB_TABLE_GRANT.'`.`id`='.
             DB_TABLE_ACCESS.'.`grant_id`
             AND '.DB_TABLE_ACCESS.'.`id` in('.$tmp.')
             ORDER BY '.DB_TABLE_ACCESS.'.`id` ASC';

  return db_query($query);
}

/**
 * Delete access.
 *
 * @param $p_id int access id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_access($p_id)
{
    $t_id = db_prepare_int($p_id);

    $query =  'DELETE FROM '.DB_TABLE_ACCESS.' WHERE id='.$t_id;
    db_query($query);

    return (db_last_error() == '');
}

/**
 * Delete accesses.
 *
 * @param $p_id_array  array of int accesses id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_accesses($p_id_array)
{
    $tmp = TRUE;
    foreach($p_id_array as $id)
    {
        $result =  delete_access($id);
        $tmp = $tmp && $result;
    }

    return $tmp;
}

/**
 * Add access.
 *
 * @param $p_grant_id int grant id
 * @param $p_module_id int module id
 * @param $p_action string action
 *
 * @return boolean
 */
function add_access($p_grant_id, $p_module_id, $p_action)
{
    $t_grant_id = db_prepare_int($p_grant_id);

    $p_module_action_id = get_module_action_id($p_module_id, $p_action);
    $t_module_action_id = db_prepare_int($p_module_action_id);
    if ($t_module_action_id == 0)
    {
        return FALSE;
    }

    $query = 'INSERT INTO '.DB_TABLE_ACCESS.'(`grant_id`, `module_action_id`)
              VALUES('.$t_grant_id.','.$t_module_action_id.')';

    db_query($query);
    return TRUE;
}

/**
 * Get access id by grant and module.
 *
 * @param $p_grant_id int grant id
 * @param $p_module_id int module id
 * @param $p_action string
 * @return int access id on success or 0 on failure
 */
function get_access_id($p_grant_id, $p_module_id, $p_action)
{
    $t_grant_id = db_prepare_int($p_grant_id);

    $p_module_action_id = get_module_action_id($p_module_id, $p_action);
    $t_module_action_id = db_prepare_int($p_module_action_id);

    if ($t_module_action_id == 0)
    {
        return 0;
    }

    $accesses[] = db_extract(DB_TABLE_ACCESS, '`grant_id` = '.$t_grant_id.
                ' and `module_action_id`='.$t_module_action_id);
    $id = 0;

    if (count($accesses[0]) > 0)
    {
        $id = $accesses[0][0]['id'];
    }

    return $id;
}

/**
 * Get access.
 *
 * @param integer $p_id  access id
 *
 * @return access class
 */
function get_access($p_id)
{
    $t_id = db_prepare_int($p_id);
    global $WEB_APP;
    $query = 'SELECT '.DB_TABLE_ACCESS.'.`id`, '.
             DB_TABLE_TRANSLATION.'.`text` as `module_name`,'.
             '`'.DB_TABLE_GRANT.'`.`grant_title`,'.
             DB_TABLE_MODULE_ACTION.'.`action`
             FROM '.DB_TABLE_MODULE.','.DB_TABLE_ACCESS.', '.
             DB_TABLE_TRANSLATION.', `'.DB_TABLE_GRANT.'`,'.
             DB_TABLE_MODULE_ACTION.
             ' WHERE '.DB_TABLE_ACCESS.'.`module_action_id`='.
             DB_TABLE_MODULE_ACTION.'.`id`
             AND '.DB_TABLE_MODULE_ACTION.'.`module_id`='.
             DB_TABLE_MODULE.'.`id`
             AND '.DB_TABLE_MODULE.'.`name` = '.
             DB_TABLE_TRANSLATION.'.`name`
             AND '.DB_TABLE_TRANSLATION.'.`language_id`= '.
             $WEB_APP['settings']['language_id'].'
             AND `'.DB_TABLE_GRANT.'`.`id`='.
             DB_TABLE_ACCESS.'.`grant_id`
             AND '.DB_TABLE_ACCESS.'.`id` = '.$t_id;

    $accesses[] = db_query($query);
    $access = new access();
    if (count($accesses[0]) > 0)
    {
        $access->id = $accesses[0][0]['id'];
        $access->grant = $accesses[0][0]['grant_title'];
        $access->module = $accesses[0][0]['module_name'];
        $access->action = $accesses[0][0]['action'];
    }

    return $access;
}

/**
 * Edit access.
 *
 * @param $p_id int access id
 * @param $p_grant_id int grant id
 * @param $p_module_id int module id
 * @param $p_action string
 * @return array|boolean
 */
function edit_access($p_id, $p_grant_id, $p_module_id, $p_action)
{
    $t_id = db_prepare_int($p_id);
    $t_grant_id = db_prepare_int($p_grant_id);

    $p_module_action_id = get_module_action_id($p_module_id, $p_action);
    $t_module_action_id = db_prepare_int($p_module_action_id);

    if ($t_module_action_id == 0)
    {
        return FALSE;
    }

    $query = 'UPDATE '.DB_TABLE_ACCESS.
             ' SET `grant_id` = '.$t_grant_id.',
             `module_action_id` = '.$t_module_action_id.'
             WHERE `id` ='.$t_id;

    return db_query($query);
}

/**
 * Get module id for access id.
 *
 * @param $p_id int access id
 *
 * @return int module id on success, 0 on failure
 */
function get_access_module_id($p_id)
{
    $t_id = db_prepare_int($p_id);

    $query = 'SELECT ma.`module_id`
              FROM '.DB_TABLE_MODULE_ACTION.' ma
              INNER JOIN '.DB_TABLE_ACCESS.' a
              ON ma.`id`=a.`module_action_id`
              AND a.`id`='.$t_id.'
              LIMIT 1';

    $result = db_query($query);
    return (isset($result[0]['module_id']) ? (int) $result[0]['module_id'] : 0);
}

/**
 * Get access count for module_action id. Use for delete module_actions.
 * If access with module_action exist - impossible delete module_action.
 *
 * @param $p_module_action_id int module_action id
 *
 * @return int access count
 */
function get_access_count_for_module_action_id($p_module_action_id)
{
    $t_module_action_id = db_prepare_int($p_module_action_id);

    return db_count(DB_TABLE_ACCESS, '`module_action_id`='.$t_module_action_id);
}
