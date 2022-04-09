<?php

class archive
{

}

/**
 * @param string $groups id list
 * @param string $date_from
 * @param string $date_to
 * @return array count of archived results and answers
 */
function archive_results($groups, $date_from, $date_to)
{
  global $adodb;
  $date_from = db_prepare_date($date_from);
  $date_to = db_prepare_date($date_to);

  $where = " user_answer_user_result_id in 
              (SELECT id FROM " . DB_TABLE_USER_RESULTS . " WHERE `user_result_user_id` in 
                (SELECT id FROM " . DB_TABLE_USER . "  WHERE `user_group_id` in ($groups))       
              ) AND user_answer_time between $date_from AND $date_to;";
  $query = "INSERT INTO " . DB_TABLE_ARCHIVE_ANSWERS . " SELECT * FROM " . DB_TABLE_USER_ANSWERS . " WHERE " . $where;
  db_exec($query);
  $result[] = $adodb->affected_rows();
  if ($adodb->ErrorMsg() == '') {
    $query = "DELETE FROM " . DB_TABLE_USER_ANSWERS . " WHERE " . $where;
    db_exec($query);
  }

  $query = "INSERT INTO " . DB_TABLE_ARCHIVE_RESULTS . " SELECT * FROM " . DB_TABLE_USER_RESULTS .
    " WHERE `user_result_user_id` in (SELECT id FROM " . DB_TABLE_USER . " WHERE `user_group_id` in ($groups))
      AND user_result_time_begin between $date_from AND $date_to;";
  db_exec($query);
  $result[] = $adodb->affected_rows();
  if ($adodb->ErrorMsg() == '') {
    $query =
      "DELETE FROM " . DB_TABLE_USER_RESULTS . " WHERE `user_result_user_id` in (SELECT id FROM " . DB_TABLE_USER .
      " WHERE `user_group_id` in ($groups)) AND user_result_time_begin between $date_from AND $date_to;";
    db_exec($query);
  }
  return $result;
}

/**
 * @param string $groups id list
 * @param string $date_from
 * @param string $date_to
 * @return array count of archived results and answers
 */
function unarchive_results($groups, $date_from, $date_to)
{
  global $adodb;
  $date_from = db_prepare_date($date_from);
  $date_to = db_prepare_date($date_to);

  $where = " user_answer_user_result_id in 
              (SELECT id FROM " . DB_TABLE_ARCHIVE_RESULTS . " WHERE `user_result_user_id` in 
                (SELECT id FROM " . DB_TABLE_USER . "  WHERE `user_group_id` in ($groups))       
              ) AND user_answer_time between $date_from AND $date_to;";
  $query = "INSERT INTO " . DB_TABLE_USER_ANSWERS . " SELECT * FROM " . DB_TABLE_ARCHIVE_ANSWERS . " WHERE " . $where;
  db_exec($query);
  $result[] = $adodb->affected_rows();
  if ($adodb->ErrorMsg() == '') {
    $query = "DELETE FROM " . DB_TABLE_ARCHIVE_ANSWERS . " WHERE " . $where;
    db_exec($query);
  }

  $query = "INSERT INTO " . DB_TABLE_USER_RESULTS . " SELECT * FROM " . DB_TABLE_ARCHIVE_RESULTS .
    " WHERE `user_result_user_id` in (SELECT id FROM " . DB_TABLE_USER . " WHERE `user_group_id` in ($groups))
      AND user_result_time_begin between $date_from AND $date_to;";
  db_exec($query);
  $result[] = $adodb->affected_rows();
  if ($adodb->ErrorMsg() == '') {
    $query =
      "DELETE FROM " . DB_TABLE_ARCHIVE_RESULTS . " WHERE `user_result_user_id` in (SELECT id FROM " . DB_TABLE_USER .
      " WHERE `user_group_id` in ($groups)) AND user_result_time_begin between $date_from AND $date_to;";
    db_exec($query);
  }
  return $result;
}