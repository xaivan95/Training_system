<?php

/**ieldƒ
 * @see module_base
 */
class module_report_groups extends module_base
{
  function view()
  {
    global $WEB_APP;

    $user_id = get_user_id($_SESSION['user_login']);
    $groups = get_groups('group_name', 'ASC', 1, 0, NULL, $user_id);
    $WEB_APP['show_form'] = TRUE;
    $WEB_APP['show_table'] = FALSE;


    if (!isset($_GET['action'])) {
      $_GET['action'] = '';
    }

    if ((($_GET['action'] == 'report') || ($_GET['action'] == 'print')) && (count($_POST) == 0)) {
      if (!isset($_SESSION['report_groups']['last_result']) || !isset($_SESSION['report_groups']['group_array']) ||
        !isset($_SESSION['report_groups']['test_array']) || !isset($_SESSION['report_groups']['testing_period']) ||
        !isset($_SESSION['report_groups']['testing_period_from']) ||
        !isset($_SESSION['report_groups']['testing_period_to']) || !isset($_SESSION['report_groups']['scores']) ||
        !isset($_SESSION['report_groups']['scores_from']) || !isset($_SESSION['report_groups']['scores_to'])) {
        header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_groups');
        exit();
      }

      if ($_SESSION['report_groups']['last_result']) {
        $WEB_APP['items_count'] = 1;
      } else {

        if ($_SESSION['report_groups']['best_result'] == TRUE) $WEB_APP['items_count'] =
          get_groups_report_max_count($_SESSION['report_groups']['group_array'],
            $_SESSION['report_groups']['test_array'], $_SESSION['report_groups']['testing_period'],
            $_SESSION['report_groups']['testing_period_from'], $_SESSION['report_groups']['testing_period_to']); else
          $WEB_APP['items_count'] = get_user_results_count_for_groups_report($_SESSION['report_groups']['group_array'],
            $_SESSION['report_groups']['test_array'], $_SESSION['report_groups']['last_result'],
            $_SESSION['report_groups']['best_result'], $_SESSION['report_groups']['testing_period'],
            $_SESSION['report_groups']['testing_period_from'], $_SESSION['report_groups']['testing_period_to'],
            $_SESSION['report_groups']['scores'], $_SESSION['report_groups']['scores_from'],
            $_SESSION['report_groups']['scores_to']);
      }

      // Pages count.
      $pages = get_pages_count($WEB_APP['items_count'],
        ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
      if ($WEB_APP['page'] > $pages) {
        $WEB_APP['page'] = $pages;
      }

      if (!$_SESSION['report_groups']['generate_csv_file']) {

        if ($_SESSION['report_groups']['best_result'] == TRUE) $user_results =
          get_groups_report_max($_SESSION['report_groups']['group_array'], $_SESSION['report_groups']['test_array'],
            $_SESSION['report_groups']['testing_period'], $_SESSION['report_groups']['testing_period_from'],
            $_SESSION['report_groups']['testing_period_to'], $WEB_APP['sort_field'], $WEB_APP['sort_order'],
            $WEB_APP['page'], $WEB_APP['count']); else
          $user_results =
            get_groups_report($_SESSION['report_groups']['group_array'], $_SESSION['report_groups']['test_array'],
              $_SESSION['report_groups']['last_result'], $_SESSION['report_groups']['testing_period'],
              $_SESSION['report_groups']['testing_period_from'], $_SESSION['report_groups']['testing_period_to'],
              $_SESSION['report_groups']['scores'], $_SESSION['report_groups']['scores_from'],
              $_SESSION['report_groups']['scores_to'], $_SESSION['report_groups']['testing_time_column'],
              $WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
              $_SESSION['report_groups']['average']);
      }
      if ($_SESSION['report_groups']['generate_csv_file']) {
        if ($_SESSION['report_groups']['best_result'] == TRUE) $user_results =
          get_groups_report_max($_SESSION['report_groups']['group_array'], $_SESSION['report_groups']['test_array'],
            $_SESSION['report_groups']['testing_period'], $_SESSION['report_groups']['testing_period_from'],
            $_SESSION['report_groups']['testing_period_to'], $WEB_APP['sort_field'], $WEB_APP['sort_order'],
            $WEB_APP['page'], $WEB_APP['count']); else
          $user_results =
            get_groups_report($_SESSION['report_groups']['group_array'], $_SESSION['report_groups']['test_array'],
              $_SESSION['report_groups']['last_result'], $_SESSION['report_groups']['testing_period'],
              $_SESSION['report_groups']['testing_period_from'], $_SESSION['report_groups']['testing_period_to'],
              $_SESSION['report_groups']['scores'], $_SESSION['report_groups']['scores_from'],
              $_SESSION['report_groups']['scores_to'], $_SESSION['report_groups']['testing_time_column'],
              $WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
              $_SESSION['report_groups']['average']);
        // Generate csv file.
        $report_text = '';

        // Column names.
        if ($_SESSION['report_groups']['number_column']) {
          $report_text .= 'id;';
        }

        if ($_SESSION['report_groups']['group_column']) {
          $report_text .= text('txt_group') . ';';
        }

        $report_text .= text('txt_name') . ';';

        if ($_SESSION['report_groups']['login_column']) {
          $report_text .= text('txt_login') . ';';
        }

        if ($_SESSION['report_groups']['user_info_column']) {
          $report_text .= text('txt_info') . ';';
        }

        if ($_SESSION['report_groups']['test_column']) {
          $report_text .= text('txt_test') . ';';
        }

        if ($_SESSION['report_groups']['scores_column']) {
          $report_text .= text('txt_score') . ';';
        }

        if ($_SESSION['report_groups']['results_column']) {
          $report_text .= text('txt_report_results') . ';';
        }

        if ($_SESSION['report_groups']['correct_column']) {
          $report_text .= text('txt_corrects') . ';';
        }

        if ($_SESSION['report_groups']['testing_date_column']) {
          $report_text .= text('txt_date') . ';';
        }

        if ($_SESSION['report_groups']['testing_time_column']) {
          $report_text .= text('txt_time') . ';';
        }

        if ($_SESSION['report_groups']['completed_column']) {
          $report_text .= text('txt_completed') . ';';
        }

        if ($_SESSION['report_groups']['out_of_time_column']) {
          $report_text .= text('txt_out_of_time') . ';';
        }

        if ($_SESSION['report_groups']['percent_column']) {
          $report_text .= text('txt_report_percent') . ';';
        }

        if ($_SESSION['report_groups']['completed_questions_column']) {
          $report_text .= text('txt_report_completed_questions') . ';';
        }

        if ($_SESSION['report_groups']['ip_address']) {
          $report_text .= text('txt_ip_address') . ';';
        }

        $report_text .= "\r\n";

        foreach ($user_results as $user_result) {
          if ($_SESSION['report_groups']['number_column']) {
            $report_text .= $user_result['id'] . ';';
          }

          if ($_SESSION['report_groups']['group_column']) {
            $report_text .= $user_result['group_name'] . ';';
          }

          $report_text .= $user_result['user_name'] . ';';

          if ($_SESSION['report_groups']['login_column']) {
            $report_text .= $user_result['user_login'] . ';';
          }

          if ($_SESSION['report_groups']['user_info_column']) {
            $report_text .= $user_result['user_info'] . ';';
          }

          if ($_SESSION['report_groups']['test_column']) {
            $report_text .= $user_result['user_result_test_title'] . ';';
          }

          if ($_SESSION['report_groups']['scores_column']) {
            $report_text .= $user_result['user_result_score'] . ';';
          }

          if ($_SESSION['report_groups']['results_column']) {
            $report_text .= $user_result['user_result_results'] . ';';
          }

          if ($_SESSION['report_groups']['correct_column']) {
            $report_text .= $user_result['user_result_righ_questions'] . ';';
          }

          if ($_SESSION['report_groups']['testing_date_column']) {
            $report_text .= $user_result['time_begin'] . ';';
          }

          if ($_SESSION['report_groups']['testing_time_column']) {
            $report_text .= $user_result['test_time'] . ';';
          }

          if ($_SESSION['report_groups']['completed_column']) {
            $report_text .= $user_result['user_result_completed'] . ';';
          }

          if ($_SESSION['report_groups']['out_of_time_column']) {
            $report_text .= $user_result['user_result_out_of_time'] . ';';
          }

          if ($_SESSION['report_groups']['percent_column']) {
            $report_text .= $user_result['user_result_percent_right'] . ';';
          }

          if ($_SESSION['report_groups']['completed_questions_column']) {
            $report_text .= $user_result['user_result_completed'] . ';';
          }

          if ($_SESSION['report_groups']['ip_address']) {
            $report_text .= $user_result['user_result_ip'] . ';';
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
      $WEB_APP['show_form'] = !$_SESSION['report_groups']['hide_header'];
      $WEB_APP['show_table'] = TRUE;
    }

    if (isset($_POST['scores_from'])) {
      $correct_post = TRUE;

      if (!isset($_POST['group'])) {
        $WEB_APP['errorstext'] .= text('txt_select_groups') . '<br>';
        $select_groups = array();
        $correct_post = FALSE;
      } else {
        $_SESSION['report_groups']['group_array'] = $_POST['group'];
        $select_groups = array();
        foreach ($_SESSION['report_groups']['group_array'] as $group_id) {
          $group = get_group($group_id);
          $select_groups[] = $group->name;
        }
      }

      if (!isset($_POST['test'])) {
        $WEB_APP['errorstext'] .= text('txt_select_tests') . '<br>';
        $select_tests = array();
        $correct_post = FALSE;
      } else {
        $_SESSION['report_groups']['test_array'] = $_POST['test'];
        $select_tests = array();
        foreach ($_SESSION['report_groups']['test_array'] as $test_id) {
          $test = get_test($test_id);
          $select_tests[] = $test->name;
        }
      }

      $_SESSION['report_groups']['last_result'] = isset($_POST['last_result']);
      $_SESSION['report_groups']['best_result'] = isset($_POST['best_result']);
      $_SESSION['report_groups']['hide_header'] = isset($_POST['hide_header']);
      $_SESSION['report_groups']['generate_csv_file'] = isset($_POST['generate_csv_file']);
      $_SESSION['report_groups']['average'] = isset($_POST['average']);

      // Testing period
      $_SESSION['report_groups']['testing_period'] = isset($_POST['testing_period']);
      $_SESSION['report_groups']['testing_period_from'] = $_POST['testing_period_from'];
      $_SESSION['report_groups']['testing_period_to'] = $_POST['testing_period_to'];

      // Scores
      $_SESSION['report_groups']['scores'] = isset($_POST['scores']);
      $_SESSION['report_groups']['scores_from'] = $_POST['scores_from'];
      $_SESSION['report_groups']['scores_to'] = $_POST['scores_to'];

      // Columns
      $_SESSION['report_groups']['number_column'] = isset($_POST['number_column']);
      $_SESSION['report_groups']['group_column'] = isset($_POST['group_column']);
      $_SESSION['report_groups']['login_column'] = isset($_POST['login_column']);
      $_SESSION['report_groups']['user_info_column'] = isset($_POST['user_info_column']);
      $_SESSION['report_groups']['test_column'] = isset($_POST['test_column']);
      $_SESSION['report_groups']['scores_column'] = isset($_POST['scores_column']);
      $_SESSION['report_groups']['results_column'] = isset($_POST['results_column']);
      $_SESSION['report_groups']['correct_column'] = isset($_POST['correct_column']);
      $_SESSION['report_groups']['testing_date_column'] = isset($_POST['testing_date_column']);
      $_SESSION['report_groups']['testing_time_column'] = isset($_POST['testing_time_column']);
      $_SESSION['report_groups']['completed_column'] = isset($_POST['completed_column']);
      $_SESSION['report_groups']['out_of_time_column'] = isset($_POST['out_of_time_column']);
      $_SESSION['report_groups']['percent_column'] = isset($_POST['percent_column']);
      $_SESSION['report_groups']['completed_questions_column'] = isset($_POST['completed_questions_column']);
      $_SESSION['report_groups']['ip_address'] = isset($_POST['ip_address']);

      if ($correct_post) {
        header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_groups&action=report');
        exit();
      }
    } else {
      if (!isset($_SESSION['report_groups']['group_array'])) {
        $_SESSION['report_groups']['group_array'] = array();
      }

      $select_groups = array();
      foreach ($_SESSION['report_groups']['group_array'] as $group_id) {
        $group = get_group($group_id);
        $select_groups[] = $group->name;
      }

      if (!isset($_SESSION['report_groups']['test_array'])) {
        $_SESSION['report_groups']['test_array'] = array();
      }
      $select_tests = array();
      foreach ($_SESSION['report_groups']['test_array'] as $test_id) {
        $test = get_test($test_id);
        $select_tests[] = $test->name;
      }

      if (!isset($_SESSION['report_groups']['last_result'])) {
        $_SESSION['report_groups']['last_result'] = FALSE;
      }

      if (!isset($_SESSION['report_groups']['best_result'])) {
        $_SESSION['report_groups']['best_result'] = FALSE;
      }

      if (!isset($_SESSION['report_groups']['hide_header'])) {
        $_SESSION['report_groups']['hide_header'] = FALSE;
      }

      if (!isset($_SESSION['report_groups']['generate_csv_file'])) {
        $_SESSION['report_groups']['generate_csv_file'] = FALSE;
      }

      // Testing period
      if (!isset($_SESSION['report_groups']['testing_period'])) {
        $_SESSION['report_groups']['testing_period'] = FALSE;
      }

      if (!isset($_SESSION['report_groups']['testing_period_from'])) {
        $_SESSION['report_groups']['testing_period_from'] = date('Y-m-d');
      }

      if (!isset($_SESSION['report_groups']['testing_period_to'])) {
        $_SESSION['report_groups']['testing_period_to'] = date('Y-m-d');
      }

      //Scores
      if (!isset($_SESSION['report_groups']['scores'])) {
        $_SESSION['report_groups']['scores'] = FALSE;
      }

      if (!isset($_SESSION['report_groups']['scores_from'])) {
        $_SESSION['report_groups']['scores_from'] = 0;
      }

      if (!isset($_SESSION['report_groups']['scores_to'])) {
        $_SESSION['report_groups']['scores_to'] = 1000;
      }

      // Columns
      if (!isset($_SESSION['report_groups']['number_column'])) {
        $_SESSION['report_groups']['number_column'] = TRUE;
      }

      if (!isset($_SESSION['report_groups']['group_column'])) {
        $_SESSION['report_groups']['group_column'] = TRUE;
      }

      if (!isset($_SESSION['report_groups']['login_column'])) {
        $_SESSION['report_groups']['login_column'] = TRUE;
      }

      if (!isset($_SESSION['report_groups']['user_info_column'])) {
        $_SESSION['report_groups']['user_info_column'] = TRUE;
      }

      if (!isset($_SESSION['report_groups']['test_column'])) {
        $_SESSION['report_groups']['test_column'] = TRUE;
      }

      if (!isset($_SESSION['report_groups']['scores_column'])) {
        $_SESSION['report_groups']['scores_column'] = TRUE;
      }

      if (!isset($_SESSION['report_groups']['results_column'])) {
        $_SESSION['report_groups']['results_column'] = TRUE;
      }

      if (!isset($_SESSION['report_groups']['correct_column'])) {
        $_SESSION['report_groups']['correct_column'] = TRUE;
      }

      if (!isset($_SESSION['report_groups']['testing_date_column'])) {
        $_SESSION['report_groups']['testing_date_column'] = TRUE;
      }

      if (!isset($_SESSION['report_groups']['testing_time_column'])) {
        $_SESSION['report_groups']['testing_time_column'] = TRUE;
      }

      if (!isset($_SESSION['report_groups']['completed_column'])) {
        $_SESSION['report_groups']['completed_column'] = FALSE;
      }

      if (!isset($_SESSION['report_groups']['out_of_time_column'])) {
        $_SESSION['report_groups']['out_of_time_column'] = FALSE;
      }

      if (!isset($_SESSION['report_groups']['percent_column'])) {
        $_SESSION['report_groups']['percent_column'] = TRUE;
      }

      if (!isset($_SESSION['report_groups']['completed_questions_column'])) {
        $_SESSION['report_groups']['completed_questions_column'] = FALSE;
      }

      if (!isset($_SESSION['report_groups']['ip_address'])) {
        $_SESSION['report_groups']['ip_address'] = FALSE;
      }

    }
    if (defined('GROUP_REPORT_LIMITED_SECTIONS') && GROUP_REPORT_LIMITED_SECTIONS == TRUE) {
      $user_id = get_user_id($_SESSION['user_login']);
      $sections = get_unhidden_sections_for_user_id($user_id);
    } else {
      $sections = get_sections('section_name');
    }
    $tests = array();
    $section = '';

    if (isset($_SESSION['report_groups']['section_id']) && is_scalar($_SESSION['report_groups']['section_id'])) {
      $tmp = get_section($_SESSION['report_groups']['section_id']);
      if (isset($tmp->id)) {
        $section = $tmp->name;
        $tests = get_tests_for_section_id($tmp->id);
      } else {
        if (!defined('GROUP_REPORT_LIMITED_SECTIONS') || GROUP_REPORT_LIMITED_SECTIONS == FALSE) {
          $tests = get_tests('test_name');
        }
      }
    }

    /************************************************
     *               Form fields                  ***
     * *********************************************/
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

    // Params
    $fields[] = new field(FALSE, text('txt_report_settings'), 'header');
    $fields[] =
      new field(FALSE, text('txt_hide_header'), 'checkbox', 'hide_header', $_SESSION['report_groups']['hide_header'],
        "hide_header");
    $fields[] = new field(FALSE, text('txt_generate_csv_file'), 'checkbox', 'generate_csv_file',
      $_SESSION['report_groups']['generate_csv_file'], "generate_csv_file");
    $fields[] = new field(FALSE, text('txt_display_the_last_result_only'), 'checkbox', 'last_result',
      $_SESSION['report_groups']['last_result'], "last_result", '', '', '', 'document.getElementById("best_result").checked=false;
       document.getElementById("best_result").disabled=document.getElementById("last_result").checked;
       document.getElementById("average").checked=false;
       document.getElementById("average").disabled=document.getElementById("last_result").checked;');
    $fields[] = new field(FALSE, text('txt_display_the_best_result_only'), 'checkbox', 'best_result',
      $_SESSION['report_groups']['best_result'], "best_result", '', '', '', 'document.getElementById("last_result").checked=false;
       document.getElementById("last_result").disabled=document.getElementById("best_result").checked;
       document.getElementById("average").checked=false;
       document.getElementById("average").disabled=document.getElementById("best_result").checked;');
    $fields[] =
      new field(FALSE, text('txt_average'), 'checkbox', 'average', $_SESSION['report_groups']['average'], 'average');

    // Testing time
    $fields[] = new field(FALSE, text('txt_testing_period'), 'header');
    $fields[] = new field(FALSE, text('txt_from'), 'date', 'testing_period_from',
      $_SESSION['report_groups']['testing_period_from']);
    $fields[] =
      new field(FALSE, text('txt_to'), 'date', 'testing_period_to', $_SESSION['report_groups']['testing_period_to']);
    $fields[] = new field(FALSE, text('txt_testing_period'), 'checkbox', 'testing_period',
      $_SESSION['report_groups']['testing_period'], 'testing_period');

    // Scores
    $fields[] = new field(FALSE, text('txt_scores'), 'header');
    $fields[] = new field(FALSE, text('txt_from'), 'number', 'scores_from', $_SESSION['report_groups']['scores_from']);
    $fields[] = new field(FALSE, text('txt_to'), 'number', 'scores_to', $_SESSION['report_groups']['scores_to']);
    $fields[] =
      new field(FALSE, text('txt_scores'), 'checkbox', 'scores', $_SESSION['report_groups']['scores'], 'scores');

    // Columns
    $fields[] = new field(FALSE, text('txt_columns'), 'header');
    $fields[] =
      new field(FALSE, text('txt_number'), 'checkbox', 'number_column', $_SESSION['report_groups']['number_column'],
        'number_column');
    $fields[] =
      new field(FALSE, text('txt_group'), 'checkbox', 'group_column', $_SESSION['report_groups']['group_column'],
        'group_column');
    $fields[] =
      new field(FALSE, text('txt_login'), 'checkbox', 'login_column', $_SESSION['report_groups']['login_column'],
        'login_column');
    $fields[] =
      new field(FALSE, text('txt_info'), 'checkbox', 'user_info_column', $_SESSION['report_groups']['user_info_column'],
        'user_info_column');
    $fields[] = new field(FALSE, text('txt_test'), 'checkbox', 'test_column', $_SESSION['report_groups']['test_column'],
      'test_column');
    $fields[] =
      new field(FALSE, text('txt_scores'), 'checkbox', 'scores_column', $_SESSION['report_groups']['scores_column'],
        'scores_column');
    $fields[] =
      new field(FALSE, text('txt_results'), 'checkbox', 'results_column', $_SESSION['report_groups']['results_column'],
        'results_column');
    $fields[] = new field(FALSE, text('txt_number_of_correct_answers'), 'checkbox', 'correct_column',
      $_SESSION['report_groups']['correct_column'], 'correct_column');
    $fields[] = new field(FALSE, text('txt_testing_date'), 'checkbox', 'testing_date_column',
      $_SESSION['report_groups']['testing_date_column'], 'testing_date_column');
    $fields[] = new field(FALSE, text('txt_testing_time'), 'checkbox', 'testing_time_column',
      $_SESSION['report_groups']['testing_time_column'], 'testing_time_column');
    $fields[] = new field(FALSE, text('txt_completed'), 'checkbox', 'completed_column',
      $_SESSION['report_groups']['completed_column'], 'completed_column');
    $fields[] = new field(FALSE, text('txt_out_of_time'), 'checkbox', 'out_of_time_column',
      $_SESSION['report_groups']['out_of_time_column'], 'out_of_time_column');
    $fields[] =
      new field(FALSE, text('txt_percent'), 'checkbox', 'percent_column', $_SESSION['report_groups']['percent_column'],
        'percent_column');
    $fields[] = new field(FALSE, text('txt_completed_questions'), 'checkbox', 'completed_questions_column',
      $_SESSION['report_groups']['completed_questions_column'], 'completed_questions_column');
    $fields[] =
      new field(FALSE, text('txt_ip_address'), 'checkbox', 'ip_address', $_SESSION['report_groups']['ip_address'],
        'ip_address');

    $columns = array();


    if ($_SESSION['report_groups']['number_column']) {
      $columns[] = new column('id', 'id');
    }

    if ($_SESSION['report_groups']['group_column']) {
      $columns[] = new column('group_name', text('txt_group'));
    }


    $columns[] = new column('user_name', text('txt_user_name'));

    if ($_SESSION['report_groups']['login_column']) {
      $columns[] = new column('user_login', text('txt_login'));
    }

    if ($_SESSION['report_groups']['user_info_column']) {
      $columns[] = new column('user_info', text('txt_info'));
    }

    if ($_SESSION['report_groups']['test_column']) {
      $columns[] = new column('user_result_test_title', text('txt_test'));
    }

    if ($_SESSION['report_groups']['scores_column']) {
      $columns[] = new column('user_result_score', text('txt_score'));
    }

    if ($_SESSION['report_groups']['results_column']) {
      $columns[] = new column('user_result_results', text('txt_report_results'));
    }

    if ($_SESSION['report_groups']['correct_column']) {
      $columns[] = new column('user_result_righ_questions', text('txt_corrects'));
    }

    if ($_SESSION['report_groups']['testing_date_column']) {
      $columns[] = new column('time_begin', text('txt_date'));
    }

    if ($_SESSION['report_groups']['testing_time_column']) {
      $columns[] = new column('test_time', text('txt_time'));
    }

    if ($_SESSION['report_groups']['completed_column']) {
      $columns[] = new column('user_result_completed', text('txt_completed'));
    }

    if ($_SESSION['report_groups']['out_of_time_column']) {
      $columns[] = new column('user_result_out_of_time', text('txt_out_of_time'));
    }

    if ($_SESSION['report_groups']['percent_column']) {
      $columns[] = new column('user_result_percent_right', text('txt_report_percent'));
    }

    if ($_SESSION['report_groups']['completed_questions_column']) {
      $columns[] = new column('user_result_completed_questions', text('txt_report_completed_questions'));
    }

    if ($_SESSION['report_groups']['ip_address']) {
      $columns[] = new column('user_result_ip', text('txt_ip_address'));
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

    //$WEB_APP['hide_edit'] = 0;
    $WEB_APP['hide_delete'] = 1;
    if (isset($_SESSION['report_groups']['average'])) $WEB_APP['average'] = $_SESSION['report_groups']['average'];
    $WEB_APP['fields'] = $fields;
    $WEB_APP['title_add'] = text('txt_report_settings');
    $WEB_APP['submit_title'] = text('txt_create');
    //$WEB_APP['show_empty_value'] = TRUE;
    $WEB_APP['escape'] = FALSE;
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['title'] = text('txt_group_reports');
    $WEB_APP['submit_title'] = text('txt_create');
    $WEB_APP['row_actions'] =
      array($WEB_APP['action_details'], $WEB_APP['action_download'], $WEB_APP['action_print_report'],
        $WEB_APP['action_details_limited'], $WEB_APP['action_finish']);
    if (isset($_GET['action']) && ($_GET['action'] == 'print')) {
      $WEB_APP['view']->display('print_array_table.tpl', text('txt_group_reports'), TRUE);
    } else {
      $WEB_APP['print_url'] =
        $WEB_APP['cfg_url'] . '?module=report_groups&action=print&sort=' . $WEB_APP['sort_field'] . '&order=' .
        $WEB_APP['sort_order'] . '&page=' . $WEB_APP['page'];
      if ($_GET['action'] != '') {
        $WEB_APP['action'] = $_GET['action'];
      }
      $WEB_APP['view']->display('table_array_rows.tpl', text('txt_group_reports'));
    }
  }

  function details()
  {
    global $WEB_APP;
    header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_user_results&action=details&id=' . $WEB_APP['id']);
    exit();
  }

  /** @noinspection PhpUnused */
  function details_limited()
  {
    global $WEB_APP;
    header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_user_results&action=details_limited&id=' .
      $WEB_APP['id']);
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

  function finish()
  {
    global $WEB_APP;
    $user_result = get_user_result(intval($WEB_APP['id']));
    if ($user_result->id != NULL) {
      $resume_text = '';
      $test = get_test($user_result->test);
      if ($test->concl_type == 0) {
        $resume_text = get_resume_text_for_user_result_id($user_result->id);
        $conclusions_text = get_conclusions_text_for_user_result_id($user_result->id);
      } elseif ($test->concl_type == 1) {
        // Get max score.
        $all_questions = get_questions_for_test_id($test->id);
        $themes = get_themes_for_test_id($test->id);
        $max_scores = array();
        foreach ($themes as $theme) {
          $max_scores[$theme['theme_id']] = 0;
        }
        $max_score = 0;
        foreach ($all_questions as $tmp_question) {
          if ($test->type == 0) {
            $max_score += $tmp_question['question_weight'];
            $max_scores[$tmp_question['question_theme_id']] += $tmp_question['question_weight'];
          }
          if ($test->type == 1) {
            switch ($tmp_question['question_type']) {
              case 2:
              case 0:
                $max_score += $tmp_question['question_weight'];
                $max_scores[$tmp_question['question_theme_id']] += $tmp_question['question_weight'];
                break;

              case 1:
                $answers = get_answers_for_question_id($tmp_question['question_id']);
                foreach ($answers as $answer) {
                  if ($answer['answer_right'] == 1) {
                    $max_score += $tmp_question['question_weight'];
                    $max_scores[$tmp_question['question_theme_id']] += $tmp_question['question_weight'];
                  }
                }
                break;

              case 3:
                $answers = get_answers_for_question_id($tmp_question['question_id']);
                $max_score += $tmp_question['question_weight'] * count($answers);
                $max_scores[$tmp_question['question_theme_id']] += $tmp_question['question_weight'] * count($answers);
                break;
              case 4:
                $answers = get_answers_for_question_id($tmp_question['question_id']);
                $max_score += $tmp_question['question_weight'] * count($answers) / 2;
                $max_scores[$tmp_question['question_theme_id']] += $tmp_question['question_weight'] * count($answers) /
                  2;
                break;
            }
          }
        }

        $resume_text = get_resume_text_for_user_result_id_by_max_score($_SESSION['test']['user_result_id'], $max_score);
        $conclusions_text =
          get_conclusions_text_for_user_result_id_by_max_scores($_SESSION['test']['user_result_id'], $max_scores);

      }

      $user_result->results = $resume_text;
      $user_result->completed = TRUE;
      edit_user_result($user_result);
      if (isset($conclusions_text)) {
        foreach ($conclusions_text as $user_result_theme) {
          foreach ($_SESSION['test']['user_result_themes'] as $value) {
            if ($value['title'] == $user_result_theme->theme) {
              edit_user_result_theme($value['id'], $_SESSION['test']['user_result_id'], $user_result_theme->theme,
                $user_result_theme->result);
            }
          }
        }
      }
    }
    header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_groups&action=report');
  }

  /** @noinspection PhpUnused */
  function change_section()
  {
    $limited = defined('GROUP_REPORT_LIMITED_SECTIONS') || GROUP_REPORT_LIMITED_SECTIONS == TRUE;

    if (isset($_GET['section_id']) && is_scalar($_GET['section_id'])) {
      $section_id = (int)$_GET['section_id'];
      $_SESSION['report_groups']['section_id'] = $section_id;
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
        $tests = get_tests('test_name');
        foreach ($tests as $test) {
          printf("<option value=\"%d\">%s</option>\n", $test['id'], htmlspecialchars($test['test_name']));
        }
        die();
      }
    }
  }
}

