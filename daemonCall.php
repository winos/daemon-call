#!/usr/bin/php
<?php
require_once './src/DaemonCall.php';
require_once './src/lib/SkySocketElastix.php';

/**
* This is a daemon for audio events
*
* @author    Dawin Ossa <dawinos@gmail.com>
* @copyright Skyguard.net
*/
define('TIME_CALL_SLEEP', 10);

// Coleccion de sims
$gpsNumbers = ['3135709916', '3007400080'];
$daemon = new DaemonCall($gpsNumbers);

try {
  $elastix = new SkySocketElastix("127.0.0.1", "3000");
  // hacemos login
  $elastix->write("Action: Login", true);
  $elastix->write("Username: admin", true);
  $elastix->write("Secret: maylo165*", true);

} catch (Exception $e) {
  die($e->getMessage());
}

$totalGps = count($gpsNumbers);
echo "I'm the daemon of skyguard :D\n";
do {

  for ($i = 0; $i < $totalGps; $i++ ) {
    print "Marcando a numero: {$gpsNumbers[$i]} \n";
    $elastix->write("Action: Originate", true);
    $elastix->write("Channel: sip/3135709916@GoIP1", true);
    $elastix->write("MaxRetries: 2", true);
    $elastix->write("RetryTime: 300", true);
    $elastix->write("WaitTime: 45", true);
    $elastix->write("Context: outboundmsg1", true);
    $elastix->write("Exten: s", true);
    $elastix->write("Priority: 1", true);
    $elastix->write("Callerid: BOO-045 <3135709916>", true);

    // wait...
    $elastix->read(function ($me) {

      $line  = $me->getLine();
      echo "message from socket: ".$line;
      if(preg_match('/^.*\.(wav)$/i', $line))
        return true;
    });

    sleep(TIME_CALL_SLEEP);
  }

} while (true);
