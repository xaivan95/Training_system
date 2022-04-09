<?php
/**
 * Created by PhpStorm.
 * User: Ravil
 * Date: 06.03.2015
 * Time: 10:14
 */

class ordered_sequence {
    /**
     * int sequence id.
     */
    var $id;

    /**
     * int question id.
     */
    var $question;

    /**
     * float score.
     */
    var $score;

    /**
     * array of int.
     * string of int stored in database
     * use explode to convert string to array and use comma as separator
     */
    var $sequence;
}

/**
 * @param $p_sequence
 */
function add_sequence($p_sequence) {
    $t_sequence_question_id = db_prepare_int($p_sequence->question);
    $t_sequence_score = db_prepare_float($p_sequence->score);
    $t_sequence_sequence = db_prepare_string(implode (',', $p_sequence->sequence));

    $query =  'INSERT INTO '.DB_TABLE_SEQUENCES.'(
			`question_id`,
			`score`,
			`sequence`
			)
	    	  VALUES(
			'.$t_sequence_question_id.',
			'.$t_sequence_score.',
			'.$t_sequence_sequence.'
			)';

    db_exec($query);
}

/**
 * @param $p_question_id
 */
function delete_sequence_for_question_id($p_question_id){
    $t_question_id = db_prepare_int($p_question_id);
    $query = 'DELETE FROM '.DB_TABLE_SEQUENCES.
        ' WHERE `question_id` = '.$t_question_id;
    db_exec($query);
}

/**
 * @param $p_question_id
 * @return array
 */
function get_sequences_for_question_id($p_question_id)
{
    $t_question_id = db_prepare_int($p_question_id);

    return db_extract(DB_TABLE_SEQUENCES, '`question_id` = '.$t_question_id);
}

/**
 * @param $p_sequence_id
 * @return ordered_sequence
 */
function get_sequence($p_sequence_id)
{
    $t_sequence_id = db_prepare_int($p_sequence_id);

    $sequences[] = db_extract(DB_TABLE_SEQUENCES, '`sequence_id`='.$t_sequence_id);
    $sequence = new ordered_sequence();
    if (count($sequences[0]) > 0)
    {
        $sequence->id = $sequences[0][0]['sequence_id'];
        $sequence->question = $sequences[0][0]['question_id'];
        $sequence->score = $sequences[0][0]['score'];
        $sequence->sequence = $sequences[0][0]['sequence'];
    }

    return $sequence;
}