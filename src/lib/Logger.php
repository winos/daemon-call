<?php
/**
 * Logger class
 *
 * @author    Dawin Ossa <dawinos@gmail.com>
 * @copyright Skyguard.net
 */

date_default_timezone_set('America/Bogota');

class Logger
{
  private static $_file = null;

  public static function open()
  {
    self::$_file = fopen(realpath( '.' )."/log/skyguard-daemon-".date("Y-m-d").".log", "a+");
  }

  public function close()
  {
    if ($this->_file) fclose($this->_file);
  }

  public static function write($msg, $type)
  {
    fwrite(self::$_file, "[".date("Y-m-d H:i:s")."  - {$type} ] {$msg}\n");
  }
}
