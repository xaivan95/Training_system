<?php

/**
 * @see module_base
 */
class module_report_themes extends module_base
{
  function view()
  {
    global $WEB_APP;

    $user_id = get_user_id($_SESSION['user_login']);
    $groups = get_groups('group_name', 'ASC', 1, 0, NULL, $user_id);
    $tests = get_tests('test_name', 'ASC');

    $show_form = TRUE;
    $show_table = FALSE;
    global $user_result_themes;


    if (!isset($_GET['action'])) {
      $_GET['action'] = '';
    }

    if ($_GET['action'] == 'print') {
      if (!isset($_SESSION['report_themes']['last_result']) || !isset($_SESSION['report_themes']['group_array']) ||
        !isset($_SESSION['report_themes']['test']) || !isset($_SESSION['report_themes']['testing_period']) ||
        !isset($_SESSION['report_themes']['testing_period_from']) ||
        !isset($_SESSION['report_themes']['testing_period_to']) || !isset($_SESSION['report_themes']['scores']) ||
        !isset($_SESSION['report_themes']['scores_from']) || !isset($_SESSION['report_themes']['scores_to']) ||
        !isset($_SESSION['report_themes']['hide_header'])) {
        header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_themes');
        exit();
      }

      $user_results =
        get_groups_report($_SESSION['report_themes']['group_array'], $_SESSION['report_themes']['test_array'],
          $_SESSION['report_themes']['last_result'], $_SESSION['report_themes']['best_result'],
          $_SESSION['report_themes']['testing_period'], $_SESSION['report_themes']['testing_period_from'],
          $_SESSION['report_themes']['testing_period_to'], $_SESSION['report_themes']['scores'],
          $_SESSION['report_themes']['scores_from'], $_SESSION['report_themes']['scores_to'],
          $_SESSION['report_themes']['testing_time_column'], $WEB_APP['sort_field'], $WEB_APP['sort_order'],
          $WEB_APP['page'], $WEB_APP['count'], $_SESSION['report_themes']['average']);
      $show_form = !$_SESSION['report_themes']['hide_header'];
      $show_table = TRUE;
      $values = $this->get_values($user_results);
    }

    if ($_GET['action'] == 'view') {
      if (!isset($_SESSION['report_themes']['last_result']) || !isset($_SESSION['report_themes']['group_array']) ||
        !isset($_SESSION['report_themes']['test']) || !isset($_SESSION['report_themes']['testing_period']) ||
        !isset($_SESSION['report_themes']['testing_period_from']) ||
        !isset($_SESSION['report_themes']['testing_period_to']) || !isset($_SESSION['report_themes']['scores']) ||
        !isset($_SESSION['report_themes']['scores_from']) || !isset($_SESSION['report_themes']['scores_to']) ||
        !isset($_SESSION['report_themes']['hide_header'])) {
        header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_themes');
        exit();
      }

      $WEB_APP['items_count'] =get_user_results_count_for_groups_report($_SESSION['report_themes']['group_array'],
        $_SESSION['report_themes']['test_array'], $_SESSION['report_themes']['last_result'],
        $_SESSION['report_themes']['best_result'], $_SESSION['report_themes']['testing_period'],
        $_SESSION['report_themes']['testing_period_from'], $_SESSION['report_themes']['testing_period_to'],
        $_SESSION['report_themes']['scores'], $_SESSION['report_themes']['scores_from'],
        $_SESSION['report_themes']['scores_to']);

      // Pages count.
      $pages = get_pages_count($WEB_APP['items_count'],
        ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);

      if ($WEB_APP['page'] > $pages) {
        $WEB_APP['page'] = $pages;
      }

      $user_results =
        get_groups_report($_SESSION['report_themes']['group_array'], array($_SESSION['report_themes']['test']),
          $_SESSION['report_themes']['last_result'], $_SESSION['report_themes']['testing_period'],
          $_SESSION['report_themes']['testing_period_from'], $_SESSION['report_themes']['testing_period_to'],
          $_SESSION['report_themes']['scores'], $_SESSION['report_themes']['scores_from'],
          $_SESSION['report_themes']['scores_to'], $_SESSION['report_themes']['testing_time_column'],
          $WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count']);

      $show_form = !$_SESSION['report_themes']['hide_header'];
      $show_table = TRUE;
      $values = $this->get_values($user_results);
    }

    if (isset($_POST['scores_from'])) {
      $correct_post = TRUE;

      if (!isset($_POST['group'])) {
        $WEB_APP['errorstext'] .= text('txt_select_groups') . '<br>';
        $select_groups = array();
        $correct_post = FALSE;
      } else {
        $_SESSION['report_themes']['group_array'] = $_POST['group'];
        $select_groups = array();
        foreach ($_SESSION['report_themes']['group_array'] as $group_id) {
          $group = get_group($group_id);
          $select_groups[] = $group->name;
        }
      }

      if (!isset($_POST['test'])) {
        $WEB_APP['errorstext'] .= text('txt_select_test') . '<br>';
        $select_test = '';
        $correct_post = FALSE;
      } else {
        $_SESSION['report_themes']['test'] = $_POST['test'];
        $test = get_test($_SESSION['report_themes']['test']);
        $select_test = $test->name;
      }

      $_SESSION['report_themes']['last_result'] = isset($_POST['last_result']);
      $_SESSION['report_themes']['hide_header'] = isset($_POST['hide_header']);
      $_SESSION['report_themes']['generate_csv_file'] = isset($_POST['generate_csv_file']);

      // Testing period
      $_SESSION['report_themes']['testing_period'] = isset($_POST['testing_period']);
      $_SESSION['report_themes']['testing_period_from'] = $_POST['testing_period_from'];
      $_SESSION['report_themes']['testing_period_to'] = $_POST['testing_period_to'];

      // Scores
      $_SESSION['report_themes']['scores'] = isset($_POST['scores']);
      $_SESSION['report_themes']['scores_from'] = $_POST['scores_from'];
      $_SESSION['report_themes']['scores_to'] = $_POST['scores_to'];

      // Columns
      $_SESSION['report_themes']['number_column'] = isset($_POST['number_column']);
      $_SESSION['report_themes']['group_column'] = isset($_POST['group_column']);
      $_SESSION['report_themes']['test_column'] = isset($_POST['test_column']);
      $_SESSION['report_themes']['scores_column'] = isset($_POST['scores_column']);
      $_SESSION['report_themes']['results_column'] = isset($_POST['results_column']);
      $_SESSION['report_themes']['correct_column'] = isset($_POST['correct_column']);
      $_SESSION['report_themes']['testing_date_column'] = isset($_POST['testing_date_column']);
      $_SESSION['report_themes']['testing_time_column'] = isset($_POST['testing_time_column']);
      $_SESSION['report_themes']['percent_column'] = isset($_POST['percent_column']);
      $_SESSION['report_themes']['completed_questions_column'] = isset($_POST['completed_questions_column']);

      if ($correct_post) {
        $WEB_APP['items_count'] =get_user_results_count_for_groups_report($_SESSION['report_themes']['group_array'],
          $_SESSION['report_themes']['test_array'], $_SESSION['report_themes']['last_result'],
          $_SESSION['report_themes']['best_result'], $_SESSION['report_themes']['testing_period'],
          $_SESSION['report_themes']['testing_period_from'], $_SESSION['report_themes']['testing_period_to'],
          $_SESSION['report_themes']['scores'], $_SESSION['report_themes']['scores_from'],
          $_SESSION['report_themes']['scores_to']);

        // Pages count.
        $pages = get_pages_count($WEB_APP['items_count'],
          ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
        if ($WEB_APP['page'] > $pages) {
          $WEB_APP['page'] = $pages;
        }
        if ($_SESSION['report_themes']['generate_csv_file']) {
          $user_results =
            get_groups_report($_SESSION['report_themes']['group_array'], $_SESSION['report_themes']['test_array'],
              $_SESSION['report_themes']['last_result'], $_SESSION['report_themes']['best_result'],
              $_SESSION['report_themes']['testing_period'], $_SESSION['report_themes']['testing_period_from'],
              $_SESSION['report_themes']['testing_period_to'], $_SESSION['report_themes']['scores'],
              $_SESSION['report_themes']['scores_from'], $_SESSION['report_themes']['scores_to'],
              $_SESSION['report_themes']['testing_time_column'], $WEB_APP['sort_field'], $WEB_APP['sort_order'],
              $WEB_APP['page'], $WEB_APP['count'], $_SESSION['report_themes']['average']);
        } else {
          $user_results =
            get_groups_report($_SESSION['report_themes']['group_array'], array($_SESSION['report_themes']['test']),
              $_SESSION['report_themes']['last_result'], $_SESSION['report_themes']['testing_period'],
              $_SESSION['report_themes']['testing_period_from'], $_SESSION['report_themes']['testing_period_to'],
              $_SESSION['report_themes']['scores'], $_SESSION['report_themes']['scores_from'],
              $_SESSION['report_themes']['scores_to'], $_SESSION['report_themes']['testing_time_column'],
              $WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count']);
        }

        $show_form = !$_SESSION['report_themes']['hide_header'];
        $show_table = TRUE;

        $values = $this->get_values($user_results);

        if ($_SESSION['report_themes']['generate_csv_file']) {
          $this->generate_csv_file($user_result_themes, $values);
        }
      }
    } else {
      if (!isset($_SESSION['report_themes']['group_array'])) {
        $_SESSION['report_themes']['group_array'] = array();
      }

      $select_groups = array();
      foreach ($_SESSION['report_themes']['group_array'] as $group_id) {
        $group = get_group($group_id);
        $select_groups[] = $group->name;
      }

      if (!isset($_SESSION['report_themes']['test_array'])) {
        $_SESSION['report_themes']['test_array'] = array();
      }
      $select_tests = array();
      foreach ($_SESSION['report_themes']['test_array'] as $test_id) {
        $test = get_test($test_id);
        $select_tests[] = $test->name;
      }

      if (!isset($_SESSION['report_themes']['last_result'])) {
        $_SESSION['report_themes']['last_result'] = FALSE;
      }

      if (!isset($_SESSION['report_themes']['hide_header'])) {
        $_SESSION['report_themes']['hide_header'] = FALSE;
      }

      if (!isset($_SESSION['report_themes']['generate_csv_file'])) {
        $_SESSION['report_themes']['generate_csv_file'] = FALSE;
      }

      // Testing period
      if (!isset($_SESSION['report_themes']['testing_period'])) {
        $_SESSION['report_themes']['testing_period'] = FALSE;
      }

      if (!isset($_SESSION['report_themes']['testing_period_from'])) {
        $_SESSION['report_themes']['testing_period_from'] = date('Y-m-d');
      }

      if (!isset($_SESSION['report_themes']['testing_period_to'])) {
        $_SESSION['report_themes']['testing_period_to'] = date('Y-m-d');
      }

      //Scores
      if (!isset($_SESSION['report_themes']['scores'])) {
        $_SESSION['report_themes']['scores'] = FALSE;
      }

      if (!isset($_SESSION['report_themes']['scores_from'])) {
        $_SESSION['report_themes']['scores_from'] = 0;
      }

      if (!isset($_SESSION['report_themes']['scores_to'])) {
        $_SESSION['report_themes']['scores_to'] = 1000;
      }

      // Columns
      if (!isset($_SESSION['report_themes']['number_column'])) {
        $_SESSION['report_themes']['number_column'] = TRUE;
      }

      if (!isset($_SESSION['report_themes']['group_column'])) {
        $_SESSION['report_themes']['group_column'] = TRUE;
      }

      if (!isset($_SESSION['report_themes']['test_column'])) {
        $_SESSION['report_themes']['test_column'] = TRUE;
      }

      if (!isset($_SESSION['report_themes']['scores_column'])) {
        $_SESSION['report_themes']['scores_column'] = TRUE;
      }

      if (!isset($_SESSION['report_themes']['results_column'])) {
        $_SESSION['report_themes']['results_column'] = TRUE;
      }


      if (!isset($_SESSION['report_themes']['correct_column'])) {
        $_SESSION['report_themes']['correct_column'] = TRUE;
      }

      if (!isset($_SESSION['report_themes']['testing_date_column'])) {
        $_SESSION['report_themes']['testing_date_column'] = TRUE;
      }

      if (!isset($_SESSION['report_themes']['testing_time_column'])) {
        $_SESSION['report_themes']['testing_time_column'] = TRUE;
      }

      if (!isset($_SESSION['report_themes']['percent_column'])) {
        $_SESSION['report_themes']['percent_column'] = TRUE;
      }

      if (!isset($_SESSION['report_themes']['completed_questions_column'])) {
        $_SESSION['report_themes']['completed_questions_column'] = TRUE;
      }
    }

    // Form fields.
    if (!isset($select_test)) {
      $select_test = '';
    }

    $WEB_APP['fields'] = $this->build_form($groups, $tests, $select_groups, $select_test);


    $columns = array();


    if ($_SESSION['report_themes']['number_column']) {
      $columns[] = new column('id', 'id');
    }

    if ($_SESSION['report_themes']['group_column']) {
      $columns[] = new column('group_name', text('txt_group'));
    }

    $columns[] = new column('user_name', text('txt_user_name'));

    if ($_SESSION['report_themes']['test_column']) {
      $columns[] = new column('user_result_test_title', text('txt_test'));
    }

    if ($_SESSION['report_themes']['scores_column']) {
      $columns[] = new column('user_result_score', text('txt_score'));
    }

    if ($_SESSION['report_themes']['results_column']) {
      $columns[] = new column('user_result_results', text('txt_report_results'));
    }

    if ($_SESSION['report_themes']['correct_column']) {
      $columns[] = new column('user_result_righ_questions', text('txt_corrects'));
    }

    if ($_SESSION['report_themes']['testing_date_column']) {
      $columns[] = new column('time_begin', text('txt_date'));
    }

    if ($_SESSION['report_themes']['testing_time_column']) {
      $columns[] = new column('test_time', text('txt_time'));
    }

    if ($_SESSION['report_themes']['percent_column']) {
      $columns[] = new column('user_result_percent_right', text('txt_report_percent'));
    }

    if ($_SESSION['report_themes']['completed_questions_column']) {
      $columns[] = new column('user_result_completed_questions', text('txt_report_completed_questions'));
    }


    $keys = array();
    if (!isset($user_result_themes)) {
      $user_result_themes = array();
    }
    foreach ($user_result_themes as $user_result_theme) {
      $key = $user_result_theme['user_result_themes_theme_caption'];
      if (!in_array($key, $keys)) {
        $keys[] = $key;
      }
    }

    foreach ($keys as $key) {
      $columns[] = new column($key, $key);
    }

    // Create paginator.
    if (!isset($pages)) {
      $pages = 1;
    }


    $paginator = new paginator($WEB_APP['page'], $pages);
    $paginator->url = $WEB_APP['script_name'];
    $paginator->url_query_array = $WEB_APP['url_query_array'];

    if (!isset($values)) {
      $values = array();
    }

    $WEB_APP['row_actions'] =
      array($WEB_APP['action_details'], $WEB_APP['action_download'], $WEB_APP['action_print_report']);

    //$WEB_APP['editform'] = FALSE;
    $WEB_APP['paginator'] = $paginator;
    $WEB_APP['hide_edit'] = 1;
    $WEB_APP['hide_delete'] = 1;
    $WEB_APP['rows'] = $values;
    $WEB_APP['rows_count'] = count($values);
    $WEB_APP['columns_count'] = count($columns);
    $WEB_APP['columns'] = $columns;
    $WEB_APP['title_add'] = text('txt_report_settings');
    $WEB_APP['submit_title'] = text('txt_create');
    $WEB_APP['show_empty_value'] = FALSE;
    $WEB_APP['show_form'] = $show_form;
    $WEB_APP['show_table'] = $show_table;
    $WEB_APP['escape'] = FALSE;
    $WEB_APP['editform'] = TRUE;
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';

    if ($_GET['action'] == 'print') {
      $WEB_APP['view']->display('print_array_table.tpl', text('txt_themes_report'), TRUE);
    } else {
      $WEB_APP['print_url'] =
        $WEB_APP['cfg_url'] . '?module=report_themes&action=print&sort=' . $WEB_APP['sort_field'] . '&order=' .
        $WEB_APP['sort_order'] . '&page=' . $WEB_APP['page'];

      $WEB_APP['view']->display('table_array_rows.tpl', text('txt_themes_report'));
    }

  }

  function get_values($user_results)
  {
    if (!$user_results) {
      return array();
    }

    $user_results_id = array();
    foreach ($user_results as $user_result_tmp) {
      $user_results_id[] = $user_result_tmp['id'];
    }

    global $user_result_themes;
    $user_result_themes = get_user_result_themes_for_user_result_id_array($user_results_id);
    $values = array();
    foreach ($user_results as $user_result_tmp) {
      $values[$user_result_tmp['id']] = array();
      foreach ($user_result_tmp as $key => $value) {
        $values[$user_result_tmp['id']][$key] = $value;
      }
    }
    foreach ($user_result_themes as $user_result_theme) {
      $values[$user_result_theme['user_result_themes_user_result_id']][$user_result_theme['user_result_themes_theme_caption']] =
        $user_result_theme['user_result_themes_result'];
    }

    return $values;
  }

  function generate_csv_file($user_result_themes, $values)
  {
    global $WEB_APP;

    if (!isset($_SESSION['report_themes']['last_result']) || !isset($_SESSION['report_themes']['group_array']) ||
      !isset($_SESSION['report_themes']['test']) || !isset($_SESSION['report_themes']['testing_period']) ||
      !isset($_SESSION['report_themes']['testing_period_from']) ||
      !isset($_SESSION['report_themes']['testing_period_to']) || !isset($_SESSION['report_themes']['scores']) ||
      !isset($_SESSION['report_themes']['scores_from']) || !isset($_SESSION['report_themes']['scores_to'])) {
      header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_themes');
      exit();
    }

    // Generate csv file.
    $report_text = '';

    // Column names.
    if ($_SESSION['report_themes']['number_column']) {
      $report_text .= 'id;';
    }

    if ($_SESSION['report_themes']['group_column']) {
      $report_text .= text('txt_group') . ';';
    }

    $report_text .= text('txt_name') . ';';

    if ($_SESSION['report_themes']['test_column']) {
      $report_text .= text('txt_test') . ';';
    }

    if ($_SESSION['report_themes']['scores_column']) {
      $report_text .= text('txt_score') . ';';
    }

    if ($_SESSION['report_themes']['results_column']) {
      $report_text .= text('txt_report_results') . ';';
    }

    if ($_SESSION['report_themes']['correct_column']) {
      $report_text .= text('txt_corrects') . ';';
    }

    if ($_SESSION['report_themes']['testing_date_column']) {
      $report_text .= text('txt_date') . ';';
    }

    if ($_SESSION['report_themes']['testing_time_column']) {
      $report_text .= text('txt_time') . ';';
    }

    if ($_SESSION['report_themes']['percent_column']) {
      $report_text .= text('txt_report_percent') . ';';
    }

    if ($_SESSION['report_themes']['completed_questions_column']) {
      $report_text .= text('txt_report_completed_questions') . ';';
    }
    $keys = array();
    foreach ($user_result_themes as $user_result_theme) {
      $key = $user_result_theme['user_result_themes_theme_caption'];
      if (!in_array($key, $keys)) {
        $keys[] = $key;
      }
    }

    foreach ($keys as $key) {
      $report_text .= $key . ';';
    }

    $report_text .= "\r\n";

    foreach ($values as $value) {
      if ($_SESSION['report_themes']['number_column']) {
        $report_text .= $value['id'] . ';';
      }

      if ($_SESSION['report_themes']['group_column']) {
        $report_text .= $value['group_name'] . ';';
      }

      $report_text .= $value['user_name'] . ';';

      if ($_SESSION['report_themes']['test_column']) {
        $report_text .= $value['user_result_test_title'] . ';';
      }

      if ($_SESSION['report_themes']['scores_column']) {
        $report_text .= $value['user_result_score'] . ';';
      }

      if ($_SESSION['report_themes']['results_column']) {
        $report_text .= $value['user_result_results'] . ';';
      }

      if ($_SESSION['report_themes']['correct_column']) {
        $report_text .= $value['user_result_righ_questions'] . ';';
      }

      if ($_SESSION['report_themes']['testing_date_column']) {
        $report_text .= $value['time_begin'] . ';';
      }

      if ($_SESSION['report_themes']['testing_time_column']) {
        $report_text .= $value['test_time'] . ';';
      }

      if ($_SESSION['report_themes']['percent_column']) {
        $report_text .= $value['user_result_percent_right'] . ';';
      }

      if ($_SESSION['report_themes']['completed_questions_column']) {
        $report_text .= $value['user_result_completed_questions'] . ';';
      }

      foreach ($keys as $key) {
        $report_text .= ((isset($value[$key])) ? $value[$key] : ' ') . ';';
      }

      $report_text .= "\r\n";
    }


    $csv_file_charset = $WEB_APP['settings']['csv_file_charset'];
    if ($csv_file_charset != 'utf-8') {
      $report_text = @iconv('utf-8', $csv_file_charset, $report_text);
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
      $_SESSION['report_themes']['last_result'], 'last_result');
    $fields[] =
      new field(FALSE, text('txt_hide_header'), 'checkbox', 'hide_header', $_SESSION['report_themes']['hide_header'],
        'hide_header');
    $fields[] = new field(FALSE, text('txt_generate_csv_file'), 'checkbox', 'generate_csv_file',
      $_SESSION['report_themes']['generate_csv_file'], 'generate_csv_file');

    $fields[] = new field(FALSE, text('txt_testing_period'), 'header');
    $fields[] = new field(FALSE, text('txt_from'), 'date', 'testing_period_from',
      $_SESSION['report_themes']['testing_period_from']);
    $fields[] =
      new field(FALSE, text('txt_to'), 'date', 'testing_period_to', $_SESSION['report_themes']['testing_period_to']);
    $fields[] = new field(FALSE, text('txt_testing_period'), 'checkbox', 'testing_period',
      $_SESSION['report_themes']['testing_period'], 'testing_period');

    $fields[] = new field(FALSE, text('txt_scores'), 'header');
    $fields[] = new field(FALSE, text('txt_from'), 'number', 'scores_from', $_SESSION['report_themes']['scores_from']);
    $fields[] = new field(FALSE, text('txt_to'), 'number', 'scores_to', $_SESSION['report_themes']['scores_to']);
    $fields[] =
      new field(FALSE, text('txt_scores'), 'checkbox', 'scores', $_SESSION['report_themes']['scores'], 'scores');

    $fields[] = new field(FALSE, text('txt_columns'), 'header');
    $fields[] =
      new field(FALSE, text('txt_number'), 'checkbox', 'number_column', $_SESSION['report_themes']['number_column'],
        'number_column');
    $fields[] =
      new field(FALSE, text('txt_group'), 'checkbox', 'group_column', $_SESSION['report_themes']['group_column'],
        'group_column');
    $fields[] = new field(FALSE, text('txt_test'), 'checkbox', 'test_column', $_SESSION['report_themes']['test_column'],
      'test_column');
    $fields[] =
      new field(FALSE, text('txt_scores'), 'checkbox', 'scores_column', $_SESSION['report_themes']['scores_column'],
        'scores_column');
    $fields[] =
      new field(FALSE, text('txt_results'), 'checkbox', 'results_column', $_SESSION['report_themes']['results_column'],
        'results_column');
    $fields[] = new field(FALSE, text('txt_number_of_correct_answers'), 'checkbox', 'correct_column',
      $_SESSION['report_themes']['correct_column'], 'correct_column');
    $fields[] = new field(FALSE, text('txt_testing_date'), 'checkbox', 'testing_date_column',
      $_SESSION['report_themes']['testing_date_column'], 'testing_date_column');
    $fields[] = new field(FALSE, text('txt_testing_time'), 'checkbox', 'testing_time_column',
      $_SESSION['report_themes']['testing_time_column'], 'testing_time_column');
    $fields[] =
      new field(FALSE, text('txt_percent'), 'checkbox', 'percent_column', $_SESSION['report_themes']['percent_column'],
        'percent_column');
    $fields[] = new field(FALSE, text('txt_completed_questions'), 'checkbox', 'completed_questions_column',
      $_SESSION['report_themes']['completed_questions_column'], 'completed_questions_column');

    return $fields;
  }

  function details()
  {
    global $WEB_APP;
    header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_user_results&action=details&id=' . $WEB_APP['id']);
    exit();
  }

  function download()
  {
    global $WEB_APP;
    header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_user_results&action=download&id=' . $WEB_APP['id']);
    exit();
  }

  /** @noinspection PhpUnused */
  function print_report()
  {
    global $WEB_APP;
    header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_user_results&action=print_report&id=' . $WEB_APP['id']);
    exit();
  }
}

