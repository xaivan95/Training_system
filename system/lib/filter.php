<?php

// Define field types.
const T_NUM = 1;
const T_STR = 2;

/**
 * Base class for filter fields.
 */
class table_field
{
  /**
   * string field name
   */
  var $name;

  /**
   * string field value
   */
  var $value;

  /**
   * int field type
   */
  var $type;

  /**
   * Constructor.
   *
   * @param $name string field name
   * @param $value string field value
   * @param $type int field type
   */
  function __construct($name = '', $value = '', $type = T_NUM)
  {
    $this->name = $name;

    if ($value == '') {
      $this->value = '`' . db_escape_string($this->name) . '`';
    } else {
      $this->value = $value;
    }

    $this->type = $type;
  }
}

/**
 * Number field class.
 */
class num_field extends table_field
{
  /**
   * Implementation of table_field::__construct().
   * @param string $name string
   * @param string $value string
   */
  function __construct($name = '', $value = '')
  {
    parent::__construct($name, $value);
    $this->type = T_NUM;
  }
}

/**
 * String field class.
 */
class str_field extends table_field
{
  /**
   * Implementation of table_field::__construct().
   * @param string $name string
   * @param string $value string
   */
  function __construct($name = '', $value = '')
  {
    parent::__construct($name, $value);
    $this->type = T_STR;
  }
}

/**
 * Base class for all filters.
 */
class filter
{
  /**
   * table_field array
   * @see table_field
   */
  var $fields;

  /**
   * string field name
   */
  var $field;

  /**
   * string escape text
   */
  var $e_text;

  /**
   * quoted escaped text
   */
  var $p_text;

  /**
   * string text
   */
  var $text;

  /**
   * bool add to sql query 'and'
   */
  var $add_and;

  /**
   * Constructor.
   */
  function __construct()
  {
    global $WEB_APP;
    $this->field = $WEB_APP['field'];
    $this->e_text = db_escape_string($WEB_APP['text_field']);
    $this->p_text = db_prepare_string($WEB_APP['text_field']);
    $this->text = $this->p_text;
    $this->add_and = TRUE;
  }

  /**
   * Build sql query for filter.
   *
   * @return string sql query
   */
  function query()
  {
    $tmp = '';
    if (($this->field != '') && ($this->text != "''")) {
      foreach ($this->fields as $tmp_field) {
        if ($this->field == $tmp_field->name) {
          if ($tmp_field->type == T_NUM) {
            $tmp = $tmp_field->value . '=' . $this->text;
            break;
          }

          if ($tmp_field->type == T_STR) {
            $tmp = $tmp_field->value . ' LIKE ' . "'%" . $this->e_text . "%'";
            break;
          }
        }
      }
    }

    if ($this->add_and && ($tmp != '')) {
      $tmp = ' AND ' . $tmp;
    }

    return $tmp;
  }
}

class group_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id'), new str_field('group_name'), new str_field('group_description'),
      new num_field('group_login_available'), new num_field('group_registration_available'));
    $this->add_and = FALSE;

  }
}

class access_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id', DB_TABLE_ACCESS . '.`id`'), new str_field('grant_title'),
      new str_field('module_name', DB_TABLE_TRANSLATION . '.`text`'), new str_field('action'));

  }
}

class category_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id'), new str_field('category_name', DB_TABLE_TRANSLATION . '.`text`'),
      new num_field('position'), new num_field('hidden'));
  }
}

class category_module_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id', DB_TABLE_CATEGORY_MODULE . '.`id`'),
      new str_field('category_name', DB_TABLE_TRANSLATION . '.`text`'),
      new str_field('module_name', '`module_translation`.`text`'),
      new num_field('position', DB_TABLE_CATEGORY_MODULE . '.`position`'),
      new num_field('hidden', DB_TABLE_CATEGORY_MODULE . '.`hidden`'));
  }
}

class grant_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id'), new str_field('grant_title'), new num_field('grant_hidden'));
    $this->add_and = FALSE;
  }
}

class language_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id'), new str_field('name'), new str_field('short_name'));
    $this->add_and = FALSE;
  }
}

class module_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id', DB_TABLE_MODULE . '.`id`'),
      new str_field('module_name', DB_TABLE_TRANSLATION . '.`text`'),
      new str_field('module', DB_TABLE_MODULE . '.`module`'), new str_field('image', DB_TABLE_MODULE . '.`image`'),
      new num_field('hidden', DB_TABLE_MODULE . '.`hidden`'));
  }
}

class translation_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id', DB_TABLE_TRANSLATION . '.`id`'),
      new str_field('language', DB_TABLE_LANGUAGES . '.`name`'),
      new str_field('name', DB_TABLE_TRANSLATION . '.`name`'), new str_field('text', DB_TABLE_TRANSLATION . '.`text`'));
    $this->add_and = FALSE;
  }
}

class account_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields =
      array(new num_field('id', DB_TABLE_USER . '.`id`'), new str_field('user_login', DB_TABLE_USER . '.`user_login`'),
        new str_field('group_name', '`' . DB_TABLE_GROUP . '`.`group_name`'),
        new str_field('user_name', DB_TABLE_USER . '.`user_name`'),
        new str_field('user_info', DB_TABLE_USER . '.`user_info`'),
        new str_field('user_mail', DB_TABLE_USER . '.`user_mail`'),
        new num_field('user_hidden', DB_TABLE_USER . '.`user_hidden`'));
  }
}

class course_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id'), new str_field('title'), new num_field('hidden'));
    $this->add_and = FALSE;
  }
}

class viewed_books_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('user_id'), new str_field('book_title'), new str_field('chap_title'),
      new str_field('view_date'));
    $this->add_and = TRUE;
  }
}

class favorie_books_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields =
      array(new num_field('id'), new str_field('book_title'), new num_field('user_id'), new num_field('book_id'));
    $this->add_and = FALSE;
  }
}

class group_course_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id', DB_TABLE_GROUP_COURSE . '.`id`'),
      new str_field('group_name', DB_TABLE_GROUP . '.`group_name`'),
      new str_field('title', DB_TABLE_COURSE . '.`title`'));
  }
}

class group_user_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id', DB_TABLE_GROUP_USER . '.`id`'),
      new str_field('group_name', DB_TABLE_GROUP . '.`group_name`'),
      new str_field('user_name', DB_TABLE_USER . '.`user_name`'));
  }
}

class book_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id'), new str_field('book_guid'), new str_field('book_mediastorage'),
      new str_field('book_title'));
    $this->add_and = FALSE;
  }
}

class book_course_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id', DB_TABLE_BOOK_COURSE . '.`id`'),
      new str_field('book_title', DB_TABLE_BOOK . '.`book_title`'),
      new str_field('title', DB_TABLE_COURSE . '.`title`'),
      new num_field('hidden', DB_TABLE_BOOK_COURSE . '.`hidden`'));
  }
}

// Tests.

class section_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id'), new str_field('section_name'), new num_field('section_hidden'));
    $this->add_and = FALSE;
  }
}

class group_section_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id', DB_TABLE_GROUP_SECTION . '.`id`'),
      new str_field('group_name', DB_TABLE_GROUP . '.`group_name`'),
      new str_field('section_name', DB_TABLE_SECTION . '.`section_name`'));
  }
}

class test_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id'), new str_field('test_name'));
    $this->add_and = FALSE;
  }
}

class section_test_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id'), new str_field('section_name'), new str_field('test_name'),
      new num_field('test_is_hidden'));
  }
}

class user_result_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $t_timezone = db_prepare_string(date('P'));
    $this->fields =
      array(new num_field('id'), new num_field('user_result_user_id'), new num_field('user_result_test_id'),
        new num_field('user_result_completed'), new str_field('user_result_results'),
        new str_field('time_begin', 'CONVERT_TZ(`user_result_time_begin`, \'+0:00\', ' . $t_timezone . ')'),
        new str_field('time_end', 'CONVERT_TZ(`user_result_time_end`, \'+0:00\', ' . $t_timezone . ')'),
        new num_field('user_result_completed_questions'), new num_field('user_result_right_questions'),
        new num_field('user_result_score'), new num_field('user_result_percent_right'),
        new num_field('user_result_total_questions'), new str_field('user_result_test_title'));
  }
}

class report_user_results_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $t_timezone = db_prepare_string(date('P'));
    $this->fields = array(new num_field('id', 'results.`id`'), new str_field('group_name'), new str_field('user_name'),
      new str_field('user_result_test_title'),
      new str_field('time_begin', 'CONVERT_TZ(`user_result_time_begin`, \'+0:00\', ' . $t_timezone . ')'),
      new str_field('user_result_ip'));
    $this->add_and = FALSE;
  }
}

class module_action_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id', DB_TABLE_MODULE_ACTION . '.`id`'),
      new str_field('module_name', DB_TABLE_TRANSLATION . '.`text`'), new str_field('action'));
  }
}

class message_filter extends filter
{
  function __construct()
  {
    parent::__construct();
    $this->fields = array(new num_field('id'), new str_field('message_title'), new str_field('message_date'),
      new str_field('message_text'));
    $this->add_and = FALSE;
  }
}

