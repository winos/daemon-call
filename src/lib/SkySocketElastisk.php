<?php
/**
 * SkySocketElastisk
 *
 * @author    Dawin Ossa <dawinos@gmail.com>
 * @copyright Skyguard.net
 */
class SkySocketElastisk
{
  private $_socket = null;
  private $_error = null;
  private $_line;

  const TIMEOUT = 30;

  public function __construct($host='', $port='5038')
  {
    self::_connect($host, $port);
  }

  private function _connect($host, $port)
  {
    $socket = fsockopen($host, $port, $erno, $errstr, self::TIMEOUT);
    if (!$socket) {
      $this->_error = $errstr;
      throw new Exception("{$errstr}: {$errno}", $errno);
    } else {
      $this->_socket =  $socket;
    }
  }

  public function write($msg="", $doubleReturn = false)
  {
    //if ($this->_error) {
      $msg = $doubleReturn ? $msg . "\r\n\r\n" : "\r\n";
      fputs($this->_socket, $msg);
    //}
  }

  public function read($cb)
  {
    while (!feof($this->_socket)){
        $this->_line = fgets($this->_socket);
        $cb($this);
        //if ($return) $this->close();
     }
  }

  public function getLine()
  {
    return $this->_line;
  }

  public function close()
  {
      fclose($this->_socket);
  }

}
