<?php

/**
 * Specify answer object. Answers table.
 */
class answer
{
  /**
   * int answer id.
   */
  var $id;

  /**
   * int question id.
   */
  var $question;

  /**
   * int answer number.
   */
  var $number;

  /**
   * string answer text.
   */
  var $text;

  /**
   * int answer score.
   */
  var $score;

  /**
   * int (0, 1) answer is right.
   */
  var $right;

  /**
   * string answer text html.
   */
  var $text_html;

  /**
   * binary answer text.
   */
  var $text_bin;

  /**
   * int answer corresp.
   */
  var $corresp;

  /**
   * int next question number.
   */
  var $nextq;

  /**
   * string open answer mask
   */
  var $mask;

  /**
   * int (0, 1) open answer is MultiLine
   */
  var $multi_line;

  /**
   * int max length of answer
   */
  var $max_length;

  /**
   * int (0, 1)  answer option type 0 - Single, 1 - Multiple
   */
  var $option_type;

  /**
   * int rows count of answer
   */
  var $rows;

  /**
   * int text direction
   */
  var $bidi;

  var $font_name;
  var $font_color;
  var $font_size;

  // not stored in database
  var $css;
}

/**
 * Add answer.
 *
 * @param $p_answer answer class
 *
 */
function add_answer($p_answer)
{
  $t_answer_question_id = db_prepare_int($p_answer->question);
  $t_answer_number = db_prepare_int($p_answer->number);
  $t_answer_text = db_prepare_string($p_answer->text);
  $t_answer_score = db_prepare_float($p_answer->score);
  $t_answer_right = db_prepare_int($p_answer->right);
  $t_answer_text_html = db_prepare_string($p_answer->text_html);
  $t_answer_text_bin = ($p_answer->text_bin == NULL) ? 'NULL' : db_prepare_string($p_answer->text_bin);
  $t_answer_corresp = db_prepare_int($p_answer->corresp);
  $t_answer_nextq = db_prepare_int($p_answer->nextq);

  //Added in version 4
  $t_answer_mask = db_prepare_string($p_answer->mask);
  $t_answer_multi_line = db_prepare_int($p_answer->multi_line);
  $t_answer_max_length = db_prepare_int($p_answer->max_length);
  $t_answer_option_type = db_prepare_int($p_answer->option_type);
  $t_answer_rows = db_prepare_int($p_answer->rows);
  $t_answer_bidi = db_prepare_int($p_answer->bidi);

  // Added in version 4.1
  $t_answer_font_name = db_prepare_string($p_answer->font_name);
  $t_answer_font_color = db_prepare_string($p_answer->font_color);
  $t_answer_font_size = db_prepare_int($p_answer->font_size);


  if ($t_answer_max_length < 0) $t_answer_max_length = 0;
  if ($t_answer_rows < 1) $t_answer_max_length = 1;

  $query = "INSERT INTO " . DB_TABLE_ANSWERS . "(
			`answer_question_id`,
			`answer_number`,
			`answer_text`,
			`answer_score`,
			`answer_right`,
			`answer_text_html`,
			`answer_text_bin`,
			`answer_corresp`,
			`answer_nextq`,
			`answer_mask`,
			`answer_multi_line`,
			`answer_max_length`,
			`answer_option_type`,
			`answer_rows`,
			`answer_bidi`,
			`answer_font_name`,
			`answer_font_color`,
			`answer_font_size`
			)
	  VALUES(
			 $t_answer_question_id,
			 $t_answer_number,
			 $t_answer_text,
			 $t_answer_score,
			 $t_answer_right,
			 $t_answer_text_html,
			 $t_answer_text_bin,
			 $t_answer_corresp,
			 $t_answer_nextq,
			 $t_answer_mask,
			 $t_answer_multi_line ,
			 $t_answer_max_length,
			 $t_answer_option_type ,
			 $t_answer_rows ,
			 $t_answer_bidi ,
			 $t_answer_font_name,
			 $t_answer_font_color,
			 $t_answer_font_size
			)";

  db_exec($query);
}

/**
 * Delete answers for question id.
 *
 * @param $p_question_id int question id
 *
 */
function delete_answers_for_question_id($p_question_id)
{
  $t_question_id = db_prepare_int($p_question_id);

  $query = 'DELETE FROM ' . DB_TABLE_ANSWERS . ' WHERE `answer_question_id` = ' . $t_question_id;

  db_exec($query);
}

/**
 * Get answers for question id.
 *
 * @param $p_question_id int question id
 *
 * @return array answers array
 */
function get_answers_for_question_id($p_question_id)
{
  $t_question_id = db_prepare_int($p_question_id);

  return db_extract(DB_TABLE_ANSWERS, '`answer_question_id` = ' . $t_question_id, '`answer_number` ASC');
}

/**
 * Get answers of specified type for question id.
 *
 * @param $p_question_id int question id
 * @param $p_option_type int question id
 *
 * @return array answers array
 */
function get_answers_for_question_id_typed($p_question_id, $p_option_type)
{
  $t_question_id = db_prepare_int($p_question_id);
  $t_option_type = db_prepare_int($p_option_type);

  return db_extract(DB_TABLE_ANSWERS,
    "`answer_question_id` = $t_question_id	AND	`answer_option_type` = $t_option_type", '`answer_number` ASC');
}

/**
 * Get answer id.
 *
 * @param $p_answer_id int answer id
 *
 * @return answer class
 */
function get_answer($p_answer_id)
{
  $t_answer_id = db_prepare_int($p_answer_id);

  $answers[] = db_extract(DB_TABLE_ANSWERS, '`answer_id`=' . $t_answer_id);
  $answer = new answer();
  if (count($answers[0]) > 0) {
    $answer->id = $answers[0][0]['answer_id'];
    $answer->question = $answers[0][0]['answer_question_id'];
    $answer->number = $answers[0][0]['answer_number'];
    $answer->text = $answers[0][0]['answer_text'];
    $answer->score = $answers[0][0]['answer_score'];
    $answer->right = $answers[0][0]['answer_right'];
    $answer->text_html = $answers[0][0]['answer_text_html'];
    $answer->text_bin = $answers[0][0]['answer_text_bin'];
    $answer->corresp = $answers[0][0]['answer_corresp'];
    $answer->nextq = $answers[0][0]['answer_nextq'];

    $answer->mask = $answers[0][0]['answer_mask'];
    $answer->multi_line = $answers[0][0]['answer_multi_line'];
    $answer->max_length = $answers[0][0]['answer_max_length'];
    $answer->option_type = $answers[0][0]['answer_option_type'];
    $answer->rows = $answers[0][0]['answer_rows'];
    $answer->bidi = $answers[0][0]['answer_bidi'];

    $answer->font_name = $answers[0][0]['answer_font_name'];
    $answer->font_color = $answers[0][0]['answer_font_color'];
    $answer->font_size = $answers[0][0]['answer_font_size'];

    if (trim($answer->font_name) == "") $answer->font_name = "initial";
    if ((trim($answer->font_size) == "") || ($answer->font_size == 0)) $answer->font_size = "initial";
    if (trim($answer->font_color) == "") $answer->font_color = "initial";
    $answer->css = ' style="font-family: ' . $answer->font_name . '; font-size:' . $answer->font_size . 'pt; color:' .
      $answer->font_color . ';"';
  }

  return $answer;
}
