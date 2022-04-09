<?php

/**
 * @see module_base
 */
class module_incomplete_testing extends module_base
{
  /** @noinspection PhpUnused */
  function on_finish()
  {
    global $WEB_APP;

    if (!isset($_POST['selected_row'])) {
      header('Location: ' . $WEB_APP['cfg_url'] . '?module=incomplete_testing');
      exit();
    }

    $WEB_APP['title'] = text('txt_delete');
    $WEB_APP['items'] = get_user_results_from_array($_POST['selected_row']);
    $WEB_APP['items_count'] = count($WEB_APP['items']);

    if (count($WEB_APP['items']) === 0) {
      redirect($WEB_APP['errorstext']);
    }
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['action'] = 'finish';
    // Pages count.
    $pages =
      get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
    if ($WEB_APP['page'] > $pages) {
      $WEB_APP['page'] = $pages;
    }

    // Create paginator.
    $paginator = new paginator($WEB_APP['page'], $pages);
    $paginator->url = $WEB_APP['script_name'];
    $paginator->url_query_array = $WEB_APP['url_query_array'];
    $WEB_APP['paginator'] = $paginator;
    $WEB_APP['row_actions'] = array($WEB_APP['action_continue'], $WEB_APP['action_finish']);

    // Table titles.
    $columns = array();
    $columns[] = new column('id', 'id');
    $columns[] = new column('user_result_test_title', text('txt_test'));
    $columns[] = new column('time_begin', text('txt_date'));

    $WEB_APP['columns'] = $columns;

    $WEB_APP['id'] = 'id';
    $WEB_APP['columns_count'] = count($columns);
    //$WEB_APP['hide_edit'] = 1;
    $WEB_APP['hide_delete'] = 1;
    $WEB_APP['show_insert'] = FALSE;
    $WEB_APP['title'] = text('txt_finish_incomplete_tests');
    $WEB_APP['view']->display('table.tpl', text('txt_view_results'));

  }

  /** @noinspection PhpUnused */
  function continue_testing()
  {
    global $WEB_APP;

    $id = $WEB_APP['id'];
    $user_result = get_user_result($id);

    if (!isset($user_result->id)) {
      header('Location: ' . $WEB_APP['cfg_url'] . '?module=incomplete_testing');
      exit();
    }

    $user_id = get_user_id($_SESSION['user_login']);
    if (!($user_result->completed) && ($user_id == $user_result->user)) {
      $_SESSION['test'] = @unserialize($user_result->test_data);

      header('Location: ' . $WEB_APP['cfg_url'] . '?module=testing');
      exit();
    }

    redirect($WEB_APP['errorstext']);
  }

  function finish()
  {
    global $WEB_APP;

    $WEB_APP['title'] = text('txt_delete');
    $WEB_APP['items'] = get_user_results_from_array(array($WEB_APP['id']));
    $WEB_APP['items_count'] = count($WEB_APP['items']);

    if (count($WEB_APP['items']) === 0) {
      redirect($WEB_APP['errorstext']);
    }
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['action'] = 'finish';
    // Pages count.
    $pages =
      get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
    if ($WEB_APP['page'] > $pages) {
      $WEB_APP['page'] = $pages;
    }

    // Create paginator.
    $paginator = new paginator($WEB_APP['page'], $pages);
    $paginator->url = $WEB_APP['script_name'];
    $paginator->url_query_array = $WEB_APP['url_query_array'];
    $WEB_APP['paginator'] = $paginator;
    $WEB_APP['row_actions'] = array($WEB_APP['action_continue'], $WEB_APP['action_finish']);

    // Table titles.
    $columns = array();
    $columns[] = new column('id', 'id');
    $columns[] = new column('user_result_test_title', text('txt_test'));
    $columns[] = new column('time_begin', text('txt_date'));

    $WEB_APP['columns'] = $columns;

    $WEB_APP['id'] = 'id';
    $WEB_APP['columns_count'] = count($columns);
    //$WEB_APP['hide_edit'] = 1;
    $WEB_APP['hide_delete'] = 1;
    $WEB_APP['show_insert'] = FALSE;
    $WEB_APP['title'] = text('txt_finish_incomplete_test');
    $WEB_APP['view']->display('table.tpl', text('txt_view_results'));
  }

  function view()
  {
    global $WEB_APP;
    global $adodb;

    $user_id = get_user_id($_SESSION['user_login']);

    if (is_confirm_action('finish')) {
      if (!isset($_POST['selected_row'])) {
        header('Location: ' . $WEB_APP['cfg_url'] . '?module=incomplete_testing');
        exit();
      }

      $result = finish_user_results($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_error_finishing_testing') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }

    $WEB_APP['items_count'] = get_incomplete_tests_user_results_count_for_user_id($user_id, new user_result_filter());

    // Pages count.
    $pages =
      get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
    if ($WEB_APP['page'] > $pages) {
      $WEB_APP['page'] = $pages;
    }

    $user_results = get_incomplete_tests($user_id, $WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'],
      $WEB_APP['count'], new user_result_filter());
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    $adodb->Close();

    // Create paginator.
    $paginator = new paginator($WEB_APP['page'], $pages);
    $paginator->url = $WEB_APP['script_name'];
    $paginator->url_query_array = $WEB_APP['url_query_array'];
    $WEB_APP['paginator'] = $paginator;
    $WEB_APP['row_actions'] = array($WEB_APP['action_continue'], $WEB_APP['action_finish']);

    // Table titles.
    $columns = array();
    $columns[] = new column('id', 'id');
    $columns[] = new column('user_result_test_title', text('txt_test'));
    $columns[] = new column('time_begin', text('txt_date'));

    $WEB_APP['columns'] = $columns;

    $WEB_APP['items'] = $user_results;
    //$WEB_APP['rows_count'] = count($user_results);
    $WEB_APP['editform'] = TRUE;
    $WEB_APP['list_actions'] = array($WEB_APP['list_action_finish']);
    $WEB_APP['action'] = 'view';
    $WEB_APP['id'] = 'id';
    $WEB_APP['columns_count'] = count($columns);
    $WEB_APP['show_insert'] = FALSE;
    $WEB_APP['title'] = text('txt_incomplete_testing');
    $WEB_APP['view']->display('table.tpl', text('txt_incomplete_testing'));
  }
}
