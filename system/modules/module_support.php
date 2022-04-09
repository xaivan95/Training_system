<?php

/**
 * @see module_base
 */
class module_support extends module_base
{

  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title'] = text('txt_support');

  }

  /**
   * @throws \PHPMailer\PHPMailer\Exception
   */
  function view()
  {
    global $WEB_APP;
    $user = get_user(get_user_id($_SESSION['user_login']));
    $WEB_APP['log'] = array();
    $email = '';
    $topic = '';
    $message = '';
    if (isset($_POST['topic'])) $topic = $_POST['topic'];
    if (isset($_POST['message'])) $message = $_POST['message'];
    if (isset($_POST['email'])) $email = $_POST['email']; else if (isset($_SESSION['email'])) $email =
      $_SESSION['email'];

    if (is_add_edit_form("topic")) {
      $correct_post = TRUE;
      if (!$_POST['topic']) {
        $WEB_APP['errorstext'] .= text('txt_insert_topic') . "<br>";
        $correct_post = FALSE;
      }

      if (!$_POST['message']) {
        $WEB_APP['errorstext'] .= text('txt_insert_message') . "<br>";
        $correct_post = FALSE;
      }

      if ($email == '') {
        $WEB_APP['errorstext'] .= text('txt_mail_from_does_not_correct_insert_correct_email') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post) {
//        $message = wordwrap($_POST['message'], 70);
//        $header = 'Content-Type: text/plain; charset=utf-8' . "\r\n";
//        $header .= "From:  $user->name <$email>\r\n";
//        $header .= "Reply-To: $email\r\n";
//        $header .= "Date:" . date(DATE_RFC822) . "\r\n";
//        $header .= "X-Mailer:КАОС 54 кафедра\r\n";
//        $subject = $_POST['topic'] . "\r\n";
//        $was_sent = mail($WEB_APP['settings']['support_mail'], $subject, $_POST['message'], $header);
        if (isset($_FILES) || ($_FILES["file"]["size"] > 0)) {
          $file_name = $_FILES["file"]["tmp_name"];
          $original_file_name = basename($_FILES["file"]["name"]);
        } else {
          $file_name = "";
          $original_file_name = "";
        }
        if (send_mail($user->name, $email, $WEB_APP['settings']['support_mail'], $_POST['topic'], $_POST['message'],
            $file_name, $original_file_name) ==
          TRUE) $WEB_APP['infotext'] .= text('txt_message_was_sent'); else $WEB_APP['errorstext'] .= "Can not send message <br>";
      }
    }

    $fields = array();
    if ($user->mail == '') {
      $fields[] = new field(TRUE, 'E-mail', "email", "email", $email, "email");
    } else {
      $_SESSION['email'] = $user->mail;
    }
    $fields[] = new field(TRUE, text('txt_topic'), "text", "topic", $topic, "topic");
    $fields[] = new field(TRUE, text('txt_message'), "textarea", "message", $message, "message");
    $fields[] = new field(FALSE, text('txt_file'), "file", "file", "", "", "", "", "", NULL, FALSE, '');

    $WEB_APP['fields'] = $fields;
    $WEB_APP['submit_title'] = text('txt_send');
    $WEB_APP['form_title'] = text('txt_support');
    $WEB_APP['title'] = text('txt_support');
    $WEB_APP['title_edit'] = text('txt_support');
    $WEB_APP['form_enctype'] = TRUE;

    $WEB_APP['view']->display('form_page.tpl', text('txt_support'));
  }
}

