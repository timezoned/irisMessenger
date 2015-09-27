<?PHP
/*
Date: 09/15/2015
Name: Pat Coggins

Overview:
This project which we call Iris, My personal Messenger. 
In Greek mythology, Iris, is the personification of the rainbow and messenger of the gods.
Iris is associated with communication, messages, the rainbow, and new endeavors.
This project exemplifies a myriad of things you can do in everyday life using Nexmo's
messaging gateway. When you have completed the installation and configuration of this project, 
you will be able to send a text message to your Nexmo phone number, schedule a time for the 
server to call or Text you back using (V)oice or (T)ext, say what you instructed and repeat
at a given interval if needed.  You will also be able to Delete messages based on last one out.

Prerequisites:
You must have a recent version of PHP installed (mine is 5.5.27) You must have a recent version
of MySQL installed (mine is 5.6.26) You must have a web server installed (mine is Apache/2.4.16)
A MySQL admin interface or Query browser to set up the DB. These are all available in what is 
called a LAMP stack. LAMP - Includes Linux, Apache, MySQL, and PHP/Python/Perl Search google for
lamp stack and the OS that you are running.

Once you have these programs configured and running, download the myNexmoCallback.php file to your
web server's home directory. Also, download and edit the irisMessengerConfig.php file. Put in your
ipaddress, MySQL username and password and Nexmo credentials (if you have them already).

Next download the nexmoMessages_2015-09-27_13.00.18.sql then restore this Database using MySQLWorkBench,
admin tool or from the command line. Lets assume you have a root login on your local host and you downloaded
the nexmoMessages_2015-09-27_13.00.18.sql to your current directory enter the following and hit enter. 
You will be prompted for the password and assuming you have the priveleges do create you should now have
a myDB Database (Schema) and nexmoMessages Table
Now lets test it all: bring up your browser and paste this in the address field: 

http://127.0.0.1/myNexmoCallback.php?msisdn=19991233456&to=1234567890&messageId=000000007C123B2D&text=10+m+V+M+testing+new+number&type=text&keyword=10&message-timestamp=2015-09-24+14%3A48%3A45&doDebug=1
*note that for our test we have appended an additional argument at the end called doDebug. 
doDebug is set to 1 will output debug information to the browser window for a local test. 
If doDebug=2 it will output debug information to the specified log file

If you set doDebug=1 and pasted the URL in to you browser you should see somthing like this:
After running the command above you should see the following in your browser window:
[2015-09-24 15:30 UTC] HTTP request URL:/myNexmoCallback.php?msisdn=19991233456&to=1234567890&messageId=000000007C123B2D&text=10+m+V+M+testing+new+number&type=text&keyword=10&message-timestamp=2015-09-24+14%3A48%3A45&doDebug=1
[2015-09-24 15:30 UTC] get_message_parts:Array ( [0] => 10 [1] => m [2] => V [3] => M [4] => testing [5] => new [6] => number ) 
[2015-09-24 15:30 UTC] interval:10 multiplier:m method:V repeatInterval:M message:testing new number 
[2015-09-24 15:30 UTC] processMessage->get_message_parts: controlArray ( [interval] => 10 [multiplier] => m [method] => V [repeatInterval] => M [message] => testing new number ) 
[2015-09-24 15:30 UTC] processMessage->getRepeatInterval:2015-09-24 14:58:45

You should setup an account at http://www.nexmo.com. After you have setup your
SMS number and credentiuals, add them to this file and   

Playing with our new Messenger

Text Number to Use: 1(202)838-8265

Usage:
send a text to the number listed above with the following fields filled in.
[Delay] [Multiplier] [Method] [Repeat Interval] [Repeat Multiplier] [The message you want sent]

Where: 
[Delay] is the number of minutes/hours to delay before starting
[Multiplier] The time Multiplier (s)econds, (m)inutes, (h)ours, (D)ays, (M)onths
[Method] What method to use for sending the message (V)oice call or (T)ext message
[Repeat Interval] If no repeat desired use 0, otherwise use a number for the Multiplier below.
[Repeat Multiplier] The time Multiplier to use for repeating. (N)one, (s)econds, (m)inutes, (h)ours, (D)ays, (M)onths.
 
Scenario 1
Lets say your name is Jane and you are going on a date at 7PM but, you would like a way to
end the date early if its not going well. You could send a text message to schedule a call or
text before you go. You would send a text message to 12028388265 with the following 
contents:

30 m V 0 N Jane, I am very sorry to disturb you, after hours, but you are needed at the office immediately! 

Lets break down this message:
30 - is the wait interval
m  - is the interval multiplier. (lower case) specifies (m)inutes.
V  - The method which can be (T)ext or (V)oice
0  - The repeat interval (This is just a one time message).
N  - The repeat interval which could be (N)one, (m)inutes, (h)ours, (D)ays, (W)eeks, (M)onths, (Y)ears
Message to speak when it calls us - Jane, I am very sorry to disturb you after hours, but you are needed at the office immediately!

Result: We will get voice call 30 minutes from when we sent this message saying, Jane, 
 I am very sorry to disturb you after hours, but you are needed at the office immediately!


Scenario 2
Your name is Dave and you work on a spaceship. You need a text reminder to take medication. 
It's 6PM and you take your meds at 7AM every day. Since its only 6PM now and we don't want
the daily messages to begin until 7AM tomorrow,  you know that 7AM is 13 hours away so You could send this text message  :

13 h T 1 D Good morning Dave, Please take your morning medication now. Have a nice day!    

Here's our breakdown:
13 - the wait interval
h  - The interval multiplier (h)ours
T  - The method to contact us (T)ext
1  - The repeat interval
D  - The repeat interval multiplier (D)ays
Message to send back to us - Good morning Dave, Please take your morning medication now.


I'm sure you can think up a few more. This is merely a starting point, You can add on a more
complex scheduler and user Database to make this a pay for service. 

Other Commands you can text to the number provided are
?
Help
Help Interval
Help Args

If you would like to stop a repeat or recurring message simply reply to the number with delete. This will delete the last message that was sent so it cannot ever send again.

Description: This is a cross platform web based backend processor example with backend CLI processing.
for getting URL parameters, filtering, validating and storing them in a MySQL DB table, and retrieving,
sending and resending voice and Text messages written in PHP.

This version is written to work with the nexmo messaging gateway.  There are 3 files in
this project 2 php source files and a MySQL backup file. This file myNexmoCallback.php 
is the web server callback processesor,  and should be installed in your web server document 
directory. Next, messageSender.php is a php (C)ommand (L)ine (I)nterface application that can
be installed anywhere. 


Variable:	Description:
//From Nexmo
//    [msisdn] => 19991233456
//    [to] => 1234567890
//    [messageId] => 000000007C123B2D
//    [text] => Testing new number
//    [type] => text
//    [message-timestamp] => 2014-06-27 17:19:09

*/

include_once('/volumes/hd2/smsProject1/irisMessenger/irisMessengerConfig.php');

/* Set our directory and log name */
define("LOG_FILE", "/tmp/myNexmoCallback.log");
define ("WEB_EOL", "<br/>");


//Filter URL Parameters - You can change these filters to what ever works for you
$msisdn      =filter_input(INPUT_GET, 'msisdn', FILTER_SANITIZE_SPECIAL_CHARS);
$to          =filter_input(INPUT_GET, 'to', FILTER_SANITIZE_SPECIAL_CHARS);
$messageId   =filter_input(INPUT_GET, 'messageId', FILTER_SANITIZE_SPECIAL_CHARS);
$text        =filter_input(INPUT_GET, 'text', FILTER_SANITIZE_STRING);
$type        =filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
$timeStamp   =filter_input(INPUT_GET, 'message-timestamp', FILTER_SANITIZE_SPECIAL_CHARS);
$doDebug     =filter_input(INPUT_GET, 'doDebug', FILTER_VALIDATE_INT);

if(! isset($doDebug))
  $doDebug = 2;

if($doDebug == 1) { //echo debug data to web page
  echo(date('[Y-m-d H:i e] '). "HTTP request URL:". $_SERVER['REQUEST_URI']. WEB_EOL);
}
else if($doDebug == 2) { //writes debug info to log file
  error_log(date('[Y-m-d H:i e] '). "HTTP request URL:". $_SERVER['REQUEST_URI'] . PHP_EOL, 3, LOG_FILE);
}

if(strlen($messageId) > 0 && strlen($text) < 200) {
  //Instantiate our class and pass along the vars from the included config

  $processMessage = new ProcessMessage($doDebug, $hostName, $dbHost, $dbPort, $dbUser, $dbPass, $dbName, $dbMessageTable );

  //Parse the incoming message in to working fileds for our control array
  $controlArray = $processMessage->get_message_parts($text, $doDebug);   
  if($doDebug == 1) {
    echo(date('[Y-m-d H:i e] '). "processMessage->get_message_parts: control". print_r($controlArray, true) . WEB_EOL);
  }
  else if($doDebug == 2) {
    error_log(date('[Y-m-d H:i e] '). "processMessage->get_message_parts: control". print_r($controlArray, true) . PHP_EOL, 3, LOG_FILE);
  }   
  if($controlArray['Delete'] === 1)
    $result = $processMessage->deleteRecord($dbMessageTable, $msisdn);
  else {
    if($controlArray['needHelp'] === 0)
      $sendTime = $processMessage->getRepeatInterval($timeStamp, $controlArray['interval'], $controlArray['multiplier'], $controlArray['repeatInterval'],  $controlArray['repeatMultiplier']);
    else {
      $sendTime = date('Y-m-d H:i:s');
    }
    if($doDebug == 1) {
      echo(date('[Y-m-d H:i e] '). "processMessage->getRepeatInterval:". $sendTime . WEB_EOL);
    }
    else if($doDebug == 2) {
      error_log(date('[Y-m-d H:i e] '). "processMessage->getRepeatInterval:". $sendTime . PHP_EOL, 3, LOG_FILE);
    }   
  
    //Stuff our vars in to an array and pass to insertRecord
    $insertRecord = $processMessage->insertRecord($dbMessageTable,
	    		array(
	    			"msisdn" 	       => $msisdn,
	    			"to" 	           => $to,
	    			"messageId"        => $messageId,
	    			"text" 	           => $text,
	    			"type" 	           => $type,
	    			"timeStamp"        => $timeStamp,
	    			"output"           => $controlArray['message'],
	    			"scheduled"        => $sendTime,
	    			"method"           => $controlArray['method'],
	    			"repeatInterval"   => $controlArray['repeatInterval'],
	    			"repeatMultiplier" => $controlArray['repeatMultiplier'],
	    			"needHelp"         => $controlArray['needHelp']
	    		)
	    	);

  }
  http_response_code(200);
  exit;

} 

  class ProcessMessage {
	private $dbh            = '';

	public function __construct($doDebug, $hostName, $dbHost, $dbPort, $dbUser, $dbPass, $dbName, $dbMessageTable){
      try {
	    $this->dbh	= new PDO("mysql:dbname={$dbName};host={$dbHost};port={$dbPort}", $dbUser, $dbPass);
      } 
      catch (PDOException $e) {
        /* Our Connection failed, log it */
        if($doDebug == 1) {
          echo(date('[Y-m-d H:i e] '). "PDO Connection failed:". $e->getMessage() . WEB_EOL);
        }
        else if($doDebug == 2) {
          error_log(date('[Y-m-d H:i e] '). "PDO Connection failed:". $e->getMessage() . PHP_EOL, 3, LOG_FILE);
        }
      }
    }

	/* insert the records in our DB */
	public function insertRecord($dbMessageTable, $toAdd = array()){
		if( is_array($toAdd)){
			$columns = "";
			foreach($toAdd as $i => $s){
				$columns .= "`$i` = :$i, ";
			}
			 // Remove last ","
			$columns = substr($columns, 0, -2);
			$sql   = $this->dbh->prepare("INSERT INTO `{$dbMessageTable}` SET {$columns}");
			foreach($toAdd as $key => $value){
					$value = htmlspecialchars_decode($value, ENT_QUOTES);
					$sql->bindValue(":$key", $value);
			}
			$sql->execute();
			
		}else{
			return false;
		}
	}
	
	public function deleteRecord($dbMessageTable, $msisdn){
        if(strlen($msisdn) >= 10) {
			$sql   = $this->dbh->prepare("DELETE FROM `{$dbMessageTable}` WHERE `msisdn`=:msisdn AND repeatMultiplier != 'N' ORDER BY scheduled DESC LIMIT 1");
			$sql->bindValue(":msisdn", $msisdn);
			$sql->execute();
		}else{
			return false;
		}
	}
	
	
    /* create array using space as delimiter then parse for commands*/
    public function get_message_parts($string, $doDebug) {
      $stringArray = explode(" ", $string);
      $interval = 0; $multiplier = ''; $method = 'T'; $repeatInterval = '0'; $repeatMultiplier = "N"; $message = ""; $needHelp = 0; $delete = 0;
      if(count($stringArray) > 4) { // make sure we have a message with at least  interval, multiplier, method, repeatInterval and repeatMultiplier
        if(is_numeric($stringArray[0]) && strlen($stringArray[1]) === 1) { //Got number so it should be the callback time in seconds
          $interval  = $stringArray[0];
          $multiplier = $stringArray[1];
          $method = strtoupper($stringArray[2]); // (T)ext or (V)oice
          if(is_numeric($stringArray[3]))
            $repeatInterval = $stringArray[3]; // (s)econd, (m)inute, (h)our, (d)ay, (M)onth (N)one
          $repeatMultiplier = $stringArray[4]; // (s)econd, (m)inute, (h)our, (d)ay, (M)onth (N)one
          if($repeatMultiplier == 'n')
            $repeatMultiplier = 'N';
          // 10 m T N
          for($r=5;$r<count($stringArray);$r++)
            $message .= $stringArray[$r]." ";
            
          if($doDebug == 1) { //echo debug data to web page
            echo(date('[Y-m-d H:i e] '). "get_message_parts1:". print_r($stringArray, true). WEB_EOL);
            echo(date('[Y-m-d H:i e] '). "interval:". $interval . " multiplier:" . $multiplier . " method:" . $method .
                 " repeatInterval:" . $repeatInterval . " repeatMultiplier:" . $repeatMultiplier ." message:" . $message. WEB_EOL);
          }
          else if($doDebug == 2) { //writes debug info to log file
            error_log(date('[Y-m-d H:i e] '). "get_message_parts1:". print_r($stringArray, true) . PHP_EOL, 3, LOG_FILE);
            error_log(date('[Y-m-d H:i e] '). "interval:". $interval . " multiplier:" . $multiplier .
                      " method:" . $method . " repeatInterval:" . $repeatInterval . " repeatMultiplier:" . $repeatMultiplier ." message:" . $message . PHP_EOL, 3, LOG_FILE);
          }
        }
        else { //Incorrect input
          if($doDebug == 1) { //echo debug data to web page
            echo(date('[Y-m-d H:i e] '). "get_message_parts2:". print_r($stringArray, true). WEB_EOL);
          }
          else if($doDebug == 2) { //writes debug info to log file
            error_log(date('[Y-m-d H:i e] '). "get_message_parts:2". print_r($stringArray, true) . PHP_EOL, 3, LOG_FILE);
          }
          $needHelp = 1;
          $message = "Format: {wait interval} {interval Multiplier} {method} {repeat Interval} {repeat Interval Multiplier} {message} ".
                     "\r\neg:5 m T 0 N this is a test";

        }
      }
      else if(count($stringArray) === 1 && strtoupper($stringArray[0]) === 'DELETE') { //Delete the last message sent to this device
        $delete = 1;
      }       
      else if(strtoupper($string) === 'HELP' || $string === '?') { 
        if($doDebug == 1) { //echo debug data to web page
          echo(date('[Y-m-d H:i e] '). "get_message_parts3:". print_r($stringArray, true). WEB_EOL);
        }
        else if($doDebug == 2) { //writes debug info to log file
          error_log(date('[Y-m-d H:i e] '). "get_message_parts3:". print_r($stringArray, true) . PHP_EOL, 3, LOG_FILE);
        }
        $needHelp = 1;
        $message = "Iris messenger, allows you to schedule one time or repeat Text and Voice messages. For more Help send:".
                   " Help Format, Help Args, Help Intervals, Help Multipliers or Help Delete";
          
      }
      else if(strtoupper($string) === 'HELP FORMAT') { 
        if($doDebug == 1) { //echo debug data to web page
          echo(date('[Y-m-d H:i e] '). "get_message_parts4:". print_r($stringArray, true). WEB_EOL);
        }
        else if($doDebug == 2) { //writes debug info to log file
          error_log(date('[Y-m-d H:i e] '). "get_message_parts4:". print_r($stringArray, true) . PHP_EOL, 3, LOG_FILE);
        }
        $needHelp = 1;
        $message = "Format: {wait interval} {interval Multiplier} {method} {repeat Interval} {repeat Interval Multiplier [can be 0 and N]} {message} ".
                   "\r\neg:5 m T 0 N this is a test";
          
      }
      else if(strtoupper($string) === 'HELP ARGS') { 
        if($doDebug == 1) { //echo debug data to web page
          echo(date('[Y-m-d H:i e] '). "get_message_parts5:". print_r($stringArray, true). WEB_EOL);
        }
        else if($doDebug == 2) { //writes debug info to log file
          error_log(date('[Y-m-d H:i e] '). "get_message_parts5:". print_r($stringArray, true) . PHP_EOL, 3, LOG_FILE);
        }
        $needHelp = 1;
        $message = "waitInterval, intervalMultiplier: wait before send.\r\nrepeatInterval, repeatMultiplier: scheduled repeat. Multipliers are: [(m)inutes (h)ours (D)ays (M)onths (N)one]";
      }
      else if(strtoupper($string) === 'HELP INTERVALS' || strtoupper($string) === 'HELP INTERVAL') { 
        if($doDebug == 1) { //echo debug data to web page
          echo(date('[Y-m-d H:i e] '). "get_message_parts:6". print_r($stringArray, true). WEB_EOL);
        }
        else if($doDebug == 2) { //writes debug info to log file
          error_log(date('[Y-m-d H:i e] '). "get_message_parts6:". print_r($stringArray, true) . PHP_EOL, 3, LOG_FILE);
        }
        $needHelp = 1;
        $message = "The first Interval and multiplier are how long to wait before sending the first message. the second set is for scheduled repeats or 0 N for one time.";
      }

      else if(strtoupper($string) === 'HELP MULTIPLIERS' || strtoupper($string) === 'HELP MULTIPLIER') { 
        if($doDebug == 1) { //echo debug data to web page
          echo(date('[Y-m-d H:i e] '). "get_message_parts7:". print_r($stringArray, true). WEB_EOL);
        }
        else if($doDebug == 2) { //writes debug info to log file
          error_log(date('[Y-m-d H:i e] '). "get_message_parts7:". print_r($stringArray, true) . PHP_EOL, 3, LOG_FILE);
        }
        $needHelp = 1;
        $message = "Multipliers are: [(m)inutes (h)ours (D)ays (W)eeks (M)onths]. Note that case is important!";
      }
      else if(strtoupper($string) === 'HELP DELETE') { 
        if($doDebug == 1) { //echo debug data to web page
          echo(date('[Y-m-d H:i e] '). "get_message_parts8:". print_r($stringArray, true). WEB_EOL);
        }
        else if($doDebug == 2) { //writes debug info to log file
          error_log(date('[Y-m-d H:i e] '). "get_message_parts8:". print_r($stringArray, true) . PHP_EOL, 3, LOG_FILE);
        }
        $needHelp = 1;
        $message = "To delete a repeating message, you must wait until it sends, then reply with Delete. That will delete the last message to your number.";
      }
      else { //Incorrect input
        if($doDebug == 1) { //echo debug data to web page
          echo(date('[Y-m-d H:i e] '). "get_message_parts9:". print_r($stringArray, true). WEB_EOL);
        }
        else if($doDebug == 2) { //writes debug info to log file
          error_log(date('[Y-m-d H:i e] '). "get_message_parts9:". print_r($stringArray, true) . PHP_EOL, 3, LOG_FILE);
        }
        $needHelp = 1;
        $message = "Format: {wait interval} {interval Multiplier} {method} {repeat Interval} {repeat Interval Multiplier} {message} ".
                   "\r\neg:5 m T 0 N this is a test";

      }

      $message = trim($message);
      return  array("interval" => $interval, "multiplier" => $multiplier, 
                    "method" => $method, "repeatInterval" => $repeatInterval,
                    "repeatMultiplier" => $repeatMultiplier, "message" => $message,
                    "needHelp" => $needHelp, "Delete" => $delete);
    }

    public function getRepeatInterval($timeStamp, $interval, $multiplier,  $repeatInterval, $repeatMultiplier) {
      $date = new DateTime($timeStamp);
      if(ctype_lower($multiplier) ) { //Must be a time with hour, minute or second
        $myDateInterval = "PT".$interval.(strtoupper($multiplier));
      }
      else if(!ctype_lower($multiplier) ) { //Must be Day, Week, Month, Year
        $myDateInterval = "P".$interval.(strtoupper($multiplier));
      }
      //echo 'timeStamp:'.$timeStamp.' myDateInterval:'.$myDateInterval."<br/>"; 
      // DateInterval Always starts with (P)eriod ( (T)ime if needed (H)ours (M)inute (S)econds) number (D)ay (W)eek, (M)onth (Y)ear
      $date->add(new DateInterval($myDateInterval));
      return ($date->format('Y-m-d H:i:s')); // 2015-09-23 21:27:44
    }
    
}
