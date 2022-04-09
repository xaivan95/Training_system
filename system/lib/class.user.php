<?php

/**
 * Specify user object. Users table.
 */
class user
{
  /**
   * string user id
   */
  var $id;

  /**
   * int user group
   */
  var $group;

  /**
   * string user name
   */
  var $name;

  /**
   * string user password
   */
  var $password;

  /**
   * string user info
   */
  var $info;

  /**
   * int (1,0) hide user
   */
  var $hidden;

  /**
   * string user email
   */
  var $mail;

  /**
   * string user login
   */
  var $login;

  /**
   * array user grants
   */
  var $grants;

  /**
   * string user theme
   */
  var $theme;

  /**
   * int user language_id
   */
  var $language_id;

  /**
   * string user language
   */
  var $language;

  var $birthday;
  var $position;
  var $phone;
  var $address;
  var $field1;
  var $field2;
  var $field3;
}

/**
 * Get users.
 *
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter account_filter
 *
 * @return array users array
 */
function get_users($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0, $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field,
    array('id', 'user_login', 'group_name', 'user_name', 'user_info', 'user_mail', 'user_hidden'))) {
    $t_sort_field = 'id';
  }

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "LIMIT $limit, $t_count";
  } else {
    $limit_str = '';
  }

  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';

  return db_query('SELECT ' . DB_TABLE_USER . '.*, `' . DB_TABLE_GROUP . '`.`group_name`
              FROM ' . DB_TABLE_USER . ', `' . DB_TABLE_GROUP . '`' . ' WHERE ' . DB_TABLE_USER . '.`user_group_id`=`' .
    DB_TABLE_GROUP . '`.`id`' . $tmp . ' ORDER BY ' . $t_sort_field . ' ' . $t_sort_order . ' ' . $limit_str);
}


function get_users_with_viewed_books($p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0,
                                     $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($t_sort_field == '') $t_sort_field = 'id';

  if (!in_array($t_sort_field,
    array('id', 'user_login', 'group_name', 'user_name', 'user_info', 'user_mail', 'user_hidden'))) {
    $t_sort_field = 'id';
  }

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "LIMIT $limit, $t_count";
  } else {
    $limit_str = '';
  }

  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';

  return db_query("SELECT " . DB_TABLE_USER . ".*, `" . DB_TABLE_GROUP . "`.`group_name`
              FROM " . DB_TABLE_USER . ", `" . DB_TABLE_GROUP . "`" . " WHERE " . DB_TABLE_USER . ".`user_group_id`=`" .
    DB_TABLE_GROUP . "`.`id`  AND (" . DB_TABLE_USER . ".`id` in (SELECT `user_id` FROM `" . DB_TABLE_USER_BOOK_VIEWS .
    "`))  $tmp  ORDER BY  $t_sort_field  $t_sort_order  $limit_str");
}

/**
 * Get unhidden users by group id.
 *
 * @param $p_group_id int user group id
 *
 * @return array users array
 */
function get_visible_users_for_group_id($p_group_id)
{
  $t_group_id = db_prepare_int($p_group_id);

  return db_extract(DB_TABLE_USER, '`user_group_id`=' . $t_group_id . ' AND `user_hidden` = 0 ORDER BY user_name');
}

/**
 * Get users count by filter.
 *
 * @param $p_filter account_filter
 *
 * @return int users count
 */
function get_users_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';

  $query = 'SELECT COUNT(*) AS `_count_`
         FROM ' . DB_TABLE_USER . ', `' . DB_TABLE_GROUP . '`' . ' WHERE ' . DB_TABLE_USER . '.`user_group_id`=`' .
    DB_TABLE_GROUP . '`.`id`' . $tmp;

  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

function get_users_with_viewed_books_count($p_filter)
{
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';

  $query = "SELECT COUNT(*) AS `_count_`
         FROM " . DB_TABLE_USER . ", `" . DB_TABLE_GROUP . "`" . " WHERE " . DB_TABLE_USER . ".`user_group_id`=`" .
    DB_TABLE_GROUP . "`.`id` AND (" . DB_TABLE_USER . ".`id` in (SELECT `user_id` FROM `" . DB_TABLE_USER_BOOK_VIEWS .
    "`))" . $tmp;

  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}


/**
 * Get users from users id array.
 *
 * @param $p_id_array array of int users id
 *
 * @return array users array
 */
function get_users_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  $query = 'SELECT ' . DB_TABLE_USER . '.*, `' . DB_TABLE_GROUP . '`.`group_name`
         FROM ' . DB_TABLE_USER . ', `' . DB_TABLE_GROUP . '`' . ' WHERE ' . DB_TABLE_USER . '.`user_group_id`=`' .
    DB_TABLE_GROUP . '`.`id`
            AND ' . DB_TABLE_USER . '.`id` in(' . $tmp . ')
            ORDER BY ' . DB_TABLE_USER . '.`id` ASC';
  return db_query($query);
}

/**
 * Delete user.
 *
 * @param $p_id int user id
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_user($p_id)
{
  $t_id = db_prepare_int($p_id);
  // ID = 1 main aadministrator
  // ID = 2 anonymous
  if ($t_id > 2) {

    // delete user grants
    delete_user_grants($t_id);

    // delete user results
    $user_results_id = get_user_all_results_id($p_id);
    delete_user_results($user_results_id);

    // delete user
    $query = 'DELETE FROM ' . DB_TABLE_USER . ' WHERE id=' . $t_id;
    db_exec($query);

    return (db_last_error() == '');
  } else return FALSE;
}

/**
 * Delete users.
 *
 * @param $p_id_array array of int users id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function delete_users($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_user($id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Add user.
 *
 * @param $p_group int user group id
 * @param $p_login string user login
 * @param $p_password string user password
 * @param $p_name string user name
 * @param $p_info string user info
 * @param $p_mail string user mail
 * @param $p_hidden int user is hidden
 * @param $p_grants array user grants
 * @param $p_language_id int user language
 * @param int $p_birthday
 * @param string $p_position
 * @param string $p_phone
 * @param string $p_address
 * @param string $p_field1
 * @param string $p_field2
 * @param string $p_field3
 */
function add_user($p_group, $p_login, $p_password, $p_name, $p_info, $p_mail, $p_hidden, $p_grants,
                  $p_language_id = DEFAULT_LANGUAGE, $p_birthday = '', $p_position = '', $p_phone = '', $p_address = '',
                  $p_field1 = '', $p_field2 = '', $p_field3 = '', $p_theme = CFG_DEFAULT_THEME)
{
  $t_group = db_prepare_int($p_group);
  $t_login = db_prepare_string($p_login);
  $t_password = db_prepare_string(md5($p_password));
  $t_name = db_prepare_string($p_name);
  $t_info = db_prepare_string($p_info);
  $t_hidden = db_prepare_string($p_hidden);
  $t_mail = db_prepare_string($p_mail);
  $t_language_id = db_prepare_int($p_language_id);
  $t_birthday = db_prepare_date($p_birthday);
  $t_position = db_prepare_string($p_position);
  $t_phone = db_prepare_string($p_phone);
  $t_address = db_prepare_string($p_address);
  $t_field1 = db_prepare_string($p_field1);
  $t_field2 = db_prepare_string($p_field2);
  $t_field3 = db_prepare_string($p_field3);
  $t_theme = db_prepare_string($p_theme);
  if ($t_mail == "''") $t_mail = 'NULL';

  $query = "INSERT INTO " . DB_TABLE_USER . "
    (
      `user_group_id`,
      `user_name`,
      `user_password`,
      `user_info`,
      `user_hidden`,
      `user_mail`,
      `user_login`,
      `user_theme`,
      `user_language_id`,
      `user_birthday`,
      `user_position`,
      `user_phone`,
      `user_address`,
      `user_field1`,
      `user_field2`,
      `user_field3`
    )
    VALUES
    (
      $t_group, 
      $t_name, 
      $t_password, 
      $t_info, 
      $t_hidden, 
      $t_mail, 
      $t_login, 
      $t_theme,
      $t_language_id, 
      $t_birthday, 
      $t_position, 
      $t_phone,
      $t_address,
      $t_field1,
      $t_field2,
      $t_field3
    )";
  db_exec($query);
  $id = db_insert_id();

  if (!empty($p_grants)) {
    foreach ($p_grants as $key => $value) {
      add_user_grant($id, $key);
    }
  }
}

/**
 * Get user id by user login.
 *
 * @param $p_login string user login
 *
 * @return int user id on success or 0 on failure
 */
function get_user_id($p_login)
{
  $t_login = db_prepare_string(trim($p_login));

  $users[] = db_extract(DB_TABLE_USER, '`user_login` = ' . $t_login);
  $id = 0;

  if (count($users[0]) > 0) {
    $id = $users[0][0]['id'];
  }

  return $id;
}

/**
 * Get user group id by user login.
 *
 * @param $p_login string user login
 *
 * @return int user group id on success or 0 on failure
 */
function get_user_groupid($p_login)
{
  $t_login = db_prepare_string(trim($p_login));

  $users[] = db_extract(DB_TABLE_USER, '`user_login` = ' . $t_login);
  $id = 0;

  if (count($users[0]) > 0) {
    $id = $users[0][0]['user_group_id'];
  }

  return $id;
}

/**
 * Get user id by user mail.
 *
 * @param $p_mail string user mail
 *
 * @return int user id on success or 0 on failure
 */
function get_user_id_by_mail($p_mail)
{
  $t_mail = db_prepare_string(trim($p_mail));

  $users[] = db_extract(DB_TABLE_USER, '`user_mail` = ' . $t_mail);
  $id = 0;

  if (count($users[0]) > 0) {
    $id = $users[0][0]['id'];
  }

  return $id;
}

/**
 * Get user.
 *
 * @param integer $p_id user id
 *
 * @return user class
 */
function get_user($p_id)
{
  $t_id = db_prepare_int($p_id);
  $query = 'SELECT ' . DB_TABLE_USER . '.*, `' . DB_TABLE_GROUP . '`.`group_name`
          FROM ' . DB_TABLE_USER . ', `' . DB_TABLE_GROUP . '`' . ' WHERE ' . DB_TABLE_USER . '.`user_group_id`=`' .
    DB_TABLE_GROUP . '`.`id`
            AND ' . DB_TABLE_USER . '.`id` = ' . $t_id;
  $users = db_query($query);

  $user = new user();
  if (isset($users[0]) && (count($users[0]) > 0)) {
    $user->id = $users[0]['id'];
    $user->name = $users[0]['user_name'];
    $user->login = $users[0]['user_login'];
    $user->group = $users[0]['group_name'];
    $user->info = $users[0]['user_info'];
    $user->mail = $users[0]['user_mail'];
    $user->hidden = $users[0]['user_hidden'];
    $user->password = $users[0]['user_password'];
    $user->theme = $users[0]['user_theme'];
    $user->language_id = $users[0]['user_language_id'];
    $user->birthday = $users[0]['user_birthday'];
    $user->position = $users[0]['user_position'];
    $user->phone = $users[0]['user_phone'];
    $user->address = $users[0]['user_address'];
    $user->field1 = $users[0]['user_field1'];
    $user->field2 = $users[0]['user_field2'];
    $user->field3 = $users[0]['user_field3'];

    $grants = get_grants_by_user_id($user->id);
    $user->grants = array();
    foreach ($grants as $key => $grant) {
      $user->grants[$grant['ug_grant_id']] = 1;
    }
  }
  return $user;
}

/**
 * Edit user.
 *
 * @param integer $p_user_id new user id
 * @param integer $p_group new user group id
 * @param string $p_login new user login
 * @param string $p_password new user password
 * @param string $p_name new user name
 * @param string $p_info new user info
 * @param string $p_mail new user mail
 * @param integer $p_hidden is hidden user
 * @param array $p_grants new user grants
 * @param integer $p_language_id user language
 * @param int $p_birthday
 * @param string $p_position
 * @param string $p_phone
 * @param string $p_address
 * @param string $p_field1
 * @param string $p_field2
 * @param string $p_field3
 */
function edit_user($p_user_id, $p_group, $p_login, $p_password, $p_name, $p_info, $p_mail, $p_hidden, $p_grants,
                   $p_language_id = DEFAULT_LANGUAGE, $p_birthday = '', $p_position = '', $p_phone = '',
                   $p_address = '', $p_field1 = '', $p_field2 = '', $p_field3 = '', $p_theme = '')
{
  $t_user_id = db_prepare_int($p_user_id);
  $t_group = db_prepare_int($p_group);
  $t_login = db_prepare_string($p_login);
  $user = get_user($t_user_id);
  if ($p_password == '') {
    $t_password = "'" . $user->password . "'";
  } else {
    $t_password = db_prepare_string(md5($p_password));
  }
  $t_name = db_prepare_string($p_name);
  $t_info = db_prepare_string($p_info);
  $t_hidden = db_prepare_string($p_hidden);
  $t_mail = db_prepare_string($p_mail);
  $t_language_id = db_prepare_int($p_language_id);
  $t_position = db_prepare_string($p_position);
  $t_phone = db_prepare_string($p_phone);
  $t_address = db_prepare_string($p_address);
  $t_field1 = db_prepare_string($p_field1);
  $t_field2 = db_prepare_string($p_field2);
  $t_field3 = db_prepare_string($p_field3);
  $t_theme = db_prepare_string($p_theme);
  if ($p_birthday !== '') $a_birthday = ",`user_birthday` =" . db_prepare_string($p_birthday); else $a_birthday = "";
  if ($t_mail == "''") {
    $t_mail = 'NULL';
  }

  $query = /** @lang MySQL */
    "UPDATE " . DB_TABLE_USER . " 
    SET `user_group_id` = $t_group, `user_name` =  $t_name, 
    `user_password` = $t_password, `user_info` = $t_info, `user_hidden` = $t_hidden, `user_mail` = $t_mail,
    `user_login` = $t_login, `user_theme`=$t_theme, `user_language_id` = $t_language_id  $a_birthday  , 
    `user_position` = $t_position, `user_phone` = $t_phone, `user_address` = $t_address, 
    `user_field1` = $t_field1, `user_field2` = $t_field2, `user_field3` = $t_field3 
  WHERE `id` = $t_user_id;";

  db_exec($query);

  // delete user grants
  delete_user_grants($t_user_id);

  // add user grants
  foreach ($p_grants as $key => $value) {
    add_user_grant($t_user_id, $key);
  }
}

/**
 * Change user settings.
 *
 * @param integer $p_user_id new user id
 * @param string $p_theme
 * @param integer $p_language_id user language
 */
function change_user_settings($p_user_id, $p_theme = CFG_DEFAULT_THEME, $p_language_id = DEFAULT_LANGUAGE)
{
  $t_user_id = db_prepare_int($p_user_id);
  $t_theme = db_prepare_string($p_theme);
  $t_language_id = db_prepare_int($p_language_id);

  $query = 'UPDATE ' . DB_TABLE_USER . ' SET `user_theme` = ' . $t_theme . ', `user_language_id` = ' . $t_language_id .
    ' WHERE `id` = ' . $t_user_id;

  db_exec($query);
}

/**
 * Change user password.
 *
 * @param $p_user_id int user id
 * @param $p_new_password string new user password
 */
function change_user_password($p_user_id, $p_new_password)
{
  $t_user_id = db_prepare_int($p_user_id);
  $t_new_password = db_prepare_string(md5($p_new_password));

  $query = 'UPDATE ' . DB_TABLE_USER . ' SET  `user_password` = ' . $t_new_password . ' WHERE `id` = ' . $t_user_id;

  db_query($query);
}

/**
 * Get account by account login and account password.
 *
 * @param $p_login string account login
 * @param $p_password string account password
 *
 * @return user class
 */
function get_user_from_login_password($p_login, $p_password)
{
  $t_login = db_prepare_string($p_login);
  $t_password = db_prepare_string(md5($p_password));

  $query = 'SELECT ' . DB_TABLE_USER . '.*, `' . DB_TABLE_GROUP . '`.`group_name`
          FROM ' . DB_TABLE_USER . ', `' . DB_TABLE_GROUP . '`' . ' WHERE ' . DB_TABLE_USER . '.`user_login` = ' .
    $t_login . ' AND `user_password` = ' . $t_password . ' AND ' . DB_TABLE_USER . '.`user_group_id` = `' .
    DB_TABLE_GROUP . '`.`id`';
  $users[] = db_query($query);

  $user = new user();
  if (count($users[0]) > 0) {
    $user->id = $users[0][0]['id'];
    $user->name = $users[0][0]['user_name'];
    $user->login = $users[0][0]['user_login'];
    $user->group = $users[0][0]['group_name'];
    $user->info = $users[0][0]['user_info'];
    $user->mail = $users[0][0]['user_mail'];
    $user->hidden = $users[0][0]['user_hidden'];
    $user->password = $users[0][0]['user_password'];
    $user->theme = $users[0][0]['user_theme'];
    $user->language_id = $users[0][0]['user_language_id'];
    $user->birthday = $users[0][0]['user_birthday'];
    $user->position = $users[0][0]['user_position'];
    $user->phone = $users[0][0]['user_phone'];
    $user->address = $users[0][0]['user_address'];
    $user->field1 = $users[0][0]['user_field1'];
    $user->field2 = $users[0][0]['user_field2'];
    $user->field3 = $users[0][0]['user_field3'];

    $grants = get_grants_by_user_id($user->id);
    $user->grants = array();
    foreach ($grants as $key => $grant) {
      $user->grants[$grant['ug_grant_id']] = 1;
    }
  }
  return $user;
}

/**
 * Is user have access on module.
 *
 * @param $p_login string user login
 * @param $p_password string user password
 * @param $p_module string module name
 * @param $p_action
 * @return bool
 */
function is_user_access($p_login, $p_password, $p_module, $p_action)
{
  $t_login = db_prepare_string($p_login);
  $t_password = db_prepare_string(md5($p_password));
  $t_module = db_prepare_string($p_module);
  $t_action = db_prepare_string($p_action);

  $query = 'SELECT ' . DB_TABLE_USER . '.`id`
              FROM ' . DB_TABLE_USER . ', ' . DB_TABLE_USER_GRANTS . ', ' . DB_TABLE_ACCESS . ', ' . DB_TABLE_MODULE .
    ', ' . DB_TABLE_MODULE_ACTION . ',`' . DB_TABLE_GRANT . '`
              WHERE ' . DB_TABLE_USER . '.`user_login`=' . $t_login . '
              AND ' . DB_TABLE_USER . '.`user_password`=' . $t_password . '
              AND ' . DB_TABLE_USER . '.`id`=' . DB_TABLE_USER_GRANTS . '.`ug_user_id`
              AND ' . DB_TABLE_USER_GRANTS . '.`ug_grant_id` = ' . DB_TABLE_ACCESS . '.`grant_id`
              AND ' . DB_TABLE_ACCESS . '.`module_action_id`=' . DB_TABLE_MODULE_ACTION . '.`id`
              AND ' . DB_TABLE_MODULE . '.`module`=' . $t_module . '
              AND ' . DB_TABLE_MODULE . '.`hidden`=0
              AND ' . DB_TABLE_MODULE_ACTION . '.`action`=' . $t_action . '
              AND ' . DB_TABLE_MODULE . '.`id` = ' . DB_TABLE_MODULE_ACTION . '.`module_id`
              AND `' . DB_TABLE_GRANT . '`.`id`=' . DB_TABLE_USER_GRANTS . '.`ug_grant_id`';

  $items = db_query($query);

  return (count($items) > 0);
}

/**
 * Get user actions for module.
 *
 * @param $p_login string user login
 * @param $p_password string user password
 * @param $p_module string module name
 *
 * @return array availabe actions array
 */
function get_user_actions($p_login, $p_password, $p_module)
{
  $t_login = db_prepare_string($p_login);
  $t_password = db_prepare_string(md5($p_password));
  $t_module = db_prepare_string($p_module);

  $query = 'SELECT ' . DB_TABLE_MODULE_ACTION . '.`action`
              FROM ' . DB_TABLE_USER . ', ' . DB_TABLE_USER_GRANTS . ', ' . DB_TABLE_ACCESS . ', ' . DB_TABLE_MODULE .
    ', ' . DB_TABLE_MODULE_ACTION . ',`' . DB_TABLE_GRANT . '`
              WHERE ' . DB_TABLE_USER . '.`user_login`=' . $t_login . '
              AND ' . DB_TABLE_USER . '.`user_password`=' . $t_password . '
              AND ' . DB_TABLE_USER . '.`id`=' . DB_TABLE_USER_GRANTS . '.`ug_user_id`
              AND ' . DB_TABLE_USER_GRANTS . '.`ug_grant_id` = ' . DB_TABLE_ACCESS . '.`grant_id`
              AND ' . DB_TABLE_ACCESS . '.`module_action_id`=' . DB_TABLE_MODULE_ACTION . '.`id`
              AND ' . DB_TABLE_MODULE . '.`module`=' . $t_module . '
              AND ' . DB_TABLE_MODULE . '.`hidden`=0
              AND ' . DB_TABLE_MODULE . '.`id` = ' . DB_TABLE_MODULE_ACTION . '.`module_id`
              AND `' . DB_TABLE_GRANT . '`.`id`=' . DB_TABLE_USER_GRANTS . '.`ug_grant_id`
              AND `' . DB_TABLE_GRANT . '`.`grant_hidden` = 0';

  $items = db_query($query);

  $actions = array();

  foreach ($items as $item) {
    $actions[] = $item['action'];
  }

  return $actions;
}

/**
 * Get modules by user login and user password.
 *
 * @param $p_login string user login
 * @param $p_password string user password
 *
 * @return array of modules
 */
function get_user_modules($p_login, $p_password) 
{
  $t_login = db_prepare_string($p_login);
  $t_password = db_prepare_string(md5($p_password));

  $query = 'SELECT
                c.`name` `category_name`,
                m.`name` `module_name`,
                m.`module`,
                m.`image`
              FROM ' . DB_TABLE_ACCESS . ' a
              INNER JOIN ' . DB_TABLE_MODULE_ACTION . ' ma
                ON  a.`module_action_id` = ma.`id`
              INNER JOIN `' . DB_TABLE_GRANT . '` g
                ON g.`id`=a.`grant_id`
                AND g.`grant_hidden`=0
              INNER JOIN ' . DB_TABLE_MODULE . ' m
                ON m.`id`=ma.`module_id`
              INNER JOIN ' . DB_TABLE_CATEGORY_MODULE . ' cm
                ON cm.`module_id`=m.`id`
                AND cm.`hidden`=0
                AND m.`hidden`=0
              INNER JOIN ' . DB_TABLE_CATEGORY . ' c
                ON c.`id`=cm.`category_id`
                AND c.`hidden`=0
              INNER JOIN ' . DB_TABLE_USER_GRANTS . ' ug
                ON ug.`ug_grant_id`=g.`id`
              INNER JOIN ' . DB_TABLE_USER . ' u
                ON u.`id`=ug.`ug_user_id`
                AND u.`user_login`=' . $t_login . '
                AND u.`user_password`=' . $t_password . '
                GROUP BY m.`module`, m.`name`, c.`name`
              ORDER BY c.`position` , cm.`position`
             ';

  return db_query($query);
}

/**
 * Is user exist by user login.
 *
 * @param $p_login string user login
 *
 * @return bool TRUE on success or FALSE on failure
 */
function is_login_exist($p_login)
{
  $t_login = db_prepare_string($p_login);

  $count = db_count(DB_TABLE_USER, '`user_login` = ' . $t_login);

  return $count > 0;
}

/**
 * Is user exist by user mail.
 *
 * @param $p_mail string user mail
 *
 * @return bool TRUE on success or FALSE on failure
 */
function is_email_exist($p_mail)
{
  $t_mail = db_prepare_string($p_mail);

  $count = db_count(DB_TABLE_USER, '`user_mail` = ' . $t_mail);

  return $count > 0;
}

/**
 * Find user id by user login or user mail.
 *
 * @param $p_user_info string user login or user mail
 *
 * @return int user id on success or 0 on failure
 */
function find_user($p_user_info)
{
  $t_user_info = db_prepare_string($p_user_info);

  $query = 'SELECT `id`
            FROM ' . DB_TABLE_USER . ' WHERE `user_login` = ' . $t_user_info . ' OR `user_mail` = ' . $t_user_info .
    ' LIMIT 1';

  $item = db_query($query);

  $id = 0;

  if (isset($item[0])) {
    $id = $item[0]['id'];
  }

  return $id;
}

/**
 * Move users to other group.
 *
 * @param $p_group_id int new group id
 * @param $p_id_array array of int users id array
 *
 * @return bool TRUE on success or FALSE on failure
 */
function move_users($p_group_id, $p_id_array)
{
  $t_group_id = db_prepare_int($p_group_id);
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);

  $query = 'UPDATE ' . DB_TABLE_USER . ' SET `user_group_id` = ' . $t_group_id . ' WHERE `id` in (' . $tmp . ')';

  db_query($query);

  return (db_last_error() == '');
}


function remove_empty_results($p_user_id)
{
  $p_user_id = db_prepare_int($p_user_id);
  $query = "DELETE FROM `" . DB_TABLE_USER_RESULTS . "` WHERE `id` IN (
	SELECT * FROM (
		SELECT result.id FROM `" . DB_TABLE_USER_RESULTS . "` as result 
		LEFT JOIN `" . DB_TABLE_TESTS . "` as test ON test.id=result.user_result_test_id
		WHERE `user_result_user_id`=$p_user_id AND test.`test_max_count`=0 AND `user_result_completed`=1 AND `user_result_completed_questions`=0) as p
)";
  db_query($query);
  return (db_last_error() == '');
}

function get_empty_results_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);
  if ($size == 0) return NULL;
  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);
  $query = "SELECT result.id FROM `" . DB_TABLE_USER_RESULTS . "` as result 
		LEFT JOIN `" . DB_TABLE_TESTS . "` as test ON test.id=result.`user_result_test_id`
		WHERE `user_result_user_id` IN ($tmp) AND test.`test_max_count`=0 AND  user_result_completed=1 
		AND user_result_completed_questions=0";
  return db_query($query);
}

/**
 * @param $from_id array of int
 * @param $to_id int
 * @return bool
 */
function combine_users(array $from_id, $to_id)
{
  $to_id = db_prepare_int($to_id);

  // To prevent combine the user with himself
  $idx = array_search($to_id, $from_id);
  if (FALSE !== $idx) unset($from_id[$idx]);

  $id_to_update = implode(",", $from_id);
  $query = "UPDATE " . DB_TABLE_USER_RESULTS .
    " SET `user_result_user_id`=$to_id WHERE `user_result_user_id` in ($id_to_update)";
  db_query($query);
  if (db_last_error() == '') {
    $query = "DELETE FROM " . DB_TABLE_USER_GRANTS . " WHERE `ug_user_id` in ($id_to_update)";
    db_query($query);
    $query = "DELETE FROM " . DB_TABLE_USER . " WHERE `id` in ($id_to_update)";
    db_query($query);
    return (db_last_error() == '');
  } else {
    return FALSE;
  }
}

/**
 * @param int User ID
 * @param string Test MediaID
 * @return bool
 */
function user_complete_test_by_media_id($p_user_id, $p_media_id)
{
  $user_id = db_prepare_int($p_user_id);
  $test_id = get_test_id_by_multimedia_id($p_media_id);
  $query = "SELECT COUNT(id) FROM " . DB_TABLE_USER_RESULTS .
    " WHERE user_result_user_id=$user_id AND user_result_test_id=$test_id AND user_result_completed=1";
  $result = db_query($query);
  if ($result[0][0] > 0) return TRUE; else return FALSE;
}

/**
 * @param int User ID
 * @param string Test MediaID
 * @return bool
 */
function user_complete_succesfully_test_by_media_id($p_user_id, $p_media_id)
{
  $user_id = db_prepare_int($p_user_id);
  $test_id = get_test_id_by_multimedia_id($p_media_id);
  $query = "SELECT MAX(`resume_low`) FROM `" . DB_TABLE_RESUME . "` WHERE resume_test_id=$test_id";
  $result = db_query($query);
  $min_score = $result[0][0];
  $query = "SELECT COUNT(id) FROM " . DB_TABLE_USER_RESULTS . " WHERE user_result_user_id=$user_id 
  AND user_result_test_id=$test_id AND user_result_completed=1 AND `user_result_score`>=$min_score";
  $result = db_query($query);
  return $result[0][0] > 0;
}