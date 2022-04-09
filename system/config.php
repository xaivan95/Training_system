<?php

// Запуск сессии
session_name('SID');  //устанавливаем имя сессии
if (!session_id()) {   // если идентификатора сессии для пользователя нет
  @session_start([    // запускаем сессию
    'cookie_lifetime' => 86400,  // на сутки
]);
}

// Start timer.
require_once 'utils.php';         // подключаем файл
header("Content-Type: text/html; charset=utf-8"); // начало заголовка
// константа с версией сервера
$ver = explode('.', PHP_VERSION);
$php_version = $ver[0];
define('PHP_MAIN_VERSION', (float)$ver[0] . "." . $ver[1]);
error_reporting(E_ALL ^ E_NOTICE);

if (PHP_MAIN_VERSION < 5.4) ini_set("magic_quotes_runtime", "0");

define('CFG_PATH', dirname(__FILE__));

// устанвока констант
define('CFG_BOOKS_DIR', CFG_PATH . '/books/');
define('CFG_TESTS_DIR', CFG_PATH . '/tests/');
define('CFG_LIB_DIR', CFG_PATH . '/lib/');
define('CFG_MENU_THEMES_DIR', CFG_PATH . '/../book_themes/');
define('CFG_ERRORS_LOG_DIR', CFG_PATH . '/log/');
define('CFG_LOG_DIR', CFG_PATH . '/log/');
define('CFG_ADODB_DIR', CFG_LIB_DIR . 'adodb/');
define('CFG_SMARTY_DIR', CFG_LIB_DIR . 'smarty/');
const CFG_HELP_DIR = 'help/';
const CFG_THEMES_DIR = 'themes/';
const CFG_DEFAULT_THEME = 'twiboostrap';
const CFG_DEFAULT_THEME_DIR = 'themes/twiboostrap/';
const CFG_THEME_COMPILE_DIR = 'templates_c/';

const CFG_SHOW_VERSION = TRUE;
const CFG_CHECK_INSTALL = TRUE;
const CFG_WRITE_SQL_LOG = FALSE;
const CURRENT_DB_VERSION = 34;

const DEFAULT_ORDER = "DESC";
const DEFAULT_LANGUAGE = 2; //Изначально Русский
const HIDDEN_IS_DISABLED = TRUE;// Ограничиваем скрытые книги и тесты, если "false", то скрытые тесты и книги доступны по прямой ссылке
const FIELDS_DIVIDER = " || ";
const EMPTY_FIELDS = "#$#$#$";
const HIGHTLIGHT_WRONG_FIELDS = TRUE;
const TRANSLATE_TEST = TRUE;
const TRANSLATE_BOOK = TRUE;
const TEST_LINK_IMG = 'ex.svg';
const TEST_COMPLETED_LINK_IMG = 'ex_completed.svg';
const MAIL_FROM_NAME = 'Образовательная среда 54 кафедры';

const CFG_MIN_MYSQL_VERSION = '1.0';

// версия.
const CFG_VERSION = '1.0';
const CFG_VERSION_DATE = 20220205;

// тестирование
const TEST_TYPE_COMMON = 0;
const TEST_TYPE_SCORED = 1;


const QUESTION_TYPE_DEFAULT = 0;
const QUESTION_TYPE_COMMON = 1;
const QUESTION_TYPE_SCORED = 2;


const TEST_SELECT_TEST_FROM_SECTION = 1;
const TEST_ALL_TESTS_IN_LIST = 2;

// Group report
const GROUP_REPORT_LIMITED_SECTIONS = FALSE;
// удаление файлов установки
if (!isset($no_config)) {
  if (CFG_CHECK_INSTALL) {
    if (file_exists("install.php") || file_exists("install/")) {
      echo "Remove <strong>install.php</strong> file and <strong>install</strong> folder.<br>";
      echo "Удалите файл <strong>install.php</strong> и папку <strong>install</strong>.";
      exit();
    }
  }
  define('CFG_CHECK_STATUS', TRUE);

  if (CFG_CHECK_STATUS) {
    status_report();   //вывод из файла utils статуса, если есть ошибки
  }
  // загрузка параметров бд
  require_once 'config_inc.php';
// если параметров нет
  if (!defined('CFG_HOST')) {
    echo 'Run install.php.';
    exit();
  }

  // URL.
  $host_len = strlen(CFG_HOST);

  if (substr(CFG_HOST, $host_len - 1, 1) != '/') {
    define('CFG_HOST_NAME', CFG_HOST . '/');
  } else {
    define('CFG_HOST_NAME', CFG_HOST);
  }
  define('CFG_URL', 'http://' . CFG_HOST_NAME);

  // Запись ошибок в логи
  define('CFG_WRITE_ERROR_LOG', FALSE);

  if (CFG_WRITE_ERROR_LOG) {
    require_once CFG_LIB_DIR . 'lib.error_log.php'; //если пишем в лог то подключаем файл с логами и функциями для записи
  }

  // стиль первого окна
  define('LOGIN_LIST', 1);
  define('LOGIN_DIRECT', 2);

  // подключаем библиотеки
  require_once CFG_SMARTY_DIR . 'Smarty.class.php'; // библиотека шаблонизатора
  require_once CFG_ADODB_DIR . 'adodb.inc.php';     // библиотека для работы БД
  require_once CFG_PATH . '/router.php'; //подключаем модуль маршрутизации

  require_once CFG_LIB_DIR . 'lib.database.php';
  require_once CFG_LIB_DIR . 'lib.settings.php';
  require_once CFG_PATH . '/db.php';
  require_once CFG_LIB_DIR . 'filter.php';
  require_once CFG_LIB_DIR . 'is_email.php';
  require_once CFG_LIB_DIR . 'form.php';
  require_once CFG_LIB_DIR . 'help.php';
  require_once CFG_LIB_DIR . 'query.php';
  require_once CFG_LIB_DIR . 'count.php';
  require_once CFG_LIB_DIR . 'xml.php';
  require_once CFG_PATH . '/views/view_base.php';
  require_once CFG_PATH . '/views/view_smarty.php';
  require_once CFG_LIB_DIR . 'class.log.php';
  require_once CFG_LIB_DIR . 'class.language.php';
  require_once CFG_LIB_DIR . 'class.user.php';
  require_once CFG_LIB_DIR . 'class.grant.php';
  require_once CFG_LIB_DIR . 'class.group.php';
  require_once CFG_LIB_DIR . 'class.book.php';
  require_once CFG_LIB_DIR . 'class.book_course.php';
  require_once CFG_LIB_DIR . 'class.favorite_books.php';
  require_once CFG_LIB_DIR . 'class.chapter.php';
  require_once CFG_LIB_DIR . 'class.column.php';
  require_once CFG_LIB_DIR . 'class.field.php';
  require_once CFG_LIB_DIR . 'class.translation.php';
  require_once CFG_LIB_DIR . 'class.paginator.php';
  require_once CFG_LIB_DIR . 'class.course.php';
  require_once CFG_LIB_DIR . 'class.group_course.php';
  require_once CFG_LIB_DIR . 'class.category.php';
  require_once CFG_LIB_DIR . 'class.module.php';
  require_once CFG_LIB_DIR . 'class.category_module.php';
  require_once CFG_LIB_DIR . 'class.access.php';
  require_once CFG_LIB_DIR . 'class.xml_item.php';
  require_once CFG_LIB_DIR . 'class.query.php';
  require_once CFG_LIB_DIR . 'class.module_action.php';
  require_once CFG_LIB_DIR . 'class.viewed_books.php';
  require_once CFG_LIB_DIR . 'class.group_user.php';
  require_once CFG_LIB_DIR . 'class.archive.php';

  // тесты.
  require_once CFG_LIB_DIR . 'class.section.php';
  require_once CFG_LIB_DIR . 'class.group_section.php';
  require_once CFG_LIB_DIR . 'class.test.php';
  require_once CFG_LIB_DIR . 'class.section.php';
  require_once CFG_LIB_DIR . 'class.hint.php';
  require_once CFG_LIB_DIR . 'class.theme.php';
  require_once CFG_LIB_DIR . 'class.resume.php';
  require_once CFG_LIB_DIR . 'class.question.php';
  require_once CFG_LIB_DIR . 'class.answer.php';
  require_once CFG_LIB_DIR . 'class.conclusion.php';
  require_once CFG_LIB_DIR . 'class.section_test.php';
  require_once CFG_LIB_DIR . 'class.user_result.php';
  require_once CFG_LIB_DIR . 'class.user_answer.php';
  require_once CFG_LIB_DIR . 'class.user_result_theme.php';
  require_once CFG_LIB_DIR . 'class.user_result_time.php';
  require_once CFG_LIB_DIR . 'class.inline_field.php';
  require_once CFG_LIB_DIR . 'class.sequence.php';
  require_once CFG_LIB_DIR . 'class.message.php';

  // подключаемся к БД
  $adodb = open_database();
  $version = get_mysql_version();
  if (!check_db_version($version)) die ('Required MySQL version ' . CFG_MIN_MYSQL_VERSION . ' and above.');

  // загружаем настройки
  $WEB_APP['settings'] = settings_get();
  $theme = $WEB_APP['settings']['theme'];
  if (is_dir(CFG_THEMES_DIR . "$theme/")) $theme = $WEB_APP['settings']['theme']; else
    $theme = CFG_DEFAULT_THEME;

  //Настройки пользователя
  if (isset($_SESSION['user_login'])) {
    $user = get_user_from_login_password($_SESSION['user_login'], $_SESSION['user_password']);
    if (($user->id !== null and $user->name !== 'anonymous') and
      ($user->theme !== null) and $WEB_APP['settings']['admset_user_change_theme']) {
      $WEB_APP['settings']['user_theme'] = $user->theme;
      $theme = $WEB_APP['settings']['user_theme'];
    }
  }

  // Установка директорий
  define('CFG_TEMPLATES_DIR', CFG_THEMES_DIR . $theme . '/templates/');
  define('CFG_COMPILE_DIR', CFG_THEMES_DIR . $theme . '/templates_c/');
  define('CFG_CSS_DIR', CFG_URL . CFG_THEMES_DIR . $theme . '/css/');
  define('CFG_IMAGES_DIR', CFG_URL . CFG_THEMES_DIR . $theme . '/images/');
  define('CFG_IMG_DIR', CFG_URL . CFG_THEMES_DIR . $theme . '/img/');
  define('CFG_THEME_JS_DIR', CFG_URL . CFG_THEMES_DIR . $theme . '/js/');


  // установка времени
  date_default_timezone_set($WEB_APP['settings']['timezone']);

  $WEB_APP['editform'] = TRUE;
  $WEB_APP['errorstext'] = '';

  $parse_url_array = parse_url(CFG_URL);
  $WEB_APP['host'] = $parse_url_array['host'];

  if (CFG_SHOW_VERSION) {
    $WEB_APP['version'] = CFG_VERSION;
    $WEB_APP['version_date'] = CFG_VERSION_DATE;
  }
  $WEB_APP['cfg_url'] = CFG_URL;
  $WEB_APP['images_dir'] = CFG_IMAGES_DIR;
  $WEB_APP['css_dir'] = CFG_CSS_DIR;
  $WEB_APP['theme_js_dir'] = CFG_THEME_JS_DIR;
  global $help;
  $WEB_APP['help'] = $help;

  if (isset($user) and ($user->id !== null and $user->name !== 'anonymous') and
    ($user->language_id != 0) and $WEB_APP['settings']['admset_user_change_language']) {
    load_translations($user->language_id);
    $WEB_APP['language_code'] = get_language_short_name_by_id($user->language_id);
  } else {
    load_translations($WEB_APP['settings']['language_id']);
    $WEB_APP['language_code'] = get_language_short_name_by_id($WEB_APP['settings']['language_id']);
  }


  // загрузка страниц.
  require_once CFG_LIB_DIR . 'class.action.php';
  require_once CFG_LIB_DIR . 'class.list_action.php';

  $WEB_APP['list_actions'] = array($WEB_APP['list_action_delete']);
}

