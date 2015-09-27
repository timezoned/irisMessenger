# irisMessenger
## Overview:
This project which we call Iris, My personal Messenger. 
In Greek mythology, Iris, is the personification of the rainbow and messenger of the gods.
Iris is associated with communication, messages, the rainbow, and new endeavors.
This project exemplifies a myriad of things you can do in everyday life using Nexmo's
messaging gateway. When you have completed the installation and configuration of this project, you will be able to send a text message to your Nexmo phone number, schedule a time for the server to call or Text you back using (V)oice or (T)ext, say what you instructed and repeat at a given interval if needed.  You will also be able to Delete messages based on last one out.

## Description: 
This is a cross platform web based backend processing example with backend CLI processing.
for getting URL parameters, filtering, validating and storing them in a MySQL DB table, and retrieving, sending and resending voice and Text messages written in PHP.

This version is written to work with the nexmo messaging gateway.  There are 4 files in
this project 2 php source files and a MySQL backup file. This file myNexmoCallback.php 
is the web server callback processesor,  and should be installed in your web server document 
directory. Next,  irisMessengerConfig.php which is simply the repository for credentials and variables that both applications use  and messageSender.php is a php (C)ommand (L)ine (I)nterface application that can be installed anywhere. 

## Prerequisites:
You must have a recent version of PHP installed (mine is 5.5.27) You must have a 
recent version of MySQL installed (mine is 5.6.26) You must have a web server installed (mine is Apache/2.4.16) A MySQL admin interface or Query browser to set up the DB. These are all available in what is called a LAMP stack. LAMP - Includes Linux, Apache, MySQL, and PHP/Python/Perl Search google forlamp stack and the OS that you are running.

Once you have these programs configured and running, download the myNexmoCallback.php file to your web server's home directory. Also, download and edit the irisMessengerConfig.php file. Put in your IP Address, MySQL username and password and Nexmo credentials (if you have them already).

Next download the nexmoMessages_2015-09-27_13.00.18.sql then restore this Database using MySQLWorkBench, admin tool or from the command line. Lets assume you have a root login on your local host and you downloaded the nexmoMessages_2015-09-27_13.00.18.sql to your current directory enter the following and hit enter.  You will be prompted for the password and assuming you have the priveleges do create you should now have a myDB Database (Schema) and nexmoMessages Table

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

Text Number to Use: Nexmo Number from your account

## Usage:
send a text to the number listed above with the following fields filled in.
[Delay] [Multiplier] [Method] [Repeat Interval] [Repeat Multiplier] [The message you want sent]

## Where: 
[Delay] is the number of minutes/hours to delay before starting
[Multiplier] The time Multiplier (s)econds, (m)inutes, (h)ours, (D)ays, (M)onths
[Method] What method to use for sending the message (V)oice call or (T)ext message
[Repeat Interval] If no repeat desired use 0, otherwise use a number for the Multiplier below.
[Repeat Multiplier] The time Multiplier to use for repeating. (N)one, (s)econds, (m)inutes, (h)ours, (D)ays, (M)onths.
 
## Scenario 1
Lets say your name is Jane and you are going on a date at 7PM but, you would like a way to
end the date early if its not going well. You could send a text message to schedule a call or
text before you go. You would send a text message to 12028388265 with the following 
contents:

### 30 m V 0 N Jane, I am very sorry to disturb you, after hours, but you are needed at the office immediately! 

### Lets break down this message:
* 30 - is the wait interval
* m  - is the interval multiplier. (lower case) specifies (m)inutes.
* V  - The method which can be (T)ext or (V)oice
* 0  - The repeat interval (This is just a one time message).
* N  - The repeat interval which could be (N)one, (m)inutes, (h)ours, (D)ays, (W)eeks, (M)onths, (Y)ears
* Message to speak when it calls us - Jane, I am very sorry to disturb you after hours, but you are needed at the office immediately!

### Result: We will get voice call 30 minutes from when we sent this message saying, Jane, 
 I am very sorry to disturb you after hours, but you are needed at the office immediately!




## Scenario 2
Your name is Dave and you work on a spaceship. You need a text reminder to take medication. 
It's 6PM and you take your meds at 7AM every day. Since its only 6PM now and we don't want
the daily messages to begin until 7AM tomorrow,  you know that 7AM is 13 hours away so You could send this text message  :

### 13 h T 1 D Good morning Dave, Please take your morning medication now. Have a nice day!    

### Here's our breakdown:
* **13** - the wait interval
* **h**  - The interval multiplier (h)ours
* **T**  - The method to contact us (T)ext
* **1**  - The repeat interval
* **D**  - The repeat interval multiplier (D)ays
* Message** to send back to us - Good morning Dave, Please take your morning medication now.


I'm sure you can think up a few more. This is merely a starting point, You can add on a more
complex scheduler and user Database to make this a pay for service. 

### Other Commands you can text to the number provided are
?
Help
Help Interval
Help Args

If you would like to stop a repeat or recurring message simply reply to the number with delete. This will delete the last message that was sent so it cannot ever send again.

