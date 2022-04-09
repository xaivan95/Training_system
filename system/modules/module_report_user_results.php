<?php

/**
 * @see module_base
 */
class module_report_user_results extends module_base
{
  function __construct()
  {
    global $WEB_APP;

    $WEB_APP['title_delete'] = text('txt_delete_user_results');
  }

  function on_delete()
  {
    global $WEB_APP;
    global $adodb;
    if (!isset($_POST['selected_row'])) {
      $this->view();
      exit();
    }

    if (is_confirm_delete_action()) {
      delete_user_results($_POST['selected_row']);

      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }

    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';
    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_group_user_tests_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    // Table titles.
    $columns = array();
    $columns[] = new column('id', 'id');
    $columns[] = new column('group_name', text('txt_group'));
    $columns[] = new column('user_name', text('txt_user_name'));
    $columns[] = new column('user_result_test_title', text('txt_test'));
    $columns[] = new column('time_begin', text('txt_testing_date'));
    if ($WEB_APP['settings']['tst_collect_ip'] == 1) $columns[] = new column('ip', text('txt_ip_address'));
    $WEB_APP['columns'] = $columns;
    $WEB_APP['escape'] = TRUE;
    $WEB_APP['list_action_extra_checkbox'] = TRUE;
    $WEB_APP['list_action_extra_checkbox_title'] = text('txt_preserve_overall_results');

    $WEB_APP['submit_title'] = text('txt_delete');

    $WEB_APP['title'] = text('txt_delete_user_results');
    $WEB_APP['view']->display('list_action.tpl', text('txt_user_results'));
  }

  function view()
  {
    global $WEB_APP;
    global $adodb;
    $group_user_tests = '';
    $user_id = get_user_id($_SESSION['user_login']);

    $WEB_APP['title'] = text('txt_user_results');

    if (is_confirm_delete_action()) {
      $delete_overall_results = !(isset($_POST['extra_checkbox']) && ($_POST['extra_checkbox'] == 'checked'));
      delete_user_results($_POST['selected_row'], $delete_overall_results);

      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }

    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $user_result = get_user_result($WEB_APP['id']);
      if (!isset($user_result->id)) {
        redirect($WEB_APP['errorstext']);
      }
      $group_user_tests = get_group_user_tests_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'delete';
      $WEB_APP['title'] = $WEB_APP['title_delete'];
    }

    if (is_delete_action()) {
      $group_user_tests = get_group_user_tests_from_array($_POST['selected_row']);
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'delete';
      $WEB_APP['title'] = $WEB_APP['title_delete'];
    }

    if ($WEB_APP['action'] == 'view') {
      // Pages count.
      $WEB_APP['items_count'] = get_group_user_tests_count(new report_user_results_filter(), $user_id);


      // Pages count.
      $pages = get_pages_count($WEB_APP['items_count'],
        ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
      if ($WEB_APP['page'] > $pages) {
        $WEB_APP['page'] = $pages;
      }

      $group_user_tests =
        get_group_user_tests($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
          new report_user_results_filter(), $user_id);

      // Create paginator.
      $paginator = new paginator($WEB_APP['page'], $pages);
      $paginator->url = $WEB_APP['script_name'];
      $paginator->url_query_array = $WEB_APP['url_query_array'];
      $WEB_APP['paginator'] = $paginator;
    }


    // Table titles.
    $columns = array();
    $columns[] = new column('id', 'id');
    $columns[] = new column('group_name', text('txt_group'));
    $columns[] = new column('user_name', text('txt_user_name'));
    $columns[] = new column('user_result_test_title', text('txt_test'));
    $columns[] = new column('time_begin', text('txt_testing_date'));
    if ($WEB_APP['settings']['tst_collect_ip'] == 1) $columns[] = new column('user_result_ip', text('txt_ip_address'));

    $WEB_APP['row_actions'] =
      array($WEB_APP['action_details'], $WEB_APP['action_download'], $WEB_APP['action_print_report'],
        $WEB_APP['action_print_report_compact'], $WEB_APP['action_print_report_themes'], $WEB_APP['action_delete']);

    $WEB_APP['items'] = $group_user_tests;
    $WEB_APP['items_count'] = count($group_user_tests);
    $WEB_APP['columns'] = $columns;
    $WEB_APP['columns_count'] = count($columns);
    $WEB_APP['show_insert'] = FALSE;
    $WEB_APP['escape'] = FALSE;
    $WEB_APP['editfrom'] = TRUE;


    $WEB_APP['view']->display('table.tpl', text('txt_user_results'));
  }

  function on_confirm_delete()
  {
    global $WEB_APP;
    global $adodb;

    $result = delete_user_results($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_user_results') . "<br>";
      $this->view();
      exit();
    }
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    redirect($WEB_APP['errorstext']);
  }

  /** @noinspection PhpUnused */
  function print_report_compact()
  {
    global $WEB_APP;

    $user_result = get_user_result($WEB_APP['id']);
    $WEB_APP['test_css'] = $user_result->test_css;
    if (!isset($user_result->id)) {
      header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_user_results');
      exit();
    }
    $user_answers = get_user_answers_for_user_result_id($WEB_APP['id']);
    $test_time = gmdate('H:i:s', get_user_result_test_time($WEB_APP['id']));

    $user = get_user($user_result->user);
    $titles = array();
    $titles[] = array('name' => text('txt_user_name'), 'value' => $user->name);
    $titles[] = array('name' => text('txt_test'), 'value' => $user_result->test_title);
    if ($WEB_APP['settings']['tst_collect_ip'] == 1) $titles[] =
      array('name' => text('txt_ip_address'), 'value' => $user_result->ip);
    $titles[] = array('name' => text('txt_testing_began'), 'value' => $user_result->time_begin);
    $titles[] = array('name' => text('txt_testing_finished'), 'value' => $user_result->time_end);
    $titles[] = array('name' => text('txt_testing_time'), 'value' => $test_time);
    $titles[] = array('name' => text('txt_completed'),
      'value' => ($user_result->completed == 1) ? text('txt_yes') : text('txt_no'));
    $titles[] = array('name' => text('txt_out_of_time'),
      'value' => ($user_result->out_of_time == 1) ? text('txt_yes') : text('txt_no'));
    $titles[] = array('name' => text('txt_scores'), 'value' => $user_result->score);
    $titles[] = array('name' => text('txt_total'), 'value' => $user_result->total_questions);
    $titles[] = array('name' => text('txt_answered'), 'value' => $user_result->completed_questions);
    $titles[] = array('name' => text('txt_corrects'), 'value' => $user_result->right_questions);
    $titles[] = array('name' => text('txt_result'), 'value' => $user_result->results);
    $percent_right = round($user_result->percent_right, $WEB_APP['settings']['admset_percprecision']);
    $titles[] = array('name' => text('txt_percentage_of_correct_answers'), 'value' => $percent_right);
    $titles[] = array('name' => text('txt_group'), 'value' => $user->group);

    $user_answers2 = array();

    for ($i = 0; $i < count($user_answers); $i++) {
      $user_answers2[$i] = $user_answers[$i];
      $user_answers2[$i]['user_answer_id'] = $i + 1;
      $user_answers2[$i]['user_answer_is_right'] =
        ($user_answers[$i]['user_answer_is_right'] == 1) ? text('txt_yes') : text('txt_no');
    }

    $columns = array();
    $columns[] = new column("user_answer_id", "#");
    $columns[] = new column("user_answer_question", text('txt_question'));
    $columns[] = new column("user_answer_answer", text('txt_one_answer'));
    $columns[] = new column("user_answer_score", text('txt_score'));
    $columns[] = new column("user_answer_is_right", text('txt_correct'));

    $WEB_APP['titles'] = $titles;
    $WEB_APP['rows_count'] = count($user_answers2);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['columns'] = $columns;
    $WEB_APP['columns_count'] = 4;
    $WEB_APP['rows'] = $user_answers2;
    $WEB_APP['escape'] = TRUE;
    $WEB_APP['title'] = text('txt_user_results');
    if ($WEB_APP['action'] == 'print_report') {
      $WEB_APP['view']->display('rep_users_print.tpl', $WEB_APP['title'], TRUE);
    } elseif ($WEB_APP['action'] == 'print_report_compact') {
      $WEB_APP['view']->display('rep_users_print_compact.tpl', $WEB_APP['title'], TRUE);
    } elseif ($WEB_APP['action'] == 'print_report_themes') {
      $WEB_APP['view']->display('rep_users_print_themes.tpl', $WEB_APP['title'], TRUE);
    }

    if ($WEB_APP['action'] == 'details') {
      $WEB_APP['view']->display('report.tpl', $WEB_APP['title']);
    }
  }

  /** @noinspection PhpUnused */
  function print_report_themes()
  {
    global $WEB_APP;

    $user_result = get_user_result($WEB_APP['id']);
    $WEB_APP['test_css'] = $user_result->test_css;
    if (!isset($user_result->id)) {
      header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_user_results');
      exit();
    }
    $test_time = gmdate('H:i:s', get_user_result_test_time($WEB_APP['id']));

    $user = get_user($user_result->user);
    $titles = array();
    $titles[] = array('name' => text('txt_login'), 'value' => $user->name);
    $titles[] = array('name' => text('txt_test'), 'value' => $user_result->test_title);
    if ($WEB_APP['settings']['tst_collect_ip'] == 1) $titles[] =
      array('name' => text('txt_ip_address'), 'value' => $user_result->ip);
    $titles[] = array('name' => text('txt_testing_began'), 'value' => $user_result->time_begin);
    $titles[] = array('name' => text('txt_testing_finished'), 'value' => $user_result->time_end);
    $titles[] = array('name' => text('txt_testing_time'), 'value' => $test_time);
    $titles[] = array('name' => text('txt_completed'),
      'value' => ($user_result->completed == 1) ? text('txt_yes') : text('txt_no'));
    $titles[] = array('name' => text('txt_out_of_time'),
      'value' => ($user_result->out_of_time == 1) ? text('txt_yes') : text('txt_no'));
    $titles[] = array('name' => text('txt_scores'), 'value' => $user_result->score);
    $titles[] = array('name' => text('txt_total'), 'value' => $user_result->total_questions);
    $titles[] = array('name' => text('txt_answered'), 'value' => $user_result->completed_questions);
    $titles[] = array('name' => text('txt_corrects'), 'value' => $user_result->right_questions);
    $titles[] = array('name' => text('txt_result'), 'value' => $user_result->results);
    $percent_right = round($user_result->percent_right, $WEB_APP['settings']['admset_percprecision']);
    $titles[] = array('name' => text('txt_percentage_of_correct_answers'), 'value' => $percent_right);

    $user_result_themes = get_user_result_themes_for_user_result_id($user_result->id);
    $themes = array();
    foreach ($user_result_themes as $user_result_theme) {
      $answers = get_answers_for_user_result_theme($user_result_theme['user_result_themes_id'], $user_result->id);
      $theme = array();
      $theme['theme'] = $user_result_theme['user_result_themes_theme_caption'];
      $theme['result'] = $user_result_theme['user_result_themes_result'];
      $theme['questions_count'] = count($answers);
      $theme['right_questions'] = 0;
      $theme['score'] = 0;
      foreach ($answers as $answer) {
        $theme['right_questions'] += $answer['user_answer_is_right'];
        $theme['score'] += $answer['user_answer_score'];
      }

      $theme['uncorrect_questions'] = $theme['questions_count'] - $theme['right_questions'];
      $theme['percent_right'] =
        round(($theme['questions_count'] == 0) ? 0 : $theme['right_questions'] / $theme['questions_count'] * 100,
          $WEB_APP['settings']['admset_percprecision']);
      $themes[] = $theme;

    }
    $WEB_APP['themes'] = $themes;
    $WEB_APP['themes_count'] = count($themes);

    $themes_columns = array();
    $themes_columns[] = new column("theme", text('txt_theme'));
    $themes_columns[] = new column("result", text('txt_result'));
    $themes_columns[] = new column("questions_count", text('txt_questions'));
    $themes_columns[] = new column("right_questions", text('txt_right_questions'));
    $themes_columns[] = new column("score", text('txt_score'));
    $themes_columns[] = new column("uncorrect_questions", text('txt_uncorrect_questions'));
    $themes_columns[] = new column("percent_right", text('txt_percentage_of_correct_answers'));
    $WEB_APP['themes_columns'] = $themes_columns;
    $WEB_APP['themes_columns_count'] = count($themes_columns);

    $WEB_APP['titles'] = $titles;
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['columns_count'] = 4;
    $WEB_APP['escape'] = TRUE;
    $WEB_APP['title'] = text('txt_user_results');
    if ($WEB_APP['action'] == 'print_report') {
      $WEB_APP['view']->display('rep_users_print.tpl', $WEB_APP['title'], TRUE);
    } elseif ($WEB_APP['action'] == 'print_report_compact') {
      $WEB_APP['view']->display('rep_users_print_compact.tpl', $WEB_APP['title'], TRUE);
    } elseif ($WEB_APP['action'] == 'print_report_themes') {
      $WEB_APP['view']->display('rep_users_print_themes.tpl', $WEB_APP['title'], TRUE);
    }

    if ($WEB_APP['action'] == 'details') {
      $WEB_APP['view']->display('report.tpl', $WEB_APP['title']);
    }
  }

  function chart()
  {
    require_once CFG_LIB_DIR . 'chart.php';
  }

  function details()
  {
    $this->print_report();
  }

  /**
   * @param bool $hide_limited need to hide answers log of tests with 'hide log' property.
   */
  function print_report($hide_limited = FALSE)
  {
    global $WEB_APP;

    /** @var int $WEB_APP */
    $user_result = get_user_result($WEB_APP['id']);
    $WEB_APP['test_css'] = $user_result->test_css;
    if (!isset($user_result->id)) {
      header('Location: ' . $WEB_APP['cfg_url'] . '?module=report_user_results');
      exit();
    }
    $test_time = gmdate('H:i:s', get_user_result_test_time($WEB_APP['id']));

    $user = get_user($user_result->user);
    $titles = array();
    $titles[] = array('name' => text('txt_user_name'), 'value' => $user->name);
    $titles[] = array('name' => text('txt_test'), 'value' => $user_result->test_title);
    if ($WEB_APP['settings']['tst_collect_ip'] == 1) $titles[] =
      array('name' => text('txt_ip_address'), 'value' => $user_result->ip);
    $titles[] = array('name' => text('txt_testing_began'), 'value' => $user_result->time_begin);
    $titles[] = array('name' => text('txt_testing_finished'), 'value' => $user_result->time_end);
    $titles[] = array('name' => text('txt_testing_time'), 'value' => $test_time);
    $titles[] = array('name' => text('txt_completed'),
      'value' => ($user_result->completed == 1) ? text('txt_yes') : text('txt_no'));
    $titles[] = array('name' => text('txt_out_of_time'),
      'value' => ($user_result->out_of_time == 1) ? text('txt_yes') : text('txt_no'));
    $titles[] = array('name' => text('txt_scores'), 'value' => $user_result->score);
    $titles[] = array('name' => text('txt_total'), 'value' => $user_result->total_questions);
    $titles[] = array('name' => text('txt_answered'), 'value' => $user_result->completed_questions);
    $titles[] = array('name' => text('txt_corrects'), 'value' => $user_result->right_questions);
    $titles[] = array('name' => text('txt_result'), 'value' => $user_result->results);
    $percent_right = round($user_result->percent_right, $WEB_APP['settings']['admset_percprecision']);
    if (($WEB_APP['action'] == 'print_report') or ($WEB_APP['action'] == 'print_report_compact')) {
      $titles[] = array('name' => text('txt_percentage_of_correct_answers'), 'value' => $percent_right);
    } else {
      $titles[] = array('name' => text('txt_percentage_of_correct_answers'),
        'value' => $percent_right . "<img src = \"" . $WEB_APP['cfg_url'] .
          "?module=report_user_results&action=chart&p=" . $percent_right . "\" align=\"absmiddle\">");
    }


    $user_result_themes = get_user_result_themes_for_user_result_id($user_result->id);
    $themes = array();
    foreach ($user_result_themes as $user_result_theme) {
      $answers = get_answers_for_user_result_theme($user_result_theme['user_result_themes_id'], $user_result->id);
      $theme = array();
      $theme['theme'] = $user_result_theme['user_result_themes_theme_caption'];
      $theme['result'] = $user_result_theme['user_result_themes_result'];
      $theme['questions_count'] = count($answers);
      $theme['right_questions'] = 0;
      $theme['score'] = 0;
      foreach ($answers as $answer) {
        $theme['right_questions'] += $answer['user_answer_is_right'];
        $theme['score'] += $answer['user_answer_score'];
      }

      $theme['uncorrect_questions'] = $theme['questions_count'] - $theme['right_questions'];
      $theme['percent_right'] =
        round(($theme['questions_count'] == 0) ? 0 : $theme['right_questions'] / $theme['questions_count'] * 100,
          $WEB_APP['settings']['admset_percprecision']);
      $themes[] = $theme;

    }
    $WEB_APP['themes'] = $themes;
    $WEB_APP['themes_count'] = count($themes);

    $themes_columns = array();
    $themes_columns[] = new column("theme", text('txt_theme'));
    $themes_columns[] = new column("result", text('txt_result'));
    $themes_columns[] = new column("questions_count", text('txt_questions'));
    $themes_columns[] = new column("right_questions", text('txt_right_questions'));
    $themes_columns[] = new column("score", text('txt_score'));
    $themes_columns[] = new column("uncorrect_questions", text('txt_uncorrect_questions'));
    $themes_columns[] = new column("percent_right", text('txt_percentage_of_correct_answers'));
    $WEB_APP['themes_columns'] = $themes_columns;
    $WEB_APP['themes_columns_count'] = count($themes_columns);

    $i = 0;
    $answer_fields_list = '';
    if ($hide_limited == TRUE) {
      $test = get_test($user_result->test);
      $may_view_log = $test->is_show_answers_log == 1;
    } else $may_view_log = TRUE;

    $user_answers2 = array();
    if ($may_view_log == TRUE) {
      $user_answers = get_user_answers_for_user_result_id($WEB_APP['id']);
      foreach ($user_answers as $user_answer) {
        $i++;
        $titles[] = array('value' => text('txt_question') . ' ' . $i, 'name' => 'header');
        $titles[] = array('name' => text('txt_correct'),
          'value' => ($user_answer['user_answer_is_right'] == 1) ? text('txt_yes') :
            "<span style=\"color:red\">" . text('txt_no') . "</span>");
        $titles[] = array('name' => text('txt_score'), 'value' => $user_answer['user_answer_score']);
        $titles[] = array('name' => text('txt_time'), 'value' => $user_answer['user_answer_time']);
        //str_replace to prevent autoplay of video and audio
        $titles[] = array('name' => text('txt_question'),
          'value' => str_replace(' autoplay ', ' ', $user_answer['user_answer_question']));
        $titles[] = array('name' => text('txt_rep_answer'),
          'value' => str_replace(' autoplay ', ' ', $user_answer['user_answer_answer']));
        if (($user_answer['user_answer_answer_fields'] !== EMPTY_FIELDS) &&
          ($user_answer['user_answer_answer_fields'] !== '')) {
          $titles[] = array('name' => text('txt_answer_fields'), 'value' => $user_answer['user_answer_answer_fields']);
          $answer_fields_list .= $user_answer['user_answer_answer_fields'] . FIELDS_DIVIDER;
        }
        $titles[] = array('name' => text('txt_correct_answer'), 'value' => $user_answer['user_answer_correct_answer']);
        $record_file_name = "records/$user_result->user/" . $user_result->id . "_" . $i . ".mp3";
        if (file_exists($record_file_name)) {
          $titles[] =
            array('name' => text('txt_record'), 'value' => "<audio controls src='" . $record_file_name . "' ></audio>");
        }
      }


      $WEB_APP['results_fields'] = json_encode(explode(FIELDS_DIVIDER, $answer_fields_list));

      for ($i = 0; $i < count($user_answers); $i++) {
        $user_answers2[$i] = $user_answers[$i];
        $user_answers2[$i]['user_answer_id'] = $i + 1;
        $user_answers2[$i]['user_answer_is_right'] =
          ($user_answers[$i]['user_answer_is_right'] == 1) ? text('txt_yes') : text('txt_no');
      }
    }

    $columns = array();
    $columns[] = new column("user_answer_id", text('txt_question'));
    $columns[] = new column("user_answer_time", text('txt_time'));
    $columns[] = new column("user_answer_score", text('txt_score'));
    $columns[] = new column("user_answer_is_right", text('txt_correct'));

    $WEB_APP['titles'] = $titles;
    $WEB_APP['rows_count'] = count($user_answers2);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['columns'] = $columns;
    $WEB_APP['columns_count'] = 4;
    $WEB_APP['rows'] = $user_answers2;
    $WEB_APP['escape'] = TRUE;
    $WEB_APP['title'] = text('txt_user_results');
    $WEB_APP['html_header'] = $user_result->test_html_header;
    $WEB_APP['scripts'][] = 'question.js';
    if ($WEB_APP['action'] == 'print_report') {
      $WEB_APP['view']->display('rep_users_print.tpl', $WEB_APP['title'], TRUE);
    } elseif ($WEB_APP['action'] == 'print_report_compact') {
      $WEB_APP['view']->display('rep_users_print_compact.tpl', $WEB_APP['title'], TRUE);
    } elseif ($WEB_APP['action'] == 'print_report_themes') {
      $WEB_APP['view']->display('rep_users_print_themes.tpl', $WEB_APP['title'], TRUE);
    }

    if ($WEB_APP['action'] == 'details' || $WEB_APP['action'] == 'details_limited') {
      $WEB_APP['title'] = $WEB_APP['title'] . " (ID: $user_result->id)";
      $WEB_APP['view']->display('report.tpl', $WEB_APP['title']);
    }
  }

  /** @noinspection PhpUnused */
  function details_limited()
  {
    $this->print_report(TRUE);
  }

  function download()
  {
    global $WEB_APP;
    $user_result = get_user_result($WEB_APP['id']);
    if (!isset($user_result->id)) {
      redirect($WEB_APP['errorstext']);
    }
    $user_answers = get_user_answers_for_user_result_id($WEB_APP['id']);
    $user_answers2 = array();
    $report_text =
      text('txt_question') . ";" . text('txt_time') . ";" . text('txt_score') . ";" . text('txt_correct') . "\r\n";
    for ($i = 0; $i < count($user_answers); $i++) {
      $user_answers2[$i] = $user_answers[$i];
      $user_answers2[$i]['id'] = $i + 1;
      $user_answers2[$i]['user_answer_is_right'] =
        ($user_answers[$i]['user_answer_is_right'] == 1) ? text('txt_yes') : text('txt_no');
      $report_text .= $user_answers2[$i]['id'] . ';' . $user_answers2[$i]['user_answer_time'] . ";" .
        $user_answers2[$i]['user_answer_score'] . ";" . $user_answers2[$i]['user_answer_is_right'] . "\r\n";
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
  }

}