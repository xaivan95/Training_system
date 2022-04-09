<?php

class module_viewed_books extends module_base
{
  function view()
  {
    global $WEB_APP;
    $user_id = get_user_id($_SESSION['user_login']);
    $title = text('txt_viewed_books');

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