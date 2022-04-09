<?php

/**
 * Specify question object. Questions table.
 */
class question
{
  /**
   * @var int question id
   */
  var $id;

  /**
   * @var int original question id
   */
  var $guid;

  /**
   * @var int mode: Default, Control, Score
   */
  var $mode;

  /**
   * @var bool may answer be partially right (not used now)
   */
  var $partially_right_answer;

  /**
   * @var bool glued with previous question
   */
  var $glued;

  /**
   * @var int test id
   */
  var $test;

  /**
   * @var string question text
   */
  var $text;

  /**
   * @var int question number
   */
  var $number;

  /**
   * @var bool question time is limited?
   */
  var $time_limited;

  /**
   * @var string question time
   */
  var $time;

  /**
   * @var boolean Voice Record available?
   */
  var $voice_record;

  /**
   * @var boolean Voice Record time limited?
   */
  var $voice_record_time_limited;

  /**
   * @var string Voice Record maximum time
   */
  var $voice_record_max_time;


  /**
   * @var int question type
   */
  var $type;

  /**
   * @var string question explanation
   */
  var $explanation;

  /**
   * binary question image
   */
  var $image;

  /**
   * @var string question reference
   */
  var $reference;

  /**
   * @var string question etalon
   */
  var $etalon;

  /**
   * @var int etalon score
   */
  var $etalon_score;

  /**
   * @var int (0, 1) case sensetive
   */
  var $is_case_sensetive;

  /**
   * @var int theme id
   */
  var $theme_id;

  /**
   * @var string question text html
   */
  var $text_html;

  /**
   * binary question text
   */
  var $text_bin;

  /**
   * @var string question picture url
   */
  var $picture_url;

  /**
   * @var int question weight
   */
  var $weight;

  /**
   * @var int question hint id
   */
  var $hint;

  /**
   * @var int question sequence assess type
   */
  var $sequence_assess_type;

  /**
   * @var string question caption for List1 of matched question
   */
  var $matched_list1_caption;


  /**
   * @var string question caption for List2 of matched question
   */
  var $matched_list2_caption;

  /**
   * @var bool question show basket for left list
   */
  var $show_basket1;

  /**
   * @var bool question show basket for right list
   */
  var $show_basket2;

}

/**
 * Add question.
 *
 * @param $p_question question class
 *
 * @return ADORecordSet or false
 */
function add_question($p_question)
{
  $t_question_text = db_prepare_string($p_question->text);
  $t_question_number = db_prepare_int($p_question->number);
  $t_question_time = db_prepare_string($p_question->time);
  $t_question_type = db_prepare_int($p_question->type);
  $t_question_explanation = db_prepare_string($p_question->explanation);
  $t_question_image = ($p_question->image == NULL) ? 'NULL' : db_prepare_string($p_question->image);
  $t_question_reference = db_prepare_string($p_question->reference);
  $t_question_etalon = db_prepare_string($p_question->etalon);
  $t_question_etalon_score = db_prepare_string($p_question->etalon_score);
  $t_question_is_case_sensetive = db_prepare_int($p_question->is_case_sensetive);
  $t_question_theme_id = db_prepare_int($p_question->theme_id);
  $t_question_text_html = db_prepare_string($p_question->text_html);
  $t_question_text_bin = ($p_question->text_bin == NULL) ? 'NULL' : db_prepare_string($p_question->text_bin);
  $t_question_picture_url = db_prepare_string($p_question->picture_url);
  $t_question_weight = db_prepare_float($p_question->weight);
  $t_question_hint = db_prepare_int($p_question->hint);

  // Added in version 4
  $t_question_guid = db_prepare_string($p_question->guid);
  $t_question_mode = db_prepare_int($p_question->mode);
  $t_question_partially_right_answer = db_prepare_int($p_question->partially_right_answer);
  $t_question_glued = db_prepare_int($p_question->glued);
  $t_question_time_limited = db_prepare_int($p_question->time_limited);
  $t_question_voice_record = db_prepare_int($p_question->voice_record);
  $t_question_voice_record_time_limited = db_prepare_int($p_question->voice_record_time_limited);
  $t_question_voice_record_max_time = db_prepare_string($p_question->voice_record_max_time);
  $t_question_sequence_assess_type = db_prepare_int($p_question->sequence_assess_type);
  $t_question_matched_list1_caption = db_prepare_string($p_question->matched_list1_caption);
  $t_question_matched_list2_caption = db_prepare_string($p_question->matched_list2_caption);
  $t_question_show_basket1 = db_prepare_int($p_question->show_basket1);
  $t_question_show_basket2 = db_prepare_int($p_question->show_basket2);


  $query = "INSERT INTO  " . DB_TABLE_QUESTIONS . "(
            `question_text`,
            `question_number`,
            `question_time`,
            `question_type`,
            `question_explanation`,
            `question_image`,
            `question_reference`,
            `question_etalon`,
            `question_etalon_score`,
            `question_is_case_sensetive`,
            `question_theme_id`,
            `question_text_html`,
            `question_text_bin`,
            `question_picture_url`,
            `question_weight`,
            `question_hint`,
            `question_css`,
            `question_guid`,
            `question_mode`,
            `question_partially_right_answer`,
            `question_glued`,
            `question_time_limited`,
            `question_voice_record`,
            `question_voice_record_time_limited`,
            `question_voice_record_max_time`,
            `question_sequence_assess_type`,
            `question_matched_list1_caption`,
            `question_matched_list2_caption`,
            `question_show_basket1`,
            `question_show_basket2`
            )
         VALUES (
         $t_question_text,
            $t_question_number,
            $t_question_time,
            $t_question_type,
            $t_question_explanation,
            $t_question_image,
            $t_question_reference,
            $t_question_etalon,
            $t_question_etalon_score,
            $t_question_is_case_sensetive,
            $t_question_theme_id,
            $t_question_text_html,
            $t_question_text_bin,
            $t_question_picture_url,
            $t_question_weight,
            $t_question_hint,
            '',
            $t_question_guid,
            $t_question_mode,
            $t_question_partially_right_answer,
            $t_question_glued,
            $t_question_time_limited,
            $t_question_voice_record,
            $t_question_voice_record_time_limited,
            $t_question_voice_record_max_time,
            $t_question_sequence_assess_type,
            $t_question_matched_list1_caption,
            $t_question_matched_list2_caption,
            $t_question_show_basket1,
            $t_question_show_basket2
            )";

  return db_exec($query);
}

/**
 * Get questions.
 *
 * @param $p_id int test id
 * @param $p_page int
 * @param $p_count int
 *
 * @return array string questions
 */
function get_questions($p_id, $p_page = 1, $p_count = 0)
{
  $t_id = db_prepare_int($p_id);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $limit_str = "$limit, $t_count";
  } else {
    $limit_str = '';
  }

  $query = 'SELECT ' . DB_TABLE_QUESTIONS . '.`question_text_html`
           FROM  ' . DB_TABLE_TESTS . ', ' . DB_TABLE_QUESTIONS . ', ' . DB_TABLE_THEMES . ' WHERE ' . DB_TABLE_THEMES .
    '.`theme_test_id` = ' . DB_TABLE_TESTS . '.`id`
          AND   ' . DB_TABLE_QUESTIONS . '.`question_theme_id` = ' . DB_TABLE_THEMES . '.`theme_id`
          AND   ' . DB_TABLE_TESTS . '.`id` = ' . $t_id . ' ORDER BY question_number ';
  if ($limit_str != '') {
    $query .= ' LIMIT ' . $limit_str;
  }

  return db_query($query);
}

/**
 * Get questions count for test id.
 *
 * @param $p_id int test id
 *
 * @return int questions count
 */
function get_questions_count($p_id)
{
  $t_id = db_prepare_int($p_id);
  $query = 'SELECT COUNT(' . DB_TABLE_QUESTIONS . '.`question_text_html`) AS `_count_`
           FROM  ' . DB_TABLE_TESTS . ', ' . DB_TABLE_QUESTIONS . ', ' . DB_TABLE_THEMES . ' WHERE ' . DB_TABLE_THEMES .
    '.`theme_test_id` = ' . DB_TABLE_TESTS . '.`id`
          AND   ' . DB_TABLE_QUESTIONS . '.`question_theme_id` = ' . DB_TABLE_THEMES . '.`theme_id`
          AND   ' . DB_TABLE_TESTS . '.`id` = ' . $t_id;

  $result = db_query($query);

  return isset($result[0]['_count_']) ? $result[0]['_count_'] : 0;
}

/**
 * Get questions array for test id.
 *
 * @param $p_id int test id
 *
 * @return array questions array
 */
function get_questions_for_test_id($p_id)
{
  $t_id = db_prepare_int($p_id);

  $query = 'SELECT ' . DB_TABLE_QUESTIONS . '.*
           FROM  ' . DB_TABLE_TESTS . ', ' . DB_TABLE_QUESTIONS . ', ' . DB_TABLE_THEMES . ' WHERE ' . DB_TABLE_THEMES .
    '.`theme_test_id` = ' . DB_TABLE_TESTS . '.`id`
          AND   ' . DB_TABLE_QUESTIONS . '.`question_theme_id` = ' . DB_TABLE_THEMES . '.`theme_id`
          AND   ' . DB_TABLE_TESTS . '.`id` = ' . $t_id;

  return db_query($query);
}

/**
 * Get question.
 *
 * @param $p_question_id int question id
 *
 * @return question class
 */
function get_question($p_question_id)
{
  global $WEB_APP;
  $t_question_id = db_prepare_int($p_question_id);

  $query = 'SELECT *
            FROM ' . DB_TABLE_QUESTIONS . ' WHERE `question_id` = ' . $t_question_id;
  $result = db_query($query);

  $question = new question();

  if (isset($result[0])) {
    $question->id = $result[0]['question_id'];
    $question->etalon = $result[0]['question_etalon'];
    $question->etalon_score = $result[0]['question_etalon_score'];
    $question->explanation = $result[0]['question_explanation'];
    $question->image = $result[0]['question_image'];
    $question->is_case_sensetive = $result[0]['question_is_case_sensetive'];
    $question->number = $result[0]['question_number'];
    $question->reference = $result[0]['question_reference'];
    $question->text = $result[0]['question_text'];
    $question->text_bin = $result[0]['question_text_bin'];
    $question->text_html = $result[0]['question_text_html'];
    $question->theme_id = $result[0]['question_theme_id'];
    $question->time = $result[0]['question_time'];
    $question->type = $result[0]['question_type'];
    $question->picture_url = $result[0]['question_picture_url'];
    $question->weight = $result[0]['question_weight'];
    $question->hint = $result[0]['question_hint'];

    //Added in 4 version
    $question->guid = $result[0]['question_guid'];
    $question->mode = $result[0]['question_mode'];
    $question->partially_right_answer = $result[0]['question_partially_right_answer'];
    $question->glued = $result[0]['question_glued'];
    $question->time_limited = $result[0]['question_time_limited'];
    $question->voice_record = $result[0]['question_voice_record'];
    $question->voice_record_time_limited = $result[0]['question_voice_record_time_limited'];
    $question->voice_record_max_time = $result[0]['question_voice_record_max_time'];
    $question->sequence_assess_type = $result[0]['question_sequence_assess_type'];
    $question->matched_list1_caption = $result[0]['question_matched_list1_caption'];
    $question->matched_list2_caption = $result[0]['question_matched_list2_caption'];
    $question->show_basket1 = $result[0]['question_show_basket1'];
    $question->show_basket2 = $result[0]['question_show_basket2'];
  }

  // Translation inside of text and explanation
  if (defined('TRANSLATE_TEST') && TRANSLATE_TEST == TRUE) {
    $question->text_html = preg_replace_callback('/(txt_\S+;)/m', function ($m) use ($WEB_APP) {
      return ($WEB_APP['text'][substr($m[0], 0, -1)]);
    }, $question->text_html);
    $question->explanation = preg_replace_callback('/(txt_\S+;)/m', function ($m) use ($WEB_APP) {
      return ($WEB_APP['text'][substr($m[0], 0, -1)]);
    }, $question->explanation);
  }

  return $question;
}

/**
 * Get questions for theme id.
 *
 * @param $p_theme_id int test id
 *
 * @return array question class
 */
function get_questions_for_theme_id($p_theme_id)
{
  $t_theme_id = db_prepare_int($p_theme_id);

  $query = 'SELECT * FROM ' . DB_TABLE_QUESTIONS . ' WHERE `question_theme_id` = ' . $t_theme_id .
    ' ORDER BY `question_number` ';

  return db_query($query);
}

/**
 * Get questions count for theme id.
 *
 * @param $p_id int theme id
 *
 * @return int questions count
 */
function get_questions_count_for_theme_id($p_id)
{
  $t_id = db_prepare_int($p_id);

  return db_count(DB_TABLE_QUESTIONS, '`question_theme_id` = ' . $t_id);
}

/**
 * Delete questions for theme id.
 *
 * @param $p_theme_id int theme id
 *
 * @return ADORecordSet|false
 */
function delete_questions_for_theme_id($p_theme_id)
{
  $t_theme_id = db_prepare_int($p_theme_id);
  $questions = get_questions_for_theme_id($t_theme_id);

  $id_array = array();
  foreach ($questions as $question) {
    delete_answers_for_question_id($question['question_id']);
    delete_fields_for_question_id($question['question_id']);
    delete_sequence_for_question_id($question['question_id']);
    $id_array[] = $question['question_id'];
  }

  $tmp = "";
  $array = array_values($id_array);
  $size = sizeof($array);

  if ($size == 0) return FALSE;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($id_array[$i]) . ", ";
  $tmp .= db_prepare_int($array[$size - 1]);

  $query = 'DELETE FROM ' . DB_TABLE_QUESTIONS . ' WHERE `question_id` IN (' . $tmp . ')';

  return db_exec($query);
}

/**
 * Get count of options for question.
 *
 * @param $p_question_id int question id
 * @param $p_option_type int option_id
 *
 * @return int options count
 */
function get_options_count_for_question($p_question_id, $p_option_type)
{
  $t_question_id = db_prepare_int($p_question_id);
  $t_option_type = db_prepare_int($p_option_type);
  return db_count(DB_TABLE_ANSWERS,
    '`answer_question_id` = ' . $t_question_id . ' AND answer_option_type=' . $t_option_type);
}

/**
 * Have quastion any answer options?
 * @param $p_question_id int question id
 * @return bool
 */
function question_have_answer_options($p_question_id)
{
  $t_question_id = db_prepare_int($p_question_id);
  return db_count(DB_TABLE_ANSWERS, '`answer_question_id` = ' . $t_question_id) > 0;
}

/**
 * Have quastion any ашудвы?
 * @param $question_text string question id
 * @return bool
 */
function question_have_fields($question_text)
{
  return (strpos($question_text, "<input") != FALSE) || (strpos($question_text, "<select") != FALSE);
}

/**
 * Get count of options with corresp!=0 for question.
 *
 * @param $p_question_id int question id
 * @param $p_option_type int option_id
 *
 * @return int options count
 */
function get_no_zero_options_count_for_question($p_question_id, $p_option_type)
{
  $t_question_id = db_prepare_int($p_question_id);
  $t_option_type = db_prepare_int($p_option_type);
  return db_count(DB_TABLE_ANSWERS,
    "`answer_question_id` = $t_question_id AND answer_option_type=$t_option_type AND answer_corresp!=0");
}

