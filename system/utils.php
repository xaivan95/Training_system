<?php

// получаем IP пользователя
function GetIP()
{
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

// получение текущего времени
function timer_get_time()
{
  $mtime_array = explode(' ', microtime());
  return $mtime_array[1] + $mtime_array[0];
}

//запуск таймера
function timer_start()
{
  global $timestart;
  $timestart = timer_get_time();
  return TRUE;
}

// остановка таймера
function timer_stop($precision = 4)
{
  global $timestart, $timeend;
  $timeend = timer_get_time();
  $timetotal = $timeend - $timestart;

  return number_format($timetotal, $precision);
}

// сразу стартуем таймер
timer_start();

// удаление слешей в массиве
function strips(&$el)
{
  if (is_array($el)) foreach ($el as $k => $v) strips($el[$k]); else
    $el = stripslashes($el);
}

// удаление слешей для основных глобальных переменных.
$ver = explode('.', PHP_VERSION);  // получение текущей версии php
$php_main_version = (float)$ver[0] . "." . $ver[1];  // объединяем в строку
if (($php_main_version < 5.4) && (ini_get("magic_quotes_runtime") == 0)) { // Если версия <5.4 или выключена функция экранирования ковычек получаемых данных
  strips($_GET);   //удаляем слеши
  strips($_POST);
  strips($_COOKIE);
  strips($_REQUEST);
  strips($_SESSION);
  if (isset($_SERVER['PHP_AUTH_USER'])) strips($_SERVER['PHP_AUTH_USER']);
  if (isset($_SERVER['PHP_AUTH_PW'])) strips($_SERVER['PHP_AUTH_PW']);
}
// получиить текущее время
function get_utc_time()
{
  return gmdate('Y-m-d H:i:s', time());
}

// перевод текста
function text($name)
{
  global $WEB_APP;

  return (isset($WEB_APP['text'][$name]) ? $WEB_APP['text'][$name] : $name);
}

// загружаем переводы текстов
function load_translations($language_id)
{
  global $WEB_APP;

  // загружаем переводы
  $english_language_id = get_language_id('English');
  if ($english_language_id == 0) {
    echo 'Install english translations.';
    exit(); //если нет английского
  }
  $text = array(); // переменная текст массив
  $translations = db_extract(DB_TABLE_TRANSLATION, 'language_id = ' . $language_id); //загружаем с бд
  foreach ($translations as $translation) {
    $text[$translation['name']] = $translation['text'];
  }

  $WEB_APP['text'] = $text; // записываем в глобальную переменную веб_апп
}

// получить значение массива по параметру,а если его нет то вернуть 3 параметр
function get_value_array($array, $param, $default = '')
{
  return (isset($array[$param]) ? $array[$param] : $default);
}

// генератор паролей
function create_password()
{
  $count = rand(3, 5) + 4;
  $password = '';
  for ($i = 0; $i < $count; $i++) {
    $tmp = rand(0, 1);
    if ($tmp === 0) {
      $c = chr(rand(0, ord('z') - ord('a')) + ord('a'));
    } else {
      $c = chr(ord('0') + rand(0, 9));
    }

    $password .= $c;
  }
  return $password;
}
// определяем константы
define('S_PHP_VERSION', 1);
define('S_WRITABLE', 2);
define('S_UNWRITABLE', 3);
define('S_MYSQL_VERSION', 4);

// вывод HTML размедки статуса исходя из bool
function echo_status($bool, $status_const = 0, $version = 0.0)
{
  if ($status_const == 0) {
    if ($bool) {
      return '<span style="color:green"><strong>OK</strong></span>';
    }

    return '<span style="color:red"><strong>Error</strong></span>';
  }

  if ($status_const == S_PHP_VERSION) {
    if ($bool) {
      return '<span style="color:green"><strong>OK (ver ' . PHP_VERSION . ')</strong></span>';
    }

    return '<span style="color:red"><strong>Error (ver 5.0 min)</strong></span>';
  }

  if ($status_const == S_WRITABLE) {
    if ($bool) {
      return '<span style="color:green"><strong>OK</strong></span>';
    }

    return '<span style="color:red"><strong>Error: Not Writable</strong></span>';
  }

  if ($status_const == S_UNWRITABLE) {
    if ($bool) {
      return '<span style="color:green"><strong>OK</strong></span>';
    }

    return '<span style="color:red"><strong>Error: Writable</strong></span>';
  }

  if ($status_const == S_MYSQL_VERSION) {
    if ($bool) {
      return '<span style="color:green"><strong>OK (ver ' . $version . ')</strong></span>';
    }

    return '<span style="color:red"><strong>Error (ver ' . CFG_MIN_MYSQL_VERSION . ' min)</strong></span>';
  }
  return '';
}
// проверка системных требований сервера при загрузке и вывод результатов в таблице
function status_report()
{
  $enabled = TRUE;

  if (PHP_MAIN_VERSION < 5.3) {
    $register_globals = (bool)ini_get('register_globals');
    $magic_quotes_gpc = (bool)ini_get('magic_quotes_gpc');
    $magic_quotes_runtime = (bool)ini_get('magic_quotes_runtime');

    $register_globals_status = echo_status(!$register_globals);
    $magic_quotes_gpc_status = echo_status(!$magic_quotes_gpc);
    $magic_quotes_runtime_status = echo_status(!$magic_quotes_runtime);

    $enabled = !$register_globals && !$magic_quotes_gpc && !$magic_quotes_runtime;
  }
  if (PHP_MAIN_VERSION < 5.6) {
    $mbstring_internal_encoding = ini_get('mbstring.internal_encoding');
    $mbstring_http_input = ini_get('mbstring.http_input');
    $mbstring_http_output = ini_get('mbstring.http_output');

    $mbstring_internal_encoding_status = echo_status($mbstring_internal_encoding == "UTF-8");
    $mbstring_http_input_status = echo_status($mbstring_http_input == "UTF-8");
    $mbstring_http_output_status = echo_status($mbstring_http_output == "UTF-8");

    $enabled = $enabled && ($mbstring_internal_encoding && $mbstring_http_input && $mbstring_http_output);
  }
  if (PHP_MAIN_VERSION >= 5.6) {
    $default_charset = ini_get('default_charset');
    $default_charset_status = echo_status($default_charset == "UTF-8");
    $enabled = $enabled && $default_charset_status;
  }

  /** ALL PHP VERSIONS */

  $iconv = function_exists("iconv");
  $mysql_database = function_exists("mysqli_connect");
  $gd = function_exists("gd_info");
  $mbstring = function_exists("mb_eregi");
  $writable_config = !is_writable("system/config_inc.php");
  $writable_templates_c_folder = is_writable(CFG_DEFAULT_THEME_DIR . CFG_THEME_COMPILE_DIR);
  $xml_support = function_exists("xml_parser_create");
  $session_auto_start = (bool)ini_get('session.auto_start');
  $session_use_cookies = (bool)ini_get('session.use_cookies');
  $session_use_trans_sid = (bool)ini_get('session.use_trans_sid');
  $mbstring_encoding_translation = (bool)ini_get('mbstring.encoding_translation');

  $php_version_status = echo_status(PHP_MAIN_VERSION >= 5.0, S_PHP_VERSION);
  $iconv_status = echo_status($iconv);
  $mysql_database_status = echo_status($mysql_database);
  $gd_status = echo_status($gd);
  $mbstring_status = echo_status($mbstring);
  $xml_support_status = echo_status($xml_support);

  $writable_config_status = echo_status($writable_config, S_UNWRITABLE);

  $writable_templates_c_folder_status = echo_status($writable_templates_c_folder, S_WRITABLE);

  $session_auto_start_status = echo_status(!$session_auto_start);
  $session_use_cookies_status = echo_status($session_use_cookies);
  $session_use_trans_sid_status = echo_status(!$session_use_trans_sid);
  $mbstring_encoding_translation_status = echo_status(!$mbstring_encoding_translation);

  $enabled = $enabled &&
    ((PHP_MAIN_VERSION >= 5.0) && $iconv && $mysql_database && $gd && $mbstring && $writable_config &&
      $writable_templates_c_folder && $xml_support && !$session_auto_start && $session_use_cookies &&
      !$session_use_trans_sid && !$mbstring_encoding_translation);


  if (!$enabled) {
    ?>
      <html lang="en">
      <head>
          <title>КАОС 54 кафедра - статус</title>
          <meta charset="utf-8">
          <style>
              table {
                  border: 1px black solid;
              }

              th, td {
                  padding: 4px;
                  border-bottom: 1px solid #ddd;
              }

              tr:hover {
                  background-color: #dddddd;
              }
          </style>
      </head>
      <body>
      <table>
          <tr>
              <th class="col" colspan="2"><strong>КАОС 54 кафедра - статус</strong><br></th>
          </tr>
          <tr>
              <td>PHP Version</td>
              <td><?php echo $php_version_status; ?></td>
          </tr>
          <tr>
              <td>MySQL Support</td>
              <td><?php echo $mysql_database_status; ?></td>
          </tr>
          <tr>
              <td>Iconv Module</td>
              <td><?php echo $iconv_status; ?></td>
          </tr>
          <tr>
              <td>MB Strings Module</td>
              <td><?php echo $mbstring_status; ?></td>
          </tr>
          <tr>
              <td>GD Module</td>
              <td><?php echo $gd_status; ?></td>
          </tr>
          <tr>
              <td>XML Support</td>
              <td><?php echo $xml_support_status; ?></td>
          </tr>
          <tr>
              <td>Unwritable Configuration File (system/config_inc.php)</td>
              <td><?php echo $writable_config_status; ?></td>
          </tr>
          <tr>
              <td>Writable compiled templates folder (<?php echo(CFG_DEFAULT_THEME_DIR . CFG_THEME_COMPILE_DIR) ?>)</td>
              <td><?php echo $writable_templates_c_folder_status; ?></td>
          </tr>

          <tr>
          <tr>
              <td>session.auto_start = off</td>
              <td><?php echo $session_auto_start_status; ?></td>
          </tr>
          <tr>
              <td>session.use_cookies = on</td>
              <td><?php echo $session_use_cookies_status; ?></td>
          </tr>
          <tr>
              <td>session.use_trans_sid = off</td>
              <td><?php echo $session_use_trans_sid_status; ?></td>
          </tr>
          <tr>
              <td>mbstring.encoding_translation = off</td>
              <td><?php echo $mbstring_encoding_translation_status; ?></td>
          </tr>
        <?php
        if (PHP_MAIN_VERSION < 5.3) {
          ?>
            <tr>
                <td>register_globals = off</td>
                <td><?php echo $register_globals_status; ?></td>

            </tr>
            <tr>
                <td>magic_quotes_gpc = off</td>
                <td><?php echo $magic_quotes_gpc_status; ?></td>
            </tr>
            <tr>
                <td>magic_quotes_runtime = off</td>
                <td><?php echo $magic_quotes_runtime_status; ?></td>
            </tr>
          <?php
        }
        if (PHP_MAIN_VERSION < 5.6) {
          ?>
            <tr>
                <td>mbstring.internal_encoding = UTF-8</td>
                <td><?php echo $mbstring_internal_encoding_status; ?></td>
            </tr>
            <tr>
                <td>mbstring.http_input = UTF-8</td>
                <td><?php echo $mbstring_http_input_status; ?></td>
            </tr>
            <tr>
                <td>mbstring.http_output = UTF-8</td>
                <td><?php echo $mbstring_http_output_status; ?></td>
            </tr>
          <?php
        }
        if (PHP_MAIN_VERSION >= 5.6) { ?>
            <tr>
                <td>default_charset = UTF-8</td>
                <td><?php echo $default_charset_status; ?></td>
            </tr>
        <?php } ?>

      </table>
      </body>
      </html>

    <?php
    exit();
  }
}

