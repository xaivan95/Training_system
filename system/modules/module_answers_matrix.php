<?php

/**
 * @see module_base
 */
class module_answers_matrix extends module_base
{
  function view()
  {
    global $WEB_APP;
    $user_id = get_user_id($_SESSION['user_login']);
    $groups = get_groups('group_name', 'ASC', 1, 0, NULL, $user_id);
    $tests = get_tests('test_name', 'ASC');

    $WEB_APP['show_form'] = TRUE;
    $WEB_APP['show_table'] = FALSE;

    if (!isset($_GET['action'])) {
      $_GET['action'] = '';
    }

    if ($_GET['action'] == 'print') {
      if (!isset($_SESSION['answer_matrix']['hide_header']) || !isset($_SESSION['answer_matrix']['group_array']) ||
        !isset($_SESSION['answer_matrix']['test']) || !isset($_SESSION['answer_matrix']['testing_period']) ||
        !isset($_SESSION['answer_matrix']['testing_period_from']) ||
        !isset($_SESSION['answer_matrix']['testing_period_to']) || !isset($_SESSION['answer_matrix']['scores']) ||
        !isset($_SESSION['answer_matrix']['scores_from']) || !isset($_SESSION['answer_matrix']['scores_to'])) {
        header('Location: ' . $WEB_APP['cfg_url'] . '?module=answers_matrix');
        exit();
      }

      $user_results =
        get_answers_matrix($_SESSION['answer_matrix']['group_array'], array($_SESSION['answer_matrix']['test']),
          $_SESSION['answer_matrix']['last_result'], $_SESSION['answer_matrix']['testing_period'],
          $_SESSION['answer_matrix']['testing_period_from'], $_SESSION['answer_matrix']['testing_period_to'],
          $_SESSION['answer_matrix']['scores'], $_SESSION['answer_matrix']['scores_from'],
          $_SESSION['answer_matrix']['scores_to']);
      $columns = array();

      $columns[] = new column('id', 'id');
      $columns[] = new column(0, text('txt_user_name'));

      $answers_count = 0;
      foreach ($user_results as $user_result) {
        $answers_count = max($answers_count, count($user_result) - 2);
      }

      for ($i = 0; $i < $answers_count; $i++) {
        $columns[] = new column(1 + $i, $i + 1);
      }

      $WEB_APP['columns'] = $columns;
      $WEB_APP['rows'] = $user_results;
      $WEB_APP['rows_count'] = count($user_results);
      $WEB_APP['columns_count'] = count($columns);

    }

    if ($_GET['action'] == 'report') {
      if (!isset($_SESSION['answer_matrix']['hide_header']) || !isset($_SESSION['answer_matrix']['group_array']) ||
        !isset($_SESSION['answer_matrix']['test']) || !isset($_SESSION['answer_matrix']['testing_period']) ||
        !isset($_SESSION['answer_matrix']['testing_period_from']) ||
        !isset($_SESSION['answer_matrix']['testing_period_to']) || !isset($_SESSION['answer_matrix']['scores']) ||
        !isset($_SESSION['answer_matrix']['scores_from']) || !isset($_SESSION['answer_matrix']['scores_to'])) {
        header('Location: ' . $WEB_APP['cfg_url'] . '?module=answers_matrix');
        exit();
      }

      $WEB_APP['items_count'] = get_user_results_count_for_groups_report($_SESSION['answer_matrix']['group_array'],
        array($_SESSION['answer_matrix']['test']), $_SESSION['answer_matrix']['last_result'],
        $_SESSION['answer_matrix']['testing_period'], $_SESSION['answer_matrix']['testing_period_from'],
        $_SESSION['answer_matrix']['testing_period_to'], $_SESSION['answer_matrix']['scores'],
        $_SESSION['answer_matrix']['scores_from'], $_SESSION['answer_matrix']['scores_to']);


      $user_results =
        get_answers_matrix($_SESSION['answer_matrix']['group_array'], array($_SESSION['answer_matrix']['test']),
          $_SESSION['answer_matrix']['last_result'], $_SESSION['answer_matrix']['testing_period'],
          $_SESSION['answer_matrix']['testing_period_from'], $_SESSION['answer_matrix']['testing_period_to'],
          $_SESSION['answer_matrix']['scores'], $_SESSION['answer_matrix']['scores_from'],
          $_SESSION['answer_matrix']['scores_to']);
      $WEB_APP['show_form'] = !$_SESSION['answer_matrix']['hide_header'];
      $WEB_APP['show_table'] = TRUE;
      $columns = array();

      $columns[] = new column('id', 'id');
      $columns[] = new column(0, text('txt_user_name'));

      $answers_count = 0;
      foreach ($user_results as $user_result) {
        $answers_count = max($answers_count, count($user_result) - 2);
      }

      for ($i = 0; $i < $answers_count; $i++) {
        $columns[] = new column(1 + $i, $i + 1);
      }

      $WEB_APP['columns'] = $columns;
      $WEB_APP['rows'] = $user_results;
      $WEB_APP['rows_count'] = count($user_results);
      $WEB_APP['columns_count'] = count($columns);
      //$values = $this->get_values($user_results);
    }

    if (isset($_POST['scores_from'])) {
      $correct_post = TRUE;

      if (!isset($_POST['group'])) {
        $WEB_APP['errorstext'] .= text('txt_select_groups') . '<br>';
        $select_groups = array();
        $correct_post = FALSE;
      } else {
        $_SESSION['answer_matrix']['group_array'] = $_POST['group'];
        $select_groups = array();
        foreach ($_SESSION['answer_matrix']['group_array'] as $group_id) {
          $group = get_group($group_id);
          $select_groups[] = $group->name;
        }
      }

      if (!isset($_POST['test'])) {
        $WEB_APP['errorstext'] .= text('txt_select_test') . '<br>';
        $select_test = '';
        $correct_post = FALSE;
      } else {
        $_SESSION['answer_matrix']['test'] = $_POST['test'];
        $test = get_test($_SESSION['answer_matrix']['test']);
        $select_test = $test->name;
      }

      $_SESSION['answer_matrix']['last_result'] = isset($_POST['last_result']);
      $_SESSION['answer_matrix']['hide_header'] = isset($_POST['hide_header']);
      $_SESSION['answer_matrix']['generate_csv_file'] = isset($_POST['generate_csv_file']);

      // Testing period
      $_SESSION['answer_matrix']['testing_period'] = isset($_POST['testing_period']);
      $_SESSION['answer_matrix']['testing_period_from'] = $_POST['testing_period_from'];
      $_SESSION['answer_matrix']['testing_period_to'] = $_POST['testing_period_to'];

      // Scores
      $_SESSION['answer_matrix']['scores'] = isset($_POST['scores']);
      $_SESSION['answer_matrix']['scores_from'] = $_POST['scores_from'];
      $_SESSION['answer_matrix']['scores_to'] = $_POST['scores_to'];


      if ($correct_post) {
        if ($_SESSION['answer_matrix']['generate_csv_file']) {
          $this->generate_csv_file();
        } else {
          header('Location: ' . $WEB_APP['cfg_url'] . '?module=answers_matrix&action=report');
        }
        exit();
      }
    } else {
      if (!isset($_SESSION['answer_matrix']['group_array'])) {
        $_SESSION['answer_matrix']['group_array'] = array();
      }

      $select_groups = array();
      foreach ($_SESSION['answer_matrix']['group_array'] as $group_id) {
        $group = get_group($group_id);
        $select_groups[] = $group->name;
      }

      if (!isset($_SESSION['answer_matrix']['test'])) {
        $_SESSION['answer_matrix']['test'] = 0;
      }

      $test = get_test($_SESSION['answer_matrix']['test']);
      $select_test = $test->name;

      if (!isset($_SESSION['answer_matrix']['last_result'])) {
        $_SESSION['answer_matrix']['last_result'] = FALSE;
      }

      if (!isset($_SESSION['answer_matrix']['hide_header'])) {
        $_SESSION['answer_matrix']['hide_header'] = FALSE;
      }

      if (!isset($_SESSION['answer_matrix']['generate_csv_file'])) {
        $_SESSION['answer_matrix']['generate_csv_file'] = FALSE;
      }

      // Testing period
      if (!isset($_SESSION['answer_matrix']['testing_period'])) {
        $_SESSION['answer_matrix']['testing_period'] = FALSE;
      }

      if (!isset($_SESSION['answer_matrix']['testing_period_from'])) {
        $_SESSION['answer_matrix']['testing_period_from'] = date('Y-m-d');
      }

      if (!isset($_SESSION['answer_matrix']['testing_period_to'])) {
        $_SESSION['answer_matrix']['testing_period_to'] = date('Y-m-d');
      }

      //Scores
      if (!isset($_SESSION['answer_matrix']['scores'])) {
        $_SESSION['answer_matrix']['scores'] = FALSE;
      }

      if (!isset($_SESSION['answer_matrix']['scores_from'])) {
        $_SESSION['answer_matrix']['scores_from'] = 0;
      }

      if (!isset($_SESSION['answer_matrix']['scores_to'])) {
        $_SESSION['answer_matrix']['scores_to'] = 1000;
      }
    }

    // Form fields.
    if (!isset($select_test)) {
      $select_test = '';
    }

    $WEB_APP['fields'] = $this->build_form($groups, $tests, $select_groups, $select_test);

    // Create paginator.
    if (!isset($pages)) {
      $pages = 1;
    }

    $paginator = new paginator($WEB_APP['page'], $pages);
    $paginator->url = $WEB_APP['script_name'];
    $paginator->url_query_array = $WEB_APP['url_query_array'];

    $WEB_APP['editform'] = FALSE;
    $WEB_APP['paginator'] = $paginator;
    $WEB_APP['hide_edit'] = 1;
    $WEB_APP['hide_delete'] = 1;
    $WEB_APP['title_add'] = text('txt_report_settings');
    $WEB_APP['submit_title'] = text('txt_create');
    $WEB_APP['show_empty_value'] = FALSE;
    $WEB_APP['escape'] = FALSE;
    $WEB_APP['title'] = text('txt_answers_matrix');
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';

    if ($_GET['action'] == 'print') {
      $WEB_APP['view']->display('print_array_table.tpl', text('txt_answers_matrix'), TRUE);
    } else {
      $WEB_APP['print_url'] = $WEB_APP['cfg_url'] . '?module=answers_matrix&action=print';

      $WEB_APP['view']->display('table_array_rows.tpl', text('txt_answers_matrix'));
    }
  }

  function generate_csv_file()
  {
    global $WEB_APP;

    $user_results =
      get_answers_matrix($_SESSION['answer_matrix']['group_array'], array($_SESSION['answer_matrix']['test']),
        $_SESSION['answer_matrix']['last_result'], $_SESSION['answer_matrix']['testing_period'],
        $_SESSION['answer_matrix']['testing_period_from'], $_SESSION['answer_matrix']['testing_period_to'],
        $_SESSION['answer_matrix']['scores'], $_SESSION['answer_matrix']['scores_from'],
        $_SESSION['answer_matrix']['scores_to']);

    // Generate csv file.
    $report_text = 'id;';
    $report_text .= text('txt_user_id') . ';';


    $answers_count = 0;
    foreach ($user_results as $user_result) {
      $answers_count = max($answers_count, count($user_result) - 2);
    }

    for ($i = 1; $i <= $answers_count; $i++) {

      $report_text .= $i . ';';
    }

    $report_text .= "\r\n";
    foreach ($user_results as $user_result) {
      $report_text .= $user_result['id'] . ';' . $user_result[0] . ';';
      for ($i = 1; $i <= $answers_count; $i++) {
        if (isset($user_result[$i])) {
          $report_text .= $user_result[$i] . ';';
        } else {
          $report_text .= ';';
        }
      }
      $report_text .= "\r\n";
    }


    $csv_file_charset = $WEB_APP['settings']['csv_file_charset'];
    if ($csv_file_charset != 'utf-8') {
      $report_text = iconv('utf-8', $csv_file_charset, $report_text);
    }

    $len = strlen($report_text);
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=report.csv');
    header('Content-Length: ' . $len);

    print($report_text);

    exit();
  }

  function build_form($groups, $tests, $select_groups, $select_test)
  {
    $fields = array();
    $fields[] =
      new field(TRUE, text('txt_groups'), 'multiple_select', 'group[]', $select_groups, '', $groups, 'id', 'group_name',
        null, FALSE, '', '', 'data-live-search="true"');
    $fields[] =
      new field(FALSE, text('txt_test'), 'select', 'test', $select_test, '', $tests, 'id', 'test_name', null, FALSE, '',
        '', 'data-live-search="true"');
    $fields[] = new field(FALSE, text('txt_display_the_last_result_only'), 'checkbox', 'last_result',
      $_SESSION['answer_matrix']['last_result'], "last_result");
    $fields[] =
      new field(FALSE, text('txt_hide_header'), 'checkbox', 'hide_header', $_SESSION['answer_matrix']['hide_header'],
        'hide_header');
    $fields[] = new field(FALSE, text('txt_generate_csv_file'), 'checkbox', 'generate_csv_file',
      $_SESSION['answer_matrix']['generate_csv_file'], 'generate_csv_file');

    $fields[] = new field(FALSE, text('txt_testing_period'), 'header');
    $fields[] = new field(FALSE, text('txt_from'), 'date', 'testing_period_from',
      $_SESSION['answer_matrix']['testing_period_from']);
    $fields[] =
      new field(FALSE, text('txt_to'), 'date', 'testing_period_to', $_SESSION['answer_matrix']['testing_period_to']);
    $fields[] = new field(FALSE, text('txt_testing_period'), 'checkbox', 'testing_period',
      $_SESSION['answer_matrix']['testing_period'], 'testing_period');

    $fields[] = new field(FALSE, text('txt_scores'), 'header');
    $fields[] = new field(FALSE, text('txt_from'), 'number', 'scores_from', $_SESSION['answer_matrix']['scores_from']);
    $fields[] = new field(FALSE, text('txt_to'), 'number', 'scores_to', $_SESSION['answer_matrix']['scores_to']);
    $fields[] =
      new field(FALSE, text('txt_scores'), 'checkbox', 'scores', $_SESSION['answer_matrix']['scores'], 'scores');

    return $fields;
  }
}

