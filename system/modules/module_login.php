<?php

/**
 * @see module_base
 */
class module_login extends module_base
{

  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title'] = text('txt_entrance');
  }

  function view()
  {
    global $WEB_APP;

    if (isset($_POST['user_password'])) {
      $result = $this->check_login_form();
      if (!$result) {
        $this->show_login_form();
        exit;
      }
    }

    $module = $WEB_APP['module'];
    $action = $WEB_APP['action'];

    if (!isset($_SESSION['user_login']) || !isset($_SESSION['user_password'])) {
      $_SESSION['user_login'] = 'anonymous';
      $_SESSION['user_password'] = 'anonymous';
      $this->set_session_user_info();
    }

    $correct_login = $this->is_correct_login($_SESSION['user_login'], $_SESSION['user_password']);

    if ($correct_login) {
      $user = get_user_from_login_password($_SESSION['user_login'], $_SESSION['user_password']);
      $_SESSION['user_id'] = $user->id;
      $admin_grant_id = get_grant_id('admin');
      $WEB_APP['show_project_info'] = array_key_exists($admin_grant_id, $user->grants);

      $this->build_menu($_SESSION['user_login'], $_SESSION['user_password']);
      $WEB_APP['actions'] = get_user_actions($_SESSION['user_login'], $_SESSION['user_password'], $module);
      $access = is_user_access($_SESSION['user_login'], $_SESSION['user_password'], $module, $action);
      $access = $access || ($module == 'index') || (($module == 'login') && ($_SESSION['user_login'] == 'anonymous'));
      if ($access) {
        if ($module == 'login') {
          $this->show_login_form();
          exit;
        }
      } else {
        $module_id = get_module_id_by_module($module);
        if ($module_id > 0) {
          if ($_SESSION['user_login'] == 'anonymous') {
            $this->show_login_form();
            exit;
          }
          $WEB_APP['title'] = text('txt_403_forbidden');
          $WEB_APP['view']->display('403.tpl', text('txt_403_forbidden'));
        } else {
          $WEB_APP['title'] = text('txt_404_not_found');
          $WEB_APP['view']->display('404.tpl', text('txt_404_not_found'));
        }
        exit;
      }
    } else {
      $this->show_login_form();
      exit;
    }
  }

  function check_login_form()
  {
    global $WEB_APP;

    switch ($WEB_APP['settings']['logstyle']) {
      case LOGIN_LIST:
        $this->check_login_list_form();
        break;
      default:
        $this->check_login_direct_form();
        break;
    }
  }

  function check_login_list_form()
  {
    global $WEB_APP;
    $correct_post = TRUE;

    if (isset($_POST['submit_button'])) {
      $user = get_user(isset($_POST['list_user_login']) ? (int)$_POST['list_user_login'] : 0);
      $list_user_login = isset($user->login) ? $user->login : '';
      $list_user_password = isset($_POST['list_user_password']) ? $_POST['list_user_password'] : '';

      $user_login = isset($_POST['user_login']) ? trim($_POST['user_login']) : '';
      $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';

      if (($list_user_login != '') && ($list_user_password != '')) {
        $this->check_login($list_user_login, $list_user_password);
      }

      if (($user_login != '') && ($user_password != '')) {
        $this->check_login($user_login, $user_password);
      }

      if (($list_user_login == '') && ($list_user_password != '')) {
        $WEB_APP['errorstext'] .= text('txt_insert_user_name') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post && ($list_user_login != '') && ($list_user_password == '')) {
        $WEB_APP['errorstext'] .= text('txt_insert_password') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post && ($user_login == '') && ($user_password != '')) {
        $WEB_APP['errorstext'] .= text('txt_insert_login') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post && ($user_login != '') && ($user_password == '')) {
        $WEB_APP['errorstext'] .= text('txt_insert_password') . "<br>";
      }

      $WEB_APP['errorstext'] = text('txt_uncorrect_login_or_password') . '<br>';
      $this->show_login_form();
      exit;
    }
  }

  function check_login($login, $password)
  {
    global $WEB_APP;

    $check = $this->is_correct_login($login, $password);
    if ($check) {
      $user = get_user_from_login_password($login, $password);
      if ($user->hidden == 1) {
        $WEB_APP['errorstext'] = text('txt_your_account_is_blocked') . '<br>';
        $this->show_login_form();
        exit;
      }
      $_SESSION['user_login'] = $login;
      $_SESSION['user_password'] = $password;
      $_SESSION['user_id'] = get_user_id($_SESSION['user_login']);
      $this->set_session_user_info();
      $this->set_new_messages_count($user->id);

      if ($WEB_APP['module'] != 'login') {
        header('Location: ' . $WEB_APP['request_uri']);
      } else {
        header('Location: ' . $WEB_APP['cfg_url']);
      }
      exit;
    }
  }

  function is_correct_login($login, $password)
  {
    $user = get_user_from_login_password($login, $password);


    if (isset($user->grants)) {
      $_SESSION['user_login'] = $login;
      $_SESSION['user_password'] = $password;
      $this->set_session_user_info();
      $result = TRUE;
    } else {
      $result = FALSE;
    }

    return $result;
  }

  function set_session_user_info()
  {
    if (isset($_SESSION['user_login']) && isset($_SESSION['user_password'])) {
      $user = get_user_from_login_password($_SESSION['user_login'], $_SESSION['user_password']);
      $_SESSION['user_info'] = $user->name . ' @ ' . $user->group;
    } else {
      unset($_SESSION['user_group']);
    }
  }

  function show_login_form()          //форма входа
  {
    global $WEB_APP;
    unset($_SESSION['user_login']);
    unset($_SESSION['user_password']);

    $this->build_menu('anonymous', 'anonymous');

    $WEB_APP['fields'] = $this->get_form_fields();

    $WEB_APP['form_title'] = text('txt_entrance');
    $WEB_APP['submit_title'] = text('txt_entrance');
    $WEB_APP['form_title'] = text('txt_entrance');
    $WEB_APP['title'] = text('txt_entrance');
    $WEB_APP['title_edit'] = text('txt_entrance');
    $WEB_APP['module'] = 'login';
    $WEB_APP['view']->display('form_page.tpl', text('txt_entrance'));
  }

  function build_menu($login, $password)
  {
    global $WEB_APP;

    $WEB_APP['categories'] = array();

    $modules = get_user_modules($login, $password);

    foreach ($modules as $module) {
      $WEB_APP['categories'][$module['category_name']][] = $module;
    }
  }

  function get_form_fields()
  {
    global $WEB_APP;

    switch ($WEB_APP['settings']['logstyle']) {
      case LOGIN_LIST:
        $fields = $this->get_login_list_form_fields();
        break;
      default:
        $fields = $this->get_login_direct_form_fields();
        break;
    }

    return $fields;
  }

  /**
   * @return array
   */
  function get_login_list_form_fields()
  {
    // Build form values.
    global $WEB_APP;

    $groups = get_visible_groups();

    if (isset($_POST['list_group'])) {
      $group = get_group($_POST['list_group']);
      $group = $group->name;
      $users = get_visible_users_for_group_id($_POST['list_group']);
    } else {
      $group = get_group($WEB_APP['settings']['def_group']);
      $group = $group->name;
      $users = get_visible_users_for_group_id($WEB_APP['settings']['def_group']);
    }


    // Build form fields.
    $fields = array();
    $fields[] = new field(FALSE, text('txt_group'), "select", "list_group", $group, "", $groups, "id", "group_name",
      "return change_group()", FALSE);
    $fields[] = new field(FALSE, text('txt_user_name'), "select", "list_user_login", "", "", $users, "id", "user_name");
    $fields[] = new field(FALSE, text('txt_password'), "password", "list_user_password", '', '');

    $fields[] = new field(FALSE, text('txt_or'), 'header');
    $fields[] = new field(FALSE, text('txt_login'), "text", "user_login", "");
    $fields[] = new field(FALSE, text('txt_password'), "password", "user_password", '', '');

    return $fields;
  }

  function get_login_direct_form_fields()
  {
    $user_login = isset($_POST['user_login']) ? trim($_POST['user_login']) : '';

    $fields = array();
    $fields[] = new field(TRUE, text('txt_login'), "text", "user_login", $user_login);
    $fields[] = new field(TRUE, text('txt_password'), "password", "user_password", '', '');

    return $fields;
  }

  function set_new_messages_count($user_id)
  {
    $new_messages_count = get_unreaded_messages($user_id);
    if ($new_messages_count > 0) {
      $_SESSION['new_messages_count'] = $new_messages_count;
    } else unset($_SESSION['new_messages_count']);
  }

  function check_login_direct_form()
  {
    global $WEB_APP;

    $correct_post = TRUE;
    if (!isset($_POST['user_login'])) {
      $WEB_APP['errorstext'] .= text('txt_insert_user_name') . "<br>";
      $correct_post = FALSE;
    }

    if ($_POST['user_password'] == "") {
      $WEB_APP['errorstext'] .= text('txt_insert_password') . "<br>";
      $correct_post = FALSE;
    }

    if ($correct_post) {
      $login = trim($_POST['user_login']);
      $password = $_POST['user_password'];

      $check = $this->is_correct_login($login, $password);
      if (!$check) {
        $WEB_APP['errorstext'] = text('txt_uncorrect_login_or_password') . '<br>';
        $this->show_login_form();
        exit;
      } else {
        $user = get_user_from_login_password($login, $password);
        if ($user->hidden == 1) {
          $WEB_APP['errorstext'] = text('txt_your_account_is_blocked') . '<br>';
          $this->show_login_form();
          exit;
        }
        $_SESSION['user_login'] = $login;
        $_SESSION['user_password'] = $_POST['user_password'];
        $this->set_session_user_info();
        $this->set_new_messages_count($user->id);
        if ($WEB_APP['module'] != 'login') {
          header('Location: ' . $WEB_APP['request_uri']);
        } else {
          header('Location: ' . $WEB_APP['cfg_url']);
        }
        exit;
      }
    }
  }

  /** @noinspection PhpUnused */
  function change_group()
  {
    global $WEB_APP;

    if ($WEB_APP['settings']['logstyle'] != LOGIN_LIST) {
      header('Location: ' . $WEB_APP['cfg_url'] . '?module=login');
      die();
    }

    if (isset($_GET['group_id']) && is_scalar($_GET['group_id'])) {
      $group_id = (int)$_GET['group_id'];
    } else {
      die();
    }

    $group = get_group($group_id);
    if (!isset($group->id)) {
      die();
    }
    if (!$group->login_available) {
      die();
    }

    $users = get_visible_users_for_group_id($group->id);
    echo "<option selected=\"\" value=\"\"></option>\n";
    foreach ($users as $user) {
      printf("<option value=\"%d\">%s</option>\n", $user['id'], htmlspecialchars($user['user_name']));
    }
    die();
  }
}

