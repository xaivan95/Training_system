<?php

function cmp_themes($a, $b)
{
  return strcmp($a['name'], $b['name']);
}

/**
 * @see module_base
 */
class module_accounts extends module_base
{
  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title_edit'] = text('txt_edit_user');
    $WEB_APP['title_delete'] = text('txt_delete_users');
    $WEB_APP['title_add'] = text('txt_add_user');
    $WEB_APP['title_clear'] = text('txt_clear_empty_results');
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
      $result = delete_users($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_users') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_users_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';
    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_users_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column("user_login", text('txt_login')),
      new column("group_name", text('txt_group')), new column("user_name", text('txt_user_name')),
      new column("user_info", text('txt_info')), new column("user_mail", text('txt_mail')),
      new column("user_hidden", text('txt_hidden')));

    $WEB_APP['escape'] = TRUE;
    $WEB_APP['submit_title'] = text('txt_delete');
    $WEB_APP['view']->display('list_action.tpl', text('txt_users'));
  }

  /**
   * Implementation of module_base::view().
   */
  function view()
  {
    global $WEB_APP;
    global $adodb;
    $themes = $this->get_themes();
    $user_id = get_user_id($_SESSION['user_login']);
    $groups = get_groups('group_name', 'ASC', 1, 0, NULL, $user_id);
    $grants = get_grants('grant_title');
    $languages = get_unhidden_languages();
    $user = new user();

    if (is_confirm_delete_action()) {
      $result = delete_users($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_users') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (is_confirm_action('clear')) {
      $result = remove_empty_results($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_error_clearing_results') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (is_confirm_move_action()) {
      if ($_POST['group'] == 0) {
        $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
        $this->on_move();
        exit();
      }
      $result = move_users($_POST['group'], $_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_move_all_users') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
      $this->view();
      exit();
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $user = get_user($WEB_APP['id']);
      if (!isset($user->id)) {
        redirect($WEB_APP['errorstext']);
      }

      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_users_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'delete';
    }
    if (($WEB_APP['action'] == 'clear') && (!is_clear_action())) {
      $user = get_user($WEB_APP['id']);
      if (!isset($user->id)) {
        redirect($WEB_APP['errorstext']);
      }

      $WEB_APP['title'] = $WEB_APP['title_clear'];
      $WEB_APP['items'] = get_empty_results_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'clear';
    }

    if ($WEB_APP['action'] == 'view') {
      // After press Add button.
      if (is_add_form('name')) {
        $correct_post = TRUE;
        if ($_POST['group'] == 0) {
          $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
          $correct_post = FALSE;
        }
        if (trim($_POST['login']) == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_login') . "<br>";
          $correct_post = FALSE;
        }
        if (trim($_POST['password']) == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_password') . "<br>";
          $correct_post = FALSE;
        }
        if (trim($_POST['name']) == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_user_name') . "<br>";
          $correct_post = FALSE;
        }

        if (!(is_email($_POST['mail']) || ($_POST['mail'] == ""))) {
          $WEB_APP['errorstext'] .= text('txt_email_does_not_correct_insert_correct_email') . "<br>";
          $correct_post = FALSE;
        }

        if (get_user_id($_POST['login']) != 0) {
          $WEB_APP['errorstext'] .= text('txt_user_already_exist_insert_another_user_login') . "<br>";
          $correct_post = FALSE;
        }
        if (get_user_id_by_mail($_POST['mail']) != 0) {
          $WEB_APP['errorstext'] .= text('txt_user_mail_already_exist_insert_another_user_mail') . "<br>";
          $correct_post = FALSE;
        }

        if ($correct_post) {
          add_user($_POST['group'], $_POST['login'], $_POST['password'], $_POST['name'], $_POST['info'], $_POST['mail'],
            isset($_POST['hidden']) ? 1 : 0, isset($_POST['grants']) ? $_POST['grants'] : array(), $_POST['language'],
            $_POST['birthday'], $_POST['position'], $_POST['phone'], $_POST['address'], $_POST['field1'],
            $_POST['field2'], $_POST['field3'], (isset($_POST['theme'])) ? $_POST['theme'] : '');
          $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
          redirect($WEB_APP['errorstext']);
        }
      }

      if (is_delete_action()) {
        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_users_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['action'] = 'delete';
      } elseif (is_clear_action()) {
        $WEB_APP['title'] = $WEB_APP['title_clear'];
        $WEB_APP['items'] = get_empty_results_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['action'] = 'clear';
      } else {
        $WEB_APP['title'] = text('txt_users');
        // Get sections. On show sections.
        $WEB_APP['items_count'] = get_users_count(new account_filter());//db_count(DB_TABLE_USER);
        // Pages count.
        $pages = get_pages_count($WEB_APP['items_count'],
          ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);


        if ($WEB_APP['page'] > $pages) {
          $WEB_APP['page'] = $pages;
        }
        $users = get_users($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
          new account_filter());
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        $WEB_APP['items'] = $users;

        $paginator = new paginator($WEB_APP['page'], $pages);
        $paginator->url = $WEB_APP['script_name'];
        $paginator->url_query_array = $WEB_APP['url_query_array'];
        $WEB_APP['paginator'] = $paginator;
      }
      $user = $this->get_post_user();
    }
    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete'], $WEB_APP['action_clear']);
    $WEB_APP['list_actions'] = array($WEB_APP['list_action_delete'], $WEB_APP['list_action_move']);

    $WEB_APP['columns'] = array(new column('id', 'id'), new column("user_login", text('txt_login')),
      new column("group_name", text('txt_group')), new column("user_name", text('txt_user_name')),
      new column("user_info", text('txt_info')), new column("user_mail", text('txt_mail')),
      new column("user_hidden", text('txt_hidden')), new column("user_position", text('txt_user_position')));
    if (isset($WEB_APP['settings']['users_info_mode'])) {
      if ($WEB_APP['settings']['users_info_mode'] > 0) {
        $WEB_APP['columns'] = array_merge($WEB_APP['columns'],
          array(new column("user_field1", text('txt_field1')), new column("user_field2", text('txt_field2')),
            new column("user_field3", text('txt_field3'))));
      }
      if ($WEB_APP['settings']['users_info_mode'] > 1) {
        $WEB_APP['columns'] = array_merge($WEB_APP['columns'],
          array(new column("user_birthday", text('txt_birthday')), new column("user_phone", text('txt_phone')),
            new column("user_address", text('txt_address'))));

      }
    }

    $WEB_APP['escape'] = TRUE;
    $language = get_language($user->language_id);
    // Form fields.
    $fields = array();
    $fields[] =
      new field(TRUE, text('txt_group'), "select", "group", $user->group, "", $groups, "id", "group_name", null, FALSE,
        '', '', 'data-live-search="true"');
    $fields[] = new field(TRUE, text('txt_user_name'), "text", "name", $user->name, "");
    $fields[] = new field(TRUE, text('txt_login'), "text", "login", $user->login, "");
    $fields[] = new field(TRUE, text('txt_password'), "text", "password", $user->password, "");
    $fields[] = new field(TRUE, text('txt_mail'), "email", "mail", $user->mail, "");
    $fields[] = new field(FALSE, text('txt_birthday'), "date", "birthday", $user->birthday, "");
    $fields[] = new field(FALSE, text('txt_user_position'), "text", "position", $user->position, "");
    $fields[] = new field(FALSE, text('txt_phone'), "tel", "phone", $user->phone, "");
    $fields[] = new field(FALSE, text('txt_address'), "text", "address", $user->address, "");
    $fields[] = new field(FALSE, text('txt_field1'), "text", "field1", $user->field1, "");
    $fields[] = new field(FALSE, text('txt_field2'), "text", "field2", $user->field2, "");
    $fields[] = new field(FALSE, text('txt_field3'), "text", "field3", $user->field3, "");
    $fields[] =
      new field(FALSE, text('txt_language'), "select", "language", $language->name, "", $languages, "id", "name", null,
        FALSE, '', '');
    $fields[] = new field(TRUE, text('txt_theme'), 'select', 'theme', $user->theme, '', $themes, 'name', 'name');
    $fields[] = new field(FALSE, text('txt_info'), "textarea", "info", $user->info, "");
    $fields[] = new field(FALSE, text('txt_hidden'), "checkbox", "hidden", $user->hidden == 1, "hidden");
    $fields[] = new field(FALSE, text('txt_permissions'), "header");
    foreach ($grants as $grant) {

      $fields[] = new field(FALSE, $grant['grant_title'], "checkbox", "grants[" . $grant['id'] . "]",
        isset($user->grants[$grant['id']]), "grants[" . $grant['id'] . "]");
    }

    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['fields'] = $fields;
    $WEB_APP['view']->display('table.tpl', text('txt_users'));
  }



  function get_themes()
  {
    $themes = array();
    if ($handle = opendir(CFG_THEMES_DIR)) {
      while (($file_name = readdir($handle)) !== FALSE) {
        if ((is_dir(CFG_THEMES_DIR . $file_name)) && ($file_name != '.') && ($file_name != '..')) {
          $themes[] = array('name' => $file_name);
        }
      }
      closedir($handle);
    }
    usort($themes, 'cmp_themes');
    return $themes;
  }

  function on_move()
  {
    global $WEB_APP;

    if (!isset($_POST['selected_row'])) {
      $this->view();
      exit();
    }
    $groups = get_groups('group_name', 'ASC');
    $user = new user();

    $WEB_APP['title'] = text('txt_move');
    $WEB_APP['items'] = get_users_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'move';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column("user_login", text('txt_login')),
      new column("group_name", text('txt_group')), new column("user_name", text('txt_user_name')),
      new column("user_info", text('txt_info')), new column("user_mail", text('txt_mail')),
      new column("user_hidden", text('txt_hidden')));

    $WEB_APP['escape'] = TRUE;

    // Form fields.
    $fields = array();

    $fields[] =
      new field(TRUE, text('txt_group'), "select", "group", $user->group, "", $groups, 'id', 'group_name', null, FALSE,
        '', '', 'data-live-search="true"');

    $WEB_APP['fields'] = $fields;
    $WEB_APP['list_action'] = 'move';
    $WEB_APP['submit_title'] = text('txt_move');
    $WEB_APP['view']->display('list_action.tpl', text('txt_users'));
  }

  function get_post_user()
  {
    global $WEB_APP;
    $reg_grants = @unserialize($WEB_APP['settings']['reg_grants']);
    $grants = array();
    if (is_array($reg_grants)) {
      foreach ($reg_grants as $key => $value) {
        $grants[$key] = TRUE;
      }
    }

    $user = new user();
    $group = get_group((isset($_POST['group'])) ? trim($_POST['group']) : $WEB_APP['settings']['def_group']);
    $language_id = (isset($_POST['language'])) ? $_POST['language'] : 0;
    $language = get_language($language_id);
    $user->group = $group->name;
    $user->language_id = $language->id;
    $user->login = (isset($_POST['login'])) ? trim($_POST['login']) : '';
    $user->theme = (isset($_POST['theme'])) ? trim($_POST['theme']) : CFG_DEFAULT_THEME;
    $user->password = (isset($_POST['password'])) ? trim($_POST['password']) : '';
    $user->info = (isset($_POST['info'])) ? trim($_POST['info']) : '';
    $user->mail = (isset($_POST['mail'])) ? trim($_POST['mail']) : '';
    $user->hidden = isset($_POST['hidden']) ? 1 : 0;
    $user->name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
    $user->grants = isset($_POST['grants']) ? $_POST['grants'] : $grants;
    $user->birthday = (isset($_POST['birthday'])) ? trim($_POST['birthday']) : '';
    $user->position = (isset($_POST['position'])) ? trim($_POST['position']) : '';
    $user->phone = (isset($_POST['phone'])) ? trim($_POST['phone']) : '';
    $user->address = (isset($_POST['address'])) ? trim($_POST['address']) : '';
    $user->field1 = (isset($_POST['field1'])) ? trim($_POST['field1']) : '';
    $user->field2 = (isset($_POST['field2'])) ? trim($_POST['field2']) : '';
    $user->field3 = (isset($_POST['field3'])) ? trim($_POST['field3']) : '';

    return $user;
  }

  /** @noinspection PhpUnused */
  function on_clear()
  {
    global $WEB_APP;
    global $adodb;
    if (!isset($_POST['selected_row'])) {
      $this->view();
      exit();
    }
    if (is_confirm_delete_action()) {
      $result = delete_users($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_users') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_users_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';
    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_users_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column("user_login", text('txt_login')),
      new column("group_name", text('txt_group')), new column("user_name", text('txt_user_name')),
      new column("user_info", text('txt_info')), new column("user_mail", text('txt_mail')),
      new column("user_hidden", text('txt_hidden')));

    $WEB_APP['escape'] = TRUE;
    $WEB_APP['submit_title'] = text('txt_delete');
    $WEB_APP['view']->display('list_action.tpl', text('txt_users'));
  }

  function on_confirm_delete()
  {
    global $WEB_APP;
    global $adodb;

    $result = delete_users($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_delete_users') . "<br>";
      $this->view();
      exit();
    }
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    redirect($WEB_APP['errorstext']);
  }

  /** @noinspection PhpUnused */
  function on_confirm_move()
  {
    global $WEB_APP;
    global $adodb;
    if ($_POST['group'] == 0) {
      $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
      $this->on_move();
      exit();
    }
    $result = move_users($_POST['group'], $_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_move_all_users') . "<br>";
    }
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    redirect($WEB_APP['errorstext']);
    $this->view();
  }

  function edit()
  {
    global $WEB_APP;
    global $adodb;
    $themes = $this->get_themes();
    $user_id = get_user_id($_SESSION['user_login']);
    $groups = get_groups('group_name', 'ASC', 1, 0, NULL, $user_id);
    $grants = get_grants('grant_title');
    $languages = get_unhidden_languages();
    $WEB_APP['title'] = $WEB_APP['title_edit'];
    // After change press.
    $user = get_user($WEB_APP['id']);
    $user->password = '';
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    if (!isset($user->id)) {
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['id'] >= 0) && is_add_edit_form('name')) {
      $correct_post = TRUE;

      if ($_POST['group'] == 0) {
        $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
        $correct_post = FALSE;
      }
      if (trim($_POST['login']) == "") {
        $WEB_APP['errorstext'] .= text('txt_insert_login') . "<br>";
        $correct_post = FALSE;
      }

      if (trim($_POST['name']) == "") {
        $WEB_APP['errorstext'] .= text('txt_insert_user_name') . "<br>";
        $correct_post = FALSE;
      }

      if (!(is_email($_POST['mail']) || ($_POST['mail'] == ""))) {
        $WEB_APP['errorstext'] .= text('txt_email_does_not_correct_insert_correct_email') . "<br>";
        $correct_post = FALSE;
      }

      $user_id = get_user_id($_POST['login']);
      if (!(($user_id == 0) || ($user_id == $WEB_APP['id']))) {
        $WEB_APP['errorstext'] .= text('txt_user_already_exist_insert_another_user_login') . "<br>";
        $correct_post = FALSE;
      }

      $user_id = get_user_id_by_mail($_POST['mail']);
      if (!(($user_id == 0) || ($user_id == $WEB_APP['id']))) {
        $WEB_APP['errorstext'] .= text('txt_user_mail_already_exist_insert_another_user_mail') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post) {
        edit_user($WEB_APP['id'], $_POST['group'], $_POST['login'], $_POST['password'], $_POST['name'], $_POST['info'],
          $_POST['mail'], isset($_POST['hidden']) ? 1 : 0, isset($_POST['grants']) ? $_POST['grants'] : array(),
          $_POST['language'], $_POST['birthday'], $_POST['position'], $_POST['phone'], $_POST['address'],
          $_POST['field1'], $_POST['field2'], $_POST['field3'], (isset($_POST['theme'])) ? $_POST['theme'] : '');
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      }
      $user = $this->get_post_user();
      redirect($WEB_APP['errorstext']);
    }
    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column("user_login", text('txt_login')),
      new column("group_name", text('txt_group')), new column("user_name", text('txt_user_name')),
      new column("user_info", text('txt_info')), new column("user_mail", text('txt_mail')),
      new column("user_hidden", text('txt_hidden')));
    $WEB_APP['escape'] = TRUE;

    $language = get_language($user->language_id);
    // Form fields.
    $fields = array();
    $fields[] =
      new field(TRUE, text('txt_group'), "select", "group", $user->group, "", $groups, "id", "group_name", null, FALSE,
        '', '', 'data-live-search="true"');
    $fields[] = new field(TRUE, text('txt_login'), "text", "login", $user->login, "");
    $fields[] = new field(FALSE, text('txt_password'), "password", "password", $user->password, "");
    $fields[] = new field(TRUE, text('txt_user_name'), "text", "name", $user->name, "");
    $fields[] = new field(FALSE, text('txt_mail'), "email", "mail", $user->mail, "");
    $fields[] = new field(FALSE, text('txt_birthday'), "date", "birthday", $user->birthday, "");
    $fields[] = new field(FALSE, text('txt_user_position'), "text", "position", $user->position, "");
    $fields[] = new field(FALSE, text('txt_phone'), "text", "phone", $user->phone, "");
    $fields[] = new field(FALSE, text('txt_address'), "text", "address", $user->address, "");
    $fields[] = new field(FALSE, text('txt_field1'), "text", "field1", $user->field1, "");
    $fields[] = new field(FALSE, text('txt_field2'), "text", "field2", $user->field2, "");
    $fields[] = new field(FALSE, text('txt_field3'), "text", "field3", $user->field3, "");
    $fields[] =
      new field(FALSE, text('txt_language'), "select", "language", $language->name, "", $languages, "id", "name", null,
        FALSE, '', '');
    $fields[] = new field(TRUE, text('txt_theme'), 'select', 'theme', $user->theme, '', $themes, 'name', 'name');
    $fields[] = new field(FALSE, text('txt_info'), "textarea", "info", $user->info, "");
    $fields[] = new field(FALSE, text('txt_hidden'), "checkbox", "hidden", $user->hidden == 1, "hidden");
    $fields[] = new field(FALSE, text('txt_permissions'), "header");

    foreach ($grants as $grant) {
      $fields[] = new field(FALSE, $grant['grant_title'], "checkbox", "grants[" . $grant['id'] . "]",
        isset($user->grants[$grant['id']]), "");
    }
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['fields'] = $fields;
    $WEB_APP['view']->display('table.tpl', text('txt_users'));
  }

  /** @noinspection PhpUnused */
  function on_confirm_clear()
  {
    global $WEB_APP;
    global $adodb;

    $result = remove_empty_results($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_error_clearing_results') . "<br>";
      $this->view();
      exit();
    }
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    redirect($WEB_APP['errorstext']);
  }

  /**
   * Clear results with 0 answers and 1 question
   */
  function clear()
  {
    global $WEB_APP;
    remove_empty_results($WEB_APP['id']);
    header('Location: index.php?module=accounts');
  }

}
