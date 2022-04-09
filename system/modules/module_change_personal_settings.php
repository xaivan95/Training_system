<?php

/* @param $a
 * @param $b
 * @return int
 * @version $Id: module_change_personal_settings.php,v 1.5 2008-02-13 20:30:22 oleg Exp $
 */


function cmp_themes($a, $b)
{
  return strcmp($a['name'], $b['name']);
}

/**
 * @see module_base
 */
class module_change_personal_settings extends module_base
{

  function view()
  {
    global $WEB_APP;

    $groups = get_groups('group_name');
    $languages = get_unhidden_languages();
    $user = get_user_from_login_password($_SESSION['user_login'], $_SESSION['user_password']);
    $themes = $this->get_themes();

    if (!$WEB_APP['settings']['admset_user_change_group'] && !$WEB_APP['settings']['admset_user_change_name'] &&
      !$WEB_APP['settings']['admset_user_change_mail'] && !$WEB_APP['settings']['admset_user_change_theme'] &&
      !$WEB_APP['settings']['admset_user_change_language']) {
      $WEB_APP['title'] = text('txt_403_forbidden');
      $WEB_APP['view']->display('403.tpl', text('txt_403_forbidden'));
      exit();
    }

    if (isset($_POST['group']) || isset($_POST['name']) || isset($_POST['mail']) || isset($_POST['theme']) ||
      isset($_POST['language'])) {
      $correct_post = TRUE;
      if ($WEB_APP['settings']['admset_user_change_group']) {
        if (trim($_POST['group']) == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
          $correct_post = FALSE;
        }
      }
      if ($WEB_APP['settings']['admset_user_change_name']) {
        if (trim($_POST['name']) == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_user_name') . "<br>";
          $correct_post = FALSE;
        }
      }
      if ($WEB_APP['settings']['admset_user_change_mail']) {
        if (!(is_email($_POST['mail']) || ($_POST['mail'] == ""))) {
          $WEB_APP['errorstext'] .= text('txt_email_does_not_correct_insert_correct_email') . "<br>";
          $correct_post = FALSE;
        } else {
          $user_id = get_user_id_by_mail($_POST['mail']);
          if (!(($user_id == 0) || ($user_id == $user->id))) {
            $WEB_APP['errorstext'] .= text('txt_user_mail_already_exist_insert_another_user_mail') . "<br>";
            $correct_post = FALSE;
          }
        }
      }
      if ($WEB_APP['settings']['admset_user_change_theme']) {
        if (trim($_POST['theme']) == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_theme') . "<br>";
          $correct_post = FALSE;
        }
      }

      if ($WEB_APP['settings']['admset_user_change_language']) {
        if (trim($_POST['language']) == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_language') . "<br>";
          $correct_post = FALSE;
        }
      }

      $user = $this->get_post_settings($user->id);

      if ($correct_post) {
        $group = get_group_id($user->group);
        $language = get_language_id($user->language_id);
        edit_user($user->id, $group, $user->login, $_SESSION['user_password'], $user->name, $user->info, $user->mail,
          $user->hidden, $user->grants);
        change_user_settings($user->id, $user->theme, $language);
        redirect($WEB_APP['errorstext']);
      }

    }


    $fields = array();
    if ($WEB_APP['settings']['admset_user_change_group']) {
      $fields[] = new field(FALSE, text('txt_group'), "select", "group", $user->group, "", $groups, "id", "group_name");
    }

    if ($WEB_APP['settings']['admset_user_change_name']) {
      $fields[] = new field(FALSE, text('txt_user_name'), "text", "name", $user->name, "");
    }

    if ($WEB_APP['settings']['admset_user_change_mail']) {
      $fields[] = new field(FALSE, text('txt_mail'), "email", "mail", $user->mail, "");
    }

    if ($WEB_APP['settings']['admset_user_change_theme']) {
      $fields[] = new field(FALSE, text('txt_theme'), 'select', 'theme', $user->theme, '', $themes, 'name', 'name');
    }

    if ($WEB_APP['settings']['admset_user_change_language']) {
      $fields[] =
        new field(FALSE, text('txt_language'), "select", "language", get_language($user->language_id)->name, "",
          $languages, "id", "name");
    }

    $WEB_APP['fields'] = $fields;
    $WEB_APP['title'] = text('txt_change personal_settings');
    $WEB_APP['submit_title'] = text('txt_change');
    $WEB_APP['form_title'] = text('txt_configure');
    $WEB_APP['show_empty_value'] = FALSE;
    $WEB_APP['view']->display('form_page.tpl', text('txt_change personal_settings'));
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

  function get_post_settings($id)
  {
    global $WEB_APP;

    $user = get_user($id);
    if ($WEB_APP['settings']['admset_user_change_group']) {
      $group_id = (isset($_POST['group'])) ? $_POST['group'] : 0;
      $tmp_group = get_group($group_id);
      $user->group = $tmp_group->name;
    }

    if ($WEB_APP['settings']['admset_user_change_name']) {
      $user->name = (isset($_POST['name'])) ? $_POST['name'] : '';
    }

    if ($WEB_APP['settings']['admset_user_change_mail']) {
      $user->mail = (isset($_POST['mail'])) ? $_POST['mail'] : '';
    }

    if ($WEB_APP['settings']['admset_user_change_theme']) {
      $user->theme = (isset($_POST['theme'])) ? $_POST['theme'] : '';
    }

    if ($WEB_APP['settings']['admset_user_change_language']) {
      $language_id = (isset($_POST['language'])) ? $_POST['language'] : 0;
      $tmp_language = get_language($language_id);
      $user->language_id = $tmp_language->name;
    }

    return $user;
  }

}

