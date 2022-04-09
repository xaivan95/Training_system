<?php

require_once(CFG_LIB_DIR . "phpmailer/src/PHPMailer.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class message
{
  var $id;
  var $author_id;
  var $date;
  var $url;
  var $text;
  var $title;
}


function get_author_name($message_id)
{
  $message_id = db_prepare_int($message_id);
  $tmp = db_query("SELECT  `user`.`user_name` FROM `webclass_messages` as`message`
LEFT JOIN `webclass_user` as `user` on  `user`.`id` = `message`.`message_author_id`
WHERE  `message`.`id` = $message_id ");
  if (count($tmp) > 0) return $tmp[0][0]; else return "";
}

function edit_message($message_id, $title, $text)
{
  $message_id = db_prepare_int($message_id);
  $title = db_prepare_string($title);
  $text = db_prepare_string($text);
  $query = "UPDATE " . DB_TABLE_MESSAGES . " SET `message_title`=$title, `message_text`=$text WHERE `id`=$message_id";
  db_exec($query);
}

/**
 * @param $p_message_id int
 * @return message|string
 */
function get_message($p_message_id)
{
  $t_message_id = db_prepare_string($p_message_id);
  $messages[] = db_query("SELECT * FROM " . DB_TABLE_MESSAGES . " WHERE `id` =  " . $t_message_id);
  $message = new message();
  if (isset($messages[0]) && (count($messages[0]) > 0)) {
    $message->id = $messages[0][0]['id'];
    $message->author_id = $messages[0][0]['message_author_id'];
    $message->date = $messages[0][0]['message_date'];
    $message->url = $messages[0][0]['message_url'];
    $message->text = $messages[0][0]['message_text'];
    $message->title = $messages[0][0]['message_title'];
    return $message;
  } else return '';
}

/**
 * @param $message message
 * @param $groups_id array of int
 * @throws Exception
 */
function send_message($message, $groups_id, $copy_to_email = FALSE)
{
  if ($copy_to_email == TRUE) $user = get_user(get_user_id($_SESSION['user_login']));
  $message->text = str_replace(array("\r\n", "\n"), '<br>', $message->text);
  $message->author_id = db_prepare_int($message->author_id);
  $message->url = db_prepare_string($message->url);
  $message->text = db_prepare_string($message->text);
  $message->title = db_prepare_string($message->title);
  $query = "INSERT INTO " . DB_TABLE_MESSAGES . " (`message_author_id`, `message_url`, `message_text`, `message_title`) 
    VALUES ($message->author_id, $message->url, $message->text, $message->title); ";
  db_exec($query);
  $message->id = db_insert_id();
  $values = "";
  $groups_count = count($groups_id);
  for ($i = 0; $i < $groups_count; $i++) {
    $query = "SELECT id, user_mail FROM " . DB_TABLE_USER . " WHERE user_group_id=" . $groups_id[$i];
    $users_id = db_query($query);
    $users_count = count($users_id);
    for ($j = 0; $j < $users_count; $j++) {
      $values .= "(" . $users_id[$j]['id'] . ", $message->id, 0), ";
      if (($copy_to_email == TRUE) && isset($user->mail) && isset($users_id[$j]['user_mail'])) send_mail($user->name,
        $user->mail, $users_id[$j]['user_mail'], $message->title, $message->text);
    }
  }
  $values = trim($values, ", ");
  if ($values !== '') db_exec("INSERT INTO " . DB_TABLE_USER_MESSAGES .
    " (`user_id`,`message_id`, `message_status`) VALUES $values; ");
}

/**
 * @param $message_id int
 * @return bool
 */
function delete_message($message_id)
{
  db_exec("DELETE FROM " . DB_TABLE_USER_MESSAGES . " WHERE  `message_id` = $message_id; ");
  db_exec("DELETE FROM " . DB_TABLE_MESSAGES . " WHERE  `id` = $message_id; ");
  return (db_last_error() == '');
}

function delete_messages($p_id_array)
{
  $tmp = TRUE;
  foreach ($p_id_array as $id) {
    $result = delete_message($id);
    $tmp = $tmp && $result;
  }
  return $tmp;
}

/**
 * Get books count by filter.
 *
 * @param $p_filter message_filter
 * @param $user_id int
 * @return int messages count
 */
function get_sent_messages_count($p_filter, $user_id)
{
  $where = ($p_filter != NULL) ? $p_filter->query() : '';
  if ($where == '') $where = 'message_author_id=' . $user_id; else $where .= ' AND message_author_id=' . $user_id;
  return (db_count(DB_TABLE_MESSAGES, $where));
}

function get_received_messages_count($p_filter, $user_id)
{
  $where = ($p_filter != NULL) ? $p_filter->query() : '';
  if ($where == '') $where = 'user_id=' . $user_id; else $where .= ' AND user_id=' . $user_id;
  return (db_count(DB_TABLE_USER_MESSAGES, $where));
}

/**
 * Get sent messages.
 *
 * @param $author_id int
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter message_filter
 *
 * @return array message array
 */
function get_sent_messages($author_id, $p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0,
                           $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  if ($t_sort_field == '') $t_sort_field = 'id';
  if (!in_array($t_sort_field, array('id', 'message_title', 'message_date', 'message_text'))) {
    $t_sort_field = 'id';
  }

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "$limit, $t_count";
  } else {
    $limit_str = '';
  }

  $where = ($p_filter != NULL) ? $p_filter->query() : '';
  if ($where == '') $where = 'message_author_id=' . $author_id; else $where .= ' AND message_author_id=' . $author_id;
  return db_extract(DB_TABLE_MESSAGES, $where, $t_sort_field . ' ' . $t_sort_order, $limit_str);
}

/**
 * Get received messages.
 *
 * @param $user_id int
 * @param $p_sort_field string sort field
 * @param $p_sort_order string sort order: ASC or DESC
 * @param $p_page int page number
 * @param $p_count int items count on the page
 * @param $p_filter message_filter
 *
 * @return array message array
 */
function get_received_messages($user_id, $p_sort_field = 'id', $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0,
                               $p_filter = NULL)
{
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  if ($t_sort_field == '') $t_sort_field = 'id';
  if (!in_array($t_sort_field, array('id', 'message_title', 'message_date', 'message_text'))) {
    $t_sort_field = 'id';
  }

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = " LIMIT $limit, $t_count";
  } else {
    $limit_str = '';
  }
  $where = " `user_messages`.`user_id`=$user_id ";
  $where = ($p_filter->e_text == "") ? $where : $where . " AND " . $p_filter->query();

  $query = "SELECT  `messages`.`id`, `message_date`, `message_url`, `message_text` ,
       `message_title`, `message_status`, `user_name` FROM `" . DB_TABLE_MESSAGES . "` as `messages`
    LEFT JOIN `" . DB_TABLE_USER . "` as `users` ON `messages`.`message_author_id`=`users`.`id` 
    LEFT JOIN `" . DB_TABLE_USER_MESSAGES . "` as `user_messages` ON  `user_messages`.`message_id`=`messages`.`id` 
    WHERE $where ORDER BY $t_sort_field $t_sort_order $limit_str";
  return db_query($query);
}

function get_sent_messages_from_array($p_id_array)
{
  $tmp = '';
  $array = array_values($p_id_array);
  $size = sizeof($array);
  if ($size == 0) return NULL;
  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ', ';
  $tmp .= db_prepare_int($array[$size - 1]);
  $query = "SELECT *  FROM " . DB_TABLE_MESSAGES . " WHERE `id` in($tmp) ";
  return db_query($query);
}

/**
 * May user view thes message?
 *
 * @param $message_id int
 * @param $user_id int
 * @return bool
 */
function message_available_for_user($message_id, $user_id)
{
  $message_id = db_prepare_int($message_id);
  $user_id = db_prepare_int($user_id);
  $count = db_count(DB_TABLE_USER_MESSAGES, " message_id=$message_id AND user_id=$user_id");
  return $count > 0;
}

/**
 * Own user thes message?
 *
 * @param $message_id int
 * @param $user_id int
 * @return bool
 */
function message_owned_by_user($message_id, $user_id)
{
  $message_id = db_prepare_int($message_id);
  $user_id = db_prepare_int($user_id);
  $count = db_count(DB_TABLE_MESSAGES, " id=$message_id AND message_author_id=$user_id");
  return $count > 0;
}


/**
 * Set status
 *
 * @param $message_id int
 * @param $user_id int
 * @param $status int
 */
function set_message_status($message_id, $user_id, $status)
{
  $message_id = db_prepare_int($message_id);
  $user_id = db_prepare_int($user_id);
  $status = db_prepare_int($status);
  $query = "UPDATE " . DB_TABLE_USER_MESSAGES .
    " SET `message_status`=$status WHERE `user_id`=$user_id AND `message_id`=$message_id";
  db_exec($query);
}

/**
 * How many unreaded messages
 * @param $user_id int
 * @return int
 */
function get_unreaded_messages($user_id)
{
  $user_id = db_prepare_int($user_id);
  return db_count(DB_TABLE_USER_MESSAGES, "`user_id`=$user_id AND `message_status`=0");
}

function get_messages_list_with_users($message_id)
{
  $message_id = db_prepare_int($message_id);
  $query = "SELECT users.id, users.user_name, user_messages.`message_status` FROM `" . DB_TABLE_USER_MESSAGES . "` as user_messages
            LEFT JOIN `" . DB_TABLE_USER . "` as users on users.id=user_messages.`user_id`
            WHERE `user_messages`.`message_id`=$message_id
            ORDER BY users.user_name";
  return db_query($query);
}

/**
 * @param string $from_name
 * @param string $from_email
 * @param string $to_email
 * @param string $subject
 * @param string $body
 * @param string $file_name
 * @return bool
 * @throws Exception
 */
function send_mail($from_name, $from_email, $to_email, $subject, $body, $file_name = '', $original_file_name = '')
{
  if (isset($from_email) && isset($to_email) && isset($body)) {
    $email = new PHPMailer();
    $email->CharSet = 'UTF-8';
    $email->Encoding = 'base64';
    $email->SetFrom($from_email, $from_name);
    $email->Subject = $subject;
    $email->Body = $body;
    $email->AddAddress($to_email);
    if ($file_name !== '') {
      $file_to_attach = $file_name;
      $email->AddAttachment($file_to_attach, $original_file_name);
    }
    return $email->Send();
  } else return FALSE;
}