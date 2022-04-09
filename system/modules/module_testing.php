<?php

/**
 * @see module_base
 */
class module_testing extends module_base
{
  /** @noinspection PhpUnused */
  public function break_testing()
  {
    $_SESSION['test_braked'] = TRUE;
    echo 1;
    exit;
  }

  /** @noinspection PhpUnused */
  function change_section()
  {
    global $WEB_APP;

    $tst_test_selection_style = $WEB_APP['settings']['tst_test_selection_style'];

    if ($tst_test_selection_style != TEST_SELECT_TEST_FROM_SECTION) {
      die();
    }

    if (isset($_GET['section_id']) && is_scalar($_GET['section_id'])) {
      $section_id = (int)$_GET['section_id'];
    } else {
      die();
    }

    $section = get_section($section_id);
    if (!isset($section->id)) {
      die();
    }
    if ($section->hidden) {
      die();
    }

    $tests = get_unhidden_tests_for_section_id($section->id);
    echo "<option selected=\"\" value=\"\"></option>\n";
    foreach ($tests as $test) {
      printf("<option value=\"%d\">%s</option>\n", $test['id'], htmlspecialchars($test['test_name']));
    }
    die();
  }

  /**
   * @return null
   *
   */
  /** @noinspection PhpUnused */
  function get_test_description()
  {
    if (isset($_GET['test_id']) && is_scalar($_GET['test_id'])) {
      $test_id = (int)$_GET['test_id'];
    } else {
      die();
    }

    $test = get_test($test_id);
    if (!isset($test->id)) {
      die();
    }
    // Check is test enable for current user.
    $result = FALSE;
    $user_id = get_user_id($_SESSION['user_login']);
    $tests = get_tests_for_user_id($user_id);

    foreach ($tests as $tmp) {
      $result = ($result || ($tmp['id'] == $test_id));
    }

    if ($result) {
      echo $test->description;
    }
    die();
  }

  /**
   * @throws \PHPMailer\PHPMailer\Exception
   */
  function view()
  {
    if (isset($_FILES)) $this->upload_record();
    global $WEB_APP;

    if (isset($_POST['current_question']) && isset($_SESSION['test']) && isset($_SESSION['test']['current_question']) &&
      isset($_SESSION['test']['questions']) &&
      isset($_SESSION['test']['questions'][$_SESSION['test']['current_question']])) {
      if ($_POST['current_question'] != $_SESSION['test']['questions'][$_SESSION['test']['current_question']]) {
        header('Location: ' . $WEB_APP['cfg_url'] . '?module=testing');
        exit();
      }
    }

    if (isset($_SESSION['test']['test_id'])) {
      $test = get_test($_SESSION['test']['test_id']);
      if (!isset($test->id)) {
        $this->unset_test();
      }

      if (!isset($_SESSION['test']['user_result_id'])) {
        $this->unset_test();
      } else {
        $user_result = get_user_result($_SESSION['test']['user_result_id']);
        if (!isset($user_result->id)) {
          $this->unset_test();
        } else {
          if ($user_result->completed) {
            $this->unset_test();
          }
        }
      }
    }

    // Проверяем если это get или post запрос выбора теста.
    $id = $this->new_test_check_get_params();
    if ($id !== FALSE) {
      $this->unset_test();
      $this->begin_test($id);
    } elseif ($this->is_select_test()) {
      $id = $this->new_test_check_post_params();
      if ($id !== FALSE) {
        $this->begin_test($id);
      }
    }


    // New test.
    if (!isset($_SESSION['test']['test_id'])) {
      $this->new_test();
    }

    /****************************************
     ***  Update current question to db  ****
     ***************************************/
    if (isset($_SESSION['test']['current_question'])) {
      $user_result = get_user_result($_SESSION['test']['user_result_id']);
      $user_result->test_data = serialize($_SESSION['test']);
      $user_result->test_html_header = $test->html_header;
      $user_result->time_end = gmdate('Y-m-d H:i:s');
      edit_user_result($user_result);
    }

    $correct_answer = TRUE;
    $test = get_test($_SESSION['test']['test_id']);
    $WEB_APP['test_is_random_answers'] = $test->is_random_answers;
    if ($test->is_time_limit == 1) {
      if ($this->get_time_left() == NULL) {
        $correct_answer = FALSE;
      }
    }

    $question = get_question($_SESSION['test']['questions'][$_SESSION['test']['current_question']]);
    $option_count[] = get_options_count_for_question($question->id, OptionSingle);
    $option_count[] = get_options_count_for_question($question->id, OptionMultiple);
    $option_count[] = get_options_count_for_question($question->id, OptionOpen);
    $option_count[] = get_options_count_for_question($question->id, OptionOrdered);
    $option_count[] = get_options_count_for_question($question->id, OptionMatched1);
    $option_count[] = get_options_count_for_question($question->id, OptionMatched2);
    $WEB_APP["question_fields_count"] = count(get_fields_for_question_id($question->id));

    $answer_given = ((($option_count[0] > 0) && (isset($_POST['answer']))) || ($option_count[0] < 1)) ||
      ((($option_count[1] > 0) && (isset($_POST['answers']))) || ($option_count[1] < 1)) ||
      ((($option_count[2] > 0) && (isset($_POST['open_answer']))) || ($option_count[2] < 1));

    if (isset($_POST['submit_button']) && !$answer_given) {
      $_SESSION['errorstext'] = text('txt_insert_answer') . '<BR>';
    }
    if (isset($_POST['submit_button']) && (count($_POST) > 0) && $answer_given) {
      $this->time();
      $out_time = ($this->get_time_left() == NULL) && ($test->is_time_limit == 1);


      $answers = get_answers_for_question_id($question->id);
      $answers_count = count($answers);

      $question_fields = get_fields_for_question_id($question->id);
      $question_fields_count = count($question_fields);

      $user_answer = new user_answer();
      $user_answer->user_result_id = $_SESSION['test']['user_result_id'];
      $user_answer->qnumber = $question->number;
      $user_answer->question = $question->text_html;
      $user_answer->score = 0;
      $question_type = (($question->mode == QUESTION_TYPE_DEFAULT) && ($test->type == TEST_TYPE_COMMON)) ||
      ($question->mode == QUESTION_TYPE_COMMON) ? QUESTION_TYPE_COMMON : QUESTION_TYPE_SCORED;
      $highlighted_answers = array();

      if ($out_time) {
        $user_answer->answer = '';
        $user_answer->explanation = $question->explanation;
        $user_answer->time = get_utc_time();
        $user_answer->is_right = 0;
        $user_answer->theme_id = $_SESSION['test']['user_result_themes'][$question->theme_id]['id'];
        $user_answer->correct_answer = $this->get_correct_answer($answers, $option_count, null, $question_fields);
        $user_answer->score = 0;
        $answer_text = '';

      } else {

        /**********************************
         ***    Initialize values      ****
         *********************************/
        $answer_text = '';
        $correct_answer = null;
        $sequences = null;
        $user_answer->score = 0;
        $user_answer->question_id = $question->id;
        $user_answer->answered = 1;

        /**********************************
         ***    Question fields        ****
         *********************************/
        if (isset($_POST['inline'])) {
          $correct_answer = TRUE;
          $_SESSION['test']['results_fields'][$question->id] = $_POST['inline'];
        }
        $user_answer->answer_fields = '';
        for ($i = 0; $i < $question_fields_count; $i++) {
          $correct_local = isset($_POST['inline'][$i]) &&
            @mb_ereg_match('^(\s*(' . $question_fields[$i]["mask"] . ')\s*)$', $_POST['inline'][$i]);
          if ($correct_local == TRUE) {
            $user_answer->answer_fields .= $_POST['inline'][$i];
            $highlighted_answers[] = $_POST['inline'][$i];
          } else {
            $user_answer->answer_fields .= '<wrong>' . $_POST['inline'][$i] . '</wrong>';
            $highlighted_answers[] = '<wrong>' . $_POST['inline'][$i] . '</wrong>';
          }
          if ($i < $question_fields_count - 1) $user_answer->answer_fields .= FIELDS_DIVIDER;
          $correct_answer = $correct_answer && $correct_local;
          if (($question_type == QUESTION_TYPE_SCORED) && $correct_local) {
            $user_answer->score = $user_answer->score + $question_fields[$i]["score"];
          }
        }


        /**********************************
         ***     Single option         ****
         *********************************/
        if ($option_count[OptionSingle] > 0) {
          $answers_single = get_answers_for_question_id_typed($question->id, OptionSingle);
          if (!isset($_POST['answer']) && !isset($correct_answer)) {
            $correct_answer = FALSE;
            $user_answer->score = 0;
          } else {
            $answer_number = $_POST['answer'];
            $_SESSION['test']['results_single'][$question->id] = $answer_number;
            $answer = $answers_single[$answer_number];
            if (isset($_POST['inline'])) $correct_answer = $correct_answer && $answer['answer_right'] == 1; else
              $correct_answer = $answer['answer_right'] == 1;
            $answer_text = $answer['answer_text_html'];
            if ($question_type == QUESTION_TYPE_SCORED) {
              $user_answer->score = $user_answer->score + $answer['answer_score'];
            } else {
              if ($correct_answer) {
                $user_answer->score = $user_answer->score + $question->weight;
              }
            }
          }
        }

        /**********************************
         ***     Multiple options      ****
         *********************************/
        if ($option_count[OptionMultiple] > 0) {
//          if (!isset($_POST['answers']) && !isset($correct_answer)) {
//            $correct_answer = FALSE;
//            $answer_text = '';
//            $user_answer->answer = '';
//            $user_answer->score = 0;
//          } else {
          $have_answers = isset($correct_answer);
          if (!isset($correct_answer)) {
            $correct_answer = TRUE;
          }
          $answer_numbers = $_POST['answers'];
          $_SESSION['test']['results_multiple'][$question->id] = $answer_numbers;
          $correct_count = 0;
          if (isset($answer_numbers)) {
            foreach ($answer_numbers as $answer_number) {
              $answer = $answers[$answer_number];
              $answer_text .= $answer['answer_text_html'];
              if ($answer['answer_right'] == 1) {
                $correct_count++;
              }
              $correct_answer = $correct_answer && ($answer['answer_right'] == 1);
            }
          }
          $count = 0;
          foreach ($answers as $answer) {
            if (($answer['answer_right'] == 1) && ($answer['answer_option_type'] == OptionMultiple)) {
              $count++;
            }
          }

          if ($have_answers == FALSE) $correct_answer = $correct_answer && ($count == count($answer_numbers));

          if ($correct_answer) {

            if ($question_type == QUESTION_TYPE_SCORED) {
              foreach ($answer_numbers as $answer_number) {
                $answer = $answers[$answer_number];
                $user_answer->score += $answer['answer_score'];
              }
            }
          } else {
            if (count($answer_numbers) > 0 && $question_type == QUESTION_TYPE_SCORED) {
              foreach ($answer_numbers as $answer_number) {
                $answer = $answers[$answer_number];
                $user_answer->score += $answer['answer_score'];
              }
            }
          }
//          }
        }

        /**********************************
         ***      Open answer          ****
         *********************************/
        if ($option_count[OptionOpen] > 0) {
          if (!isset($correct_answer)) {
            $correct_answer = TRUE;
          }
          $open_answer_idx = 0;
          for ($i = 0; $i < $answers_count; $i++) {
            if ($answers[$i]['answer_option_type'] == OptionOpen) {
              $answer = $_POST['open_answers'][$open_answer_idx];
              if ($answers[$i]['answer_bidi'] == 1) {
                $answer = '<div dir="rtl">' . $answer . '</div>';
              }
              if ($i == 0) $answer_text .= str_replace(array("\r\n", "\n"), '<br>', $answer); else
                $answer_text .= '<br>' . str_replace(array("\r\n", "\n"), '<br>', $answer);
              $_SESSION['test']['results_open'][$question->id] = $_POST['open_answers'];
              $right_mask = $answers[$i]['answer_mask'];
              if ($WEB_APP['settings']['admset_regexpformat'] == 'POSIX') {
                if ($question->is_case_sensetive == 1) {
                  $is_correct_answer = @mb_eregi('^(\s*(' . $right_mask . ')\s*)$', $answer);
                } else {
                  $is_correct_answer =
                    @mb_ereg('^(\s*(' . mb_strtoupper($right_mask) . ')\s*)$', mb_strtoupper($answer));
                }
              } else {
                $is_correct_answer = @mb_ereg_match('^(\s*(' . $right_mask . ')\s*)$', $answer);
              }
              if (!$is_correct_answer) $correct_answer = FALSE;
              if ($question_type == QUESTION_TYPE_SCORED) {
                if ($is_correct_answer == TRUE) $user_answer->score =
                  $user_answer->score + $answers[$i]['answer_score'];
              }
              $open_answer_idx++;
            }
          }
          if ($question_type == QUESTION_TYPE_COMMON) {
            $user_answer->score = ($correct_answer ? $user_answer->score + $question->weight : 0);
          }
        }


        /**********************************
         ***     Ordered list          ****
         *********************************/
        if (!isset($correct_answer)) {
          $correct_answer = TRUE;
        }
        if ($option_count[OptionOrdered] > 0) {
          $answer_sequence = $_POST['sequence'];
          $answer_sequence_array = explode(',', $answer_sequence);
          $_SESSION['test']['results_ordered'][$question->id] = $answer_sequence_array;
          $_SESSION['test']['results_ordered_sequence'][$question->id] = $answer_sequence;
          $answer_text .= '<table>';
          foreach ($answer_sequence_array as $key => $value) {
            $answer_text .= '<tr><td>' . $answers[$answer_sequence_array[$key]]['answer_text_html'] . '</td></tr>';
          }
          $answer_text .= '</table>';

          $sequences = get_sequences_for_question_id($question->id);
          $sequences_count = count($sequences);
          $sequence_correct = array();
          $temp_correct = FALSE;
          for ($i = 0; $i < $sequences_count; $i++) {
            $sequence_correct[] = TRUE;
            $sequences_array = explode(',', $sequences[$i]['sequence']);
            for ($j = 0; $j < $answers_count; $j++) {
              if ($sequences_array[$answers[$answer_sequence_array[$j]]['answer_number']] != $j) {
                $sequence_correct[$i] = FALSE;
              }
            }
          }
          for ($i = 0; $i < $sequences_count; $i++) {
            if ($sequence_correct[$i] == TRUE) $temp_correct = TRUE;
          }


          $correct_answer = $correct_answer && $temp_correct;
          if ($question_type == QUESTION_TYPE_SCORED) {
            if ($sequences_count == 1) {
              $sequences_array = explode(',', $sequences[0]['sequence']);
              if ($question->sequence_assess_type == 0) {
                for ($i = 0; $i < $answers_count; $i++) {
                  if ($sequences_array[$answers[$answer_sequence_array[$i]]['answer_number']] == $i) {
                    $user_answer->score += $answers[$i]['answer_score'];
                  }
                }
              } else {
                if ($sequences_array[0] ==
                  $answer_sequence_array[0]) $user_answer->score += $answers[0]["answer_score"];
                for ($i = 1; $i < $answers_count; $i++) {
                  for ($j = 1; $j < $answers_count; $j++) {
                    if (($sequences_array[$j] == $answer_sequence_array[$i]) && ($sequences_array[$j - 1] ==
                        $answer_sequence_array[$i - 1])) $user_answer->score += $answers[$j]["answer_score"];
                  }
                }
              }
            } else {
              for ($i = 0; $i < $sequences_count; $i++) {
                if ($sequence_correct[$i] == TRUE) {
                  $user_answer->score += $sequences[$i]['score'];
                }
              }
            }
          }

          //                    $user_answer->answered = 1;
        }

        /**********************************
         ***     Matched list          ****
         *********************************/
        if ($option_count[OptionMatched1] > 0) {

          $sequence_left = $_POST['sequence_left'];
          if ($sequence_left == '') {
            $sequence_left_array = array();
          } else {
            $sequence_left_array = explode(',', $sequence_left);
          }
          $sequence_left_count = count($sequence_left_array);
          $sequence_left_count_values = array_count_values($sequence_left_array);

          foreach ($sequence_left_count_values as $key => $value) {
            $sequence_left_count_values[$key] = $sequence_left_count_values[$key] - 1;
          }
          $repeated_positions_count_left = array_sum($sequence_left_count_values);

          $positions_left = array();
          for ($i = 0; $i < $sequence_left_count; $i++) {
            $positions_left[] = $answers[$sequence_left_array[$i]]['answer_corresp'];
          }

          $sequence_right = $_POST['sequence_right'];
          if ($sequence_right == '') {
            $sequence_right_array = array();
          } else {
            $sequence_right_array = explode(',', $sequence_right);
          }

          $sequence_right_count = count($sequence_right_array);
          $sequence_right_count_values = array_count_values($sequence_right_array);
          foreach ($sequence_right_count_values as $key => $value) {
            $sequence_right_count_values[$key] = $sequence_right_count_values[$key] - 1;
          }
          $repeated_positions_count_right = array_sum($sequence_right_count_values);

          $positions_right = array();
          for ($i = 0; $i < $sequence_right_count; $i++) {
            $positions_right[] = $answers[$sequence_right_array[$i]]['answer_corresp'];
          }

          $option_count_no_zero[OptionMatched1] = get_no_zero_options_count_for_question($question->id, OptionMatched1);
          $option_count_no_zero[OptionMatched2] = get_no_zero_options_count_for_question($question->id, OptionMatched2);
          if (($_POST['added_left'] > 0) && ($_POST['added_right'] > 0)) {
            $expected_rows_left = $option_count_no_zero[OptionMatched1] + $repeated_positions_count_left;
            $expected_rows_right = $option_count_no_zero[OptionMatched2] + $repeated_positions_count_right;
          } else {
            $expected_rows_left = $option_count_no_zero[OptionMatched1] >= $option_count_no_zero[OptionMatched2] ?
              $option_count_no_zero[OptionMatched1] : $option_count_no_zero[OptionMatched2];
            $expected_rows_right = $expected_rows_left;
          }
          $answer_sequence_left = implode(',', $positions_left);
          $answer_sequence_right = implode(',', $positions_right);
          $correct_answer = $correct_answer && ($answer_sequence_left == $answer_sequence_right) &&
            ($expected_rows_left == $sequence_left_count) && ($expected_rows_right == $sequence_right_count);


          $_SESSION['test']['results_matched_left'][$question->id] = $sequence_left_array;
          $_SESSION['test']['results_matched_right'][$question->id] = $sequence_right_array;
          $_SESSION['test']['results_matched_left_init'][$question->id] = $sequence_left;
          $_SESSION['test']['results_matched_right_init'][$question->id] = $sequence_right;
          $count = $sequence_left_count >= $sequence_right_count ? $sequence_left_count : $sequence_right_count;
          $answer_text .= '<table>';
          for ($i = 0; $i < $count; $i++) {
            $answer_text .= '<tr>';
            $answer_text .= '<td>' . $answers[$sequence_left_array[$i]]['answer_text_html'] . '</td>';
            $answer_text .= '<td>&nbsp;&nbsp;</td>';
            $answer_text .= '<td>' . $answers[$sequence_right_array[$i]]['answer_text_html'] . '</td>';
            $answer_text .= '</tr>';
          }
          $answer_text .= '</table>';

          if ($question_type == QUESTION_TYPE_SCORED) {
            $checked_pairs = array();
            $main_answers = $sequence_left_count < $sequence_right_count ? $sequence_left_array : $sequence_right_array;
            foreach ($main_answers as $key => $value) {
              $pair = $answers[$sequence_left_array[$key]]['answer_number'] . ',' .
                $answers[$sequence_right_array[$key]]['answer_number'];
              if ($answers[$sequence_left_array[$key]]['answer_corresp'] ==
                $answers[$sequence_right_array[$key]]['answer_corresp']) {
                if (!in_array($pair, $checked_pairs)) $user_answer->score = $user_answer->score + $question->weight;
              } else {
                $user_answer->score = $user_answer->score - $question->weight;
              }
              $checked_pairs[] = $pair;
            }
          }
        }

        /**********************************
         ***     Common analyse        ****
         *********************************/
        if ($question_type == QUESTION_TYPE_COMMON) {
          if ($correct_answer == TRUE) $user_answer->score = $question->weight; else
            $user_answer->score = 0;
        }

        $_SESSION['test']['results'][$question->id] = 1;
        $user_answer->answer = $answer_text;
        if (!isset($correct_answer)) {
          $correct_answer = FALSE;
        }
        $user_answer->is_right = $correct_answer ? 1 : 0;
        $user_answer->time = get_utc_time();
        $user_answer->theme_id = $_SESSION['test']['user_result_themes'][$question->theme_id]['id'];
        $user_answer->correct_answer = $this->get_correct_answer($answers, $option_count, $question_fields, $sequences);
        $user_answer->explanation = $question->explanation;
      }
      if (is_showed_question($_SESSION['test']['user_result_id'], $question->id)) {
        edit_user_answer($user_answer);
      } else {
        add_user_answer($user_answer);
      }

      /**********************************
       ***     Show message          ****
       *********************************/
      unset($WEB_APP["right_message_title"]);
      unset($WEB_APP["wrong_message_title"]);
      unset($WEB_APP["user_question"]);
      unset($WEB_APP["user_answer"]);

      if (($test->is_response_on_right == 1) && ($correct_answer)) {
        $WEB_APP["right_message_title"] = str_replace("%SCORE%", $user_answer->score, $test->text_of_right_message);
        if (!isset($_SESSION['correct_answer_message'])) {
          $_SESSION['correct_answer_message'] = '';
        }
        $_SESSION['correct_answer_message'] .= $test->text_of_right_message . '<br>';
      }

      if (($test->is_response_on_wrong == 1) && (!$correct_answer)) {
        $WEB_APP["wrong_message_title"] = str_replace("%SCORE%", $user_answer->score, $test->text_of_wrong_message);
        $WEB_APP["user_answer"] = $user_answer->answer;
        $WEB_APP["user_question"] = $user_answer->question;
        if ($user_answer->answer_fields != "") {
          $WEB_APP["user_question"] = '<div id="fill_fields_message">' . $WEB_APP["user_question"] . "</div>";
          if (defined('HIGHTLIGHT_WRONG_FIELDS') and (HIGHTLIGHT_WRONG_FIELDS == TRUE) and
            ($test->is_show_answers_log == 1) and ($test->is_show_explanation == 1)) {
            $WEB_APP["hightlight_fields"] = TRUE;
            $WEB_APP['results_fields_message'] = json_encode($highlighted_answers);
          } else {
            $WEB_APP['results_fields_message'] = json_encode($_SESSION['test']['results_fields'][$question->id]);
            $WEB_APP["hightlight_fields"] = FALSE;
          }
        }
      }
      $on_right_explanation = ($test->is_response_on_right == 1) && $correct_answer;
      $on_wrong_explanation = ($test->is_response_on_wrong == 1) && (!$correct_answer);
      if (($test->is_show_explanation == 1) && ($on_right_explanation || $on_wrong_explanation)) {
        $WEB_APP["explanation"] = str_replace('\n', '<br>', $question->explanation);
        $WEB_APP["explanation"] = str_replace("%SCORE%", $user_answer->score, $WEB_APP["explanation"]);
        if (trim(strip_tags($WEB_APP["explanation"])) == '') $WEB_APP["explanation"] = '';
      }

      $WEB_APP['correct_answer_message'] =
        isset($_SESSION['correct_answer_message']) ? $_SESSION['correct_answer_message'] : '';
      $WEB_APP['errorstext'] = isset($_SESSION['errorstext']) ? $_SESSION['errorstext'] : '';


      $_SESSION['test']['score'][$question->id] = $user_answer->score;
      $_SESSION['test']['previous_question_id'] = $question->id;
      $_SESSION['test']['previous_question'] = $question->text_html;
      $_SESSION['test']['previous_answer'] = $answer_text;
      $_SESSION['test']['correct_answer'] = $correct_answer;


      $right_questions = get_right_questions($_SESSION['test']['user_result_id']);
      $answered_questions = get_answered_questions($_SESSION['test']['user_result_id']);
      $questions_count = count($_SESSION['test']['questions']);
      $percent_right = round($right_questions / $questions_count * 100, $WEB_APP['settings']['admset_percprecision']);
      $test = get_test($_SESSION['test']['test_id']);
      $user_id = get_user_id($_SESSION['user_login']);

      $user_result = new user_result();
      $user_result->completed_questions = $answered_questions;
      $user_result->id = $_SESSION['test']['user_result_id'];
      $user_result->percent_right = $percent_right;
      $user_result->results = '';
      $user_result->right_questions = $right_questions;
      $user_result->score = array_sum($_SESSION['test']['score']);
      $user_result->test = $test->id;
      $user_result->test_title = $test->name;
      $user_result->time_begin = gmdate('Y-m-d H:i:s', $_SESSION['test']['start_time']);
      $user_result->out_of_time = ((($this->get_time_left() == NULL) && ($test->is_time_limit == 1)) ? 1 : 0);
      $user_result->completed =
        ($user_result->out_of_time == 1) ? 1 : (($answered_questions == $questions_count) ? 1 : 0);
      if ($test->is_next_when_right && !$user_answer->is_right) $user_result->completed = FALSE;
      $user_result->time_end = gmdate('Y-m-d H:i:s');
      $user_result->total_questions = $questions_count;
      $user_result->user = $user_id;
      $user_result->test_data = serialize($_SESSION['test']);
      $user_result->test_html_header = $test->html_header;

      edit_user_result($user_result);

      $_SESSION['test']['correct_answers'] = $right_questions;
      if ($test->is_next_when_right == 1) {
        if ($correct_answer) {
          $_SESSION['test']['current_question']++;
          if ($_SESSION['test']['current_question'] == count($_SESSION['test']['questions'])) {
            $_SESSION['test']['all_questions'] = FALSE;
            $_SESSION['test']['current_question'] = 0;
          }
          if (!$_SESSION['test']['all_questions']) {
            $cc = $_SESSION['test']['current_question'];
            $qs = $_SESSION['test']['questions'][$cc];
            $rs = $_SESSION['test']['results'][$qs];
            while ((@$rs !== NULL) && ($_SESSION['test']['current_question'] < count($_SESSION['test']['questions']))) {
              if (!in_array(NULL, $_SESSION['test']['results'], TRUE)) {
                $_SESSION['test']['current_question'] = count($_SESSION['test']['questions']);
                break;
              }

              $_SESSION['test']['current_question']++;
              if (in_array(NULL, $_SESSION['test']['results'], TRUE) &&
                ($_SESSION['test']['current_question'] == count($_SESSION['test']['questions']))) {
                $_SESSION['test']['current_question'] = 0;
              }
              $cc = $_SESSION['test']['current_question'];
              if (!isset($_SESSION['test']['questions'][$cc])) {
                break;
              }
              $qs = $_SESSION['test']['questions'][$cc];
              $rs = $_SESSION['test']['results'][$qs];
            }
          }
        } else {
          $_SESSION['errorstext'] = text('txt_insert_correct_answer') . '<br>';
        }
      } else {
        if (($test->allow_not_answer_question == 0) && ($_SESSION['test']['current_question'] >= 0) &&
          (count($_POST) == 1)) {
          $_SESSION['errorstext'] = text('txt_insert_answer') . '<br>';
        } else {
          $_SESSION['test']['current_question']++;

          if ($_SESSION['test']['current_question'] == count($_SESSION['test']['questions'])) {
            $_SESSION['test']['all_questions'] = FALSE;
            $_SESSION['test']['current_question'] = 0;
          }
          if (!$_SESSION['test']['all_questions']) {
            $cc = $_SESSION['test']['current_question'];
            $qs = $_SESSION['test']['questions'][$cc];
            $rs = $_SESSION['test']['results'][$qs];
            while ((@$rs !== NULL) && ($_SESSION['test']['current_question'] < count($_SESSION['test']['questions']))) {
              if (!in_array(NULL, $_SESSION['test']['results'], TRUE)) {
                $_SESSION['test']['current_question'] = count($_SESSION['test']['questions']);
                break;
              }
              $_SESSION['test']['current_question']++;
              if (in_array(NULL, $_SESSION['test']['results'], TRUE) &&
                ($_SESSION['test']['current_question'] == count($_SESSION['test']['questions']))) {
                $_SESSION['test']['current_question'] = 0;
              }

              $cc = $_SESSION['test']['current_question'];
              if (!isset($_SESSION['test']['questions'][$cc])) {
                break;
              }
              $qs = $_SESSION['test']['questions'][$cc];
              $rs = $_SESSION['test']['results'][$qs];
            }
          }
        }
      }
    }


    if ($test->is_time_limit == 1) {
      $is_end_time = ($this->get_time_left() == NULL);
    } else {
      $is_end_time = FALSE;
    }

    if (($_SESSION['test']['current_question'] >= count($_SESSION['test']['questions'])) || $is_end_time ||
      (isset($_SESSION['test_braked']) && $_SESSION['test_braked'])) {

      if ($is_end_time) {
        $_SESSION['errorstext'] = text('txt_overtime') . '<br>';
      }

      if (isset($_SESSION['test_braked']) && $_SESSION['test_braked']) $test_breaked = TRUE; else $test_breaked = FALSE;

      $test = get_test($_SESSION['test']['test_id']);

      if ($test->concl_type == 0) {
        $resume_text = get_resume_text_for_user_result_id($_SESSION['test']['user_result_id']);
        $conclusions_text = get_conclusions_text_for_user_result_id($_SESSION['test']['user_result_id']);
      } else {
        // Get max score.
        $all_questions = get_questions_for_test_id($test->id);
        $themes = get_themes_for_test_id($test->id);
        $max_scores = array();
        foreach ($themes as $theme) {
          $max_scores[$theme['theme_id']] = 0;
        }
        $max_score = 0;
        foreach ($all_questions as $tmp_question) {
          if (in_array($tmp_question['question_id'], $_SESSION['test']['questions'])) {
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
                  $max_scores[$tmp_question['question_theme_id']] += $tmp_question['question_weight'] *
                    count($answers) / 2;
                  break;
              }
            }
          }
        }

        $resume_text = get_resume_text_for_user_result_id_by_max_score($_SESSION['test']['user_result_id'], $max_score);
        $conclusions_text =
          get_conclusions_text_for_user_result_id_by_max_scores($_SESSION['test']['user_result_id'], $max_scores);

      }
      $right_questions = get_right_questions($_SESSION['test']['user_result_id']);
      $answered_questions = get_answered_questions($_SESSION['test']['user_result_id']);
      $questions_count = count($_SESSION['test']['questions']);

      $percent_right =
        round($right_questions / max(1, $questions_count) * 100, $WEB_APP['settings']['admset_percprecision']);
      $test = get_test($_SESSION['test']['test_id']);
      $user_id = get_user_id($_SESSION['user_login']);

      $user_result = new user_result();
      $user_result->completed_questions = $answered_questions;
      $user_result->id = $_SESSION['test']['user_result_id'];
      $user_result->percent_right = $percent_right;
      $user_result->results = $resume_text;
      $user_result->right_questions = $right_questions;
      $user_result->score = array_sum($_SESSION['test']['score']);
      $user_result->test = $test->id;
      $user_result->test_title = $test->name;
      $user_result->time_begin = gmdate('Y-m-d H:i:s', $_SESSION['test']['start_time']);
      $user_result->out_of_time = ((($this->get_time_left() == NULL) && ($test->is_time_limit == 1)) ? 1 : 0);
      $user_result->completed =
        ($user_result->out_of_time == 1) ? 1 : ((($answered_questions == $questions_count) || $test_breaked) ? 1 : 0);
      $user_result->time_end = gmdate('Y-m-d H:i:s');
      $user_result->total_questions = $questions_count;
      $user_result->user = $user_id;
      $user_result->test_data = serialize($_SESSION['test']);
      $user_result->test_html_header = $test->html_header;

      edit_user_result($user_result);

      $user_result = get_user_result($_SESSION['test']['user_result_id']);

      foreach ($conclusions_text as $user_result_theme) {
        foreach ($_SESSION['test']['user_result_themes'] as $value) {
          if ($value['title'] == $user_result_theme->theme) {
            edit_user_result_theme($value['id'], $_SESSION['test']['user_result_id'], $user_result_theme->theme,
              $user_result_theme->result);
          }
        }

      }
      if ($WEB_APP['settings']['tst_showrating'] == 1) {
        $user_results = get_top_user_results_for_test_id($_SESSION['test']['test_id']);
        for ($i = 0; $i < count($user_results); $i++) {
          $user_results[$i]['id'] = $i + 1;
        }

        // Table titles.
        $columns = array();
        $columns[] = new column('id', '#');
        $columns[] = new column('user_name', text('txt_user_name'));
        $columns[] = new column('user_result_percent_right', text('txt_percentage_of_correct_answers'));
      } else {
        $user_results = array();
        $columns = array();
      }

      unset($_SESSION['test']['test_id']);
      unset($_SESSION['test']['current_question']);
      unset($_SESSION['test']['previous_question']);
      unset($_SESSION['test']['previous_answer']);
      unset($_SESSION['test']['results']);
      unset($_SESSION['test_braked']);

      $stat = array();

      if ($test->is_stat_total == 1) {
        $stat[] = array('name' => text('txt_total_questions'), 'value' => $user_result->total_questions);
      }

      $stat[] = array('name' => text('txt_answered'), 'value' => $user_result->completed_questions);

      if ($test->is_stat_rights == 1) {
        $stat[] = array('name' => text('txt_correct_answers'), 'value' => $user_result->right_questions);
      }

      if ($WEB_APP['settings']['tst_showpercent'] == 1) {
        $stat[] = array('name' => text('txt_percentage_of_correct_answers'), 'value' => $user_result->percent_right);
      }
      $stat[] = array('name' => text('txt_testing_began'), 'value' => $user_result->time_begin);
      $stat[] = array('name' => text('txt_testing_finished'), 'value' => $user_result->time_end);

      if ($test->is_show_score == 1) {
        $stat[] = array('name' => text('txt_total_scores'), 'value' => $user_result->score);
      }

      $stat_columns = array();
      $stat_columns[] = new column('name', text('txt_param'));
      $stat_columns[] = new column('value', text('txt_value'));

      $user = get_user($user_id);

      // Send email
      $user_result->results = str_replace('<br>', '\r\n', $user_result->results);

      if ($WEB_APP['settings']['tst_resmailuser_send'] == 1) {
        $message = str_replace('%TEST%', $user_result->test_title, $WEB_APP['settings']['tst_resmailuser_template']);
        $message = str_replace('%RESULTS%', $user_result->results, $message);
        $message = str_replace('%RIGHTCOUNT%', $user_result->right_questions, $message);
        $message = str_replace('%SCORE%', $user_result->score, $message);
        $message = str_replace('%TIMEBEGIN%', $user_result->time_begin, $message);
        $message = str_replace('%TIMEEND%', $user_result->time_end, $message);
        $message = str_replace('%USERNAME%', $user->name, $message);
        $message = str_replace('%GROUP%', $user->group, $message);

        $subject = str_replace('%TEST%', $user_result->test_title, $WEB_APP['settings']['tst_resmailuser_subject']);
        $subject = str_replace('%RESULTS%', $user_result->results, $subject);
        $subject = str_replace('%RIGHTCOUNT%', $user_result->right_questions, $subject);
        $subject = str_replace('%SCORE%', $user_result->score, $subject);
        $subject = str_replace('%TIMEBEGIN%', $user_result->time_begin, $subject);
        $subject = str_replace('%TIMEEND%', $user_result->time_end, $subject);
        $subject = str_replace('%USERNAME%', $user->name, $subject);
        $subject = str_replace('%GROUP%', $user->group, $subject);

        send_mail(MAIL_FROM_NAME, $WEB_APP['settings']['tst_resmailuser_from'], $user->mail, $subject, $message);
      }

      if ($WEB_APP['settings']['admset_resmail_send'] == 1) {
        $message = str_replace('%TEST%', $user_result->test_title, $WEB_APP['settings']['admset_resmail_template']);
        $message = str_replace('%RESULTS%', $user_result->results, $message);
        $message = str_replace('%RIGHTCOUNT%', $user_result->right_questions, $message);
        $message = str_replace('%SCORE%', $user_result->score, $message);
        $message = str_replace('%TIMEBEGIN%', $user_result->time_begin, $message);
        $message = str_replace('%TIMEEND%', $user_result->time_end, $message);
        $message = str_replace('%USERNAME%', $user->name, $message);
        $message = str_replace('%GROUP%', $user->group, $message);

        $subject = str_replace('%TEST%', $user_result->test_title, $WEB_APP['settings']['admset_resmail_subject']);
        $subject = str_replace('%RESULTS%', $user_result->results, $subject);
        $subject = str_replace('%RIGHTCOUNT%', $user_result->right_questions, $subject);
        $subject = str_replace('%SCORE%', $user_result->score, $subject);
        $subject = str_replace('%TIMEBEGIN%', $user_result->time_begin, $subject);
        $subject = str_replace('%TIMEEND%', $user_result->time_end, $subject);
        $subject = str_replace('%USERNAME%', $user->name, $subject);
        $subject = str_replace('%GROUP%', $user->group, $subject);

        send_mail(MAIL_FROM_NAME, $WEB_APP['settings']['admset_resmail_from'],
          $WEB_APP['settings']['admset_resmail_to'], $subject, $message);
      }

      $user_result_themes = get_user_result_themes_for_user_result_id($_SESSION['test']['user_result_id']);

      $user_result_titles = array();
      $user_result_titles[] = array('name' => 'header', 'value' => text('txt_results'));
      $user_result_titles[] = array('name' => text('txt_result'), 'value' => $user_result->results);

      $tmp_themes = $this->get_show_in_results_array();
      foreach ($user_result_themes as $user_result_theme) {
        if ($tmp_themes[$user_result_theme['user_result_themes_id']] == 1) {
          $user_result_titles[] = array('name' => $user_result_theme['user_result_themes_theme_caption'],
            'value' => $user_result_theme['user_result_themes_result']);
        }
      }
      $WEB_APP['user_result_themes'] = $user_result_titles;
      $WEB_APP['stat'] = $stat;
      $WEB_APP['stat_columns'] = $stat_columns;
      $WEB_APP['stat_rows_count'] = sizeof($stat);
      $WEB_APP['stat_columns_count'] = count($stat_columns);
      $WEB_APP['errorstext'] = isset($_SESSION['errorstext']) ? $_SESSION['errorstext'] : '';
      $WEB_APP['correct_answer_message'] =
        isset($_SESSION['correct_answer_message']) ? $_SESSION['correct_answer_message'] : '';
      unset($_SESSION['correct_answer_message']);
      $WEB_APP['tst_showstats'] = $WEB_APP['settings']['tst_showstats'] == 1;
      $WEB_APP['tst_showrating'] =
        ($WEB_APP['settings']['tst_showrating'] == 1) && ($WEB_APP['settings']['tst_ratingquantity'] > 0);
      $WEB_APP['rows'] = $user_results;
      $WEB_APP['rows_count'] = count($user_results);
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['columns'] = $columns;
      $WEB_APP['action'] = 'view';
      $WEB_APP['user_result'] = $user_result;
      $WEB_APP['resume_text'] = $resume_text;
      $WEB_APP['columns_count'] = count($columns);
      $WEB_APP['test_name'] = $test->name;
      $WEB_APP['title'] = text('txt_view_results');
      $WEB_APP['is_show_results_message'] = $test->is_show_results_message;
      $WEB_APP['show_answers_log'] = $test->is_show_answers_log;
      $WEB_APP['result_id'] = $user_result->id;
      $WEB_APP['test_css'] = $test->css;
      $this->unset_test();
      $WEB_APP['scripts'][] = 'question.js';
      $WEB_APP['scripts'][] = 'uppod.js';
      if ($option_count[OptionOpen] > 0) {
        $WEB_APP['scripts'][] = '/record/WebAudioRecorder.min.js';
        $WEB_APP['scripts'][] = '/record/record.js';
      }
      $WEB_APP['html_header'] = $test->html_header;
      $_SESSION['errorstext'] =
        str_replace(text('txt_complete_current_testing_before_new'), '', $_SESSION['errorstext']);
      $WEB_APP['view']->display('end_test.tpl', $WEB_APP['title']);
      unset($_SESSION['bm']);
      unset($_SESSION['bcid']);
      exit();
    }

    $question = get_question($_SESSION['test']['questions'][$_SESSION['test']['current_question']]);
    $hint_number = $question->hint;

    $hint = get_hint($_SESSION['test']['test_id'], $hint_number);
    if (!empty($hint)) $WEB_APP['hint'] = $hint; else unset($WEB_APP['hint']);


    if (!isset($_SESSION['test']['answers'][$question->id])) {
      $answers_array = array();
    } else {
      $answers_array = $_SESSION['test']['answers'][$question->id];
    }
    $answers = array();

    foreach ($answers_array as $answer_index) {
      $answers[] = get_answer($answer_index);
    }
    $answers_count = count($answers);
    $question_number = $_SESSION['test']['current_question'] + 1;
    $test = get_test($_SESSION['test']['test_id']);

    $WEB_APP['current_question'] = $_SESSION['test']['current_question'] + 1;

    $WEB_APP['stat_total'] = $test->is_stat_total == 1;
    $WEB_APP['stat_current'] = $test->is_stat_current == 1;
    $WEB_APP['stat_rights'] = $test->is_stat_rights == 1;
    $WEB_APP['stat_percent_of_rights'] = $test->is_stat_percent_of_rights == 1;
    $WEB_APP['stat_time'] = $test->is_stat_time == 1;
    $WEB_APP['stat_max_time'] = $test->is_stat_max_time == 1;
    $WEB_APP['stat_guid'] = $test->is_stat_guid == 1;
    $WEB_APP['stat_test_version'] = $test->is_stat_test_version == 1;

    $WEB_APP['tst_showquestionnumber'] = $WEB_APP['settings']['tst_showquestionnumber'] == 1;

    // Определяем тип мультимедийного файла
    $ext = strtolower(substr($question->picture_url, -3));
    // Изображение
    if (($ext == 'gif') || ($ext == 'jpg') || ($ext == 'png') || ($ext == 'bmp')) $mmtype = 'img'; elseif (($ext ==
        'mid') || ($ext == 'midi') || ($ext == 'ra') || ($ext == 'ram') || ($ext == 'mpg') || ($ext == 'mpeg') ||
      ($ext == 'mp3') || ($ext == 'mp2') || ($ext == 'wav') || ($ext == 'au') || ($ext == 'aif') || ($ext == 'aiff')) {
      if (($ext == 'mid') || ($ext == 'midi')) $mmtype = 'audio/x-midi'; elseif (($ext == 'ra') || ($ext == 'ram'))
        $mmtype = 'audio/x-pr-realaudio';
      elseif (($ext == 'mpg') || ($ext == 'mpeg') || ($ext == 'mp3') || ($ext == 'mp2')) $mmtype = 'audio/x-mpeg';
      elseif ($ext == 'wav') $mmtype = 'audio/x-wav';
      elseif ($ext == 'au') $mmtype = 'audio/basic';
      elseif (($ext == 'aif') || ($ext == 'aiff')) $mmtype = 'audio/x-aiff';
    }
    $WEB_APP['mmtype'] = isset($mmtype) ? $mmtype : '';

    if ($test->is_stat_rights == 1) {
      $WEB_APP['correct_answers'] = $_SESSION['test']['correct_answers'];
    }
    $WEB_APP['is_back'] = ($test->is_back == 1);
    $WEB_APP['tst_showstats'] = ($WEB_APP['settings']['tst_showstats'] == 1);
    $_SESSION['test']['tst_allowstoskip'] = $test->may_skip_question == 1;
    if ($test->is_time_limit == 1) {
      $WEB_APP['time_left'] = $this->get_time_left();
      $WEB_APP['full_time_left'] = $this->get_full_time_left();
    } else {
      $WEB_APP['time_left'] = text('txt_unrestrictedly');
    }
    if ($test->time_limit == 1) {
      $WEB_APP['time_limit'] = $test->time_limit;
    } else {
      $WEB_APP['time_limit'] = text('txt_unrestrictedly');
    }
    $WEB_APP['test_guid'] = $test->guid;
    $WEB_APP['question'] = $question;

    if ($test->is_stat_total == 1) {
      $WEB_APP['total_questions'] = count($_SESSION['test']['questions']);
    }

    $WEB_APP['show_time_left'] = $test->is_stat_time == 1;
    $WEB_APP['answers'] = $answers;
    if (isset($_SESSION['test']['results'][$question->id])) {
      $WEB_APP['results'] = $_SESSION['test']['results'][$question->id];
    } else {
      $WEB_APP['results'] = NULL;
    }
    if (isset($_SESSION['test']['results_fields'][$question->id])) {
      $WEB_APP['results_fields'] = json_encode($_SESSION['test']['results_fields'][$question->id]);
    } else {
      $WEB_APP['results_fields'] = NULL;
    }
    if (isset($_SESSION['test']['results_single'][$question->id])) {
      $WEB_APP['results_single'] = $_SESSION['test']['results_single'][$question->id];
    } else {
      $WEB_APP['results_single'] = NULL;
    }
    if (isset($_SESSION['test']['results_multiple'][$question->id])) {
      $WEB_APP['results_multiple'] = $_SESSION['test']['results_multiple'][$question->id];
    } else {
      $WEB_APP['results_multiple'] = NULL;
    }
    if (isset($_SESSION['test']['results_open'][$question->id])) {
      $WEB_APP['results_open'] = $_SESSION['test']['results_open'][$question->id];
    } else {
      $WEB_APP['results_open'] = NULL;
    }
    if (isset($_SESSION['test']['results_ordered'][$question->id])) {
      $WEB_APP['results_ordered'] = $_SESSION['test']['results_ordered'][$question->id];
    } else {
      $WEB_APP['results_ordered'] = NULL;
    }
    if (isset($_SESSION['test']['results_ordered_sequence'][$question->id])) {
      $WEB_APP['results_ordered_sequence'] = $_SESSION['test']['results_ordered_sequence'][$question->id];
    } else {
      $WEB_APP['results_ordered_sequence'] = NULL;
    }
    if (isset($_SESSION['test']['results_matched_left'][$question->id])) {
      $WEB_APP['results_matched_left'] = $_SESSION['test']['results_matched_left'][$question->id];
    } else {
      $WEB_APP['results_matched_left'] = NULL;
    }
    if (isset($_SESSION['test']['results_matched_right'][$question->id])) {
      $WEB_APP['results_matched_right'] = $_SESSION['test']['results_matched_right'][$question->id];
    } else {
      $WEB_APP['results_matched_right'] = NULL;
    }
    if (isset($_SESSION['test']['results_matched_left_init'][$question->id])) {
      $WEB_APP['results_matched_left_init'] = $_SESSION['test']['results_matched_left_init'][$question->id];
    } else {
      $WEB_APP['results_matched_left_init'] = NULL;
    }
    if (isset($_SESSION['test']['results_matched_right_init'][$question->id])) {
      $WEB_APP['results_matched_right_init'] = $_SESSION['test']['results_matched_right_init'][$question->id];
    } else {
      $WEB_APP['results_matched_right_init'] = NULL;
    }

    $questions_count = count($_SESSION['test']['questions']);
    $right_questions = get_right_questions($_SESSION['test']['user_result_id']);

    $WEB_APP['answers_count'] = sizeof($answers);
    if ($_SESSION['test']['current_question'] == (count($_SESSION['test']['questions']) - 1)) $WEB_APP['submit_title'] =
      text('txt_finish'); else if ((question_have_answer_options($question->id) == TRUE) ||
      (question_have_fields($question->text_html) == TRUE)) $WEB_APP['submit_title'] = text('txt_answer'); else
      $WEB_APP['submit_title'] = text('txt_next');
    $WEB_APP['show_break_button'] =
      ($WEB_APP['submit_title'] != text('txt_finish')) || ($test->is_next_when_right == TRUE);
    $WEB_APP['media_storage'] = $test->media_storage;
    $WEB_APP['test_css'] = $test->css;

    $WEB_APP['previous_question'] =
      isset($_SESSION['test']['previous_question']) ? $_SESSION['test']['previous_question'] : '';
    $WEB_APP['previous_answer'] =
      isset($_SESSION['test']['previous_answer']) ? $_SESSION['test']['previous_answer'] : '';
    $WEB_APP['tst_showpercent'] = $WEB_APP['settings']['tst_showpercent'] == 1;
    $WEB_APP['percent_right'] =
      round($right_questions / $questions_count * 100, $WEB_APP['settings']['admset_percprecision']);
    $WEB_APP['errorstext'] = isset($_SESSION['errorstext']) ? $_SESSION['errorstext'] : '';
    $WEB_APP['correct_answer_message'] =
      isset($_SESSION['correct_answer_message']) ? $_SESSION['correct_answer_message'] : '';
    $WEB_APP['correct_answer'] = isset($_SESSION['test']['correct_answer']) ? $_SESSION['test']['correct_answer'] : '';
    if ($WEB_APP['settings']['tst_showquestionnumber'] == 1) {
      $question_number = $_SESSION['test']['current_question'] + 1;
    }

    $_SESSION['test']['user_result_time_id'] = 0;
    $this->time();

    $WEB_APP['scripts'][] = 'question.js?v=1';
    $WEB_APP['scripts'][] = 'jquery.timer.js';
    $WEB_APP['scripts'][] = 'jquery.hotkeys.min.js';
    $WEB_APP['scripts'][] = 'uppod.js';
    $WEB_APP['scripts'][] = 'question.js';
    $WEB_APP['scripts'][] = 'record/WebAudioRecorder.min.js';
    $WEB_APP['scripts'][] = 'record/record.js';

    unset($option_count);

    $option_count[] = get_options_count_for_question($question->id, OptionSingle);
    $option_count[] = get_options_count_for_question($question->id, OptionMultiple);
    $option_count[] = get_options_count_for_question($question->id, OptionOpen);
    $option_count[] = get_options_count_for_question($question->id, OptionOrdered);
    $option_count[] = get_options_count_for_question($question->id, OptionMatched1);
    $option_count[] = get_options_count_for_question($question->id, OptionMatched2);

    unset($WEB_APP['option_count']);
    $WEB_APP['option_count'][] = $option_count[OptionSingle];
    $WEB_APP['option_count'][] = $option_count[OptionMultiple];
    $WEB_APP['option_count'][] = $option_count[OptionOpen];
    $WEB_APP['option_count'][] = $option_count[OptionOrdered];
    $WEB_APP['option_count'][] = $option_count[OptionMatched1];
    $WEB_APP['option_count'][] = $option_count[OptionMatched2];

    if ($WEB_APP['option_count'][OptionOrdered] > 0) {
      $WEB_APP['init_sequence'] = '';
      $answer_numbers = array();
      foreach ($answers as $answer) {
        $answer_numbers[] = $answer->number;
      }
      $WEB_APP['init_sequence'] = implode(',', $answer_numbers);
    }

    if ($WEB_APP['option_count'][OptionMatched1] > 0) {
      $WEB_APP['matched'][] = 'true';
      $positions_array = array();
      $numbers = array();
      for ($i = 0; $i < $answers_count; $i++) {
        if ($answers[$i]->option_type == OptionMatched1) {
          $positions_array[] = $answers[$i]->corresp;
          $numbers[] = $answers[$i]->number;
        }
      }
      $WEB_APP['init_sequence_left'] = implode(',', $numbers);
      $positions_unique_array = array_unique($positions_array, SORT_NUMERIC);
      $positions_left_count = count($positions_array);
      $WEB_APP['added_left'] = $positions_left_count - count($positions_unique_array);
    }

    if ($WEB_APP['option_count'][OptionMatched2] > 0) {
      $WEB_APP['matched'][] = 'true';
      $positions_array = array();
      $numbers = array();
      for ($i = 0; $i < $answers_count; $i++) {
        if ($answers[$i]->option_type == OptionMatched2) {
          $positions_array[] = $answers[$i]->corresp;
          $numbers[] = $answers[$i]->number;
        }
      }
      $WEB_APP['init_sequence_right'] = implode(',', $numbers);
      $positions_unique_array = array_unique($positions_array, SORT_NUMERIC);
      $positions_right_count = count($positions_array);
      $WEB_APP['added_right'] = $positions_right_count - count($positions_unique_array);
    }

    $WEB_APP['title'] = text('txt_question') . ' ' . $question_number;
    $WEB_APP['html_header'] = $test->html_header;
    $WEB_APP['test_name'] = $test->name;
    $WEB_APP['test_id'] = $test->id;
    $WEB_APP['result_id'] = $_SESSION['test']['user_result_id'];
    $WEB_APP['question_number'] = $question_number;
    if ($question->voice_record_time_limited) $WEB_APP['max_record_time'] =
      strtotime("1970-01-01 $question->voice_record_max_time UTC"); else $WEB_APP['max_record_time'] = 600;
    $WEB_APP['user_must_answer'] = ($test->allow_not_answer_question == 0) &&
      (($option_count[OptionMultiple] > 0) || ($option_count[OptionSingle] > 0));
    $WEB_APP['view']->display('question.tpl',
      $test->name . ". " . text('txt_question') . ' ' . $question_number . ". " . text('txt_result') . " ID: " .
      $_SESSION['test']['user_result_id']);
    unset($_SESSION['errorstext']);
    unset($_SESSION['correct_answer_message']);
  }

  function upload_record()
  {
    if (isset($_SESSION['user_id']) && isset($_FILES)) {
      $user_id = $_SESSION['user_id'];
      $input = $_FILES['audio_data_' . $user_id]['tmp_name'];
      if (file_exists($input)) {
        $upload_dir = "records/$user_id/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0744);
        $output = $upload_dir . $_FILES['audio_data_' . $user_id]["name"];
        move_uploaded_file($input, $output);
      }
    }
  }

  function unset_test()
  {
    unset($_SESSION['errorstext']);
    unset($_SESSION['correct_answer_message']);
    unset($_SESSION['test']);
  }

  /**
   * @return int or false
   */
  function new_test_check_get_params()
  {
    if (isset($_GET['bm'])) {
      $_SESSION['bm'] = $_GET['bm'];
    }

    // Just if not current active testing
    if (!isset($_SESSION['test']['test_id'])) {
      //Book chapter to return after testing
      if (isset($_GET['bcid'])) {
        $_SESSION['bcid'] = $_GET['bcid'];
      }
      //Book to return after testing
      if (isset($_GET['bbid'])) {
        $_SESSION['bbid'] = (int)$_GET['bbid'];
      } elseif (isset($_GET['bmid'])) {
        $_SESSION['bbid'] = get_book_id_by_multimedia_id($_GET['bmid']);
      }
    }

    if ((isset($_GET['tid']) || isset($_GET['tname']) || isset($_GET['tmid']) || isset($_GET['test_id']))) {
      if (isset($_SESSION['test']['test_id'])) {
        $_SESSION['errorstext'] = text('txt_complete_current_testing_before_new');
      } else {
        $id = 0;
        if (isset($_GET['tid']) && is_string($_GET['tid'])) {
          $id = $_GET['tid'];
        } elseif (isset($_GET['test_id']) && is_string($_GET['test_id'])) {
          $id = $_GET['test_id'];
        } elseif (isset($_GET['tname']) && is_string($_GET['tname'])) {
          $id = get_test_id_by_name($_GET['tname']);
        } elseif (isset($_GET['tmid']) && is_string($_GET['tmid'])) {
          $id = get_test_id_by_multimedia_id($_GET['tmid']);
        }

        return $this->check_new_test_id($id);
      }
    }

    return FALSE;
  }

  /**
   * @param $test_id
   * @return integer or false
   */
  function check_new_test_id($test_id)
  {
    global $WEB_APP;

    $user_id = get_user_id($_SESSION['user_login']);
    $test = get_test($test_id);

    if (!isset($test->id)) {
      $WEB_APP['errorstext'] = text('txt_choose_test') . '<br>';
      return FALSE;
    }
    # Check is user have access to test.
    $access = test_available_for_user($_SESSION['user_login'], $test_id);
    if ($access === FALSE) {
      $WEB_APP['errorstext'] = text('txt_choose_test') . '<br>';
      return FALSE;
    }

    # Check is test from section.
    $tst_test_selection_style = $WEB_APP['settings']['tst_test_selection_style'];

    if ($tst_test_selection_style == TEST_SELECT_TEST_FROM_SECTION) {
      if (isset($_POST['section_id']) && is_string($_POST['section_id'])) {
        $section_id = (int)$_POST['section_id'];
        $access = FALSE;
        $tests = get_unhidden_tests_for_section_id($section_id);

        foreach ($tests as $tmp) {
          $access = $access || ($tmp['id'] == $test_id);
        }

        if ($access === FALSE) {
          $WEB_APP['errorstext'] = text('txt_choose_test') . '<br>';
          return FALSE;
        }
      }
    }

    $count = get_user_results_count_for_test_id($user_id, $test_id);
    if (($count >= $test->max_count) && ($test->max_count > 0)) {
      $WEB_APP['errorstext'] .= text('txt_you_exceed_maximal_amount_of_testing_for_this_test') . '<br>';
      return FALSE;
    }

    $questions = $this->get_questions_array($test);
    if (count($questions) == 0) {
      $WEB_APP['errorstext'] = text('txt_no_questions_choose_another_test') . '<br>';
      return FALSE;
    }

    return $test_id;
  }

  function get_questions_array($test)
  {
    $questions_array = array();
    $themes = get_themes_for_test_id($test->id);

    /** Randomize themes  **/
    if ($test->questions_order == SortTestByRandomThemes) {
      $themes_count = count($themes);
      for ($i = 0; $i < $themes_count; $i++) {
        $tmp_index = rand(0, $themes_count - 1);
        $tmp = $themes[$i];
        $themes[$i] = $themes[$tmp_index];
        $themes[$tmp_index] = $tmp;
      }
    }


    /** Get all questions for themes. **/
    for ($i = 0; $i < count($themes); $i++) {
      $questions_array[$i] = array();
      $theme = $themes[$i];
      $questions = get_questions_for_theme_id($theme['theme_id']);
      foreach ($questions as $question) {
        $questions_array[$i][] = $question;
      }
    }


    for ($i = 0; $i < count($themes); $i++) {
      $theme = $themes[$i];
      $questions_count = count($questions_array[$i]);
      if ($test->is_exam_mode == 1) {
        for ($j = 0; $j < $questions_count; $j++) {
          $tmp_index = rand(0, $questions_count - 1);
          $tmp = $questions_array[$i][$j];
          $questions_array[$i][$j] = $questions_array[$i][$tmp_index];
          $questions_array[$i][$tmp_index] = $tmp;
        }
      }
      if ($test->is_exam_mode == 0) {
        $count = get_questions_count_for_theme_id($theme['theme_id']);
      } else {
        $count = $theme['theme_numexam'];
      }

      $questions_array[$i] = array_slice($questions_array[$i], 0, $count);
    }

    $questions = array();
    for ($i = 0; $i < count($themes); $i++) {
      foreach ($questions_array[$i] as $question) {
        $questions[] = $question;
      }
    }


    if (($test->questions_order == SortTestNone) && ($test->is_exam_mode == 1)) {
      $questions_count = count($questions);
      for ($j = 0; $j < $questions_count; $j++) {
        $tmp_index = rand(0, $questions_count - 1);
        $tmp = $questions[$j];
        $questions[$j] = $questions[$tmp_index];
        $questions[$tmp_index] = $tmp;
      }
    }

    /** Sort by question number. **/
    if (($test->questions_order == SortTestNone) && ($test->is_exam_mode == 0)) {
      $array = array();
      $questions_count = count($questions);
      for ($j = 0; $j < $questions_count; $j++) {
        $array[$questions[$j]['question_number']] = $questions[$j];
      }

      ksort($array);
      $questions = $array;
    }

    /** Get question id array **/
    $array = array();
    foreach ($questions as $question) {
      $array[] = $question['question_id'];
    }
    return $array;
  }

  /**
   * @param $test_id int
   */
  function begin_test($test_id)
  {
    global $WEB_APP;

    $user_id = get_user_id($_SESSION['user_login']);
    $test = get_test($test_id);
    $_SESSION['test']['test_id'] = $test_id;

    $questions = $this->get_questions_array($test);
    if (!isset($_SESSION['test']['questions'])) {
      $_SESSION['test']['questions'] = $questions;
    }

    $answers = $this->get_answers_for_questions_array($test, $questions);
    $_SESSION['test']['results'] = array();
    $_SESSION['test']['score'] = array();
    for ($i = 0; $i < count($_SESSION['test']['questions']); $i++) {
      $_SESSION['test']['results'][$_SESSION['test']['questions'][$i]] = NULL;
    }

    $_SESSION['test']['answers'] = $answers;
    $_SESSION['test']['time_limit'] = $test->time_limit;
    $_SESSION['test']['start_time'] = time();
    $_SESSION['test']['correct_answers'] = 0;
    $_SESSION['test']['user_result_id'] =
      add_user_result($user_id, count($questions), $test_id, serialize($_SESSION['test']));
    $_SESSION['test']['current_question'] = 0;
    $_SESSION['test']['all_questions'] = TRUE;

    // Build user_result_themes
    $_SESSION['test']['user_result_themes'] = array();
    $themes = get_themes_for_test_id($test->id);
    foreach ($themes as $theme) {
      $_SESSION['test']['user_result_themes'][$theme['theme_id']] = array();
      $_SESSION['test']['user_result_themes'][$theme['theme_id']]['title'] = $theme['theme_caption'];
      $_SESSION['test']['user_result_themes'][$theme['theme_id']]['show_in_results'] = $theme['theme_show_in_results'];
      $_SESSION['test']['user_result_themes'][$theme['theme_id']]['id'] =
        add_user_result_theme($_SESSION['test']['user_result_id'], $theme['theme_caption'], '');
    }
    header('Location: ' . $WEB_APP['cfg_url'] . '?module=testing');
    exit();

  }

  function get_answers_for_questions_array($test, $questions_array)
  {
    $answers_array = array();
    foreach ($questions_array as $question) {
      $answers = get_answers_for_question_id($question);
      $answers_array[$question] = array();

      $answers_count = count($answers);
      for ($i = 0; $i < $answers_count; $i++) {
        $answer = $answers[$i];
        $answers_array[$question][$i] = $answer['answer_id'];
      }
      if ($test->is_random_answers == 1) {
        $question_class = get_question($question);
        if ($question_class->type == 3) {
          $answers_count = count($answers) / 2;
        } else {
          $answers_count = count($answers);
        }
        for ($j = 0; $j < $answers_count; $j++) {
          $tmp_index = rand(0, $answers_count - 1);
          $tmp = $answers_array[$question][$j];
          $answers_array[$question][$j] = $answers_array[$question][$tmp_index];
          $answers_array[$question][$tmp_index] = $tmp;
        }
      }
    }

    return $answers_array;
  }

  function is_select_test()
  {
    return ((!isset($_SESSION['test']['test_id'])) &&
      (isset($_POST['test_id']) || isset($_POST['section_id']) || isset($_GET['tid']) || isset($_GET['tname']) ||
        isset($_GET['tmid']) || isset($_GET['test_id'])));
  }

  function new_test_check_post_params()
  {
    global $WEB_APP;

    if (count($_POST) > 0) {
      $id = 0;
      if (isset($_POST['test_id']) && is_string($_POST['test_id'])) {
        $id = (int)$_POST['test_id'];
      }

      $tst_test_selection_style = $WEB_APP['settings']['tst_test_selection_style'];

      if ($tst_test_selection_style == TEST_SELECT_TEST_FROM_SECTION) {
        if (isset($_POST['section_id']) && is_string($_POST['section_id'])) {
          $section_id = (int)$_POST['section_id'];
          $user_id = get_user_id($_SESSION['user_login']);
          $sections = get_unhidden_sections_for_user_id($user_id);
          $result = FALSE;

          foreach ($sections as $section) {
            $result = $result || ($section['id'] == $section_id);
          }

          if (!$result) {
            $WEB_APP['errorstext'] .= text('txt_choose_section') . '<BR>';
            return FALSE;
          }
        } else {
          $WEB_APP['errorstext'] .= text('txt_choose_section') . '<BR>';
          return FALSE;
        }
      }

      return $this->check_new_test_id($id);
    }

    return FALSE;
  }

  private function new_test()
  {
    global $WEB_APP;

    unset($_SESSION['test']['test_id']);
    unset($_SESSION['test']['current_question']);
    unset($_SESSION['test']['previous_question']);
    unset($_SESSION['test']['previous_answer']);
    unset($_SESSION['test']['questions']);

    $tst_test_selection_style = $WEB_APP['settings']['tst_test_selection_style'];

    if ($tst_test_selection_style == TEST_SELECT_TEST_FROM_SECTION) {
      $this->section_form();
    } else {
//      $this->all_tests_form();
      $this->all_tests_list_form();
    }

    exit();
  }

  private function section_form()
  {
    global $WEB_APP;

    $user_id = get_user_id($_SESSION['user_login']);
    $sections = get_unhidden_sections_for_user_id($user_id);
    $tests = array();
    $section = '';
    $test = '';

    if (count($_POST) > 0) {
      if (isset($_POST['section_id']) && is_scalar($_POST['section_id'])) {
        $tmp = get_section($_POST['section_id']);
        if (isset($tmp->id)) {
          $section = $tmp->name;
          $tests = get_unhidden_tests_for_section_id($tmp->id);
        }
      }
    }

    $fields[] =
      new field(TRUE, text('txt_section'), 'select', 'section_id', $section, '', $sections, 'id', 'section_name',
        'return change_section()', FALSE, '', '', 'data-live-search="true"');
    $fields[] = new field(TRUE, text('txt_test'), 'select', 'test_id', $test, '', $tests, 'id', 'test_name',
      'return get_test_description()', TRUE, '', '', 'data-live-search="true"');

    $WEB_APP['fields'] = $fields;
    $WEB_APP['description_field'] = 'test_description';
    $WEB_APP['title'] = text('txt_testing');
    $WEB_APP['form_title'] = text('txt_change');
    $WEB_APP['submit_title'] = text('txt_choose');
    $WEB_APP['view']->display('form_page.tpl', $WEB_APP['title']);
  }

  private function all_tests_list_form()
  {
    global $WEB_APP;

    $user_id = get_user_id($_SESSION['user_login']);
    $tests = get_tests_for_user_id($user_id);
    for ($i = 0; $i < count($tests); $i++) {
      if ($tests[$i]['test_is_date_limit'] == 0) {
        $tests[$i]['test_date_limit_from'] = '-';
        $tests[$i]['test_date_limit_to'] = '-';
        $tests[$i]['test_name'] =
          "<a href='?module=testing&tid=" . $tests[$i]['id'] . "'>" . $tests[$i]['test_name'] . "</a>";
      } elseif ((time() >= strtotime($tests[$i]['test_date_limit_from'])) &&
        (time() <= strtotime($tests[$i]['test_date_limit_to']))) $tests[$i]['test_name'] =
        "<a href='?module=testing&tid=" . $tests[$i]['id'] . "'>" . $tests[$i]['test_name'] . "</a>";
    }

//    $WEB_APP['items_count'] = get_tests_for_user_id($user_id, TRUE);
    $pages = get_pages_count(0, ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
    if ($WEB_APP['page'] > $pages) {
      $WEB_APP['page'] = $pages;
    }
    $columns = array();
    $columns[] = new column('test_name', text('txt_test'));
    $columns[] = new column('test_date_limit_from', text('txt_from'));
    $columns[] = new column('test_date_limit_to', text('txt_to'));
    $columns[] = new column('test_description', text('txt_description'));
    $paginator = new paginator($WEB_APP['page'], $pages);
    $paginator->url = $WEB_APP['script_name'];
    $paginator->url_query_array = $WEB_APP['url_query_array'];
    $WEB_APP['paginator'] = $paginator;
    $WEB_APP['rows'] = $tests;
    $WEB_APP['rows_count'] = count($tests);
    $WEB_APP['columns_count'] = count($columns);
    $WEB_APP['columns'] = $columns;
    $WEB_APP['show_table'] = TRUE;
    $WEB_APP['show_form'] = FALSE;
    $WEB_APP['show_table_only'] = TRUE;
    $WEB_APP['description_field'] = 'test_description';
    $WEB_APP['title'] = text('txt_testing');
    $WEB_APP['view']->display('table_array_rows.tpl', $WEB_APP['title']);

  }

  function get_time_left()
  {
    if (!isset($_SESSION['test']['user_result_id'])) {
      return NULL;
    }
    $user_result_id = $_SESSION['test']['user_result_id'];
    $time = get_user_result_test_time($user_result_id);

    list($h, $m, $s) = preg_split('/:/', $_SESSION['test']['time_limit']);
    $time_limit = $h * 60 * 60 + $m * 60 + $s;
    $time_left = $time_limit - $time;
    if ($time_left <= 0) {
      return NULL;
    }

    return gmdate('H:i:s', $time_left);
  }

  public function time()
  {
    if ((!isset($_SESSION['test']['user_result_id'])) || (!isset($_SESSION['test']['current_question']))) {
      return;
    }

    $current_question = $_SESSION['test']['current_question'];

    if (!isset($_SESSION['test']['questions'][$current_question])) {
      return;
    }

    if (!isset($_SESSION['test']['user_result_time_id'])) {
      return;
    }
  }

  function get_correct_answer($answers, $option_count, $fields, $sequences = null)
  {
    $answer_text = '';
    $answers_count = count($answers);
    if (isset($fields)) {
      $answer_fields = array();
      foreach ($fields as $field) {
        $answer_fields[] = $field["mask"];
      }
      $answer_text = implode(FIELDS_DIVIDER, $answer_fields);
    }

    if (($option_count[OptionMultiple] > 0) || ($option_count[OptionSingle] > 0)) {
      $items = array();
      foreach ($answers as $answer) {
        if ((isset($answer['answer_right']) && ($answer['answer_right'] == 1)) &&
          (($answer['answer_option_type'] == OptionMultiple) || ($answer['answer_option_type'] == OptionSingle))) {
          $items[] = $answer['answer_text_html'];
        }
      }
      $answer_text .= implode("\n", $items);
    }

    if ($option_count[OptionOpen] > 0) {
      foreach ($answers as $answer) {
        if ($answer['answer_option_type'] == OptionOpen) {
          $answer_text = $answer_text . $answer['answer_mask'] . ', ';
        }
      }
      $answer_text = substr_replace($answer_text, '', strlen($answer_text) - 2, 2);
    }

    if ($option_count[OptionOrdered] > 0) {
      $sequences_count = count($sequences);
      $answers_matrix = null;
      $answer_text .= '<table>';
      if ($sequences_count > 1) $count = $answers_count + 1; else $count = $answers_count;

      for ($i = 0; $i < $sequences_count; $i++) {
        $sequences_array = explode(',', $sequences[$i]['sequence']);
        for ($j = 0; $j < $answers_count; $j++) {
          $answers_matrix[$i][$j] = $sequences_array[$j];
        }
      }

      array_unshift($answers_matrix, null);
      $answers_matrix = call_user_func_array('array_map', $answers_matrix);

      $matrix = array();
      for ($i = 0; $i < $answers_count; $i++) {
        for ($j = 0; $j < $sequences_count; $j++) {
          if ($sequences_count > 1) $answer_position = $answers_matrix[$i][$j]; else $answer_position =
            $answers_matrix[$i];
          $matrix[$answer_position][$j] = $answers[$i]['answer_text_html'];
        }
      }

      for ($i = 0; $i < $count; $i++) {
        $answer_text .= '<tr>';
        if (($sequences_count > 1) && ($i == 0)) {
          for ($j = 0; $j < $sequences_count; $j++) {
            $answer_text .= '<th>Option ' . ($j + 1) . '</th><td>&nbsp;&nbsp;</td>';
          }
          $answer_text .= '</tr>';
        }
        for ($j = 0; $j < $sequences_count; $j++) {
          $answer_text .= '<td>';
          $answer_text .= $matrix[$i][$j];
          $answer_text .= '</td><td>&nbsp;&nbsp;</td>';
        }
        $answer_text .= '</tr>';

      }
      $answer_text .= '</table>';
    }

    if ($option_count[OptionMatched1] > 0) {
      $answer_text .= '<table>';
      $answers2 = $answers;
      if ($option_count[OptionMatched1] >= $option_count[OptionMatched2]) {
        $big_option = OptionMatched1;
        $small_option = OptionMatched2;
      } else {
        $big_option = OptionMatched2;
        $small_option = OptionMatched1;
      }
      foreach ($answers as $answer_left) {
        if ($answer_left['answer_option_type'] == $big_option) {
          $answer_text .= '<tr>';
          foreach ($answers2 as $answer_right) {
            if (($answer_right['answer_option_type'] == $small_option) &&
              ($answer_left['answer_corresp'] == $answer_right['answer_corresp'])) {
              if ($big_option == OptionMatched1) {
                $answer_text .= '<td>' . $answer_left['answer_text_html'] . '</td>';
                $answer_text .= '<td>&nbsp;&nbsp;</td>';
                $answer_text .= '<td>' . $answer_right['answer_text_html'] . '</td>';
              } else {
                $answer_text .= '<td>' . $answer_right['answer_text_html'] . '</td>';
                $answer_text .= '<td>&nbsp;&nbsp;</td>';
                $answer_text .= '<td>' . $answer_left['answer_text_html'] . '</td>';
              }

            }
          }
          $answer_text .= '</tr>';
        }
      }
      $answer_text .= '</table>';
    }

    // To prevent "player" and "style" ID's repeat of question text.
    $regex = '/(player\d{3,4})/m';
    $answer_text = preg_replace($regex, "$1_a", $answer_text);

    $regex = '/(style\d{3,4})/m';
    return preg_replace($regex, "$1_a", $answer_text);
  }

  /**
   * Get theme_show_in_results params array from themes session info.
   *
   * @return array keys - user result theme id, values - int (0, 1) is show result.
   */
  protected function get_show_in_results_array()
  {
    $themes = array();
    foreach ($_SESSION['test']['user_result_themes'] as $theme) {
      $themes[$theme['id']] = $theme['show_in_results'];
    }

    return $themes;
  }

  function get_full_time_left()
  {
    if (!isset($_SESSION['test']['user_result_id'])) {
      return NULL;
    }
    $user_result_id = $_SESSION['test']['user_result_id'];
    $time = get_user_result_test_time($user_result_id);

    list($h, $m, $s) = preg_split('/:/', $_SESSION['test']['time_limit']);
    $time_limit = $h * 60 * 60 + $m * 60 + $s;
    $time_left = $time_limit - $time;
    if ($time_left <= 0) {
      return NULL;
    }
    return gmdate('Y-m-d', $time_left) . "T" . gmdate('H:i:s', $time_left);
  }

  /** @noinspection PhpUnused */
  public function back_question()
  {
    if (isset($_SESSION['test']) && is_array($_SESSION['test']) && isset($_SESSION['test']['current_question']) &&
      isset($_SESSION['test']['test_id'])) {
      $test = get_test($_SESSION['test']['test_id']);

      if (isset($test->is_back) && ($test->is_back == 1) && ($_SESSION['test']['current_question'] > 0)) {
        $_SESSION['test']['current_question']--;
        echo '1';
      }
    }
    exit;
  }

  /** @noinspection PhpUnused */
  public function skip_question()
  {
    global $WEB_APP;

    if (isset($_SESSION['test']) && is_array($_SESSION['test']) && isset($_SESSION['test']['current_question']) &&
      isset($_SESSION['test']['test_id']) && isset($WEB_APP['settings']) && is_array($WEB_APP['settings']) &&
      isset($_SESSION['test']['tst_allowstoskip']) && $_SESSION['test']['tst_allowstoskip'] == TRUE) {
      $_SESSION['test']['current_question']++;

      if ($_SESSION['test']['current_question'] == count($_SESSION['test']['questions'])) {
        $_SESSION['test']['all_questions'] = FALSE;
        $_SESSION['test']['current_question'] = 0;
      }
      if (!$_SESSION['test']['all_questions']) {
        while (($_SESSION['test']['results'][$_SESSION['test']['questions'][$_SESSION['test']['current_question']]] !==
            NULL) && ($_SESSION['test']['current_question'] < count($_SESSION['test']['questions']))) {
          $_SESSION['test']['current_question']++;
          if (in_array(NULL, $_SESSION['test']['results'], TRUE) &&
            ($_SESSION['test']['current_question'] == count($_SESSION['test']['questions']))) {
            $_SESSION['test']['current_question'] = 0;
          }
        }
      }

      echo 1;
    }

    exit;
  }

  /** @noinspection PhpUnused */
  private function all_tests_form()
  {
    global $WEB_APP;

    $user_id = get_user_id($_SESSION['user_login']);
    $tests = get_tests_for_user_id($user_id);
    $test = '';

    $fields[] = new field(TRUE, text('txt_test'), 'select', 'test_id', $test, '', $tests, 'id', 'test_name',
      'return get_test_description()', TRUE);

    $WEB_APP['fields'] = $fields;
    $WEB_APP['description_field'] = 'test_description';
    $WEB_APP['title'] = text('txt_testing');
    $WEB_APP['form_title'] = text('txt_change');
    $WEB_APP['submit_title'] = text('txt_choose');
    $WEB_APP['view']->display('form_page.tpl', $WEB_APP['title']);

  }
}
