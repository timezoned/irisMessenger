<?PHP
include_once('./irisMessengerConfig.php');

date_default_timezone_set(timezone_name_from_abbr('UTC'));

while(true) {
  echo "in while".PHP_EOL;
  $repeatFlag = 0; 
  $message_array = get_messageToSend($dbHost, $dbUser, $dbPass, $dbName, $myService, $sleepOffset);
  for($idx = 0; $idx < count($message_array);$idx++) {
    if(count($message_array[$idx]) > 0 && strlen($message_array[$idx]['output']) > 0 && strlen($message_array[$idx]['msisdn']) > 0) {
      $message = $message_array[$idx]['output'];
      //Check for country code
      if(strlen($message_array[$idx]['msisdn']) == 11 && substr($message_array[$idx]['msisdn'],0,1) != '1')
        $phoneNumber = '1'.$message_array[$idx]['msisdn'];
      else 
        $phoneNumber = $message_array[$idx]['msisdn'];    
      echo "Phone:".$phoneNumber.PHP_EOL;
    
      # Strip out non-digits
      $phoneNumber = preg_replace('/[ )(.-]/', "", $phoneNumber); 

      $sysID = $message_array[$idx]['sysID'];
      echo "Phone:".$phoneNumber.PHP_EOL;
      $method = $message_array[$idx]['method'];
    
      if($myService == 'nexmo') {
        try {
          if(strlen($message) > 160)
            $message = substr($message,0,160);
          $scheduled = "";
          $message = json_decode(sendMessage($api_key, $api_secret, $myNexmoNumber, $phoneNumber, $message, $method), true);
          if( $message_array[$idx]['repeatInterval'] != "0" ||  $message_array[$idx]['repeatMultiplier'] != "N") {
            //$time = gmmktime();
            $timeStamp = gmdate("Y-m-d H:i:s"); 
            $newTimeStamp = getRepeatInterval($timeStamp, $message_array[$idx]['repeatInterval'], $message_array[$idx]['repeatMultiplier']);
            echo 'newTimeStamp:'.$newTimeStamp.PHP_EOL; 
            $repeatFlag = 1; 
          }
          else
            $newTimeStamp = $message_array[$idx]['scheduled'];
          if($method === "T") {
            if(($message['message-count'] == 1 && $message['messages'][0]['status'] == 0)) { 
              echo 'messages[0]->status:'. $message['messages'][0]['status'].PHP_EOL;
              echo 'messages[0]->messageid:'. $message['messages'][0]['message-id'].PHP_EOL;
              put_msgStatus($dbHost, $dbUser, $dbPass, $dbName, $myService, $message_array[$idx]['sysID'], $message['messages'][0]['message-id'], $message['messages'][0]['status'], $newTimeStamp, $repeatFlag);
            }      
            else { //add 
              echo "failed\n\n";
              print_r($message);
              $timeStamp = gmdate("Y-m-d H:i:s"); 
              $newTimeStamp = getRepeatInterval($timeStamp, '30', 's');
              echo 'scheduled: '.$message_array[$idx]['scheduled'].PHP_EOL;
              echo 'newTimeStamp: '.$newTimeStamp.PHP_EOL; 
              put_msgStatus($dbHost, $dbUser, $dbPass, $dbName, $myService, $message_array[$idx]['sysID'], '', $message['messages'][0]['status'], $newTimeStamp, $repeatFlag);
            }
          }     

          else if($method === "V") {
            if($message['status'] == 0) { 
              echo 'messages->status:'. $message['status'].PHP_EOL;
              echo 'messages->messageid:'. $message['call_id'].PHP_EOL;
              echo 'repeatFlag:'. $repeatFlag.PHP_EOL;
              put_msgStatus($dbHost, $dbUser, $dbPass, $dbName, $myService, $message_array[$idx]['sysID'], $message['call_id'], $message['status'], $newTimeStamp, $repeatFlag);
            }      
            else {
              echo "failed".PHP_EOL;
              print($message['error_text']);
              $timeStamp = gmdate("Y-m-d H:i:s"); 
              $newTimeStamp = getRepeatInterval($timeStamp, '30', 's');
              echo 'scheduled: '.$message_array[$idx]['scheduled'].PHP_EOL;
              echo 'newTimeStamp: '.$newTimeStamp.PHP_EOL; 
              put_msgStatus($dbHost, $dbUser, $dbPass, $dbName, $myService, $message_array[$idx]['sysID'], '', $message['status'], $newTimeStamp, $repeatFlag);
            }
          }    
        } //End Try
        catch (Exception $e) {
          echo 'Caught Exception :'.$e->getMessage().PHP_EOL;
        }      
      }  //end if service = nexmo    
    }    // end if count  
  }      //End for
  echo "sms_test Sleep for ". $sleepOffset ." seconds".PHP_EOL.PHP_EOL;
  sleep($sleepOffset);
} //End While

function getRepeatInterval($timeStamp, $repeatInterval, $repeatMultiplier) {
  $date = new DateTime($timeStamp);
  if(ctype_lower($repeatMultiplier) ) { //Must be a time with hour, minute or second
    $myDateInterval = "PT" . $repeatInterval . (strtoupper($repeatMultiplier));
echo 'myDateInterval:'.$myDateInterval.PHP_EOL;
  }
  if(!ctype_lower($repeatMultiplier) ) { //Must be Day, Week, Month, Year
    $myDateInterval = "P" . $repeatInterval . (strtoupper($repeatMultiplier));
  }
  // DateInterval Always starts with (P)eriod ( (T)ime if needed (H)ours (M)inute (S)econds) number (D)ay (W)eek, (M)onth (Y)ear
  $date->add(new DateInterval($myDateInterval));
  return ($date->format('Y-m-d H:i:s')); // 2015-09-23 21:27:44
}
    

function put_msgStatus($dbHost, $dbUser, $dbPass, $dbName, $myService, $sysID, $outgoingID, $messageStatus, $scheduled, $repeatFlag) {
  //Connect
  $dbConnect = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
  // Check connection
  if ($dbConnect->connect_error) {
    die("Connection failed: " . $dbConnect->connect_error);
  }
  if($repeatFlag === 1 && $messsageStatus === 0)
    $sql = "update nexmoMessages SET outgoingID='', messageStatus = '$messageStatus', scheduled = '$scheduled' where sysID='$sysID'";
  else
    $sql = "update nexmoMessages SET outgoingID='$outgoingID', messageStatus = '$messageStatus', scheduled = '$scheduled' where sysID='$sysID'";
  $res = $dbConnect->query($sql);
  echo $sql.PHP_EOL;
  $dbConnect->close();
  return $res;
}

function get_messageToSend($dbHost, $dbUser, $dbPass, $dbName, $myService, $sleepOffset) {
  //Connect
  $myList = Array();
  $dbConnect = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
  // Check connection
  if ($dbConnect->connect_error) {
    die("Connection failed: " . $dbConnect->connect_error);
  }
  date_default_timezone_set(timezone_name_from_abbr('UTC'));
  $mydate = date("F j, Y, G:i");
  $sql = "select  * from mydb.nexmoMessages where (TIMESTAMPDIFF(SECOND, UTC_TIMESTAMP(), scheduled) < $sleepOffset AND TIMESTAMPDIFF(SECOND, UTC_TIMESTAMP(), scheduled)" .
         " > -$sleepOffset) AND (isnull(outgoingID) OR length(outgoingID) = 0)";
  $res = $dbConnect->query($sql);
  echo $sql.PHP_EOL;
  $i = 0;
  while($row = $res->fetch_array(MYSQLI_ASSOC)) {
    $myList[$i] = $row;
    $i++;
  }  
  $res->free();
  $dbConnect->close();
  return $myList;
}

function sendMessage($api_key, $api_secret, $from, $to, $output, $method) {
  if($method === "V")
    $output = '<break time="1500ms"/>, Hello, <break time="300ms"/>'.$output;
    $params = [
     'api_key' => $api_key,
     'api_secret' => $api_secret,
     'to' => $to,
     'from' => $from,
     //'machine_detection' => 'true',
     //'machine_timeout' => '5000',
     'text' => $output
    ];
  if($method === "T")
    $url = 'https://rest.nexmo.com/sms/json?' . http_build_query($params);
  else if($method === "V") {
    $url = 'https://api.nexmo.com/tts/json?' . http_build_query($params);
  }
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  return($response);
}

?>
