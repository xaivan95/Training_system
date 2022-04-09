<?php


class module_report_viewed_books extends module_base
{
  function view()
  {
    global $WEB_APP;
    global $adodb;
    unset($_SESSION['viewed_book_user_id']);
    if ($WEB_APP['action'] == 'view') {
      $WEB_APP['title'] = text('txt_report_viewed_books');
      $WEB_APP['items_count'] = get_users_with_viewed_books_count(new account_filter());
      $pages = get_pages_count($WEB_APP['items_count'],
        ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
      if ($WEB_APP['page'] > $pages) {
        $WEB_APP['page'] = $pages;
      }
      $users =
        get_users_with_viewed_books($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
          new account_filter());
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      $WEB_APP['items'] = $users;

      $paginator = new paginator($WEB_APP['page'], $pages);
      $paginator->url = $WEB_APP['script_name'];
      $paginator->url_query_array = $WEB_APP['url_query_array'];
      $WEB_APP['paginator'] = $paginator;
    }
    $WEB_APP['row_actions'] = array($WEB_APP['action_details']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column("user_login", text('txt_login')),
      new column("group_name", text('txt_group')), new column("user_name", text('txt_user_name')),
      new column("user_info", text('txt_info')), new column("user_mail", text('txt_mail')),
      new column("user_hidden", text('txt_hidden')), new column("user_position", text('txt_user_position')));
    if (isset($WEB_APP['settings']['users_info_mode'])) {
      if ($WEB_APP['settings']['users_info_mode'] > 0) {
        $WEB_APP['columns'] = array_merge($WEB_APP['columns'],
          array(new column("user_field1", text('txt_field1')), new column("user_field2", text('txt_field2')),
            new column("user_field3", text('txt_field3'))));
      }
      if ($WEB_APP['settings']['users_info_mode'] > 1) {
        $WEB_APP['columns'] = array_merge($WEB_APP['columns'],
          array(new column("user_birthday", text('txt_birthday')), new column("user_phone", text('txt_phone')),
            new column("user_address", text('txt_address'))));

      }
    }

    $WEB_APP['escape'] = TRUE;
    $WEB_APP['view']->display('table.tpl', text('txt_report_viewed_books'));
  }

  function details()
  {
    global $WEB_APP;
//    header('Location: ' . $WEB_APP['cfg_url'] . '?module=viewed_books&action=view&uid=' . $WEB_APP['id']);
//    exit();
    $user_id = $WEB_APP['id'];
    $user = get_user($user_id);
    $title = text('txt_report_viewed_books') . ': ' . $user->name;

    $WEB_APP['items_count'] = get_viewed_chapters_count($user_id, new viewed_books_filter());
    $pages =
      get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
    if ($WEB_APP['page'] > $pages) {
      $WEB_APP['page'] = $pages;
    }

    $paginator = new paginator($WEB_APP['page'], $pages);
    $paginator->url = $WEB_APP['script_name'];
    $paginator->url_query_array = $WEB_APP['url_query_array'];
    $WEB_APP['paginator'] = $paginator;
    $viewed_chapters =
      get_viewed_chapters($user_id, $WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
        new viewed_books_filter());
    $views_count = count($viewed_chapters);
    for ($i = 0; $i < $views_count; $i++) {
      $viewed_chapters[$i]['book_title'] =
        "<a href='" . $WEB_APP['cfg_url'] . "?module=view_books&action=show&bid=" . $viewed_chapters[$i]['book_id'] .
        "'>" . $viewed_chapters[$i]['book_title'] . "</a>";

    }

    $columns = array();
    $columns[] = new column('view_date', text('txt_date'));
    $columns[] = new column('book_title', text('txt_book'));
    $columns[] = new column('chap_title', text('txt_chapter'));

    $WEB_APP['columns'] = $columns;
    $WEB_APP['items'] = $viewed_chapters;
    $WEB_APP['action'] = 'view';
    $WEB_APP['id'] = 'id';
    $WEB_APP['columns_count'] = count($columns);
    $WEB_APP['title'] = $title;
    $WEB_APP['view']->display('table.tpl', $title);
  }
}