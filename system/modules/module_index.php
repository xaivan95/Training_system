<?php

/**
 * @see module_base
 */
class module_index extends module_base
{
  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title'] = '';
  }

  function view()
  {
    global $WEB_APP;
    $WEB_APP['title'] = '';
    $text = $WEB_APP['text'];
    $information_items = array();
    $information_items["Server name"] = $_SERVER['SERVER_NAME'];
    if (function_exists('apache_get_version')) {
        $version = apache_get_version();

        $apache_version = preg_split('/ /', $version);

        if (isset($apache_version[1])) {
            $information_items["Apache version"] = $apache_version[0] . " " . $apache_version[1];
          } else {
            $information_items["Apache version"] = $version;
          }
      }

    global $adodb;
    $information_items["PHP version"] = phpversion();
    $db_server_info = $adodb->ServerInfo();
    $information_items["MySQL version"] = $db_server_info['version'];
    // $information_items["Smarty version"] = $WEB_APP['view_version'];
    if (CFG_SHOW_VERSION) {
        $information_items["Version"] = CFG_VERSION;
        $information_items["DB Version"] = $WEB_APP['settings']['db_version'];
      }

    $WEB_APP['information_items'] = $information_items;

    $statistics_items = array();

    $statistics_items[$text['txt_stat_accounts']] = db_count(DB_TABLE_USER);
    $statistics_items[$text['txt_stat_groups']] = db_count(DB_TABLE_GROUP);
    $statistics_items[$text['txt_stat_categories']] = db_count(DB_TABLE_CATEGORY);
    $statistics_items[$text['txt_stat_modules']] = db_count(DB_TABLE_MODULE);
    $statistics_items[$text['txt_stat_languages']] = db_count(DB_TABLE_LANGUAGES);
    $statistics_items[$text['txt_stat_translations']] = db_count(DB_TABLE_TRANSLATION);

    $WEB_APP['statistics_items'] = $statistics_items;
    $WEB_APP['view']->display('index.tpl', '');
  }
}
