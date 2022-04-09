<?php

const OptionSingle = 0;
const OptionMultiple = 1;
const OptionOpen = 2;
const OptionOrdered = 3;
const OptionMatched1 = 4;
const OptionMatched2 = 5;
const SortTestNone = 0;
const SortTestByRandomThemes = 2;


/**
 * Specify test object. Tests table.
 */
class test
{
  /**
   * Test id.
   */
  var $id;

  /**
   * Test GUID.
   */
  var $guid;

  /**
   * Test engine version.
   */
  var $engine_version;

  /**
   * Test version.
   */
  var $version;

  /**
   * Test date.
   */
  var $date;

  /**
   * Testing password.
   */
  var $password;

  /**
   * Test description.
   */
  var $description;

  /**
   * Test name.
   */
  var $name;

  /**
   * Test type.
   */
  var $type;

  /**
   * Partially right answer. Not used now.
   */
  var $partially_right_answer;

  /**
   * Questions order sequence.
   * 0 - None, 1 - Theme, 2 - Random theme
   */
  var $questions_order;

  /**
   * Adaptive test.
   */
  var $adaptive;


  /**
   * Test author.
   */
  var $author;

  /**
   * Questions count.
   */
  var $questions_count;

  /**
   * Exam mode.
   */
  var $is_exam_mode;

  /**
   * Random answers.
   */
  var $is_random_answers;

  /**
   * Use time limit.
   */
  var $is_time_limit;

  /**
   * Time limit.
   */
  var $time_limit;

  /**
   * Possible back.
   */
  var $is_back;

  /**
   * May skip a question.
   */
  var $may_skip_question;


  /**
   * Show score.
   */
  var $is_show_score;

  /**
   * Show results message.
   */
  var $is_show_results_message;

  /**
   * Next when right.
   */
  var $is_next_when_right;

  /**
   * Show reference.
   */
  var $is_show_reference;

  /**
   * Response on right.
   */
  var $is_response_on_right;

  /**
   * Response on wrong.
   */
  var $is_response_on_wrong;

  /**
   * Text of right message.
   */
  var $text_of_right_message;

  /**
   * Text of wrong message.
   */
  var $text_of_wrong_message;

  /**
   * Show explanation.
   */
  var $is_show_explanation;

  /**
   * Conclusion type.
   */
  var $concl_type;

  /**
   * Stat total.
   */
  var $is_stat_total;

  /**
   * Stat current.
   */
  var $is_stat_current;

  /**
   * Stat rights.
   */
  var $is_stat_rights;

  /**
   * Stat percent of rights.
   */
  var $is_stat_percent_of_rights;

  /**
   * Stat time.
   */
  var $is_stat_time;

  /**
   * Stat max time.
   */
  var $is_stat_max_time;

  /**
   * Stat GUID.
   */
  var $is_stat_guid;

  /**
   * Stat test version.
   */
  var $is_stat_test_version;

  /**
   * Media storage.
   */
  var $media_storage;

  /**
   * Sort by theme.
   */
  var $is_sort_by_theme;

  /**
   * Max count.
   */
  var $max_count;

  /**
   * CSS.
   */
  var $css;

  /**
   * Use date limit.
   */
  var $is_date_limit;

  /**
   * Date limit start.
   */
  var $date_limit_from;

  /**
   * Date limit end.
   */
  var $date_limit_to;

  /**
   * string test <HEADER> section
   */
  var $html_header;

  /**
   * Need to show user answers log
   */
  var $is_show_answers_log;

  var $allow_not_answer_question;

  /**
   * User id of test's author
   */
  var $author_id;
}

/**
 * Get test from RecordSet.
 *
 * @param ADORecordSet $result
 *
 * @return test class
 */
function get_test_from_result($result)
{
  $test = new test();

  $test->id = $result->fields['id'];
  $test->description = $result->fields['test_description'];
  $test->name = $result->fields['test_name'];
  $test->type = $result->fields['test_type'];
  $test->author = $result->fields['test_author'];
  $test->questions_count = $result->fields['test_questions_count'];
  $test->is_exam_mode = $result->fields['test_is_exam_mode'];
  $test->is_random_answers = $result->fields['test_is_random_answers'];
  $test->is_time_limit = $result->fields['test_is_time_limit'];
  $test->time_limit = $result->fields['test_time_limit'];
  $test->is_back = $result->fields['test_is_back'];
  $test->is_show_score = $result->fields['test_is_show_score'];
  $test->is_show_results_message = $result->fields['test_is_show_results_message'];
  $test->is_next_when_right = $result->fields['test_is_next_when_right'];
  $test->is_show_reference = $result->fields['test_is_show_reference'];
  $test->is_response_on_right = $result->fields['test_is_response_on_right'];
  $test->is_response_on_wrong = $result->fields['test_is_response_on_wrong'];
  $test->text_of_right_message = $result->fields['test_text_of_right_message'];
  $test->text_of_wrong_message = $result->fields['test_text_of_wrong_message'];
  $test->is_show_explanation = $result->fields['test_is_show_explanation'];
  $test->concl_type = $result->fields['test_concl_type'];
  $test->is_stat_total = $result->fields['test_is_stat_total'];
  $test->is_stat_current = $result->fields['test_is_stat_current'];
  $test->is_stat_rights = $result->fields['test_is_stat_rights'];
  $test->is_stat_time = $result->fields['test_is_stat_time'];
  $test->media_storage = $result->fields['test_media_storage'];
  $test->is_sort_by_theme = $result->fields['test_is_sort_by_theme'];
  $test->max_count = $result->fields['test_max_count'];
  $test->css = $result->fields['test_css'];
  $test->is_date_limit = $result->fields['test_is_date_limit'];
  $test->date_limit_from = $result->fields['test_date_limit_from'];
  $test->date_limit_to = $result->fields['test_date_limit_to'];

  // Added in 4 version
  $test->guid = $result->fields['test_guid'];
  $test->version = $result->fields['test_version'];
  $test->engine_version = $result->fields['test_engine_version'];
  $test->password = $result->fields['test_password'];
  $test->questions_order = $result->fields['test_questions_order'];
  $test->adaptive = $result->fields['test_is_adaptive'];
  $test->date = $result->fields['test_date'];
  $test->partially_right_answer = $result->fields['test_is_partially_right_answer'];
  $test->may_skip_question = $result->fields['test_may_skip_question'];
  $test->is_stat_percent_of_rights = $result->fields['test_is_stat_percent_of_rights'];
  $test->is_stat_max_time = $result->fields['test_is_stat_max_time'];
  $test->is_stat_guid = $result->fields['test_is_stat_guid'];
  $test->is_stat_test_version = $result->fields['test_is_stat_test_version'];
  $test->html_header = $result->fields['test_html_header'];
  $test->is_show_answers_log = $result->fields['test_is_show_answers_log'];
  $test->allow_not_answer_question = $result->fields['test_allow_not_answer_question'];

  // Added in 4.2 version
  $test->author_id = $result->fields['test_author_id'];

  return $test;
}

/**
 * Add test.
 *
 * @param $p_test test class
 * @param $p_id int test id. If test id is 0 - add test with next test id.
 *
 * @return ADORecordSet or false
 */
function add_test($p_test, $p_id = 0)
{
  $t_id = db_prepare_int($p_id);

  $t_test_description = db_prepare_string($p_test->description);
  $t_test_name = db_prepare_string($p_test->name);
  $t_test_type = db_prepare_int($p_test->type);
  $t_test_author = db_prepare_string($p_test->author);
  $t_test_questions_count = db_prepare_int($p_test->questions_count);
  $t_test_is_exam_mode = db_prepare_int($p_test->is_exam_mode);
  $t_test_is_random_answers = db_prepare_int($p_test->is_random_answers);
  $t_test_is_time_limit = db_prepare_int($p_test->is_time_limit);
  $t_test_time_limit = db_prepare_string($p_test->time_limit);
  $t_test_is_back = db_prepare_int($p_test->is_back);
  $t_test_is_show_score = db_prepare_int($p_test->is_show_score);
  $t_test_is_show_results_message = db_prepare_int($p_test->is_show_results_message);
  $t_test_is_next_when_right = db_prepare_int($p_test->is_next_when_right);
  $t_test_is_show_reference = db_prepare_int($p_test->is_show_reference);
  $t_test_is_response_on_right = db_prepare_int($p_test->is_response_on_right);
  $t_test_is_response_on_wrong = db_prepare_int($p_test->is_response_on_wrong);
  $t_test_text_of_right_message = db_prepare_string($p_test->text_of_right_message);
  $t_test_text_of_wrong_message = db_prepare_string($p_test->text_of_wrong_message);
  $t_test_is_show_explanation = db_prepare_int($p_test->is_show_explanation);
  $t_test_concl_type = db_prepare_int($p_test->concl_type);
  $t_test_is_stat_total = db_prepare_int($p_test->is_stat_total);
  $t_test_is_stat_current = db_prepare_int($p_test->is_stat_current);
  $t_test_is_stat_rights = db_prepare_int($p_test->is_stat_rights);
  $t_test_is_stat_time = db_prepare_int($p_test->is_stat_time);
  $t_test_media_storage = db_prepare_string($p_test->media_storage);
  $t_test_is_sort_by_theme = db_prepare_int($p_test->is_sort_by_theme);
  $t_test_max_count = db_prepare_int($p_test->max_count);
  $t_test_css = db_prepare_string($p_test->css);
  $t_test_is_date_limit = db_prepare_int($p_test->is_date_limit);
  $t_test_date_limit_from = db_prepare_date($p_test->date_limit_from);
  $t_test_date_limit_to = db_prepare_date($p_test->date_limit_to);

  // Added in version 4
  $t_test_guid = db_prepare_string($p_test->guid);
  $t_test_engine_version = db_prepare_float($p_test->engine_version);
  $t_test_version = db_prepare_float($p_test->version);
  $t_test_date = db_prepare_date($p_test->date);
  $t_test_password = db_prepare_string($p_test->password);
  $t_test_partially_right_answer = db_prepare_int($p_test->partially_right_answer);
  $t_test_questions_order = db_prepare_int($p_test->questions_order);
  $t_test_adaptive = db_prepare_int($p_test->adaptive);
  $t_test_may_skip_question = db_prepare_int($p_test->may_skip_question);
  $t_test_is_stat_percent_of_rights = db_prepare_int($p_test->is_stat_percent_of_rights);
  $t_test_is_stat_max_time = db_prepare_int($p_test->is_stat_max_time);
  $t_test_is_stat_guid = db_prepare_int($p_test->is_stat_guid);
  $t_test_is_stat_test_version = db_prepare_int($p_test->is_stat_test_version);
  $t_test_html_header = db_prepare_string($p_test->html_header);
  $t_test_is_show_answers_log = db_prepare_int($p_test->is_show_answers_log);
  $t_test_allow_not_answer_question = db_prepare_int($p_test->allow_not_answer_question);

  // Added in 4.2 version
  $user_id = get_user_id($_SESSION['user_login']);
  $t_test_author_id = db_prepare_int($user_id);

  if ($t_id == 0) {
    $insert = "";
    $values = "";
  } else {
    $insert = "id,";
    $values = "'$t_id',";
  }

  /** @noinspection SqlInsertValues */
  $query = "INSERT INTO " . DB_TABLE_TESTS . "(
      $insert
      `test_description`,
      `test_name`,
      `test_type`,
      `test_author`,
      `test_questions_count`,
      `test_is_exam_mode`,
      `test_is_random_answers`,
      `test_is_time_limit`,
      `test_time_limit`,
      `test_is_back`,
      `test_is_show_score`,
      `test_is_show_results_message`,
      `test_is_next_when_right`,
      `test_is_show_reference`,
      `test_is_response_on_right`,
      `test_is_response_on_wrong`,
      `test_text_of_right_message`,
      `test_text_of_wrong_message`,
      `test_is_show_explanation`,
      `test_concl_type`,
      `test_is_stat_total`,
      `test_is_stat_current`,
      `test_is_stat_rights`,
      `test_is_stat_time`,
      `test_media_storage`,
      `test_is_sort_by_theme`,
      `test_max_count`,
			`test_css`,
			`test_is_date_limit`,
			`test_date_limit_from`,
			`test_date_limit_to`,
			`test_guid`,
			`test_version`,
			`test_engine_version`,
			`test_password`,
			`test_questions_order`,
			`test_is_adaptive`,
			`test_date`,
			`test_is_partially_right_answer`,
			`test_may_skip_question`,
			`test_is_stat_percent_of_rights`,
			`test_is_stat_max_time`,
			`test_is_stat_guid`,
			`test_is_stat_test_version`,
			`test_html_header`,
			`test_is_show_answers_log`,
			`test_allow_not_answer_question`,
			`test_author_id`
		)
        VALUES
        (
             $values
             $t_test_description,
             $t_test_name,
             $t_test_type,
             $t_test_author,
             $t_test_questions_count,
             $t_test_is_exam_mode,
             $t_test_is_random_answers,
             $t_test_is_time_limit,
             $t_test_time_limit,
             $t_test_is_back,
             $t_test_is_show_score,
             $t_test_is_show_results_message,
             $t_test_is_next_when_right,
             $t_test_is_show_reference,
             $t_test_is_response_on_right,
             $t_test_is_response_on_wrong,
             $t_test_text_of_right_message,
             $t_test_text_of_wrong_message,
             $t_test_is_show_explanation,
             $t_test_concl_type,
             $t_test_is_stat_total,
             $t_test_is_stat_current,
             $t_test_is_stat_rights,
             $t_test_is_stat_time,
             $t_test_media_storage,
             $t_test_is_sort_by_theme,
			       $t_test_max_count,
             $t_test_css,
             $t_test_is_date_limit,
             $t_test_date_limit_from,
             $t_test_date_limit_to,
             $t_test_guid,
             $t_test_version,
             $t_test_engine_version,
             $t_test_password,
             $t_test_questions_order,
             $t_test_adaptive,
             $t_test_date,
             $t_test_partially_right_answer,
             $t_test_may_skip_question,
             $t_test_is_stat_percent_of_rights,
             $t_test_is_stat_max_time,
             $t_test_is_stat_guid,
             $t_test_is_stat_test_version,
             $t_test_html_header,
             $t_test_is_show_answers_log,
             $t_test_allow_not_answer_question,
             $t_test_author_id
        )";

  return db_exec($query);
}

/**
 * Get tests.
 *
 * @param string $p_sort_field sort field
 * @param string $p_sort_order sort order: ASC or DESC
 * @param int $p_page page number
 * @param int $p_count items count on the page
 * @param object $p_filter test_filter object
 * @param int test's author id
 *
 * @return array tests array
 */
function get_tests($p_sort_field = "id", $p_sort_order = DEFAULT_ORDER, $p_page = 1, $p_count = 0, $p_filter = NULL,
                   $p_author_id = NULL)
{
  global $WEB_APP;
  $t_sort_field = db_escape_string($p_sort_field);
  $t_sort_order = db_prepare_sort_order($p_sort_order);
  $t_page = db_prepare_int($p_page);
  $t_count = db_prepare_int($p_count);
  $t_author_id = db_prepare_int($p_author_id);

  if (isset($p_author_id) && user_have_grant_id($p_author_id, $WEB_APP['settings']['limited_tests_grant_id'])) {
    $author_where = " WHERE `test_author_id`=$t_author_id OR `test_author_id`=0 ";
  } else
    $author_where = '';

  $query = 'SELECT * FROM ' . DB_TABLE_TESTS . $author_where;
  $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
  if ($tmp != '') {
    $query .= ' WHERE ' . $tmp;
  }
  if ($t_sort_field == "") $t_sort_field = "id";
  $query .= " ORDER BY `$t_sort_field` $t_sort_order";

  if ($p_count != 0) {
    $limit = ($t_page - 1) * $t_count;
    $query .= " LIMIT  $limit, $t_count";
  }

  return db_query($query);
}

/**
 * Get tests from test id array.
 *
 * @param $p_id_array array of test id
 *
 * @return array tests array
 */
function get_tests_from_array($p_id_array)
{

  $tmp = "";
  $array = array_values($p_id_array);
  $size = sizeof($array);

  if ($size == 0) return NULL;

  for ($i = 0; $i < $size - 1; $i++) $tmp .= db_prepare_int($array[$i]) . ", ";
  $tmp .= db_prepare_int($array[$size - 1]);

  $query = 'SELECT * FROM ' . DB_TABLE_TESTS . ' WHERE `id` IN(' . $tmp . ')
        ORDER BY `id` ';

  return db_query($query);
}

/**
 * Get test id by name.
 *
 * @param string $p_name test name
 *
 * @return int test id. 0- test does not exist
 */
function get_test_id_by_name($p_name)
{
  $t_name = db_prepare_string($p_name);

  $query = 'SELECT `id`
        FROM ' . DB_TABLE_TESTS . ' WHERE `test_name` = ' . $t_name;

  $result = db_query($query);

  return isset($result[0]['id']) ? $result[0]['id'] : 0;
}

/**
 * Get test id by multimedia id.
 *
 * @param string  multimedia ID
 *
 * @return int test id. 0- test does not exist
 */
function get_test_id_by_multimedia_id($p_name)
{
  $t_name = db_prepare_string($p_name);
  $query = "SELECT `id`  FROM " . DB_TABLE_TESTS . " WHERE `test_media_storage` =  $t_name";
  $result = db_query($query);

  return isset($result[0]['id']) ? $result[0]['id'] : 0;
}

/**
 * Get test id by GUID.
 *
 * @param string  GUID
 *
 * @return int test id. 0- test does not exist
 */
function get_test_id_by_guid($p_guid)
{
  $t_guid = db_prepare_string($p_guid);
  $query = 'SELECT `id`  FROM ' . DB_TABLE_TESTS . ' WHERE `test_guid` = ' . $t_guid;
  $result = db_query($query);

  return isset($result[0]['id']) ? $result[0]['id'] : 0;
}

/**
 * Get test title by id.
 *
 * @param int  test id
 *
 * @return string test title
 */
function get_test_title_by_id($p_id)
{
  $t_id = db_prepare_string($p_id);
  $query = 'SELECT `test_name`  FROM ' . DB_TABLE_TESTS . ' WHERE `id` = ' . $t_id;
  $result = db_query($query);

  return isset($result[0]['test_name']) ? $result[0]['test_name'] : "";
}

/**
 * Get tests count.
 *
 * @param $p_filter test_filter object
 *
 * @return int tests count
 */
function get_tests_count($p_filter, $p_author_id = NULL)
{
  global $WEB_APP;

  if (isset($p_author_id) && user_have_grant_id($p_author_id, $WEB_APP['settings']['limited_tests_grant_id'])) {
    $t_author_id = db_prepare_int($p_author_id);
    $filter = " `test_author_id`=$t_author_id OR `test_author_id`=0 ";
    if ($p_filter->query() !== '') $filter .= " AND " . $p_filter->query();
    return db_count(DB_TABLE_TESTS, $filter);
  } else {
    $tmp = ($p_filter != NULL) ? $p_filter->query() : '';
    return db_count(DB_TABLE_TESTS, $tmp);
  }
}

/**
 * Get tests count for section id.
 *
 * @param $p_id int section id
 *
 * @return int tests count
 */
function get_tests_count_for_section_id($p_id)
{
  $t_id = db_prepare_int($p_id);

  return db_count(DB_TABLE_SECTION_TEST, '`section_id` = ' . $t_id);
}

/**
 * Get test.
 *
 * @param $p_id int test id
 *
 * @return test class
 */
function get_test($p_id)
{
  global $adodb;

  $test = new test();
  $t_id = db_prepare_int($p_id);
  $query = "SELECT * FROM " . DB_TABLE_TESTS . " WHERE id = $t_id";
  $result = $adodb->Execute($query);

  if (!$result->EOF) {
    $test = get_test_from_result($result);
  } else {
    $t_id = db_prepare_string($p_id);
    $query = "SELECT * FROM " . DB_TABLE_TESTS . " WHERE test_guid = $t_id";
    $result = $adodb->Execute($query);
    if (!$result->EOF) {
      $test = get_test_from_result($result);
    }
  }

  return $test;
}

/**
 * Get value from array. If value does not exist - return empty string.
 *
 * @param $items array
 * @param $key string key
 *
 * @return mixed if key exist, or '' if key does not exist
 */
function get_item_data($items, $key)
{
  return array_key_exists($key, $items) && isset($key, $items) ? $items[$key] : '';
}

/**
 * Import test.
 *
 * @param $file string xml test file
 * @param $id int test id
 *
 * @return int test id
 */
function import_test($file, $id = 0)
{
  global $adodb;

  $items = get_xml_data($file);

  // Test info.
  $test = new test();
  $test->guid = get_item_data($items, "Test/Info/GUID");
  $test->media_storage = get_item_data($items, "Test/Info/MediaStorage");
  $test->engine_version = get_item_data($items, "Test/Info/EngineVersion");
  $test->engine_version = db_prepare_float($test->engine_version);
  $test->version = get_item_data($items, "Test/Info/TestVersion");
  $test->date = get_item_data($items, "Test/Info/TestDate");
  $test->password = get_item_data($items, "Test/Info/PswView");
  $test->name = get_item_data($items, "Test/Info/Title");
  $test->author = get_item_data($items, "Test/Info/Author");
  if (trim(strip_tags($test->author)) == '') $test->author = '';
  $test->type = get_item_data($items, "Test/Info/TestType");
  $test->partially_right_answer = get_item_data($items, "Test/Info/PartiallyRightAnswer");
  $test->is_exam_mode = get_item_data($items, "Test/Info/RandomizeQuestions") == "True" ? 1 : 0;
  $test->is_random_answers = get_item_data($items, "Test/Info/RandomizeAnswers") == "True" ? 1 : 0;
  $test->questions_order = get_item_data($items, "Test/Info/QuestionsOrder");
  $test->is_sort_by_theme = get_item_data($items, "Test/Info/QuestionsOrder") == "True" ? 1 : 0;
  $test->adaptive = get_item_data($items, "Test/Info/Adaptive");
  $test->description = get_item_data($items, "Test/Info/Description");
  if (trim(strip_tags($test->description)) == '') $test->description = '';
  $test->css = get_item_data($items, "Test/Info/CSS");
  $test->questions_count = get_item_data($items, "Test/Questions/Count");
  $test->html_header = get_item_data($items, "Test/Info/HtmlHeader");
  $test->concl_type =
    get_item_data($items, "Test/Info/AssessmentType") == 1 ? 1 : 0;  //may be empty on early builds of tMaker 7

  // Limitations
  $test->is_time_limit = get_item_data($items, "Test/Limitations/TimeLimited") == "True" ? 1 : 0;
  $test->time_limit = get_item_data($items, "Test/Limitations/TimeLimit");
  $test->is_date_limit = get_item_data($items, "Test/Limitations/DateLimited") == "True" ? 1 : 0;
  $test->date_limit_from = get_item_data($items, "Test/Limitations/DateLimitFrom");
  $test->date_limit_to = get_item_data($items, "Test/Limitations/DateLimitTo");
  $test->max_count = get_item_data($items, "Test/Limitations/MaxCount");

  // Testing
  $test->is_back = get_item_data($items, "Test/Testing/MayBack") == "True" ? 1 : 0;
  $test->may_skip_question = get_item_data($items, "Test/Testing/MaySkipQuestion") == "True" ? 1 : 0;
  $test->allow_not_answer_question = get_item_data($items, "Test/Testing/AllowNotAnswerQuestion") == "True" ? 1 : 0;
  $test->is_show_answers_log = get_item_data($items, "Test/Testing/ShowAnswersLog") == "True" ? 1 : 0;

  //Status
  $test->is_stat_total = get_item_data($items, "Test/Status/QuestionsCount") == "True" ? 1 : 0;
  $test->is_stat_current = get_item_data($items, "Test/Status/CurrentQuestionNumber") == "True" ? 1 : 0;
  $test->is_stat_rights = get_item_data($items, "Test/Status/RightAnswersCount") == "True" ? 1 : 0;
  $test->is_stat_percent_of_rights = get_item_data($items, "Test/Status/PercentOfRightAnswers") == "True" ? 1 : 0;
  $test->is_stat_time = get_item_data($items, "Test/Status/TestingTime") == "True" ? 1 : 0;
  $test->is_stat_max_time = get_item_data($items, "Test/Status/MaxTestingTime") == "True" ? 1 : 0;
  $test->is_stat_guid = get_item_data($items, "Test/Status/TestGUID") == "True" ? 1 : 0;
  $test->is_stat_test_version = get_item_data($items, "Test/Status/TestVersion") == "True" ? 1 : 0;

  //Respond on answer a question
  $test->is_next_when_right = get_item_data($items, "Test/QuestionRespond/NextAfterRightAnswerOnly") == "True" ? 1 : 0;
  $test->is_response_on_right = get_item_data($items, "Test/QuestionRespond/OnRight") == "True" ? 1 : 0;
  $test->is_response_on_wrong = get_item_data($items, "Test/QuestionRespond/OnWrong") == "True" ? 1 : 0;
  $test->text_of_right_message = get_item_data($items, "Test/QuestionRespond/TextOfRightMessage");
  $test->text_of_wrong_message = get_item_data($items, "Test/QuestionRespond/TextOfWrongMessage");
  $test->is_show_explanation = get_item_data($items, "Test/QuestionRespond/ShowExplanation") == "True" ? 1 : 0;
  $test->is_show_reference = get_item_data($items, "Test/QuestionRespond/ShowReference") == "True" ? 1 : 0;

  //Respond on test complete
  $test->is_show_results_message = get_item_data($items, "Test/TestRespond/ShowResultsMessage") == "True" ? 1 : 0;
  $test->is_show_score = get_item_data($items, "Test/TestRespond/ShowScores") == "True" ? 1 : 0;

  add_test($test, $id);

  if ($adodb->ErrorMsg() != '') {
    return 0;
  }

  // Test id
  if ($id == 0) {
    $test_id = $adodb->Insert_ID();
  } else {
    $test_id = $id;
  }
  $test->id = $test_id;


  /**********************************
   ***         Hints             ****
   *********************************/
  $hints_count = get_item_data($items, "Test/Comments/Count");

  for ($i = 0; $i < $hints_count; $i++) {
    $hint = new hint();

    $hint->test = $test_id;
    $hint->number = get_item_data($items, "Test/Comments/Comment_$i/ID");
    $hint->title = get_item_data($items, "Test/Comments/Comment_$i/Title");
    $hint->html_text = get_item_data($items, "Test/Comments/Comment_$i/TextHTML");
    if (trim(strip_tags($hint->html_text)) == '') $hint->html_text = '';

    add_hint($hint);
    $hint->id = $adodb->Insert_ID();
  }


  /**********************************
   ***         Themes            ****
   *********************************/
  $themes_count = get_item_data($items, "Test/Themes/Count");

  $themes = array();

  for ($i = 0; $i < $themes_count; $i++) {
    $theme = new theme();

    $theme->test = $test_id;
    $theme->number = $i;
    $theme->caption = get_item_data($items, "Test/Themes/Theme_$i/Title");
    $theme->show_in_results = get_item_data($items, "Test/Themes/Theme_$i/ShowInResults") == "True" ? 1 : 0;
    $theme->numexam = get_item_data($items, "Test/Themes/Theme_$i/RandomQuestionsCount");
    $theme->conclusions_count = get_item_data($items, "Test/Themes/Theme_$i/Assessments/Count");
    $theme->original_id = get_item_data($items, "Test/Themes/Theme_$i/ID");

    add_theme($theme);
    $theme->id = $adodb->Insert_ID();

    $themes[] = $theme;

    // Conclusions

    for ($j = 0; $j < $theme->conclusions_count; $j++) {
      $conclusion = new conclusion();
      $conclusion->theme = $theme->id;
      $conclusion->number = $j;
      $conclusion->top =
        get_item_data($items, "Test/Themes/Theme_" . $i . "/Assessments/Assessment_" . $j . "/TopBorder");
      $conclusion->low =
        get_item_data($items, "Test/Themes/Theme_" . $i . "/Assessments/Assessment_" . $j . "/LowBorder");
      $conclusion->text =
        get_item_data($items, "Test/Themes/Theme_" . $i . "/Assessments/Assessment_" . $j . "/Assessment");
      if (trim(strip_tags($conclusion->text)) == '') $conclusion->text = '';

      add_conclusion($conclusion);
      $conclusion->id = $adodb->Insert_ID();
    }
  }


  /**********************************
   ***         Resume            ****
   *********************************/
  $resume_count = get_item_data($items, "Test/Assessments/Count");
  for ($i = 0; $i < $resume_count; $i++) {
    $resume = new resume();

    $resume->test = $test_id;
    $resume->number = $i;
    $resume->low = get_item_data($items, "Test/Assessments/Assessment_$i/LowBorder");
    $resume->top = get_item_data($items, "Test/Assessments/Assessment_$i/TopBorder");
    $resume->text = get_item_data($items, "Test/Assessments/Assessment_$i/Assessment");
    if (trim(strip_tags($resume->text)) == '') $resume->text = '';

    add_resume($resume);
    $resume->id = $adodb->Insert_ID();
  }

  /**********************************
   ***       Questions           ****
   *********************************/

  $questions_count = $test->questions_count;
  for ($i = 0; $i < $questions_count; $i++) {
    $question = new question();
    $question->number = $i;
    $question->guid = get_item_data($items, "Test/Questions/Question_$i/ID");

    // Get theme id.
    $theme_number = get_item_data($items, "Test/Questions/Question_$i/ThemeNumber");
    $theme_id = get_item_data($items, "Test/Questions/Question_$i/ThemeID");
    if ($test->engine_version < 7.1) {
      $theme = $themes[$theme_number];
      $question->theme_id = $theme->id;
    } else {
      for ($j = 0; $j < $themes_count; $j++) {
        if (($themes[$j]->original_id == $theme_number) or (($themes[$j]->original_id == $theme_id))) {
          $theme = $themes[$j];
          $question->theme_id = $theme->id;
        }
      }
    }


    // Get hint id.
    if ($hints_count > 0) {
      $question->hint = get_item_data($items, "Test/Questions/Question_$i/CommentID");
    } else {
      $question->hint = -1;
    }

    $question->weight = get_item_data($items, "Test/Questions/Question_$i/Score");
    $question->mode = get_item_data($items, "Test/Questions/Question_$i/Mode");
    $question->partially_right_answer = get_item_data($items, "Test/Questions/Question_$i/PartiallyRightAnswer");
    $question->glued = get_item_data($items, "Test/Questions/Question_$i/Glued");
    $question->time_limited = get_item_data($items, "Test/Questions/Question_$i/TimeLimited");
    $question->time = get_item_data($items, "Test/Questions/Question_$i/TimeLimit");
    $question->voice_record = get_item_data($items, "Test/Questions/Question_$i/VoiceRecord") == "True" ? 1 : 0;
    $question->voice_record_time_limited =
      get_item_data($items, "Test/Questions/Question_$i/VoiceRecordLimited") == "True" ? 1 : 0;
    $question->voice_record_max_time = get_item_data($items, "Test/Questions/Question_$i/VoiceRecordMaxTime");
    $question->text_html = get_item_data($items, "Test/Questions/Question_$i/TextHTML");
    $question->explanation = get_item_data($items, "Test/Questions/Question_$i/Explanation");
    if (trim(strip_tags($question->explanation)) == '') $question->explanation = '';
    $question->sequence_assess_type = get_item_data($items, "Test/Questions/Question_$i/SequenceAssessType");
    $question->matched_list1_caption = get_item_data($items, "Test/Questions/Question_$i/MatchedList1/Caption");
    $question->matched_list2_caption = get_item_data($items, "Test/Questions/Question_$i/MatchedList2/Caption");
    $question->show_basket1 = get_item_data($items, "Test/Questions/Question_$i/ShowBasket1") == "True" ? 1 : 0;
    $question->show_basket2 = get_item_data($items, "Test/Questions/Question_$i/ShowBasket2") == "True" ? 1 : 0;


    if (get_item_data($items, "Test/Questions/Question_$i/AnswerOptions/Count") > 0) $question->type =
      0; else if (get_item_data($items, "Test/Questions/Question_$i/OpenAnswerOptions/Count") > 0) $question->type =
      2; else if (get_item_data($items, "Test/Questions/Question_$i/OrderedList/Count") > 0) $question->type =
      3; else if (get_item_data($items, "Test/Questions/Question_$i/MatchedList1/Count") > 0) $question->type = 4;

    add_question($question);
    $question->id = $adodb->Insert_ID();

    //Inline fields
    $FieldsCount = get_item_data($items, "Test/Questions/Question_$i/Fields/Count");
    for ($field_index = 0; $field_index < $FieldsCount; $field_index++) {
      $field = new inline_field();
      $field->question = $question->id;
      $field->mask = trim(get_item_data($items, "Test/Questions/Question_$i/Fields/Field_$field_index/Mask"));
      $field->score = get_item_data($items, "Test/Questions/Question_$i/Fields/Field_$field_index/Score");
      add_field($field);
    }

    // Answer options
    $AnswersCount = get_item_data($items, "Test/Questions/Question_$i/AnswerOptions/Count");
    for ($answers_index = 0; $answers_index < $AnswersCount; $answers_index++) {
      $answer = new answer();
      $answer->question = $question->id;
      $answer->number = $answers_index;
      $answer->score = get_item_data($items, "Test/Questions/Question_$i/AnswerOptions/Option_$answers_index/Score");
      $answer->right =
        (get_item_data($items, "Test/Questions/Question_$i/AnswerOptions/Option_$answers_index/Right") == "True") ? 1 :
          0;
      $answer->nextq =
        get_item_data($items, "Test/Questions/Question_$i/AnswerOptions/Option_$answers_index/NextQuestion");
      $answer->option_type =
        get_item_data($items, "Test/Questions/Question_$i/AnswerOptions/Option_$answers_index/OptionType");
      $answer->text_html =
        get_item_data($items, "Test/Questions/Question_$i/AnswerOptions/Option_$answers_index/TextHTML");
      add_answer($answer);
    }


    // Open answers
    $AnswersCount = get_item_data($items, "Test/Questions/Question_$i/OpenAnswerOptions/Count");
    for ($answers_index = 0; $answers_index < $AnswersCount; $answers_index++) {
      $answer = new answer();
      $answer->option_type = OptionOpen;
      $answer->question = $question->id;
      $answer->number = $answers_index;
      $answer->score =
        get_item_data($items, "Test/Questions/Question_$i/OpenAnswerOptions/Option_$answers_index/Score");
      $answer->mask = get_item_data($items, "Test/Questions/Question_$i/OpenAnswerOptions/Option_$answers_index/Mask");
      $answer->multi_line =
        (get_item_data($items, "Test/Questions/Question_$i/OpenAnswerOptions/Option_$answers_index/MultiLineAnswer") ==
          "True") ? 1 : 0;
      $answer->max_length =
        get_item_data($items, "Test/Questions/Question_$i/OpenAnswerOptions/Option_$answers_index/MaxLength");
      $answer->rows = get_item_data($items, "Test/Questions/Question_$i/OpenAnswerOptions/Option_$answers_index/Rows");
      $answer->bidi = get_item_data($items, "Test/Questions/Question_$i/OpenAnswerOptions/Option_$answers_index/BiDi");

      $answer->font_name =
        get_item_data($items, "Test/Questions/Question_$i/OpenAnswerOptions/Option_$answers_index/FontName");
      $answer->font_size =
        get_item_data($items, "Test/Questions/Question_$i/OpenAnswerOptions/Option_$answers_index/FontSize");
      $answer->font_color =
        get_item_data($items, "Test/Questions/Question_$i/OpenAnswerOptions/Option_$answers_index/FontColor");
      add_answer($answer);
    }

    // Ordered list
    $AnswersCount = get_item_data($items, "Test/Questions/Question_$i/OrderedList/Options/Count");
    for ($answers_index = 0; $answers_index < $AnswersCount; $answers_index++) {
      $answer = new answer();
      $answer->option_type = OptionOrdered;
      $answer->question = $question->id;
      $answer->number = $answers_index;
      $answer->score =
        get_item_data($items, "Test/Questions/Question_$i/OrderedList/Options/Option_$answers_index/Score");
      $answer->text_html =
        get_item_data($items, "Test/Questions/Question_$i/OrderedList/Options/Option_$answers_index/TextHTML");

      // Used only to set initial values in template
      $answer->corresp = get_item_data($items,
        "Test/Questions/Question_$i/OrderedList/Sequences/Sequence_0/Positions/Position_$answers_index/Position");
      add_answer($answer);
    }

    // Sequences
    $SequencesCount = get_item_data($items, "Test/Questions/Question_$i/OrderedList/Sequences/Count");
    for ($sequence_index = 0; $sequence_index < $SequencesCount; $sequence_index++) {

      $sequence = new ordered_sequence();
      $sequence->question = $question->id;
      $sequence->score =
        get_item_data($items, "Test/Questions/Question_$i/OrderedList/Sequences/Sequence_$sequence_index/Score");
      $sequence->sequence = explode(',',
        get_item_data($items, "Test/Questions/Question_$i/OrderedList/Sequences/Sequence_$sequence_index/Sequence"));
      add_sequence($sequence);
    }

    // Matched list 1
    $AnswersCount = get_item_data($items, "Test/Questions/Question_$i/MatchedList1/Count");
    $MatchedList1Count = $AnswersCount;
    for ($answers_index = 0; $answers_index < $AnswersCount; $answers_index++) {
      $answer = new answer();
      $answer->option_type = OptionMatched1;
      $answer->question = $question->id;
      $answer->number = $answers_index;
      $answer->score = get_item_data($items, "Test/Questions/Question_$i/MatchedList1/Option_$answers_index/Score");
      $answer->corresp =
        get_item_data($items, "Test/Questions/Question_$i/MatchedList1/Option_$answers_index/Position");
      $answer->text_html =
        get_item_data($items, "Test/Questions/Question_$i/MatchedList1/Option_$answers_index/TextHTML");
      add_answer($answer);
    }

    // Matched list 2
    $AnswersCount = get_item_data($items, "Test/Questions/Question_$i/MatchedList2/Count");
    for ($answers_index = 0; $answers_index < $AnswersCount; $answers_index++) {
      $answer = new answer();
      $answer->option_type = OptionMatched2;
      $answer->question = $question->id;
      $answer->number = $MatchedList1Count + $answers_index;
      $answer->score = get_item_data($items, "Test/Questions/Question_$i/MatchedList2/Option_$answers_index/Score");
      $answer->corresp =
        get_item_data($items, "Test/Questions/Question_$i/MatchedList2/Option_$answers_index/Position");
      $answer->text_html =
        get_item_data($items, "Test/Questions/Question_$i/MatchedList2/Option_$answers_index/TextHTML");
      add_answer($answer);
    }
  }

  return $test_id;
}

/**
 * Remove test from all sections
 *
 * @param $p_id integer
 * @return ADORecordSet
 */
function remove_test_from_all_sections($p_id)
{
  global $adodb;
  $t_id = db_prepare_int($p_id);
  $query = "DELETE FROM " . DB_TABLE_SECTION_TEST . " WHERE `test_id`=$t_id";
  return $adodb->Execute($query);
}

/**
 * Delete test.
 *
 * @param $p_id int test id
 * @param $must bool must remove test
 *
 * @return bool result
 */
function delete_test($p_id, $must = FALSE)
{
  global $adodb;
  $t_id = db_prepare_int($p_id);
  if ($must) $need_to_delete = TRUE; else
    $need_to_delete = remove_test_from_all_sections($p_id) != FALSE;

  if ($need_to_delete) {

    $query = 'DELETE FROM ' . DB_TABLE_TESTS . ' WHERE `id` = ' . $t_id;
    $adodb->Execute($query);

    delete_hints_for_test_id($t_id);
    delete_resume_for_test_id($t_id);
    delete_themes_for_test_id($t_id);

    return TRUE;
  } else return FALSE;
}

/**
 * Delete tests.
 *
 * @param $id_array array of int tests id
 * @return bool
 */
function delete_tests($id_array)
{
  $tmp = TRUE;
  foreach ($id_array as $id) {
    $result = delete_test($id);
    $tmp = $tmp && $result;
  }

  return $tmp;
}

/**
 * Update test.
 *
 * @param $p_id int test id
 * @param $file string xml test file
 */
function update_test($p_id, $file)
{
  delete_test($p_id, TRUE);
  import_test($file, $p_id);
}

/**
 * Edit test.
 *
 * @param $p_id int test id
 * @param $test test class
 *
 * @return ADORecordSet or false
 */
function edit_test($p_id, $test)
{
  $t_id = db_prepare_int($p_id);

  $t_test_description = db_prepare_string($test->description);
  $t_test_name = db_prepare_string($test->name);
  $t_test_type = db_prepare_int($test->type);
  $t_test_author = db_prepare_string($test->author);
  $t_test_questions_count = db_prepare_int($test->questions_count);
  $t_test_is_exam_mode = db_prepare_int($test->is_exam_mode);
  $t_test_is_random_answers = db_prepare_int($test->is_random_answers);
  $t_test_is_time_limit = db_prepare_int($test->is_time_limit);
  $t_test_time_limit = db_prepare_string($test->time_limit);
  $t_test_is_back = db_prepare_int($test->is_back);
  $t_test_is_show_score = db_prepare_int($test->is_show_score);
  $t_test_is_show_results_message = db_prepare_int($test->is_show_results_message);
  $t_test_is_next_when_right = db_prepare_int($test->is_next_when_right);
  $t_test_is_show_reference = db_prepare_int($test->is_show_reference);
  $t_test_is_response_on_right = db_prepare_int($test->is_response_on_right);
  $t_test_is_response_on_wrong = db_prepare_int($test->is_response_on_wrong);
  $t_test_text_of_right_message = db_prepare_string($test->text_of_right_message);
  $t_test_text_of_wrong_message = db_prepare_string($test->text_of_wrong_message);
  $t_test_is_show_explanation = db_prepare_int($test->is_show_explanation);
  $t_test_concl_type = db_prepare_int($test->concl_type);
  $t_test_is_stat_total = db_prepare_int($test->is_stat_total);
  $t_test_is_stat_current = db_prepare_int($test->is_stat_current);
  $t_test_is_stat_rights = db_prepare_int($test->is_stat_rights);
  $t_test_is_stat_percent_of_rights = db_prepare_int($test->is_stat_percent_of_rights);
  $t_test_is_stat_time = db_prepare_int($test->is_stat_time);
  $t_test_is_stat_max_time = db_prepare_int($test->is_stat_max_time);
  $t_test_is_stat_guid = db_prepare_int($test->is_stat_guid);
  $t_test_is_stat_test_version = db_prepare_int($test->is_stat_test_version);
  $t_test_media_storage = db_prepare_string($test->media_storage);
  $t_test_is_sort_by_theme = db_prepare_int($test->is_sort_by_theme);
  $t_test_max_count = db_prepare_int($test->max_count);
  $t_test_css = db_prepare_string($test->css);
  $t_test_is_date_limit = db_prepare_int($test->is_date_limit);
  $t_test_date_limit_from = db_prepare_date($test->date_limit_from);
  $t_test_date_limit_to = db_prepare_date($test->date_limit_to);
  $t_test_html_header = db_prepare_string($test->html_header);
  $t_test_is_show_answers_log = db_prepare_int($test->is_show_answers_log);
  $t_test_may_skip_question = db_prepare_int($test->may_skip_question);
  $t_test_allow_not_answer_question = db_prepare_int($test->allow_not_answer_question);

  $query = "UPDATE " . DB_TABLE_TESTS . " SET `test_description` =  $t_test_description,
    `test_name` =  $t_test_name,
    `test_type` =  $t_test_type,
    `test_author` =  $t_test_author,
    `test_questions_count` = $t_test_questions_count,
    `test_is_exam_mode` = $t_test_is_exam_mode,
    `test_is_random_answers` = $t_test_is_random_answers,
    `test_is_time_limit` = $t_test_is_time_limit,
    `test_time_limit` = $t_test_time_limit,
    `test_is_back` = $t_test_is_back,
    `test_is_show_score` = $t_test_is_show_score,
    `test_is_show_results_message` = $t_test_is_show_results_message,
    `test_is_next_when_right` = $t_test_is_next_when_right,
    `test_is_show_reference` = $t_test_is_show_reference,
    `test_is_response_on_right` = $t_test_is_response_on_right,
    `test_is_response_on_wrong` = $t_test_is_response_on_wrong,
    `test_text_of_right_message` = $t_test_text_of_right_message,
    `test_text_of_wrong_message` = $t_test_text_of_wrong_message,
    `test_is_show_explanation` = $t_test_is_show_explanation,
    `test_concl_type` = $t_test_concl_type,
    `test_is_stat_total` = $t_test_is_stat_total,
    `test_is_stat_current` = $t_test_is_stat_current,
    `test_is_stat_rights` = $t_test_is_stat_rights,
    `test_is_stat_percent_of_rights` = $t_test_is_stat_percent_of_rights,
    `test_is_stat_time` = $t_test_is_stat_time,
    `test_is_stat_max_time` = $t_test_is_stat_max_time,
    `test_is_stat_guid` = $t_test_is_stat_guid,
    `test_is_stat_test_version` = $t_test_is_stat_test_version,
    `test_media_storage` = $t_test_media_storage,
    `test_is_sort_by_theme` = $t_test_is_sort_by_theme,
    `test_max_count` = $t_test_max_count,
	  `test_css` = $t_test_css,
		`test_is_date_limit` = $t_test_is_date_limit,
		`test_date_limit_from` = $t_test_date_limit_from,
		`test_date_limit_to` = $t_test_date_limit_to,
		`test_html_header` = $t_test_html_header,
		`test_is_show_answers_log` = $t_test_is_show_answers_log,
		`test_may_skip_question` = $t_test_may_skip_question,
		`test_allow_not_answer_question` = $t_test_allow_not_answer_question
    WHERE `id` =  $t_id";

  return db_exec($query);
}

/**
 * Get tests for user id.
 *
 * @param $p_user_id int user id
 *
 * @return array|int
 */
function get_tests_for_user_id($p_user_id, $return_only_cunt = FALSE)
{

  $t_user_id = db_prepare_int($p_user_id);

//  $query = 'SELECT DISTINCT ' . DB_TABLE_TESTS . '.id, ' . DB_TABLE_TESTS . '.test_guid, ' . DB_TABLE_TESTS .
//    '.test_description, ' . DB_TABLE_TESTS . '.test_name, ' . DB_TABLE_TESTS . '.test_date_limit_from, ' .
//    DB_TABLE_TESTS . '.test_date_limit_to
//        FROM ' . DB_TABLE_TESTS . ', ' . DB_TABLE_GROUP_SECTION . ', ' . DB_TABLE_SECTION_TEST . ', ' . DB_TABLE_USER . ' WHERE
//    (' . DB_TABLE_TESTS . '.test_is_date_limit=0 OR (CURDATE()<' . DB_TABLE_TESTS . '.test_date_limit_to)) AND
//    (' . DB_TABLE_USER . '.`user_group_id` = ' . DB_TABLE_GROUP_SECTION . '.`group_id` OR
//        `' . DB_TABLE_GROUP_SECTION . '`.`group_id` in (SELECT `group_id` FROM `' . DB_TABLE_GROUP_USER . '`  WHERE `' .
//    DB_TABLE_GROUP_USER . '`.`user_id`=' . $t_user_id . '))
//        AND ' . DB_TABLE_GROUP_SECTION . '.`section_id` = ' . DB_TABLE_SECTION_TEST .
//    '.`section_id` AND (CURDATE() < ' . DB_TABLE_GROUP_SECTION . '.`limited_to`)
//        AND     ' . DB_TABLE_SECTION_TEST . '.`test_id` = ' . DB_TABLE_TESTS . '.`id`';
  $query = "SELECT DISTINCT tests.id, tests.test_guid, tests.test_description, tests.test_name, 
                            tests.test_date_limit_from, tests.test_date_limit_to ,tests.test_is_date_limit
    FROM `" . DB_TABLE_TESTS . "` tests, `" . DB_TABLE_GROUP_SECTION . "` gs, `" . DB_TABLE_SECTION_TEST . "` st, `" .
    DB_TABLE_USER . "` user WHERE
    (tests.test_is_date_limit=0 OR (CURDATE()<=tests.test_date_limit_to)) AND 
    (user.`user_group_id` = gs.`group_id` OR
    gs.`group_id` in (SELECT `group_id` FROM `webclass_group_user`  WHERE `webclass_group_user`.`user_id`=1))
";

  if (HIDDEN_IS_DISABLED == TRUE) $query .= "AND gs.`section_id` = st.`section_id` AND (CURDATE() <= gs.`limited_to`)";
  $query .= "AND st.`test_id` = tests.`id` AND user.`id` = 1 ORDER BY tests.`test_name` ASC";

  $result = db_query($query);
  if ($return_only_cunt) return count($result); else
    return $result;
}


/**
 * Get unhidden tests for section id.
 *
 * @param $p_section_id int section id
 *
 * @return array tests array
 */
function get_unhidden_tests_for_section_id($p_section_id)
{

  $t_section_id = db_prepare_int($p_section_id);

  $query = 'SELECT ' . DB_TABLE_TESTS . '.*
          FROM  ' . DB_TABLE_TESTS . ', ' . DB_TABLE_SECTION_TEST . ' WHERE ' . DB_TABLE_SECTION_TEST .
    '.`section_id` = ' . $t_section_id . ' AND   ' . DB_TABLE_SECTION_TEST . '.`test_is_hidden` = 0
        AND ' . DB_TABLE_SECTION_TEST . '.`test_id` = ' . DB_TABLE_TESTS . '.`id`
        AND (' . DB_TABLE_TESTS . '.test_is_date_limit = 0 OR (DATE(NOW()) BETWEEN ' . DB_TABLE_TESTS .
    '.test_date_limit_from AND ' . DB_TABLE_TESTS . '.test_date_limit_to))
        ORDER BY ' . DB_TABLE_TESTS . '.`test_name` ASC';

  return db_query($query);
}

/**
 * Get unhidden tests for section id.
 *
 * @param $p_section_id int or nil  section id
 *
 * @return array tests array
 */
function get_tests_for_section_id($p_section_id = -1)
{
  $t_section_id = db_prepare_int($p_section_id);

  if ($t_section_id == 0) {
    $query = 'SELECT * FROM  ' . DB_TABLE_TESTS . ' ORDER BY `test_name` ';

  } else {
    $query = 'SELECT ' . DB_TABLE_TESTS . '.*
          FROM  ' . DB_TABLE_TESTS . ', ' . DB_TABLE_SECTION_TEST . ' WHERE ' . DB_TABLE_SECTION_TEST .
      '.`section_id` = ' . $t_section_id . ' AND ' . DB_TABLE_SECTION_TEST . '.`test_id` = ' . DB_TABLE_TESTS . '.`id`
         ORDER BY ' . DB_TABLE_TESTS . '.`test_name` ';
  }

  return db_query($query);
}

function test_available_for_user($p_login, $p_test_id)
{
  $t_test_id = db_prepare_int($p_test_id);
  $user_id = get_user_id($p_login);
  $user_group_id = get_user_groupid($p_login);
  $query = "SELECT  `" . DB_TABLE_TESTS . "`.id  FROM `" . DB_TABLE_TESTS . "` WHERE  `" . DB_TABLE_TESTS .
    "`.`id`=$t_test_id AND `" . DB_TABLE_TESTS . "`.`id` IN 
    (SELECT `" . DB_TABLE_SECTION_TEST . "`.`test_id` FROM `" . DB_TABLE_SECTION_TEST . "` WHERE  `" .
    DB_TABLE_SECTION_TEST . "`.`section_id` IN 
        (SELECT `" . DB_TABLE_GROUP_SECTION . "`.`section_id` FROM `" . DB_TABLE_GROUP_SECTION . "` 
        WHERE (`" . DB_TABLE_GROUP_SECTION . "`.`group_id`=$user_group_id OR `" . DB_TABLE_GROUP_SECTION . "`.`group_id` IN 
            (SELECT `" . DB_TABLE_GROUP_USER . "`.`group_id` FROM `" . DB_TABLE_GROUP_USER . "` 
            WHERE `" . DB_TABLE_GROUP_USER . "`.`user_id`=$user_id)) AND 
            CURDATE() BETWEEN `" . DB_TABLE_GROUP_SECTION . "`.`limited_from` AND `" . DB_TABLE_GROUP_SECTION .
    "`.`limited_to` ))";
  $result = db_exec($query);
  return $result->RowCount() > 0;
}

/**
 * @param int $p_test_id
 * @return int
 */
function get_test_top_score($p_test_id)
{
  $t_test_id = db_prepare_int($p_test_id);
  $query = "
    SELECT MAX(resume_top)
    FROM `" . DB_TABLE_RESUME . "`
    WHERE resume_test_id=$t_test_id;";
  $result = db_query($query);
  return $result[0][0];
}
