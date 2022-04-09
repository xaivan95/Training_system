<?php

/**
 * @param array $a string
 * @param array $b string
 * @return int
 */
function cmp_items($a, $b)
{
  return strcasecmp($a['value'], $b['value']);
}

/**
 * @see module_base
 */
class module_tests extends module_base
{
  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title_edit'] = text('txt_edit_test');
    $WEB_APP['title_delete'] = text('txt_delete_tests');
    $WEB_APP['title_add'] = text('txt_add_test');
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
      $result = delete_tests($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_test') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_tests_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';
    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_tests_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column('test_name', text('txt_name')));

    $WEB_APP['escape'] = TRUE;

    $WEB_APP['submit_title'] = text('txt_delete');


    $WEB_APP['view']->display('list_action.tpl', text('txt_tests'));
  }

  function view()
  {
    global $WEB_APP;
    global $adodb;
    if (is_confirm_delete_action()) {
      $result = delete_tests($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_tests') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $test = get_test($WEB_APP['id']);
      if (!isset($test->id)) {
        redirect($WEB_APP['errorstext']);
      }

      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_tests_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'delete';
    }

    if ($WEB_APP['action'] == 'view') {
      $section = '';
      $upload_test_file_name = '';
      $files = array();
      // After press Add button.
      if (is_add_form('section')) {
        $correct_post = TRUE;

        $auto_update = isset($_POST['auto_update']);

        if (isset($_POST['import_file'])) {
          foreach ($_POST['import_file'] as $key => $index) {
            if (isset($index) && ($index != '') && isset($_SESSION['xml_files'][$index])) {
              $files[] = $_SESSION['xml_files'][$index];
            }
          }
        } elseif ($_FILES['import_xml_file']['size'] > 0) {
          if ($_FILES['import_xml_file']['error'] == 0) {
            $upload_test_file_name = $_FILES['import_xml_file']['tmp_name'];
            $_SESSION['xml_files'][] = $upload_test_file_name;
            $xml_files[] = array('name' => 0, 'value' => $upload_test_file_name);
            $files[] = $upload_test_file_name;
          } else {
            switch ($_FILES['import_xml_file']['error']) {
              case 1:
                $WEB_APP['errorstext'] .= "The uploaded file exceeds the <i>upload_max_filesize</i> directive in <b>php.ini</b><BR>";
                break;
              case 2:
                $WEB_APP['errorstext'] .= "The uploaded file exceeds the <i>MAX_FILE_SIZE</i> directive that was specified in the HTML form<BR>";
                break;
              case 3:
                $WEB_APP['errorstext'] .= "The uploaded file was only partially uploaded<BR>";
                break;
              case 4:
                $WEB_APP['errorstext'] .= "No file was uploaded<br>";
                break;
              case 6:
                $WEB_APP['errorstext'] .= "Missing a temporary folder. Check <i>upload_tmp_dir</i> directive in <b>php.ini</b><BR>";
                break;
              case 7:
                $WEB_APP['errorstext'] .= "Failed to write file to disk<BR>";
                break;
              case 8:
                $WEB_APP['errorstext'] .= "A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop<BR>";
                break;
            }
            $correct_post = FALSE;
            redirect($WEB_APP['errorstext']);
          }
        }
        if (count($files) == 0) {
          $WEB_APP['errorstext'] .= text('txt_insert_file') . "<br>";
          $correct_post = FALSE;
        }

        if (($_POST['section']) == 0) {

          $WEB_APP['errorstext'] .= text('txt_insert_section') . "<br>";
          $correct_post = FALSE;
        } else {
          $section = get_section($_POST['section']);
          $section = $section->name;
        }

        if ($correct_post) {
          foreach ($files as $file) {
            if ($_FILES['import_xml_file']['size'] > 0) {
              $items = get_xml_data($upload_test_file_name);
            } elseif (isset($_POST['import_file'])) {
              $items = get_xml_data($file);
            }

            if (!array_key_exists("Test/Info/Title", $items)) {
              $WEB_APP['errorstext'] .= $file . ' - ' . text('txt_incorrect_xml_file') . $file;
              redirect($WEB_APP['errorstext']);
              break;
            }

            $title = $items["Test/Info/Title"];
            $media_id = get_value_array($items, 'Test/Info/MediaStorage');
            $guid = get_value_array($items, 'Test/Info/GUID');

            $tmp_id = get_test_id_by_name($title);
            $tmp_guid = get_test_id_by_guid($guid);
            $tmp_media_id = get_test_id_by_multimedia_id($media_id);
            $file_name = basename($file);

            if ((get_test_id_by_name($title) == 0) && ($tmp_media_id == 0) && ($tmp_guid == 0)) {
              $test_id = import_test($file);
              if ($adodb->ErrorMsg() != '') $WEB_APP['errorstext'] .= $adodb->ErrorMsg() . '<br>';
              add_section_test($_POST['section'], $test_id, isset($_POST['hidden']));
            } else {
              if ($tmp_id !== 0) {
                if ($auto_update) {
                  update_test($tmp_id, $file);
                } else {
                  $test_title = get_test_title_by_id($tmp_id);
                  $WEB_APP['errorstext'] .= $file_name . ".&nbsp;" .
                    text('txt_test_already_exist_insert_another_file_name') . "&nbsp; $title.&nbsp;$test_title. <br>";
                }
              } elseif ($tmp_media_id !== 0) {
                $test_title = get_test_title_by_id($tmp_media_id);
                $WEB_APP['errorstext'] .= $file_name . '.&nbsp;' . text('txt_test_with_media_id_already_exist') .
                  "&nbsp; $media_id.&nbsp;$test_title. <br>";
              } elseif ($tmp_guid !== 0) {
                $test_title = get_test_title_by_id($tmp_guid);
                $WEB_APP['errorstext'] .= $file_name . '.&nbsp;' . text('txt_test_with_guid_already_exist') .
                  "&nbsp; $guid.&nbsp;$test_title. <br>";
//                }
              }
            }

          }
          $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
          redirect($WEB_APP['errorstext']);
        }
      } else {
        $auto_update = TRUE;

      }

      if (is_delete_action()) {
        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_tests_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['action'] = 'delete';
      } else {
        $WEB_APP['title'] = text('txt_tests');
        $user_id = get_user_id($_SESSION['user_login']);
        // Get sections. On show sections.
        $WEB_APP['items_count'] = get_tests_count(new test_filter(), $user_id);

        // Pages count.
        $pages = get_pages_count($WEB_APP['items_count'],
          ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);

        if ($WEB_APP['page'] > $pages) {
          $WEB_APP['page'] = $pages;
        }
        $tests = get_tests($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
          new test_filter(), $user_id);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        $WEB_APP['items'] = $tests;

        $paginator = new paginator($WEB_APP['page'], $pages);
        $paginator->url = $WEB_APP['script_name'];
        $paginator->url_query_array = $WEB_APP['url_query_array'];
        $WEB_APP['paginator'] = $paginator;
      }
    }

    $WEB_APP['row_actions'] = array($WEB_APP['action_update'], $WEB_APP['action_questions'], $WEB_APP['action_edit'],
      $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('test_name', text('txt_name')));

    $WEB_APP['escape'] = TRUE;

    if ($WEB_APP['action'] == 'view') {
      // Form fields.
      $fields =
        $this->get_import_form_fields($files, $section, isset($_POST['hidden']), $auto_update, $upload_test_file_name);

      $WEB_APP['fields'] = $fields;
    }
    $WEB_APP['form_enctype'] = TRUE;
    $WEB_APP['view']->display('table.tpl', text('txt_tests'));
  }

  /**
   * @param $files array
   * @param $section string
   * @param $hidden
   * @param $auto_update
   * @param $xml_file
   * @return array
   */
  function get_import_form_fields($files, $section, $hidden, $auto_update, $xml_file)
  {
    $xml_files = $this->get_xml_files();
    $sections = get_sections('section_name');

    $fields = array();
    $fields[] =
      new field(FALSE, text('txt_file'), "multiple_select", "import_file[]", $files, "", $xml_files, "name", "value",
        null, FALSE, '', '', 'data-live-search="true"');
    $fields[] =
      new field(FALSE, text('txt_file'), "file", "import_xml_file", "", "import_xml_file", $xml_file, "name", "value",
        NULL, FALSE, 'text/xml');
    $fields[] =
      new field(TRUE, text('txt_section'), "select", "section", $section, "", $sections, "id", "section_name", null,
        FALSE, '', '', 'data-live-search="true"');
    $fields[] = new field(FALSE, text('txt_hidden'), "checkbox", "hidden", $hidden == "T", 'hidden');
    $fields[] = new field(FALSE, text('txt_automatically_update_exist_tests'), "checkbox", "auto_update", $auto_update,
      'auto_update');
    return $fields;
  }

  function get_xml_files()
  {
    global $WEB_APP;
    $_SESSION['xml_files'] = array();
    $xml_files = array();

    if ($handle = opendir(CFG_TESTS_DIR)) {
      $i = 0;
      while (($file_name = readdir($handle)) !== FALSE) {
        if (strpos($file_name, '.xml') > 0) {
          $tmp_file_name = CFG_TESTS_DIR . $file_name;

          if ($WEB_APP['settings']['use_file_name_charset'] == '1') {
            $file_name = @iconv($WEB_APP['settings']['file_name_charset'], 'utf-8', $file_name);

            if ($file_name === FALSE) {
              $WEB_APP['errorstext'] .= text('txt_incorrect_file_name_charset') . "<br>";
              return array();
            }
          }
          $_SESSION['xml_files'][$i] = $tmp_file_name;
          $xml_files[$i] = array('name' => $i, 'value' => $file_name);
          $i++;
        }
      }
      closedir($handle);
    }

    //        print_r($xml_files);
    //        echo('<br>');
    usort($xml_files, 'cmp_items');
    //        print_r($xml_files);
    return $xml_files;
  }

  function on_confirm_delete()
  {
    global $WEB_APP;
    global $adodb;

    $result = delete_tests($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_tests') . "<br>";
      $this->view();
      exit();
    }
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    redirect($WEB_APP['errorstext']);
  }

  function edit()
  {
    global $WEB_APP;
    global $adodb;

    $WEB_APP['title'] = text('txt_edit_test');
    // After change press.
    $test = get_test($WEB_APP['id']);
    $themes = get_themes_for_test_id($WEB_APP['id']);
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    if (!isset($test->id)) {
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['id'] >= 0) && is_add_edit_form('name')) {
      $correct_post = TRUE;
      if (trim($_POST['name']) == "") {
        $WEB_APP['errorstext'] .= text('txt_insert_test_name') . "<br>";
        $correct_post = FALSE;
      }
      $post_id = get_test_id_by_name(trim($_POST['name']));
      if (!(($post_id == 0) || ($post_id == $WEB_APP['id']))) {
        $WEB_APP['errorstext'] .= text('txt_test_already_exist_insert_another_test_name') . "<br>";
        $correct_post = FALSE;
      }
      $themes = $this->get_post_themes($WEB_APP['id']);
      $questions_count = 0;
      for ($i = 0; $i < sizeof($themes); $i++) {
        $questions_count += $themes[$i]['theme_numexam'];
      }
      if (isset($_POST['is_exam_mode'])) {
        if ($questions_count == 0) {
          $WEB_APP['errorstext'] .= text('txt_no_questions_in_the_test') . "<br>";
          $correct_post = FALSE;
        }
      }

      if ($correct_post) {
        $test = $this->get_post_test();

        edit_test($WEB_APP['id'], $test);
        for ($i = 0; $i < sizeof($themes); $i++) {
          edit_theme($themes[$i]['theme_id'], $themes[$i]);
        }
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      }
      $test = $this->get_post_test();
      $themes = $this->get_post_themes($WEB_APP['id']);

      redirect($WEB_APP['errorstext']);
    }

    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] =
      array(new column('id', 'id'), new column('title', text('txt_name')), new column('hidden', text('txt_hidden')));

    $WEB_APP['escape'] = TRUE;
    $fields = $this->get_edit_form_fields($test, $themes);
    $WEB_APP['show_empty_value'] = FALSE;
    $WEB_APP['fields'] = $fields;
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['view']->display('table.tpl', text('txt_tests'));
  }

  function get_post_themes($test_id)
  {
    $themes = get_themes_for_test_id($test_id);

    for ($i = 0; $i < sizeof($themes); $i++) {
      $id = $themes[$i]['theme_id'];
      $themes[$i]['theme_show_in_results'] = (isset($_POST['show_in_results'][$id]));

      $themes[$i]['theme_numexam'] = $_POST['numexam'][$id];
    }

    return $themes;
  }

  function get_post_test()
  {
    global $WEB_APP;

    $test = get_test($WEB_APP['id']);
    $test->name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
    $test->author = (isset($_POST['author'])) ? trim($_POST['author']) : '';
    $test->type = (isset($_POST['type'])) ? trim($_POST['type']) : '';
    $test->media_storage = (isset($_POST['media_storage'])) ? trim($_POST['media_storage']) : '';
    $test->is_show_results_message = isset($_POST['is_show_results_message']);
    $test->is_show_score = isset($_POST['is_show_score']);
    $test->is_show_explanation = isset($_POST['is_show_explanation']);
    $test->is_response_on_right = isset($_POST['is_response_on_right']);
    $test->text_of_right_message =
      (isset($_POST['text_of_right_message'])) ? trim($_POST['text_of_right_message']) : '';
    $test->is_response_on_wrong = isset($_POST['is_response_on_wrong']);
    $test->text_of_wrong_message =
      (isset($_POST['text_of_wrong_message'])) ? trim($_POST['text_of_wrong_message']) : '';

    $test->is_stat_total = isset($_POST['is_stat_total']);
    $test->is_stat_current = isset($_POST['is_stat_current']);
    $test->is_stat_rights = isset($_POST['is_stat_rights']);
    $test->is_stat_percent_of_rights = isset($_POST['is_stat_percent_of_rights']);
    $test->is_stat_time = isset($_POST['is_stat_time']);
    $test->is_stat_max_time = isset($_POST['is_stat_max_time']);
    $test->is_stat_guid = isset($_POST['is_stat_guid']);
    $test->is_stat_test_version = isset($_POST['is_stat_test_version']);

    $test->is_back = isset($_POST['is_back']);
    $test->may_skip_question = isset($_POST['may_skip_question']);
    $test->allow_not_answer_question = isset($_POST['allow_not_answer_question']);
    $test->is_next_when_right = isset($_POST['is_next_when_right']);

    $test->is_time_limit = isset($_POST['is_time_limit']);
    $test->time_limit = (isset($_POST['time_limit'])) ? trim($_POST['time_limit']) : '';
    $test->max_count = (isset($_POST['max_count'])) ? $_POST['max_count'] : 0;
    $test->is_sort_by_theme = isset($_POST['is_sort_by_theme']);
    $test->is_exam_mode = isset($_POST['is_exam_mode']);
    $test->is_random_answers = isset($_POST['is_random_answers']);
    $test->is_date_limit = isset($_POST['is_date_limit']);
    $test->date_limit_from = (isset($_POST['date_limit_from'])) ? trim($_POST['date_limit_from']) : '';
    $test->date_limit_to = (isset($_POST['date_limit_to'])) ? trim($_POST['date_limit_to']) : '';
    $test->is_show_answers_log = isset($_POST['is_show_answers_log']);

    return $test;
  }

  function get_edit_form_fields($test, $themes)
  {
    $test_types = array();
    $test_types[] = array('name' => 0, 'value' => text('txt_test_type_common'));
    $test_types[] = array('name' => 1, 'value' => text('txt_test_type_scored'));

    $fields = array();
    $fields[] = new field(TRUE, text('txt_name'), "text", "name", $test->name);
    $fields[] = new field(FALSE, text('txt_author'), "text", "author", $test->author);

    $test_type = $test_types[$test->type];
    $fields[] =
      new field(FALSE, text('txt_test_type'), "select", "type", $test_type['value'], "", $test_types, "name", "value");
    $fields[] = new field(FALSE, text('txt_multimedia_id'), "text", "media_storage", $test->media_storage);


    $fields[] = new field(FALSE, text('txt_messages'), "header");
    $fields[] = new field(FALSE, text('txt_display_the_score'), "checkbox", "is_show_score", $test->is_show_score,
      'is_show_score');
    $fields[] =
      new field(FALSE, text('txt_display_comments'), "checkbox", "is_show_explanation", $test->is_show_explanation,
        'is_show_explanation');
    $fields[] = new field(FALSE, text('txt_notify_about_correct_answers'), "checkbox", "is_response_on_right",
      $test->is_response_on_right, 'is_response_on_right');
    $fields[] = new field(FALSE, text('txt_text_of_correct_answers'), "text", "text_of_right_message",
      $test->text_of_right_message);
    $fields[] = new field(FALSE, text('txt_notify_about_incorrect_answers'), "checkbox", "is_response_on_wrong",
      $test->is_response_on_wrong, 'is_response_on_wrong');
    $fields[] = new field(FALSE, text('txt_text_of_incorrect_answers'), "text", "text_of_wrong_message",
      $test->text_of_wrong_message);
    $fields[] = new field(FALSE, text('txt_display_results'), "checkbox", "is_show_results_message",
      $test->is_show_results_message, 'is_show_results_message');


    $fields[] = new field(FALSE, text('txt_display_statistics'), "header");
    $fields[] = new field(FALSE, text('txt_number_of_questions'), "checkbox", "is_stat_total", $test->is_stat_total,
      "is_stat_total");
    $fields[] =
      new field(FALSE, text('txt_current_question_number'), "checkbox", "is_stat_current", $test->is_stat_current,
        'is_stat_current');
    $fields[] =
      new field(FALSE, text('txt_number_of_correct_answers'), "checkbox", "is_stat_rights", $test->is_stat_rights,
        "is_stat_rights");
    $fields[] = new field(FALSE, text('txt_percentage_of_correct_answers'), "checkbox", "is_stat_percent_of_rights",
      $test->is_stat_percent_of_rights, 'is_stat_percent_of_rights');
    $fields[] =
      new field(FALSE, text('txt_time_left'), "checkbox", "is_stat_time", $test->is_stat_time, 'is_stat_time');
    $fields[] = new field(FALSE, text('txt_time_limit'), "checkbox", "is_stat_max_time", $test->is_stat_max_time,
      'is_stat_max_time');
    $fields[] =
      new field(FALSE, text('txt_test_guid'), "checkbox", "is_stat_guid", $test->is_stat_guid, 'is_stat_guid');
    $fields[] =
      new field(FALSE, text('txt_test_version'), "checkbox", "is_stat_test_version", $test->is_stat_test_version,
        'is_stat_test_version');

    $fields[] = new field(FALSE, text('txt_testing'), "header");
    $fields[] = new field(FALSE, text('txt_allow_users_to_go_back'), "checkbox", "is_back", $test->is_back, 'is_back');
    $fields[] =
      new field(FALSE, text('txt_allow_to_skip_questions'), "checkbox", "may_skip_question", $test->may_skip_question,
        'may_skip_question');
    $fields[] = new field(FALSE, text('txt_allow_not_answer_question'), "checkbox", "allow_not_answer_question",
      $test->allow_not_answer_question, 'allow_not_answer_question');
    $fields[] =
      new field(FALSE, text('txt_question_to_proceed_to_if_a_correct_answer'), "checkbox", "is_next_when_right",
        $test->is_next_when_right, 'is_next_when_right');


    $fields[] = new field(FALSE, text('txt_limitations'), "header");
    $fields[] = new field(FALSE, text('txt_testing_time_limit'), "checkbox", "is_time_limit", $test->is_time_limit,
      'is_time_limit');
    $fields[] = new field(TRUE, text('txt_time_limit'), "time", "time_limit", $test->time_limit);

    $fields[] = new field(TRUE, text('txt_maximum_amount_of_testing'), "number", "max_count", $test->max_count);
    $fields[] =
      new field(FALSE, text('txt_may_show_answers_log'), "checkbox", "is_show_answers_log", $test->is_show_answers_log);
    $fields[] = new field(FALSE, text('txt_testing_date_limit'), "checkbox", "is_date_limit", $test->is_date_limit,
      'is_date_limit');
    $fields[] = new field(FALSE, text('txt_from'), "date", "date_limit_from", $test->date_limit_from);
    $fields[] = new field(FALSE, text('txt_to'), "date", "date_limit_to", $test->date_limit_to);

    $fields[] = new field(FALSE, text('txt_topics'), "header");

    $fields[] =
      new field(FALSE, text('txt_sort_questions_by_topics'), "checkbox", "is_sort_by_theme", $test->is_sort_by_theme,
        'is_sort_by_theme');
    $fields[] =
      new field(FALSE, text('txt_shuffle_questions'), "checkbox", "is_exam_mode", $test->is_exam_mode, 'is_exam_mode');
    $fields[] = new field(FALSE, text('txt_shuffle_answers'), "checkbox", "is_random_answers", $test->is_random_answers,
      'is_random_answers');

    foreach ($themes as $theme) {
      $questions = array();
      $id = $theme['theme_id'];
      $count = get_questions_count_for_theme_id($id);
      for ($i = 0; $i <= $count; $i++) {
        $questions[] = array('value' => $i, 'name' => $i);
      }
      $fields[] = new field(FALSE, text('txt_theme') . " &laquo;" . $theme['theme_caption'] . "&raquo;", "header");
      $fields[] = new field(FALSE, text('txt_show_this_topic_in_results'), "checkbox", "show_in_results[$id]",
        $theme['theme_show_in_results'], "show_in_results[$id]");
      $fields[] =
        new field(TRUE, text('txt_questions'), "select", "numexam[$id]", $theme['theme_numexam'], "", $questions,
          "name", "value");
    }

    return $fields;
  }

  function questions()
  {
    global $WEB_APP;
    $WEB_APP['test'] = get_test($WEB_APP['id']);
    if (!isset($WEB_APP['test']->id)) {
      header('Location:' . $WEB_APP['cfg_url'] . '?module=tests');
      exit();
    }

    $WEB_APP['items_count'] = get_questions_count($WEB_APP['id']);
    $pages =
      get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);

    if ($WEB_APP['page'] > $pages) {
      $WEB_APP['page'] = $pages;
    }
    $WEB_APP['questions_text'] = get_questions($WEB_APP['id'], $WEB_APP['page'], $WEB_APP['count']);
    $WEB_APP['questions_count'] = count($WEB_APP['questions_text']);

    $paginator = new paginator($WEB_APP['page'], $pages);
    $paginator->url = $WEB_APP['script_name'];
    $paginator->url_query_array = $WEB_APP['url_query_array'];
    $WEB_APP['paginator'] = $paginator;
    $WEB_APP['test_css'] = $WEB_APP['test']->css;
    $WEB_APP['html_header'] = $WEB_APP['test']->html_header;
    $WEB_APP['title'] = text('txt_view_test_questions') . " &laquo;" . $WEB_APP['test']->name . "&raquo;";
    $WEB_APP['view']->display('questions.tpl', text('txt_tests'));
    $WEB_APP['scripts'][] = 'uppod.js';
  }

  function update()
  {
    global $WEB_APP;

    $test = get_test($WEB_APP['id']);
    if (!isset($test->id)) {
      redirect($WEB_APP['errorstext']);
    }

    $file = (!isset($_POST['file'])) ? "" : $_SESSION['xml_files'][$_POST['file']];

    if (is_add_edit_form("file")) {
      $correct_post = TRUE;
      if ($file == "") {
        $WEB_APP['errorstext'] .= text('txt_insert_file') . "<br>";
        $correct_post = FALSE;
      } else {
        $items = get_xml_data($file);
        $name = $items["Test/Info/Title"];
        $id = get_test_id_by_name($name);
        if (($id != 0) && ($id != $WEB_APP['id'])) {
          $WEB_APP['errorstext'] .= text('txt_test_already_exist_insert_another_file_name') . "<br>";
          $correct_post = FALSE;
        }
      }

      if ($correct_post) {
        update_test($WEB_APP['id'], $file);
        redirect($WEB_APP['errorstext']);
      }
    }

    $test = $test->name;
    $fields = $this->get_update_form_fields($file);

    $WEB_APP['submit_title'] = text('txt_update');
    $WEB_APP['form_title'] = text('txt_test_update') . " &laquo;$test&raquo;";
    $WEB_APP['title'] = text('txt_test_update') . " &laquo;$test&raquo;";
    $WEB_APP['fields'] = $fields;
    $WEB_APP['form_enctype'] = TRUE;
    $WEB_APP['view']->display('table.tpl', text('txt_tests'));
  }

  function get_update_form_fields($file)
  {
    $xml_files = $this->get_xml_files();
    $fields[] = new field(TRUE, text('txt_file'), "select", "file", $file, "", $xml_files, "name", "value");

    return $fields;
  }

}
