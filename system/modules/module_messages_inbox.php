<?php

/**
 * @see module_base
 */
class module_messages_inbox extends module_base
{
  function view()
  {
    global $WEB_APP;
    $user_id = get_user_id($_SESSION['user_login']);
    $WEB_APP['items_count'] = get_received_messages_count(new message_filter(), $user_id);
    $pages =
      get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
    if ($WEB_APP['page'] > $pages) $WEB_APP['page'] = $pages;

    $messages = get_received_messages($user_id, $WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'],
      $WEB_APP['count'], new message_filter());

    $messages_count = count($messages);
    for ($i = 0; $i < $messages_count; $i++) {
      $messages[$i]['message_title'] =
        '<a href="?module=messages_inbox&action=details&id=' . $messages[$i]['id'] . '">' .
        $messages[$i]['message_title'] . "</a>";
      if ($messages[$i]['message_status'] == 0) $messages[$i]['message_title'] =
        "<strong>" . $messages[$i]['message_title'] . "</strong>";
    }
    $WEB_APP['items'] = $messages;

    $paginator = new paginator($WEB_APP['page'], $pages);
    $paginator->url = $WEB_APP['script_name'];
    $paginator->url_query_array = $WEB_APP['url_query_array'];
    $WEB_APP['paginator'] = $paginator;

    $WEB_APP['row_actions'] = array($WEB_APP['action_details']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('user_name', text('txt_sender')),
      new column('message_date', text('txt_date')), new column('message_title', text('txt_title')));
    $WEB_APP['title'] = text('txt_received_messages');
    unset($_SESSION['new_messages_count']);
    $WEB_APP['view']->display('table.tpl', text('txt_received_messages'));
  }

  function details()
  {
    global $WEB_APP;
    $user_id = get_user_id($_SESSION['user_login']);
    if (message_available_for_user($WEB_APP['id'], $user_id)) {
      $message = get_message($WEB_APP['id']);
      set_message_status($WEB_APP['id'], $user_id, 1);
      $WEB_APP['title'] = $message->title;
      $WEB_APP['subtitle'] = "<h3>" . text('txt_sender') . ": " . get_author_name($message->id) . "</h3>";
      $WEB_APP['info'] = $message->text;
      $WEB_APP['view']->display('info.tpl', text('txt_message'));
    } else {
      $WEB_APP['title'] = text('txt_403_forbidden');
      $WEB_APP['view']->display('403.tpl', text('txt_403_forbidden'));
      exit();
    }
  }
}