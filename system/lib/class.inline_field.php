<?php
/**
 * Created by PhpStorm.
 * User: Ravil
 * Date: 04.03.2015
 * Time: 11:29
 */

class inline_field
{
  /**
   * int field id.
   */
  var $id;

  /**
   * int question id.
   */
  var $question;

  /**
   * string mask of right answer.
   */
  var $mask;

  /**
   * decimal score.
   */
  var $score;
}

/**
 * @param $p_field
 */
function add_field($p_field)
{
  $t_field_question_id = db_prepare_int($p_field->question);
  $t_field_mask = db_prepare_string($p_field->mask);
  $t_field_score = db_prepare_float($p_field->score);

  $query = 'INSERT INTO ' . DB_TABLE_QUESTION_FIELDS . "(
			`question_id`,
			`mask`,
			`score`
			)
	    VALUES (
			$t_field_question_id,
			$t_field_mask,
			$t_field_score
			)";

  db_exec($query);
}

/**
 * @param $p_question_id
 */
function delete_fields_for_question_id($p_question_id)
{
  $t_question_id = db_prepare_int($p_question_id);
  $query = 'DELETE FROM ' . DB_TABLE_QUESTION_FIELDS . " WHERE `question_id` = $t_question_id";
  db_exec($query);
}

/**
 * @param $p_question_id
 * @return array
 */
function get_fields_for_question_id($p_question_id)
{
  $t_question_id = db_prepare_int($p_question_id);

  return db_extract(DB_TABLE_QUESTION_FIELDS, '`question_id` = ' . $t_question_id, 'question_id, field_id');
}

/**
 * @param $p_field_id
 * @return inline_field
 */
function get_field($p_field_id)
{
  $t_field_id = db_prepare_int($p_field_id);

  $fields[] = db_extract(DB_TABLE_QUESTION_FIELDS, '`field_id`=' . $t_field_id);
  $field = new inline_field();
  if (count($fields[0]) > 0) {
    $field->id = $fields[0][0]['field_id'];
    $field->question = $fields[0][0]['question_id'];
    $field->mask = $fields[0][0]['mask'];
    $field->score = $fields[0][0]['score'];
  }

  return $field;
}