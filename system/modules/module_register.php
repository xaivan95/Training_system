<?php

function cmp_themes($a, $b)
{
  return strcmp($a['name'], $b['name']);
}

/**
 * @see module_base
 */
class module_register extends module_base
{

  function view()
  {
    global $WEB_APP;
    $themes = $this->get_themes();
    $languages = get_unhidden_languages();

    if (isset($_POST['name'])) {
      $user = $this->get_post_user();

      $correct_post = TRUE;

      if ($user->group == "") {
        $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
        $correct_post = FALSE;
      }

      if ($user->name == "") {
        $WEB_APP['errorstext'] .= text('txt_insert_user_name') . "<br>";
        $correct_post = FALSE;
      }

      if ($user->login == "") {
        $WEB_APP['errorstext'] .= text('txt_insert_login') . "<br>";
        $correct_post = FALSE;
      } else {
        if (is_login_exist($user->login)) {
          $WEB_APP['errorstext'] .= text('txt_login_already_exist_insert_another_login') . "<br>";
          $correct_post = FALSE;
        } else {
          if (strlen($user->login) < 6) {
            $WEB_APP['errorstext'] .= text('txt_your_login_must_be_at_least_6_characters') . "<br>";
            $correct_post = FALSE;
          } else {
            if ($user->login == $user->password) {
              $WEB_APP['errorstext'] .= text('txt_your_password_is_too_similar_to_your_login') . "<br>";
              $correct_post = FALSE;
            }
          }

        }
      }

      if ($user->password == "") {
        $WEB_APP['errorstext'] .= text('txt_insert_password') . "<br>";
        $correct_post = FALSE;
      }
      if ($_POST['confirm_password'] == "") {
        $WEB_APP['errorstext'] .= text('txt_insert_confirm_password') . "<br>";
        $correct_post = FALSE;
      } else {
        if ($user->password != $_POST['confirm_password']) {
          $WEB_APP['errorstext'] .= text('txt_your_password_entries_did_not_match') . "<br>";
          $correct_post = FALSE;
        } else {
          if (strlen($user->password) < 6) {
            $WEB_APP['errorstext'] .= text('txt_your_password_must_be_at_least_6_characters') . "<br>";
            $correct_post = FALSE;
          }
        }
      }
      if ($user->mail != "") {
        if (!is_email($user->mail)) {
          $WEB_APP['errorstext'] .= text('txt_email_does_not_correct_insert_correct_email') . "<br>";
          $correct_post = FALSE;
        } else {
          if (is_email_exist($user->mail)) {
            $WEB_APP['errorstext'] .= text('txt_email_already_exist_insert_another_email') . "<br>";
            $correct_post = FALSE;
          }
        }
      }

      if ($correct_post) {
        //$grants = array(2 => TRUE, 5 => TRUE, 6 => TRUE, 12 => TRUE);
        $reg_grants = @unserialize($WEB_APP['settings']['reg_grants']);
        $grants = array();
        if (is_array($reg_grants)) {
          foreach ($reg_grants as $key => $value) {
            $grants[(int)$key] = TRUE;
          }
        }
        add_user($_POST['group'], $user->login, $user->password, $user->name, "", $user->mail, 0, $grants, $user->language_id, '', '',
          '', '', '', '', '', $user->theme);

        // Send email

        if ($WEB_APP['settings']['reg_mailnotify']) {
          $to = $user->mail;
          $subject = text('txt_user_registration');
          $message = $WEB_APP['settings']['reg_mailbegin'] . "\n" . text('txt_login') . ': ' . $user->login . "\n" .
            text('txt_password') . ': ' . $user->password . "\n" . $WEB_APP['settings']['reg_mailend'];
          $headers = "From: " . $WEB_APP['settings']['reg_mailfrom'] . "\r\n" . 'Reply-To: ' .
            $WEB_APP['settings']['reg_mailfrom'] . "\r\n" . "Content-Type: text/plain; charset=utf-8\r\n" .
            'X-Mailer: PHP/' . phpversion();
          mail($to, $subject, $message, $headers);
        }
        header('Location: ' . $WEB_APP['cfg_url'] . '?module=login');
        exit();
      }
    } else {
      $user = new user();
      $group = get_group($WEB_APP['settings']['def_group']);
      $user->group = $group->name;
      $user->language_id = DEFAULT_LANGUAGE;
      $user->theme = CFG_DEFAULT_THEME;
      $confirm_password = "";
    }
    $language = get_language($user->language_id);
    if (!isset($confirm_password)) {
      $confirm_password = '';
    }
//        $groups = get_visible_groups();
    $groups = get_group_for_registration();

    $fields = array();
    $fields[] = new field(TRUE, text('txt_group'), "select", "group", $user->group, "", $groups, "id", "group_name");
    $fields[] = new field(TRUE, text('txt_user_name'), "text", "name", $user->name, "");
    $fields[] = new field(TRUE, text('txt_login'), "text", "login", $user->login, "");
    $fields[] = new field(TRUE, text('txt_password'), "password", "password", $user->password, "");
    $fields[] = new field(TRUE, text('txt_confirm_password'), "password", "confirm_password", $confirm_password, "");
    $fields[] = new field(TRUE, text('txt_mail'), "text", "mail", $user->mail, "");
    $fields[] =
      new field(FALSE, text('txt_language')." / Language", "select", "language", $language->name, "", $languages, "id", "name", null,
        FALSE, '', '');
    $fields[] = new field(TRUE, text('txt_theme'), 'select', 'theme', $user->theme, '', $themes, 'name', 'name');

    $WEB_APP['fields'] = $fields;
    $WEB_APP['title'] = text('txt_user_registration');
    $WEB_APP['submit_title'] = text('txt_user_registration');
    $WEB_APP['form_title'] = text('txt_user_registration');
    $WEB_APP['view']->display('form_page.tpl', text('txt_user_registration'));
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

  function get_post_user()
  {
    $user = new user();

    $group_id = $_POST['group'];
    $group = get_group($group_id);
    $user->group = $group->name;
    $user->name = trim($_POST['name']);
    $user->login = trim($_POST['login']);
    $user->password = $_POST['password'];
    $user->mail = trim($_POST['mail']);
    $user->theme = trim($_POST['theme']);
    $user->language_id = trim($_POST['language']);

    return $user;
  }

}

