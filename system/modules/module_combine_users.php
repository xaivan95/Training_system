<?php

class module_combine_users
{
  function view()
  {
    global $WEB_APP;

    $users = get_users('user_name', 'ASC');
    $select_users = array();
    $select_user = new user();

    if (isset($_POST["submit_button"])) {
      combine_users($_POST['users_from'], $_POST['user']);
    }


    $fields = array();
    $fields[] = new field(TRUE, text('txt_users'), 'multiple_select', 'users_from[]', $select_users, '', $users, 'id',
      'user_name', null, FALSE, '', '', 'data-live-search="true"');
    $fields[] =
      new field(TRUE, text('txt_user'), 'select', 'user', $select_user, '', $users, 'id', 'user_name', null, FALSE, '',
        '', 'data-live-search="true"');

    $WEB_APP['show_form'] = TRUE;
    $WEB_APP['show_table'] = FALSE;
    $WEB_APP['editform'] = TRUE;
    $WEB_APP['fields'] = $fields;
    $WEB_APP['submit_title'] = text('txt_combine');
    $WEB_APP['title'] = text('txt_combine_users');
    //$WEB_APP['view']->display('combine_users.tpl', text('txt_combine'));
    $WEB_APP['view']->display('table_array_rows.tpl', text('txt_combine_users'));
  }
}