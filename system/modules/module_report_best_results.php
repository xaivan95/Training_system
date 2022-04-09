l<?php

/**ieldƒ
 * @see module_base
 */
class module_report_best_results extends module_base
{
  function view()
  {
    global $WEB_APP;

    $user_id = get_user_id($_SESSION['user_login']);
    $groups = get_groups('group_name', 'ASC', 1, 0, NULL, $user_id);
    $WEB_APP['show_form'] = TRUE;
    $WEB_APP['show_table'] = FALSE;
    $section = "";

    if (!isset($_GET['action'])) {
      $_GET['action'] = '';
    }

    if ((($_GET['action'] == 'report') || ($_GET['action'] == 'print')) && (count($_POST) == 0)) {
      if (!isset($_SESSION['report_best']['group_array']) || !isset($_SESSION['report_best']['test_array']) ||
        !isset($_SESSION['report_best']['testing_period_from']) ||
        !isset($_SESSION['report_best']['testing_period_to'])) {
        header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_best_results');
        exit();
      }

      if ($_SESSION['report_best']['last_result'] or $_SESSION['report_best']['best_result']) {
        $WEB_APP['items_count'] = 1;
      } else {
        $WEB_APP['items_count'] =
          get_count_for_best_results($_SESSION['report_best']['group_array'], $_SESSION['report_best']['test_array'],
            $_SESSION['report_best']['testing_period_from'], $_SESSION['report_best']['testing_period_to']);
      }

      // Pages count.
      $pages = get_pages_count($WEB_APP['items_count'],
        ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
      if ($WEB_APP['page'] > $pages) {
        $WEB_APP['page'] = $pages;
      }

      if (!$_SESSION['report_best']['generate_csv_file']) {
        $user_results =
          get_best_results_report($_SESSION['report_best']['group_array'], $_SESSION['report_best']['test_array'],
            $_SESSION['report_best']['testing_period_from'], $_SESSION['report_best']['testing_period_to'],
            $_SESSION['report_best']['average'], $WEB_APP['page'], $WEB_APP['count']);
      }
      if ($_SESSION['report_best']['generate_csv_file']) {
        $user_results =
          get_best_results_report($_SESSION['report_best']['group_array'], $_SESSION['report_best']['test_array'],
            $_SESSION['report_best']['testing_period_from'], $_SESSION['report_best']['testing_period_to'],
            $_SESSION['report_best']['average'], $WEB_APP['page'], $WEB_APP['count']);
        // Generate csv file.
        $report_text = '';

        // Column names.
        $report_text .= 'id;';
        $report_text .= text('txt_name') . ';';
        $report_text .= "\r\n";

        foreach ($user_results as $user_result) {
          $report_text .= $user_result['id'] . ';';
          $report_text .= $user_result['user_name'] . ';';
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
      $WEB_APP['show_form'] = !$_SESSION['report_best']['hide_header'];
      $WEB_APP['show_table'] = TRUE;
    }

    if (isset($_POST['submit_button'])) {
      $correct_post = TRUE;

      if (!isset($_POST['group'])) {
        $WEB_APP['errorstext'] .= text('txt_select_groups') . '<br>';
        $select_groups = array();
        $correct_post = FALSE;
      } else {
        $_SESSION['report_best']['group_array'] = $_POST['group'];
        $select_groups = array();
        foreach ($_SESSION['report_best']['group_array'] as $group_id) {
          $group = get_group($group_id);
          $select_groups[] = $group->name;
        }
      }

      if (!isset($_POST['test'])) {
        $WEB_APP['errorstext'] .= text('txt_select_tests') . '<br>';
        $select_tests = array();
        $correct_post = FALSE;
      } else {
        $_SESSION['report_best']['test_array'] = $_POST['test'];
        $select_tests = array();
        foreach ($_SESSION['report_best']['test_array'] as $test_id) {
          $test = get_test($test_id);
          $select_tests[] = $test->name;
        }
      }

      if (isset($_POST['section_id']) && is_scalar($_POST['section_id'])) {
        $section_id = $_POST['section_id'];
        $_SESSION['report_best']['section_id'] = $section_id;
        $section = get_section($section_id);
      }


      $_SESSION['report_best']['hide_header'] = isset($_POST['hide_header']);
      $_SESSION['report_best']['generate_csv_file'] = isset($_POST['generate_csv_file']);
      $_SESSION['report_best']['average'] = isset($_POST['average']);

      // Testing period
      $_SESSION['report_best']['testing_period_from'] = $_POST['testing_period_from'];
      $_SESSION['report_best']['testing_period_to'] = $_POST['testing_period_to'];

      if ($correct_post) {
        header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_best_results&action=report');
        exit();
      }
    } else {
      if (!isset($_SESSION['report_best']['group_array'])) {
        $_SESSION['report_best']['group_array'] = array();
      }

      $select_groups = array();
      foreach ($_SESSION['report_best']['group_array'] as $group_id) {
        $group = get_group($group_id);
        $select_groups[] = $group->name;
      }

      if (!isset($_SESSION['report_best']['test_array'])) {
        $_SESSION['report_best']['test_array'] = array();
      }
      $select_tests = array();
      foreach ($_SESSION['report_best']['test_array'] as $test_id) {
        $test = get_test($test_id);
        $select_tests[] = $test->name;
      }
      if (!isset($_SESSION['report_best']['hide_header'])) {
        $_SESSION['report_best']['hide_header'] = FALSE;
      }
      if (!isset($_SESSION['report_best']['generate_csv_file'])) {
        $_SESSION['report_best']['generate_csv_file'] = FALSE;
      }

      // Testing period
      if (!isset($_SESSION['report_best']['testing_period_from'])) {
        $_SESSION['report_best']['testing_period_from'] = date('Y-m-d');
      }
      if (!isset($_SESSION['report_best']['testing_period_to'])) {
        $_SESSION['report_best']['testing_period_to'] = date('Y-m-d');
      }

      // Columns
      $_SESSION['report_best']['number_column'] = TRUE;
    }
    if (defined('GROUP_REPORT_LIMITED_SECTIONS') && GROUP_REPORT_LIMITED_SECTIONS == TRUE) {
      $user_id = get_user_id($_SESSION['user_login']);
      $sections = get_unhidden_sections_for_user_id($user_id);
    } else {
      $sections = get_sections('section_name');
    }
    $tests = array();

    if (isset($_SESSION['report_best']['section_id']) && is_scalar($_SESSION['report_best']['section_id'])) {
      $tmp = get_section($_SESSION['report_best']['section_id']);
      if (isset($tmp->id)) {
        $section = $tmp->name;
        $tests = get_tests_for_section_id($tmp->id);
      } else {
        if (!defined('GROUP_REPORT_LIMITED_SECTIONS') || GROUP_REPORT_LIMITED_SECTIONS == FALSE) {
          $tests = get_tests('test_name');
        }
      }
    }

    // Form fields.
    $fields = array();
    $fields[] =
      new field(TRUE, text('txt_groups'), 'multiple_select', 'group[]', $select_groups, '', $groups, 'id', 'group_name',
        null, FALSE, '', '', 'data-live-search="true"');

    $fields[] =
      new field(TRUE, text('txt_section'), 'select', 'section_id', $section, '', $sections, 'id', 'section_name',
        'return change_section_group_report()', FALSE, '', '', 'data-live-search="true"');

    $fields[] =
      new field(TRUE, text('txt_tests'), 'multiple_select', 'test[]', $select_tests, '', $tests, 'id', 'test_name',
        null, FALSE, '', '', 'data-live-search="true"');

    $fields[] = new field(FALSE, text('txt_report_settings'), 'header');
    $fields[] =
      new field(FALSE, text('txt_average'), 'checkbox', 'average', $_SESSION['report_best']['average'], 'average');
    $fields[] =
      new field(FALSE, text('txt_hide_header'), 'checkbox', 'hide_header', $_SESSION['report_best']['hide_header'],
        "hide_header");
//    $fields[] = new field(FALSE, text('txt_generate_csv_file'), 'checkbox', 'generate_csv_file',
//      $_SESSION['report_best']['generate_csv_file'], "generate_csv_file");


    $fields[] = new field(FALSE, text('txt_testing_period'), 'header');
    $fields[] = new field(FALSE, text('txt_from'), 'date', 'testing_period_from',
      $_SESSION['report_best']['testing_period_from']);
    $fields[] =
      new field(FALSE, text('txt_to'), 'date', 'testing_period_to', $_SESSION['report_best']['testing_period_to']);

    $columns = array();
    if (isset($_GET['action']) && ($_GET['action'] == 'print')) $columns[] = new column('user_name',
      text('txt_testing_period') . ' ' . date("d-m-Y", strtotime($_SESSION['report_best']['testing_period_from'])) .
      '—' . date("d-m-Y", strtotime($_SESSION['report_best']['testing_period_to']))); else
      $columns[] = new column('user_name', text('txt_user_name'));
    $tests_count = count($select_tests);
    for ($i = 0; $i < $tests_count; $i++) {
      $columns[] = new column($select_tests[$i], $select_tests[$i]);
    }


    if ($_GET['action'] == 'report' || $_GET['action'] == 'print') {
      $paginator = new paginator($WEB_APP['page'], $pages);
      $paginator->url = $WEB_APP['script_name'];
      $paginator->url_query_array = $WEB_APP['url_query_array'];
      $WEB_APP['paginator'] = $paginator;
      $WEB_APP['rows'] = $user_results;
      $WEB_APP['rows_count'] = count($user_results);
      $WEB_APP['columns_count'] = count($columns);
      $WEB_APP['columns'] = $columns;
    }

    $WEB_APP['show_table'] = ($_GET['action'] == 'report');
    $WEB_APP['editform'] = TRUE;

    $WEB_APP['hide_delete'] = 1;
    if (isset($_SESSION['report_best']['average'])) $WEB_APP['average'] = $_SESSION['report_best']['average'];
    $WEB_APP['test_max_score'] =
      isset($_SESSION['report_best']['average']) && $_SESSION['report_best']['average'] == TRUE;
    $WEB_APP['vertical_header'] = TRUE;
    $WEB_APP['fields'] = $fields;
    $WEB_APP['title_add'] = text('txt_report_settings');
    $WEB_APP['submit_title'] = text('txt_create');
    $WEB_APP['escape'] = FALSE;
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['title'] = text('txt_group_reports');
    $WEB_APP['submit_title'] = text('txt_create');
    $WEB_APP['table_title'] = text('txt_report_created') . ' ' . date('d.m.Y');
    if (isset($_GET['action']) && ($_GET['action'] == 'print')) {
      $WEB_APP['view']->display('print_array_table.tpl', text('txt_group_reports'), TRUE);
    } else {
      $WEB_APP['print_url'] =
        $WEB_APP['cfg_url'] . '?module=report_best_results&action=print&sort=' . $WEB_APP['sort_field'] . '&order=' .
        $WEB_APP['sort_order'] . '&page=' . $WEB_APP['page'];
      if ($_GET['action'] != '') {
        $WEB_APP['action'] = $_GET['action'];
      }
      $WEB_APP['view']->display('table_array_rows.tpl', text('txt_best_results'));
    }
  }


  /** @noinspection PhpUnused */
  function print_report()
  {
    global $WEB_APP;
    header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_user_results&action=print_report&id=' . $WEB_APP['id']);
    exit();
  }

  /** @noinspection PhpUnused */
  function change_section()
  {
    $limited = defined('GROUP_REPORT_LIMITED_SECTIONS') || GROUP_REPORT_LIMITED_SECTIONS == TRUE;

    if (isset($_GET['section_id']) && is_scalar($_GET['section_id'])) {
      $section_id = (int)$_GET['section_id'];
      $_SESSION['report_best']['section_id'] = $section_id;
      if (($section_id !== 0) || !$limited) {
        $section = get_section($section_id);
        $tests = get_tests_for_section_id($section->id);
        foreach ($tests as $test) {
          printf("<option value=\"%d\">%s</option>\n", $test['id'], htmlspecialchars($test['test_name']));
        }
      }
      die();
    } else {
      if (!$limited) {
        $tests = get_tests('test_name', 'ASC');
        foreach ($tests as $test) {
          printf("<option value=\"%d\">%s</option>\n", $test['id'], htmlspecialchars($test['test_name']));
        }
        die();
      }
    }
  }
}

