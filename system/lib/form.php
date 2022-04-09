<?php

/**
 * Redirect without errors.
 *
 * @param $errorstext string
 */
function redirect($errorstext)
{
    if ($errorstext == '')
    {
        global $WEB_APP;
        $parse_url_array = parse_url(CFG_URL);
        $port = (isset($parse_url_array['port']) && ($parse_url_array['port'] != 80)) ? ':'.$parse_url_array['port'] : '';
        $script_name = $parse_url_array['scheme']."://".
                $parse_url_array['host'].$port.
                $_SERVER['SCRIPT_NAME'].'?module='.
                $WEB_APP['module'];
        header("Location: ".$script_name);
        exit;
    }
}

/**
 * Determine whether press form button.
 *
 * @param $field string form field name.
 *
 * @return bool
 */
function is_add_edit_form($field)
{
    return isset($_POST[$field]);
}

/**
 * Determine whether press form add button.
 *
 * @param string $field  form field name.
 *
 * @return boolean
 */
function is_add_form($field)
{
    global $WEB_APP;

    return isset($_POST[$field]) && in_array('add', $WEB_APP['actions']);
}

/**
 * Determine whether an action is delete.
 *
 * @return bool TRUE if action is delete; FALSE otherwise.
 */
function is_delete_action()
{
    global $WEB_APP;
    
    return  isset($_POST['action']) &&
        isset($_POST['selected_row']) &&
        ($_POST['action'] == 'delete') &&
        in_array('delete', $WEB_APP['actions']);
}

function is_clear_action()
{
  global $WEB_APP;

  return  isset($_POST['action']) &&
    isset($_POST['selected_row']) &&
    ($_POST['action'] == 'clear') &&
    in_array('clear', $WEB_APP['actions']);
}

/**
 * Determine whether an action is confirm_delete.
 *
 * @param $action string
 * @return bool TRUE if action is delete; FALSE otherwise.
 */
function is_confirm_action($action)
{
	return  isset($_POST['action']) && 
		isset($_POST['selected_row']) &&  
		($_POST['action'] == 'confirm_'.$action);
}

/**
 * Determine whether an action is confirm_delete.
 *
 * @return bool TRUE if action is delete; FALSE otherwise.
 */
function is_confirm_delete_action()
{
    global $WEB_APP;

    return  (isset($_POST['list_action']) && ($_POST['list_action'] == 'confirm_delete')) || 
            (isset($_POST['action']) && ($_POST['action'] == 'confirm_delete')) &&
        isset($_POST['selected_row']) &&
        in_array('delete', $WEB_APP['actions']);
}

/**
 * Determine whether an action is move.
 *
 * @return bool TRUE if action is delete; FALSE otherwise.
 */
function is_move_action()
{
    global $WEB_APP;

    return  isset($_POST['list_action']) &&
        isset($_POST['selected_row']) &&
        ($_POST['list_action'] == 'move') &&
        in_array('move', $WEB_APP['actions']);
}

/**
 * Determine whether an action is confirm_move.
 *
 * @return bool TRUE if action is delete; FALSE otherwise.
 */
function is_confirm_move_action()
{
    global $WEB_APP;

    return  isset($_POST['list_action']) &&
        isset($_POST['selected_row']) &&
        ($_POST['list_action'] == 'confirm_move') &&
        in_array('move', $WEB_APP['actions']);
}

/**
 * Convert bool to string: T or F.
 *
 * @param $bool bool
 *
 * @return string T if bool is TRUE; F otherwise.
 */
function bool_to_char($bool)
{
    return ($bool ? "T" : "F");
}

