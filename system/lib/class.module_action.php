<?php

/**
 * Specify module_action object. Module_action table.
 */
class module_action
{
    /**
     * int module action id
     */
    var $id;

    /**
     * string module name
     */
    var $module;

    /**
     * string module action
     */
    var $action;
}

/**
 * Get module actions.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter module_action_filter
 *
 * @return array module actions array
 */
function get_module_actions($p_sort_field = 'id',
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

    if (!in_array($t_sort_field, array('id', 'module_name', 'action')))
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
    $query = 'SELECT '.
                DB_TABLE_MODULE_ACTION.'.`id`,
                '.DB_TABLE_TRANSLATION.'.`text` as `module_name`,
                '.DB_TABLE_MODULE_ACTION.'.`action`
              FROM
                '.DB_TABLE_MODULE_ACTION.',
                '.DB_TABLE_MODULE.',
                '.DB_TABLE_TRANSLATION.'
              WHERE
                '.DB_TABLE_MODULE_ACTION.'.`module_id` = '.DB_TABLE_MODULE.'.`id`
                AND '.DB_TABLE_TRANSLATION.'.`name` = '.DB_TABLE_MODULE.'.`name`
                AND '.DB_TABLE_TRANSLATION.'.`language_id` = '.
                $WEB_APP['settings']['language_id'].' '.$tmp.'
                ORDER BY '.$order_str;
    if ($limit_str != '')
    {
        $query .= ' LIMIT '. $limit_str;
    }

    return db_query($query);
}

/**
 * Get module_action count by filter.
 *
 * @param $p_filter module_action_filter
 *
 * @return int module_action count
 */
function get_module_actions_count($p_filter)
{
    $tmp =  ($p_filter != NULL) ? $p_filter->query() : '';
    global $WEB_APP;
    $query = 'SELECT
                COUNT(*) AS `_count_`
              FROM
                '.DB_TABLE_MODULE_ACTION.',
                '.DB_TABLE_MODULE.',
                '.DB_TABLE_TRANSLATION.'
              WHERE
                '.DB_TABLE_MODULE_ACTION.'.`module_id` = '.DB_TABLE_MODULE.'.`id`
                AND '.DB_TABLE_TRANSLATION.'.`name` = '.DB_TABLE_MODULE.'.`name`
                AND '.DB_TABLE_TRANSLATION.'.`language_id` = '.
                $WEB_APP['settings']['language_id'].' '.$tmp;

    $result = db_query($query);

    return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get module actions from module_action id array.
 *
 * @param $p_id_array array of int module action id
 *
 * @return array module actions array
 */
function get_module_actions_from_array($p_id_array)
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

    $query = 'SELECT '.
                DB_TABLE_MODULE_ACTION.'.`id`,
                '.DB_TABLE_TRANSLATION.'.`text` as `module_name`,
                '.DB_TABLE_MODULE_ACTION.'.`action`
              FROM
                '.DB_TABLE_MODULE_ACTION.',
                '.DB_TABLE_MODULE.',
                '.DB_TABLE_TRANSLATION.'
              WHERE
                '.DB_TABLE_MODULE_ACTION.'.`module_id` = '.DB_TABLE_MODULE.'.`id`
                AND '.DB_TABLE_TRANSLATION.'.`name` = '.DB_TABLE_MODULE.'.`name`
                AND '.DB_TABLE_TRANSLATION.'.`language_id` = '.
                $WEB_APP['settings']['language_id'].'
            AND '.DB_TABLE_MODULE_ACTION.'.`id` in('.$tmp.')
            ORDER BY '.DB_TABLE_MODULE_ACTION.'.`id` ASC';

    return db_query($query);
}

/**
 * Delete module action.
 *
 * @param $p_id int module action id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_module_action($p_id)
{
    $t_id = db_prepare_int($p_id);
    $module_action = get_module_action($t_id);
    $access_count = get_access_count_for_module_action_id($module_action->id);

    if ($access_count > 0)
    {
        return FALSE;
    }

    $query =  'DELETE FROM '.DB_TABLE_MODULE_ACTION.' WHERE id='.$t_id;
    db_exec($query);
    return (db_last_error() == '');
}

/**
 * Delete module actions.
 *
 * @param $p_id_array array of int module actions id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_module_actions($p_id_array)
{
    $tmp = TRUE;
    foreach($p_id_array as $id)
    {
        $result =  delete_module_action($id);
        $tmp = $tmp && $result;
    }

    return $tmp;
}

/**
 * Add module action.
 *
 * @param $p_module_id int module id
 * @param $p_action int action id
 *
 * @return ADORecordSet result
 */
function add_module_action($p_module_id, $p_action)
{
    $t_module_id = db_prepare_int($p_module_id);
    $t_action = db_prepare_string($p_action);

    $query = 'INSERT INTO '.DB_TABLE_MODULE_ACTION.
             '(`module_id`, `action`)
             VALUES('.$t_module_id.','.$t_action.')';

    return db_exec($query);
}

/**
 * Get module action id by module id and action.
 *
 * @param $p_module_id int module id
 * @param $p_action string action
 *
 * @return int module action id on success or 0 on failure
 */
function get_module_action_id($p_module_id, $p_action)
{
    $t_module_id = db_prepare_int($p_module_id);
    $t_action = db_prepare_string($p_action);

    $module_actions[] = db_extract(DB_TABLE_MODULE_ACTION,
        '`module_id` = '.$t_module_id.' and `action`='.$t_action);
    $id = 0;

    if (count($module_actions[0]) > 0)
    {
        $id = $module_actions[0][0]['id'];
    }

    return $id;
}

/**
 * Get module action.
 *
 * @param integer $p_id module action id
 *
 * @return module_action class
 */
function get_module_action($p_id)
{
    $t_id = db_prepare_int($p_id);

    global $WEB_APP;
    $query = 'SELECT '.
                DB_TABLE_MODULE_ACTION.'.`id`,
                '.DB_TABLE_TRANSLATION.'.`text` as `module_name`,
                '.DB_TABLE_MODULE_ACTION.'.`action`
              FROM
                '.DB_TABLE_MODULE_ACTION.',
                '.DB_TABLE_MODULE.',
                '.DB_TABLE_TRANSLATION.'
              WHERE
                '.DB_TABLE_MODULE_ACTION.'.`module_id` = '.DB_TABLE_MODULE.'.`id`
                AND '.DB_TABLE_TRANSLATION.'.`name` = '.DB_TABLE_MODULE.'.`name`
                AND '.DB_TABLE_TRANSLATION.'.`language_id` = '.
                $WEB_APP['settings']['language_id'].
            ' AND '.DB_TABLE_MODULE_ACTION.'.`id` = '.$t_id;

    $module_actions[] = db_query($query);
    $module_action = new module_action();
    if (count($module_actions[0]) > 0)
    {
        $module_action->id = $module_actions[0][0]['id'];
        $module_action->module = $module_actions[0][0]['module_name'];
        $module_action->action = $module_actions[0][0]['action'];
    }

    return $module_action;
}

/**
 * Edit module action.
 *
 * @param $p_id int access id
 * @param $p_module_id int new module id
 * @param $p_action string new action
 *
 * @return ADORecordSet result
 */
function edit_module_action($p_id, $p_module_id, $p_action)
{
    $t_id = db_prepare_int($p_id);
    $t_module_id = db_prepare_int($p_module_id);
    $t_action = db_prepare_string($p_action);

    $query = 'UPDATE '.DB_TABLE_MODULE_ACTION.
         ' SET `module_id` = '.$t_module_id.',
         `action` = '.$t_action.'
          WHERE `id` ='.$t_id;

    return db_exec($query);
}

/**
 * Get actions for module.
 *
 * @param $p_module_id int module id
 *
 * @return array actions array
 */
function get_module_actions_for_module_id($p_module_id)
{
    $t_module_id = db_prepare_int($p_module_id);

    $query = 'SELECT *
              FROM '.DB_TABLE_MODULE_ACTION.'
              WHERE `module_id`='.$t_module_id. '
              ORDER BY `action` ';

    return db_query($query);
}

/**
 * Get module_actions count for module id. Use for delete module.
 * If module_actino with module exist - impossible delete module.
 *
 * @param $p_module_id int module id
 *
 * @return int module_action count
 */
function get_module_actions_count_for_module_id($p_module_id)
{
   $t_module_id = db_prepare_int($p_module_id);

    return db_count(DB_TABLE_MODULE_ACTION, '`module_id`='.$t_module_id);
}
