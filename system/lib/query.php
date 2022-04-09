<?php

/**
 * Correctly quotes a string so that all strings are escaped.
 * We prefix and append to the string single-quotes.
 *
 * @param $p_string string
 *
 * @return string the quoted string
 */
function db_prepare_string($p_string)
{
  global $adodb;
  return $adodb->qstr($p_string, FALSE);
}

/**
 * Escapes special characters in a string for use in an SQL statement.
 *
 * @param $p_string string
 *
 * @return string Returns the escaped string
 */
function db_escape_string($p_string)
{
  global $adodb;
  return $adodb->escape($p_string, FALSE);
}

/**
 * Prepare integer for use in an SQL statement.
 *
 * @param $p_int integer
 *
 * @return integer
 */
function db_prepare_int($p_int)
{
  return (int)$p_int;
}

/**
 * @param $p_float
 * @return float
 */
function db_prepare_float($p_float)
{
  $p_float = str_replace(",", ".", $p_float);
  return (float)$p_float;
}

/**
 * Prepare bool for use in an SQL statement.
 *
 * @param $p_bool bool
 *
 * @return int bool
 */
function db_prepare_bool($p_bool)
{
  return (int)(bool)$p_bool;
}

/**
 * Prepare DATE for use in an SQL statement.
 *
 * @param $p_string string
 *
 * @return string
 */
function db_prepare_date($p_string)
{
  if ($p_string == '') return 'NULL'; else
    return db_prepare_string($p_string);
}

/**
 * Prepare sort order string for use in an SQL statement.
 *
 * @param $p_sort_order string
 *
 * @return string ASC or DESC
 */
function db_prepare_sort_order($p_sort_order)
{
  if (($p_sort_order == "ASC") || ($p_sort_order == "DESC")) return $p_sort_order;

  return DEFAULT_ORDER;
}

/**
 * Prepare char T, F for use in hidden field in an SQL statement.
 *
 * @param $p_char string
 *
 * @return string T or F
 */
function db_prepare_tf_char($p_char)
{
  if (($p_char == "T") || ($p_char == "F")) return $p_char;

  return "T";
}

/**
 * Prepare string to char ("T", "F") for use in an SQL statement.
 *
 * @param $p_value
 *
 * @return string char "T" or "F"
 */
function StrToBool($p_value)
{
  if (($p_value == 'TRUE') || ($p_value == '1') || ($p_value == '-1')) return "'T'";

  return "'F'";
}

/**
 * @param $p_value
 *
 * @return int (0, 1)
 */
function StrToBoolUE($p_value)
{
  if (($p_value == 'TRUE') || ($p_value == '1') || ($p_value == '-1')) return 1;

  return 0;
}

