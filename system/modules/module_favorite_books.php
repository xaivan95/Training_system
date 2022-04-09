<?php

/**
 * @see module_favorite_books
 */
class module_favorite_books extends module_base
{

  function view()
  {
    global $WEB_APP;
    global $adodb;
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      remove_book_from_favorites_by_id($WEB_APP['id']);
    }

    $WEB_APP['title'] = text('txt_favorite_books');
    $WEB_APP['items_count'] = get_favorite_books_count(new favorie_books_filter());

    // Pages count.
    $pages =
      get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);

    if ($WEB_APP['page'] > $pages) {
      $WEB_APP['page'] = $pages;
    }
    $favorite_books =
      get_favorite_books($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
        new favorie_books_filter());
    $favorites_count = count($favorite_books);
    for ($i = 0; $i < $favorites_count; $i++) {
      $favorite_books[$i]['book_title'] =
        "<a href='" . $WEB_APP['cfg_url'] . "?module=view_books&action=show&bid=" . $favorite_books[$i]['book_id'] .
        "'>" . $favorite_books[$i]['book_title'] . "</a>";

    }
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    $WEB_APP['items'] = $favorite_books;

    $paginator = new paginator($WEB_APP['page'], $pages);
    $paginator->url = $WEB_APP['script_name'];
    $paginator->url_query_array = $WEB_APP['url_query_array'];
    $WEB_APP['paginator'] = $paginator;

    $WEB_APP['row_actions'] = array($WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('book_title', text('txt_book')));
    $WEB_APP['action'] = 'view';

    $WEB_APP['view']->display('table.tpl', text('txt_favorite_books'));
  }

  function add()
  {
    if (isset($_SESSION['book_id'])) {
      add_book_to_favorites(get_user_id($_SESSION['user_login']), $_SESSION['book_id']);
      header("Location: index.php?module=view_books&action=show&bid=" . $_SESSION['book_id'] . "&cidx=" .
        $_SESSION['chapter_id']);
    }
  }
}