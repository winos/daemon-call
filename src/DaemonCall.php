<?php
/**
 * Daemon process
 *
 * @author    Dawin Ossa <dawinos@gmail.com>
 * @copyright Skyguard.net
 */
class DaemonCall
{
  private $_numbers = [];
  private $_commands = [];

  public static function init ()
  {
    echo 'Init...';
  }

  public function __construct($numbers, $logger = null)
  {
    $this->_commands =[
      ''
    ];

    $this->_numbers = $numbers;
  }


  public function addCommands ($commands) {
    array_merge($this->_commands, $commands);
  }

  private function priority ()
  {

  }

  private function call ($sim)
  {

  }


  private function upload ()
  {

  }

  public function get () {
    return $this->_numbers;
  }
}
