<?php

/* @param $a
 * @param $b
 * @return int
 * @version $Id: module_configure.php,v 1.4 2008-02-13 20:30:22 oleg Exp $
 */

function cmp_themes($a, $b)
{
  return strcmp($a['name'], $b['name']);
}

/**
 * @see module_base
 */
class module_configure extends module_base
{

  function view()
  {
    global $WEB_APP;

    $WEB_APP['title'] = text('txt_configure');
    $timezones = array();
    $timezones_list = unserialize(file_get_contents('system/timezones.dat'));
    foreach ($timezones_list as $timezone) {
      $timezones[] = array('name' => $timezone, 'value' => $timezone);
    }
    $languages = get_unhidden_languages();
    $themes = $this->get_themes();
    $users_info_modes = array(array('name' => text('txt_users_info_mode_standard'), 'value' => '0'),
      array('name' => text('txt_users_info_mode_advanced'), 'value' => '1'),
      array('name' => text('txt_users_info_mode_full'), 'value' => '2'));
    $counter_items = array(array('name' => '5', 'value' => '5'), array('name' => '10', 'value' => '10'),
      array('name' => '25', 'value' => '25'), array('name' => '50', 'value' => '50'),
      array('name' => '100', 'value' => '100'), array('name' => '0', 'value' => text('txt_all')));

    $charsets = array(array('name' => 'cp866', 'value' => 'cp866'), array('name' => 'ibm855', 'value' => 'ibm855'),
      array('name' => 'ibm866', 'value' => 'ibm866'), array('name' => 'iso-8859-5', 'value' => 'iso-8859-5'),
      array('name' => 'koi8r', 'value' => 'koi8r'), array('name' => 'utf-8', 'value' => 'utf-8'),
      array('name' => 'utf16', 'value' => 'utf16'), array('name' => 'windows-1251', 'value' => 'windows-1251'));
    $groups = get_visible_groups();
    $grants = get_grants();
    $logstyles = array(array('name' => 1, 'value' => text('txt_username_is_chosen_from_the_list')),
      array('name' => 2, 'value' => text('txt_login_password_is_entered_directly')));

    if (is_add_edit_form('language')) {
      $correct_post = TRUE;

      if (realpath(CFG_THEMES_DIR) === FALSE) {
        $WEB_APP['errorstext'] .= htmlspecialchars(CFG_THEMES_DIR) . ' ' . text('txt_folder_does_not_exist') . "<br>";
        $correct_post = FALSE;
      } else {
        $path = realpath(CFG_THEMES_DIR) . DIRECTORY_SEPARATOR . $_POST['theme'] . DIRECTORY_SEPARATOR;
        if (realpath($path . 'templates') === FALSE) {
          $WEB_APP['errorstext'] .= htmlspecialchars($path) . 'templates ' . text('txt_folder_does_not_exist') . "<br>";
          $correct_post = FALSE;
        } else {
          if (!is_readable($path . 'templates')) {
            $WEB_APP['errorstext'] .= htmlspecialchars($path) . 'templates ' . text('txt_folder_is_not_readable') .
              "<br>";
            $correct_post = FALSE;
          }
        }

        if (realpath($path . 'templates_c') === FALSE) {
          $WEB_APP['errorstext'] .= htmlspecialchars($path) . 'templates_c ' . text('txt_folder_does_not_exist') .
            "<br>";
          $correct_post = FALSE;
        } else {
          if (!is_writable($path . 'templates_c')) {
            $WEB_APP['errorstext'] .= htmlspecialchars($path) . 'templates_c ' . text('txt_folder_is_not_writable') .
              "<br>";
            $correct_post = FALSE;
          } else {
            $file = $path . 'templates_c' . DIRECTORY_SEPARATOR . uniqid(mt_rand()) . '.tmp';
            if (!($f = @fopen($file, 'w+'))) {
              $WEB_APP['errorstext'] .= htmlspecialchars($path) . 'templates_c ' . text('txt_folder_is_not_writable') .
                "<br>";
              $correct_post = FALSE;
            } else {
              fclose($f);
              unlink($file);
            }
          }
        }
      }
      if ($correct_post) {
        // Set settings.
        setting_set('language_id', $_POST['language']);
        setting_set('theme', $_POST['theme']);
        setting_set('items_per_page', $_POST['items_per_page']);
        setting_set('show_items_per_page', isset($_POST['show_items_per_page']) ? 1 : 0);
        setting_set('admset_user_change_group', isset($_POST['change_group']) ? 1 : 0);
        setting_set('admset_user_change_name', isset($_POST['change_name']) ? 1 : 0);
        setting_set('admset_user_change_mail', isset($_POST['change_mail']) ? 1 : 0);
        setting_set('admset_user_change_theme', isset($_POST['change_theme']) ? 1 : 0);
        setting_set('admset_user_change_language', isset($_POST['change_language']) ? 1 : 0);
        setting_set('timezone', $_POST['timezone']);
        setting_set('csv_file_charset', $_POST['csv_file_charset']);
        setting_set('use_file_name_charset', isset($_POST['use_file_name_charset']) ? 1 : 0);
        setting_set('file_name_charset', $_POST['file_name_charset']);
        setting_set('logstyle', $_POST['logstyle']);
        setting_set('def_group', $_POST['def_group']);
        setting_set('limited_reports_grant_id', $_POST['limited_reports_grant_id']);
        setting_set('limited_tests_grant_id', $_POST['limited_tests_grant_id']);
        setting_set('limited_books_grant_id', $_POST['limited_books_grant_id']);
        setting_set('show_login_info', isset($_POST['show_login_info']) ? 1 : 0);
        setting_set('write_book_view_log', isset($_POST['write_book_view_log']) ? 1 : 0);
        setting_set('support_mail', $_POST['support_mail']);
        setting_set('users_info_mode', $_POST['users_info_mode']);
        redirect($WEB_APP['errorstext']);
      } else {
        $language_id = isset($_POST['language']) ? $_POST['language'] : $WEB_APP['settings']['language_id'];
        $language = get_language($language_id);
        $theme = isset($_POST['theme']) ? $_POST['theme'] : $WEB_APP['settings']['theme'];
        $timezone = isset($_POST['timezone']) ? $_POST['timezone'] : $WEB_APP['settings']['timezone'];
        $csv_file_charset =
          isset($_POST['csv_file_charset']) ? $_POST['csv_file_charset'] : $WEB_APP['settings']['csv_file_charset'];
        $use_file_name_charset = isset($_POST['use_file_name_charset']) ? 1 : 0;
        $file_name_charset =
          isset($_POST['file_name_charset']) ? $_POST['file_name_charset'] : $WEB_APP['settings']['file_name_charset'];
        $items_per_page =
          isset($_POST['items_per_page']) ? $_POST['items_per_page'] : $WEB_APP['settings']['items_per_page'];
        if ($items_per_page == 0) {
          $items_per_page = text('txt_all');
        }
        $show_items_per_page = isset($_POST['show_items_per_page']) ? 1 : 0;
        $logstyle = isset($_POST['logstyle']) ? $_POST['logstyle'] : $WEB_APP['settings']['logstyle'];
        if (($logstyle != 1) && ($logstyle != 2)) {
          $logstyle = 2;
        }
        $logstyle = $logstyles[$logstyle - 1]['value'];
        $def_group = isset($_POST['def_group']) ? $_POST['def_group'] : $WEB_APP['settings']['def_group'];
        $limited_reports_grant = isset($_POST['limited_reports_grant_id']) ? $_POST['limited_reports_grant_id'] :
          $WEB_APP['settings']['limited_reports_grant_id'];
        $limited_tests_grant = isset($_POST['limited_tests_grant_id']) ? $_POST['limited_tests_grant_id'] :
          $WEB_APP['settings']['limited_tests_grant_id'];
        $limited_books_grant = isset($_POST['limited_books_grant_id']) ? $_POST['limited_books_grant_id'] :
          $WEB_APP['settings']['limited_books_grant_id'];
        $show_login_info = isset($_POST['show_login_info']) ? 1 : 0;
        $write_book_view_log = isset($_POST['write_book_view_log']) ? 1 : 0;
        $users_info_mode =
          isset($_POST['users_info_mode']) ? $_POST['users_info_mode'] : $WEB_APP['settings']['users_info_mode'];
        if (($users_info_mode != 0) && ($users_info_mode != 1) && ($users_info_mode != 2)) {
          $users_info_mode = 0;
        }
        $support_mail = $_POST['support_mail'];

        $change_group = isset($_POST['change_group']) ? 1 : 0;
        $change_name = isset($_POST['change_name']) ? 1 : 0;
        $change_mail = isset($_POST['change_mail']) ? 1 : 0;
        $change_theme = isset($_POST['change_theme']) ? 1 : 0;
        $change_language = isset($_POST['change_language']) ? 1 : 0;
      }
    } else {
      // Get settings.
      $language_id = $WEB_APP['settings']['language_id'];
      $language = get_language($language_id);
      $theme = $WEB_APP['settings']['theme'];
      $timezone = $WEB_APP['settings']['timezone'];
      $csv_file_charset = $WEB_APP['settings']['csv_file_charset'];
      $use_file_name_charset = $WEB_APP['settings']['use_file_name_charset'];
      $file_name_charset = $WEB_APP['settings']['file_name_charset'];
      $items_per_page = $WEB_APP['settings']['items_per_page'];
      if ($items_per_page == 0) {
        $items_per_page = text('txt_all');
      }
      $show_items_per_page = $WEB_APP['settings']['show_items_per_page'];
      $logstyle = $WEB_APP['settings']['logstyle'];
      if (($logstyle != 1) && ($logstyle != 2)) {
        $logstyle = 2;
      }
      $logstyle = $logstyles[$logstyle - 1]['value'];
      $def_group = get_group($WEB_APP['settings']['def_group']);
      $def_group = $def_group->name;
      $limited_reports_grant = get_grant($WEB_APP['settings']['limited_reports_grant_id']);
      if ($limited_reports_grant->id <> NULL) $limited_reports_grant = $limited_reports_grant->title; else
        $limited_reports_grant = '';
      $limited_tests_grant = get_grant($WEB_APP['settings']['limited_tests_grant_id']);
      if ($limited_tests_grant->id <> NULL) $limited_tests_grant = $limited_tests_grant->title; else
        $limited_tests_grant = '';
      $limited_books_grant = get_grant($WEB_APP['settings']['limited_books_grant_id']);
      if ($limited_books_grant->id <> NULL) $limited_books_grant = $limited_books_grant->title; else
        $limited_books_grant = '';
      $show_login_info = $WEB_APP['settings']['show_login_info'];
      $write_book_view_log = $WEB_APP['settings']['write_book_view_log'];
      $users_info_mode = $WEB_APP['settings']['users_info_mode'];
      if (($users_info_mode != 0) && ($users_info_mode != 1) && ($users_info_mode != 2)) {
        $users_info_mode = 0;
      }
      $users_info_mode = $users_info_modes[$users_info_mode]['name'];
      $support_mail = $WEB_APP['settings']['support_mail'];


      $change_group = $WEB_APP['settings']['admset_user_change_group'];
      $change_name = $WEB_APP['settings']['admset_user_change_name'];
      $change_mail = $WEB_APP['settings']['admset_user_change_mail'];
      $change_theme = $WEB_APP['settings']['admset_user_change_theme'];
      $change_language = $WEB_APP['settings']['admset_user_change_language'];

    }

    // Form fields.
    $fields = array();
    $fields[] =
      new field(FALSE, text('txt_show_login_info'), 'checkbox', 'show_login_info', $show_login_info, 'show_login_info');
    $fields[] =
      new field(FALSE, text('txt_write_book_view_log'), 'checkbox', 'write_book_view_log', $write_book_view_log,
        'write_book_view_log');
    $fields[] =
      new field(FALSE, text('txt_show_items_per_page'), 'checkbox', 'show_items_per_page', $show_items_per_page,
        'show_items_per_page');
    $fields[] =
      new field(FALSE, text('txt_use_file_name_charset'), "checkbox", "use_file_name_charset", $use_file_name_charset,
        'use_file_name_charset');
    $fields[] =
      new field(FALSE, text('txt_items_per_page'), 'select', 'items_per_page', $items_per_page, '', $counter_items,
        'name', 'value');
    $fields[] =
      new field(FALSE, text('txt_file_name_charset'), "select", "file_name_charset", $file_name_charset, '', $charsets,
        "name", "value");
    $fields[] =
      new field(FALSE, text('txt_timezone'), 'select', 'timezone', $timezone, '', $timezones, 'name', 'value', null,
        FALSE, '', '', 'data-live-search="true" data-size="15"');
    $fields[] =
      new field(FALSE, text('txt_csv_file_charset'), "select", "csv_file_charset", $csv_file_charset, '', $charsets,
        "name", "value");
    $fields[] =
      new field(FALSE, text('txt_language'), 'select', 'language', $language->name, '', $languages, 'id', 'name');
    $fields[] = new field(FALSE, text('txt_theme'), 'select', 'theme', $theme, '', $themes, 'name', 'name');
    $fields[] =
      new field(FALSE, text('txt_login_style'), 'select', 'logstyle', $logstyle, '', $logstyles, 'name', 'value');
    $fields[] =
      new field(FALSE, text('txt_default_group'), 'select', 'def_group', $def_group, '', $groups, 'id', 'group_name',
        null, FALSE, '', '', 'data-live-search="true"');
    $fields[] = new field(FALSE, text('txt_users_info_mode'), 'select', 'users_info_mode', $users_info_mode, '',
      $users_info_modes, 'value', 'name');
    $fields[] = new field(FALSE, text('txt_support_mail'), 'email', 'support_mail', $support_mail, '');

    $fields[] = new field(FALSE, text('txt_limited_grants'), 'header');
    $fields[] =
      new field(FALSE, text('txt_limited_tests_grant'), 'select', 'limited_tests_grant_id', $limited_tests_grant, '',
        $grants, 'id', 'grant_title', null, FALSE, '', '', 'data-live-search="true"');
    $fields[] =
      new field(FALSE, text('txt_limited_books_grant'), 'select', 'limited_books_grant_id', $limited_books_grant, '',
        $grants, 'id', 'grant_title', null, FALSE, '', '', 'data-live-search="true"');
    $fields[] =
      new field(FALSE, text('txt_limited_reports_grant'), 'select', 'limited_reports_grant_id', $limited_reports_grant,
        '', $grants, 'id', 'grant_title', null, FALSE, '', '', 'data-live-search="true"');

    $fields[] = new field(FALSE, text('txt_user_grants'), 'header');
    $fields[] = new field(FALSE, text('txt_change_group'), 'checkbox', 'change_group', $change_group, 'change_group');
    $fields[] = new field(FALSE, text('txt_change_name'), 'checkbox', 'change_name', $change_name, 'change_name');
    $fields[] = new field(FALSE, text('txt_change_mail'), 'checkbox', 'change_mail', $change_mail, 'change_mail');
    $fields[] = new field(FALSE, text('txt_change_theme'), 'checkbox', 'change_theme', $change_theme, 'change_theme');
    $fields[] =
      new field(FALSE, text('txt_change_language'), 'checkbox', 'change_language', $change_language, 'change_language');

    $WEB_APP['fields'] = $fields;
    $WEB_APP['show_empty_value'] = FALSE;
    $WEB_APP['submit_title'] = text('txt_change');
    $WEB_APP['form_title'] = text('txt_configure');
    $WEB_APP['title_edit'] = text('txt_configure');
    $WEB_APP['unshow_asterisk'] = TRUE;

    $WEB_APP['view']->display('form_page.tpl', text('txt_configure'));
  }

  function get_themes()
  {
    $themes = array();
    //global $WEB_APP;
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
}

