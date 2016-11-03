#!/usr/bin/php
<?php
require_once './src/DaemonCall.php';
require_once './src/lib/SkySocketElastisk.php';

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
  $elastisk = new SkySocketElastisk("127.0.0.1", "3000");
  // hacemos login
  $elastisk->write("Action: Login", true);
  $elastisk->write("Username: admin", true);
  $elastisk->write("Secret: maylo165*", true);

} catch (Exception $e) {
  die($e->getMessage());
}

$totalGps = count($gpsNumbers);
echo "I'm the daemon of skyguard :D\n";
do {

  for ($i = 0; $i < $totalGps; $i++ ) {
    print "Marcando a numero: {$gpsNumbers[$i]} \n";
    $elastisk->write("Action: Originate", true);
    $elastisk->write("Channel: sip/3135709916@GoIP1", true);
    $elastisk->write("MaxRetries: 2", true);
    $elastisk->write("RetryTime: 300", true);
    $elastisk->write("WaitTime: 45", true);
    $elastisk->write("Context: outboundmsg1", true);
    $elastisk->write("Exten: s", true);
    $elastisk->write("Priority: 1", true);
    $elastisk->write("Callerid: BOO-045 <3135709916>", true);

    // wait...
    $elastisk->read(function ($me) {
      $line  = $me->getLine();
      echo "message from socket: ".$me->getLine();
    });

    sleep(TIME_CALL_SLEEP);

    //$elastisk->close();
  }


} while (true);
