<?php

class module_groups_users extends module_base
{
  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title_edit'] = text('txt_edit');
    $WEB_APP['title_delete'] = text('txt_delete');
    $WEB_APP['title_add'] = text('txt_add');
  }

  function on_delete()
  {
    global $WEB_APP;
    global $adodb;
    if (!isset($_POST['selected_row'])) {
      $this->view();
      exit();
    }
    if (is_confirm_delete_action()) {
      $result = delete_user_groups($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_error_remove_user_from_group') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_user_groups_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';
    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_user_groups_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column('group_name', text('txt_group')),
      new column('user_name', text('txt_user')));

    $WEB_APP['escape'] = TRUE;

    $WEB_APP['submit_title'] = text('txt_delete');


    $WEB_APP['view']->display('list_action.tpl', text('txt_manage_users'));
  }

  function view()
  {
    global $WEB_APP;
    global $adodb;
    $groups = get_groups('group_name', 'ASC');
    $users = get_users('user_name', 'ASC');
    $group_user = new group_user();
    if (is_confirm_delete_action()) {
      $result = delete_user_groups($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_group_courses') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $group_user = get_user_group($WEB_APP['id']);
      if (!isset($group_user->id)) {
        redirect($WEB_APP['errorstext']);
      }

      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_user_groups_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'delete';
    }

    if ($WEB_APP['action'] == 'view') {
      // After press Add button.
      if (is_add_form('group')) {
        $correct_post = TRUE;
        if ($_POST['group'] == '') {
          $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
          $correct_post = FALSE;
        }
        if (!isset($_POST['users'])) {
          $WEB_APP['errorstext'] .= text('txt_insert_user_name') . "<br>";
          $correct_post = FALSE;
        }

        if ($correct_post) {
          $i = 0;
          foreach ($_POST['users'] as $user_id) {
            if (get_user_group_id($_POST['group'], $user_id) != 0) {
              $WEB_APP['errorstext'] .= text('txt_group_course_already_exist_insert_another_group_course') .
                ' <strong>' . get_user($user_id)->name . '</strong>.<br>';
              $correct_post = FALSE;
            }
            ++$i;
          }
        }

        if ($correct_post) {
          add_users_to_group($_POST['users'], $_POST['group']);
          $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
          redirect($WEB_APP['errorstext']);
        }
      }

      if (is_delete_action()) {
        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_user_groups_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['action'] = 'delete';
      } else {
        $WEB_APP['title'] = text('txt_manage_users');
        // Get sections. On show sections.
        $WEB_APP['items_count'] = get_user_groups_count(new group_user_filter());

        // Pages count.
        $pages = get_pages_count($WEB_APP['items_count'],
          ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
        if ($WEB_APP['page'] > $pages) {
          $WEB_APP['page'] = $pages;
        }
        $group_users =
          get_user_groups($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
            new group_user_filter());
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        $WEB_APP['items'] = $group_users;

        $paginator = new paginator($WEB_APP['page'], $pages);
        $paginator->url = $WEB_APP['script_name'];
        $paginator->url_query_array = $WEB_APP['url_query_array'];
        $WEB_APP['paginator'] = $paginator;
      }
      $group_user = $this->get_post_group_user();
    }

    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('group_name', text('txt_group')),
      new column('user_name', text('txt_user_name')));
    $WEB_APP['escape'] = TRUE;

    // Form fields.
    $fields = array();
    $fields[] =
      new field(TRUE, text('txt_group'), "select", "group", $group_user->group, "", $groups, 'id', 'group_name', null,
        FALSE, '', '', 'data-live-search="true"');
    $fields[] =
      new field(TRUE, text('txt_user'), "multiple_select", "users[]", $groups, "", $users, 'id', 'user_name', null,
        FALSE, '', '', 'data-live-search="true"');
    $WEB_APP['fields'] = $fields;
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['view']->display('table.tpl', text('txt_manage_users'));
  }

  function get_post_group_user()
  {
    $group_user = new group_user();

    $group_user->group = '';

    if (isset($_POST['group'])) {
      $group = get_group($_POST['group']);
      if ($group->name != NULL) {
        $group_user->group = $group->name;
      }
    }

    $group_user->user = '';

    if (isset($_POST['user'])) {
      $user = get_user($_POST['user']);
      if ($user->name != NULL) {
        $group_user->user = $user->name;
      }
    }

    return $group_user;
  }

  function on_confirm_delete()
  {
    global $WEB_APP;
    global $adodb;

    $result = delete_user_groups($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_remove_users_from_group') . "<br>";
      $this->view();
      exit();
    }
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    redirect($WEB_APP['errorstext']);
  }

  function edit()
  {
    global $WEB_APP;
    global $adodb;

    $groups = get_groups('group_name');
    $users = get_users('user_name');

    $WEB_APP['title'] = $WEB_APP['title_edit'];
    // After change press.
    $group_user = get_user_group($WEB_APP['id']);
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    if (!isset($group_user->id)) {
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['id'] >= 0) && is_add_edit_form('group')) {
      $correct_post = TRUE;
      if ($_POST['group'] == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
        $correct_post = FALSE;
      }
      if (trim($_POST['user']) == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_user_name') . "<br>";
        $correct_post = FALSE;
      }

      $group_user_id = get_user_group_id($_POST['group'], $_POST['user']);
      if (!(($group_user_id == 0) || ($group_user_id == $WEB_APP['id']))) {
        $WEB_APP['errorstext'] .= text('txt_group_user_already_exist') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post) {
        edit_user_group($WEB_APP['id'], $_POST['user'], $_POST['group']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      }
      $group_user = $this->get_post_group_user();
      redirect($WEB_APP['errorstext']);
    }

    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('group_name', text('txt_group')),
      new column('user_name', text('txt_user')));

    $WEB_APP['escape'] = TRUE;


    // Form fields.
    $fields = array();
    $fields[] =
      new field(TRUE, text('txt_group'), "select", "group", $group_user->group, "", $groups, 'id', 'group_name');
    $fields[] = new field(TRUE, text('txt_user'), "select", "user", $group_user->user, "", $users, 'id', 'user_name');
    $WEB_APP['fields'] = $fields;

    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['view']->display('table.tpl', text('txt_manage_users'));
  }

}