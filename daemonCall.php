#!/usr/bin/php
<?php
require_once './src/DaemonCall.php';
require_once './src/lib/SkySocketElastix.php';
require_once './src/lib/Logger.php';
/**
* This is a daemon for audio events
*
* @author    Dawin Ossa <dawinos@gmail.com>
* @copyright Skyguard.net
*/
define('TIME_CALL_SLEEP', 10);

// Coleccion de sims
$gpsNumbers = ['3135709916'];
$daemon = new DaemonCall($gpsNumbers);


try {
  
  Logger::open();
  $elastix = new SkySocketElastix("192.168.0.250", "5038");
  // hacemos login
  $elastix->write("Action: Login");
  $elastix->write("Username: skyguard");
  $elastix->write("Secret: maylo165*", true);

} catch (Exception $e) {
  die($e->getMessage());
}

$totalGps = count($gpsNumbers);
echo "I'm the daemon of skyguard :D\n";
//do {

  for ($i = 0; $i < $totalGps; $i++ ) {
    print "Marcando a numero: {$gpsNumbers[$i]} \n";
    $elastix->write("Action: Originate");
    $elastix->write("Channel: sip/3135709916@GoIP1");
    $elastix->write("MaxRetries: 2");
    $elastix->write("RetryTime: 300");
    $elastix->write("WaitTime: 45");
    $elastix->write("Context: outboundmsg1");
    $elastix->write("Exten: s");
    $elastix->write("Priority: 1");
    $elastix->write("Callerid: BOO-045 <3135709916>", true);

    // wait...
    $elastix->read(function ($me) {

      $line  = $me->getLine();
      Logger::write($line, "info");
      echo "message from socket: ".$line;
      if (strpos($line, '.wav') > 0){
	$rutaExp = explode(' ', $line);
	$file = $rutaExp[1];
	$uploadResponse = uploadFile($file, '3135709916');
	var_dump($uploadResponse);
	echo "Ruta del archivo:". $file . "\n";
      } 
     if (strpos($line, 'Hangup') > 0)
        return true;
    });
    break;
  //  sleep(TIME_CALL_SLEEP);
  }
//} while (true);
function uploadFile($filePath, $number)
{
	$post = array('phoneNumber'=>$number, 'method' => 'recordCall', 
'file_contents' 
=> new 
CURLFile($filePath), 'file_extension' => 'wav');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://files.sky-guard.net/lib/uploadFileJQuery.php');
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
	$rs = curl_exec($ch);
	curl_close ($ch);
	return $rs;
}
echo "fin de llamada;";
