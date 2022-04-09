<?php

/**
 * @see module_base
 */
class module_misapplication extends module_base
{
  function view()
  {
    global $WEB_APP;
    $groups = get_groups('group_name', 'ASC', 1, 0, NULL, $_SESSION['user_id']);
    $select_groups = array();
    $misapplications = array();
    $WEB_APP['rows_count'] = 0;

    if (isset($_POST['group'])) $_SESSION['misapplication']['group_array'] = $_POST['group'];
    foreach ($_SESSION['misapplication']['group_array'] as $group_id) {
      $group = get_group($group_id);
      $select_groups[] = $group->name;
    }

    if (isset($_POST['submit_button'])) {
      $date_from = $_POST['date_from'];
      $date_to = $_POST['date_to'];

      $WEB_APP['items_count'] =
        get_misapplication_count($_SESSION['misapplication']['group_array'], $date_from, $date_to);

      $pages = get_pages_count($WEB_APP['items_count'],
        ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
      if ($WEB_APP['page'] > $pages) $WEB_APP['page'] = $pages;

      if ($WEB_APP['items_count'] > 0) $misapplications =
        get_misapplication($_SESSION['misapplication']['group_array'], $date_from, $date_to);

      $columns = array();
      $columns[] = new column('id', 'id');
      $columns[] = new column('group_name', text('txt_group'));
      $columns[] = new column('user_name', text('txt_user_name'));
      $columns[] = new column('user_result_test_title', text('txt_test'));
      $columns[] = new column('user_result_time_begin', text('txt_begin'));
      $columns[] = new column('user_result_time_end', text('txt_time_end'));
      $columns[] = new column('user_result_ip', 'IP');


      $WEB_APP['show_form'] = TRUE;
      $WEB_APP['show_table'] = TRUE;
      $paginator = new paginator($WEB_APP['page'], $pages);
      $paginator->url = $WEB_APP['script_name'];
      $paginator->url_query_array = $WEB_APP['url_query_array'];
      $WEB_APP['paginator'] = $paginator;
      $WEB_APP['rows'] = $misapplications;
      $WEB_APP['rows_count'] = count($misapplications);
      $WEB_APP['columns_count'] = count($columns);
      $WEB_APP['columns'] = $columns;

    } else {
      $date_from = date("Y-m-d");
      $date_to = $date_from;
    }

    $_SESSION['misapplication']['date_from'] = $date_from;
    $_SESSION['misapplication']['date_to'] = $date_to;
    $fields = array();
    $fields[] = new field(FALSE, text('txt_from'), "date", "date_from", $date_from);
    $fields[] = new field(FALSE, text('txt_to'), "date", "date_to", $date_to);
    $fields[] =
      new field(TRUE, text('txt_groups'), 'multiple_select', 'group[]', $select_groups, '', $groups, 'id', 'group_name',
        null, FALSE, '', '', 'data-live-search="true"');
    $WEB_APP['fields'] = $fields;
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['submit_title'] = text('txt_create');
   // $WEB_APP['editform'] = TRUE;
    $WEB_APP['show_form'] = TRUE;
    $WEB_APP['show_table'] = $WEB_APP['rows_count'] > 0;
    $WEB_APP['view']->display('table_array_rows.tpl', text('txt_check_misapplication'));
  }
}