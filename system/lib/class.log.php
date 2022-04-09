<?php

const LOG__INFO = 'info';

class log
{
  // object instance
  private static $instance;

  private function __construct()
  {
  }

  public static function get_instance()
  {
    if (self::$instance === NULL) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  public function write($file, $level, $user, $message)
  {
    $file = @fopen(CFG_LOG_DIR . $file, 'a+');

    if ($file === FALSE) {
      return FALSE;
    }

    $message = trim(preg_replace("/([\s]+)/i", " ", $message));

    fwrite($file, '[' . @date('Y.m.d H:i:s') . '] [' . $level . '] [' . $user . '] ' . $message . "\n");

    return fclose($file);
  }

  private function __clone()
  {
  }
}

