<?php

/**
 * @see module_base
 */
class module_view_results extends module_base
{
  function view()
  {
    global $WEB_APP;
    global $adodb;

    $user_id = get_user_id($_SESSION['user_login']);
    $WEB_APP['items_count'] = get_user_results_count_for_user_id($user_id, new user_result_filter());

    // Pages count.
    $pages =
      get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
    if ($WEB_APP['page'] > $pages) {
      $WEB_APP['page'] = $pages;
    }

    $user_results =
      get_user_results($user_id, $WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
        new user_result_filter());
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    $adodb->Close();

    // Create paginator.
    $paginator = new paginator($WEB_APP['page'], $pages);
    $paginator->url = $WEB_APP['script_name'];
    $paginator->url_query_array = $WEB_APP['url_query_array'];
    $WEB_APP['paginator'] = $paginator;
    $WEB_APP['row_actions'] = array($WEB_APP['action_view_result']);

    // Table titles.
    $columns = array();
    $columns[] = new column('id', 'id');
    $columns[] = new column('user_result_test_title', text('txt_test'));
    $columns[] = new column('time_begin', text('txt_date'));
    $columns[] = new column('user_result_completed_questions', text('txt_report_completed_questions'));
    $columns[] = new column('user_result_righ_questions', text('txt_corrects'));
    $columns[] = new column('user_result_percent_right', text('txt_report_percent'));
    $columns[] = new column('user_result_results', text('txt_result'));

    $WEB_APP['columns'] = $columns;

    $WEB_APP['items'] = $user_results;
    //$WEB_APP['rows_count'] = count($user_results);
    $WEB_APP['editform'] = TRUE;

    $WEB_APP['action'] = 'view';
    $WEB_APP['id'] = 'id';
    $WEB_APP['columns_count'] = count($columns);
    //$WEB_APP['hide_edit'] = 1;
    $WEB_APP['hide_delete'] = 1;
    $WEB_APP['show_insert'] = FALSE;
    $WEB_APP['title'] = text('txt_view_results');
    $WEB_APP['view']->display('table.tpl', text('txt_view_results'));
  }

  /** @noinspection PhpUnused */
  function view_result()
  {
    global $WEB_APP;
    $user_result = get_user_result($WEB_APP['id']);
    if (!isset($user_result->id)) {
      header('Location: ' . $WEB_APP['cfg_url'] . '?module=view_results');
      exit();
    }

    $current_user = get_user_from_login_password($_SESSION['user_login'], $_SESSION['user_password']);
    $user = get_user($user_result->user);

    if ($user->id != $current_user->id) {
      header('Location: ' . $WEB_APP['cfg_url'] . '?module=view_results');
      exit();
    }

    $WEB_APP['test_css'] = $user_result->test_css;
    if ($user_result->completed != 1) {
      $titles = array();
      $titles[] = array('name' => text('txt_user_name'), 'value' => $user->name);
      $titles[] = array('name' => text('txt_test'), 'value' => $user_result->test_title);
      $titles[] = array('name' => text('txt_testing_began'), 'value' => $user_result->time_begin);
      $titles[] = array('name' => text('txt_completed'), 'value' => text('txt_no'));
      $titles[] = array('name' => text('txt_total'), 'value' => $user_result->total_questions);
      $titles[] = array('name' => text('txt_answered'), 'value' => $user_result->completed_questions);

      $WEB_APP['titles'] = $titles;
      $WEB_APP['title'] = text('txt_view_results');
      $WEB_APP['view']->display('report.tpl', text('txt_view_results'));
      exit();
    }

    $test_time = gmdate('H:i:s', get_user_result_test_time($WEB_APP['id']));

    $titles = array();
    $titles[] = array('name' => text('txt_user_name'), 'value' => $user->name);
    $titles[] = array('name' => text('txt_group'), 'value' => $user->group);
    $titles[] = array('name' => text('txt_test'), 'value' => $user_result->test_title);
    $titles[] = array('name' => text('txt_testing_began'), 'value' => $user_result->time_begin);
    $titles[] = array('name' => text('txt_testing_finished'), 'value' => $user_result->time_end);
    $titles[] = array('name' => text('txt_testing_time'), 'value' => $test_time);
    $titles[] = array('name' => text('txt_completed'), 'value' => text('txt_yes'));
    $titles[] = array('name' => text('txt_out_of_time'),
      'value' => ($user_result->out_of_time == 1) ? text('txt_yes') : text('txt_no'));
    $titles[] = array('name' => text('txt_scores'), 'value' => $user_result->score);
    $titles[] = array('name' => text('txt_total'), 'value' => $user_result->total_questions);
    $titles[] = array('name' => text('txt_answered'), 'value' => $user_result->completed_questions);
    $titles[] = array('name' => text('txt_corrects'), 'value' => $user_result->right_questions);

    $percent_right = round($user_result->percent_right, $WEB_APP['settings']['admset_percprecision']);
    if ($WEB_APP['action'] == 'print') {
      $titles[] = array('name' => text('txt_percentage_of_correct_answers'), 'value' => $percent_right);
    } else {
      $titles[] = array('name' => text('txt_percentage_of_correct_answers'),
        'value' => $percent_right . '<img src = \'' . $WEB_APP['cfg_url'] . '?module=view_results&action=chart&p=' .
          $percent_right . '\' align=\'absmiddle\'>');
    }


    $user_result_themes = get_user_result_themes_for_user_result_id($user_result->id);

    $titles[] = array('name' => 'header', 'value' => text('txt_results'));
    $titles[] = array('name' => text('txt_result'), 'value' => $user_result->results);

    foreach ($user_result_themes as $user_result_theme) {
      $titles[] = array('name' => $user_result_theme['user_result_themes_theme_caption'],
        'value' => $user_result_theme['user_result_themes_result']);
    }

    $answer_fields_list = '';
    $test = get_test($user_result->test);
    $user_answers2 = array();
    $columns = array();
    if ($test->is_show_answers_log == 1) {
      $user_answers = get_user_answers_for_user_result_id($WEB_APP['id']);

      $i = 0;
      foreach ($user_answers as $user_answer) {
        $i++;
        $titles[] = array('name' => 'header', 'value' => text('txt_question') . ' ' . $i);
        $titles[] = array('name' => text('txt_correct'),
          'value' => ($user_answer['user_answer_is_right'] == 1) ? text('txt_yes') :
            "<span style=\"color:red\">" . text('txt_no') . "</span>");
        $titles[] = array('name' => text('txt_score'), 'value' => $user_answer['user_answer_score']);
        $titles[] = array('name' => text('txt_time'), 'value' => $user_answer['user_answer_time']);
        $titles[] = array('name' => text('txt_question'), 'value' => $user_answer['user_answer_question']);
        $titles[] = array('name' => text('txt_rep_answer'), 'value' => $user_answer['user_answer_answer']);
        if (($user_answer['user_answer_answer_fields'] !== EMPTY_FIELDS) &&
          ($user_answer['user_answer_answer_fields'] !== '')) {
          $titles[] = array('name' => text('txt_answer_fields'), 'value' => $user_answer['user_answer_answer_fields']);
          $answer_fields_list .= $user_answer['user_answer_answer_fields'] . FIELDS_DIVIDER;
        }
        if (($user_answer['user_answer_explanation'] !== '') &&($user_answer['user_answer_is_right'] == 0)) $titles[] =
          array('name' => text('txt_explanation'), 'value' => $user_answer['user_answer_explanation']);
        $record_file_name = "records/$user_result->user/" . $user_result->id . "_" . $i . ".mp3";
        if (file_exists($record_file_name)) {
          $titles[] =
            array('name' => text('txt_record'), 'value' => "<audio controls src='" . $record_file_name . "' ></audio>");
        }
      }
      $WEB_APP['results_fields'] = json_encode(explode(FIELDS_DIVIDER, $answer_fields_list));
      $WEB_APP['scripts'][] = 'question.js';


      for ($i = 0; $i < count($user_answers); $i++) {
        $user_answers2[$i] = $user_answers[$i];
        $user_answers2[$i]['user_answer_id'] = $i + 1;
        $user_answers2[$i]['user_answer_is_right'] =
          ($user_answers[$i]['user_answer_is_right'] == 1) ? text('txt_yes') : text('txt_no');
      }


      $columns[] = new column('user_answer_id', text('txt_question'));
      $columns[] = new column('user_answer_time', text('txt_time'));
      $columns[] = new column('user_answer_score', text('txt_score'));
      $columns[] = new column('user_answer_is_right', text('txt_correct'));
    }

    $WEB_APP['titles'] = $titles;
    $WEB_APP['rows_count'] = count($user_answers2);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['columns'] = $columns;
    $WEB_APP['columns_count'] = 4;
    $WEB_APP['rows'] = $user_answers2;
    $WEB_APP['html_header'] = $user_result->test_html_header;

    $WEB_APP['title'] = text('txt_view_results') . " (ID: $user_result->id)";
    $WEB_APP['view']->display('report.tpl', text('txt_view_results') . " (ID: " . $user_result->id . ")");

  }

  function chart()
  {
    require CFG_LIB_DIR . 'chart.php';
  }
}

