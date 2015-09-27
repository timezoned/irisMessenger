<?PHP
/*
Pat Coggins 9/26/2015
This is our Configuration file for services and DBs
These variable apply to IrisMessenger.php and 
myNexmoCallback.php

*/
//Set our default time zone.
date_default_timezone_set(timezone_name_from_abbr('UTC'));

$hostName      = "127.0.0.1";   // Your IP Address or localhost
$sleepOffset   = 30;            //This is how often irisMessengerCLI.php runs
$myService     = 'nexmo';       //A variable in case you want to add additional gateways
$api_key       = '';            //Nexmo assigned api_key
$api_secret    = '';            //Nexmo assigned api_secret
$myNexmoNumber = '';            //Number you got for your nexmo account

/*db Stuff */
$dbHost         = "127.0.0.1";    // DB Host IP Address or localhost
$dbPort         = "3306";         // DB Host Port number (default 3600)
$dbUser         = "";             //Username for you MySQL DB
$dbPass         = "";             //Password for you MySQL USER
$dbName         = "myDB";         //MySQL DB
$dbMessageTable = "nexmoMessages" // Our table name
?>
