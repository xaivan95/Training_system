<?php

/**
 * @see module_base
 */
class module_messages extends module_base
{
  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title_delete'] = text('txt_delete');
    $WEB_APP['title_add'] = text('txt_send');
    $WEB_APP['title_edit'] = text('txt_edit');
  }

  function details()
  {
    global $WEB_APP;
    $user_id = get_user_id($_SESSION['user_login']);

    if (message_owned_by_user($WEB_APP['id'], $user_id)) {
      $message = get_message($WEB_APP['id']);
      $messages = get_messages_list_with_users($WEB_APP['id']);
      for ($i = 0; $i < count($messages); $i++) {
        if ($messages[$i]['message_status'] == 0) $messages[$i]['message_status'] =
          "<strong>" . text('txt_message_unread') . "</strong>"; else
          $messages[$i]['message_status'] = text('txt_message_read');
      }
      $columns = array();
      $columns[] = new column('id', text('txt_user_id'));
      $columns[] = new column('user_name', text('txt_user_name'));
      $columns[] = new column('message_status', text('txt_status'));
      $WEB_APP['rows'] = $messages;
      $WEB_APP['rows_count'] = count($messages);
      $WEB_APP['columns_count'] = count($columns);
      $WEB_APP['columns'] = $columns;
      $WEB_APP['show_table'] = TRUE;
      $WEB_APP['show_print_button'] = FALSE;
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['title'] = $message->title;
      $WEB_APP['info'] = $message->text;
      $WEB_APP['view']->display('sent_message.tpl', text('txt_message'));
    } else {
      $WEB_APP['title'] = text('txt_403_forbidden');
      $WEB_APP['view']->display('403.tpl', text('txt_403_forbidden'));
      exit();
    }
  }

  function edit()
  {
    global $WEB_APP;
    global $adodb;
    if (message_owned_by_user($WEB_APP['id'], get_user_id($_SESSION['user_login']))) {
      $message = get_message($WEB_APP['id']);
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      if (!isset($message->id)) {
        redirect($WEB_APP['errorstext']);
      }
      if (($WEB_APP['id'] >= 0) && is_add_edit_form('title')) {
        $correct_post = TRUE;

        if ($_POST['title'] == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_title') . "<br>";
          $correct_post = FALSE;
        }
        if (trim($_POST['message']) == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_message') . "<br>";
          $correct_post = FALSE;
        }

        if ($correct_post) {
          edit_message($WEB_APP['id'], $_POST['title'], $_POST['message']);
          $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        }
        redirect($WEB_APP['errorstext']);
      }

      $fields = array();
      $fields[] = new field(TRUE, text('txt_title'), "text", 'title', $message->title, "");
      $fields[] = new field(TRUE, text('txt_message'), "textarea", 'message', $message->text, "");
      $WEB_APP['fields'] = $fields;
      $WEB_APP['escape'] = TRUE;
      $WEB_APP['title'] = $WEB_APP['title_edit'];
      $WEB_APP['view']->display('table.tpl', text('txt_messages'));
    } else {
      $WEB_APP['title'] = text('txt_403_forbidden');
      $WEB_APP['view']->display('403.tpl', text('txt_403_forbidden'));
      exit();
    }
  }

  /**
   * @throws \PHPMailer\PHPMailer\Exception
   */
  function on_delete()
  {
    global $WEB_APP;
    global $adodb;
    if (!isset($_POST['selected_row'])) {
      $this->view();
      exit();
    }
    if (is_confirm_delete_action()) {
      $result = delete_messages($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_message') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_sent_messages_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';
    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_sent_messages_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column('message_date', text('txt_date')),
      new column('message_title', text('txt_title')));

    $WEB_APP['escape'] = TRUE;
    $WEB_APP['submit_title'] = text('txt_delete');
    $WEB_APP['view']->display('list_action.tpl', text('txt_messages'));
  }

  /**
   * @throws \PHPMailer\PHPMailer\Exception
   */
  function view()
  {
    global $WEB_APP;
    global $adodb;
    $groups = get_groups('group_name', 'ASC');
    $select_groups[] = array();

    if (is_confirm_delete_action()) {
      $result = delete_messages($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_message') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }

    if ($WEB_APP['action'] == 'add') {
      if (!isset($_POST['group'])) {
        $WEB_APP['errorstext'] .= text('txt_select_groups') . '<br>';
      } else {
        $_SESSION['message_groups']['group_array'] = $_POST['group'];
        $select_groups = array();
        foreach ($_SESSION['message_groups']['group_array'] as $group_id) {
          $group = get_group($group_id);
          $select_groups[] = $group->name;
        }
      }
    }

    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      if (message_owned_by_user($WEB_APP['id'], get_user_id($_SESSION['user_login'])) == FALSE) {
        $WEB_APP['title'] = text('txt_403_forbidden');
        $WEB_APP['view']->display('403.tpl', text('txt_403_forbidden'));
        exit();
      }
      $message = get_message($WEB_APP['id']);
      if (!isset($message->id)) {
        redirect($WEB_APP['errorstext']);
      }
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_sent_messages_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'delete';
    }

    if ($WEB_APP['action'] == 'view') {

      if (is_add_form('title')) {
        $correct_post = TRUE;

        if (!isset($_POST['group'])) {
          $WEB_APP['errorstext'] .= text('txt_select_groups') . '<br>';
          $correct_post = FALSE;
        }

        if (trim($_POST['title']) == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_title') . "<br>";
          $correct_post = FALSE;
        }

        if ($correct_post) {
          $selected_groups_ids = array();
          foreach ($_POST['group'] as $group_id) {
            $group = get_group($group_id);
            $select_groups[] = $group->name;
            $selected_groups_ids[] = $group->id;
          }
          $message = new message();
          $message->title = $_POST['title'];
          $message->text = $_POST['message'];
          $message->author_id = get_user_id($_SESSION['user_login']);
          send_message($message, $selected_groups_ids, isset($_POST['copy_to_email']));
          $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
          redirect($WEB_APP['errorstext']);
        }
      }

      if (is_delete_action()) {
        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_sent_messages_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['action'] = 'delete';
      } else {
        $user_id = get_user_id($_SESSION['user_login']);
        $WEB_APP['items_count'] = get_sent_messages_count(new message_filter(), $user_id);
        $pages = get_pages_count($WEB_APP['items_count'],
          ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
        if ($WEB_APP['page'] > $pages) $WEB_APP['page'] = $pages;

        $messages = get_sent_messages($user_id, $WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'],
          $WEB_APP['count'], new message_filter());
        $WEB_APP['items'] = $messages;
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();

        $paginator = new paginator($WEB_APP['page'], $pages);
        $paginator->url = $WEB_APP['script_name'];
        $paginator->url_query_array = $WEB_APP['url_query_array'];
        $WEB_APP['paginator'] = $paginator;
      }

    }
    $WEB_APP['row_actions'] =
      array($WEB_APP['action_details'], $WEB_APP['action_status'], $WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('message_date', text('txt_date')),
      new column('message_title', text('txt_title')));
    $fields = array();
    $fields[] =
      new field(TRUE, text('txt_groups'), 'multiple_select', 'group[]', $select_groups, '', $groups, 'id', 'group_name',
        null, FALSE, '', '', 'data-live-search="true"');
    $fields[] = new field(TRUE, text('txt_title'), "text", 'title', "", "");
    $fields[] = new field(TRUE, text('txt_message'), "textarea", 'message', "", "");
    $fields[] = new field(FALSE, text('txt_copy_to_email'), "checkbox", 'copy_to_email', "", "");

    $WEB_APP['fields'] = $fields;
    $WEB_APP['title'] = text('txt_messages');
    $WEB_APP['escape'] = TRUE;
    $WEB_APP['submit_title'] = text('txt_create');
    $WEB_APP['view']->display('table.tpl', text('txt_messages'));
  }

  /**
   * @throws \PHPMailer\PHPMailer\Exception
   */
  function on_confirm_delete()
  {
    global $WEB_APP;
    global $adodb;

    $result = delete_messages($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_delete_message') . "<br>";
      $this->view();
      exit();
    }
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    redirect($WEB_APP['errorstext']);
  }
}
