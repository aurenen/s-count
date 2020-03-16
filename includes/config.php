<?php
/************************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  file: includes/config.php
 *
 *  modify the values below with your database information
 *  keep the values within single quotes
 *
 *  the hostname is usually 'localhost' only change if your host has a 
 *  different setting
 *  
 ************************************************************************/

$db_user = '';

$db_pass = '';

$db_name = '';

$db_host = 'localhost';

// keep this short, no more than 5 characters, end with a underscore (_)
$db_prefix = 'cnt_'; 

/************************************************************************
    DO NOT EDIT BELOW THIS UNLESS YOU KNOW WHAT YOU ARE DOING.
 ************************************************************************/

define('DB_USER', $db_user);
define('DB_PASS', $db_pass);
define('DB_NAME', $db_name);
define('DB_HOST', $db_host);
define('DB_PREFIX', $db_prefix);

date_default_timezone_set('America/Los_Angeles');

// **PREVENTING SESSION HIJACKING**
// Prevents javascript XSS attacks aimed to steal the session ID
ini_set('session.cookie_httponly', 1);

// Adds entropy into the randomization of the session ID, as PHP's random number
// generator has some known flaws
ini_set('session.entropy_file', '/dev/urandom');

// Uses a strong hash
ini_set('session.hash_function', 'whirlpool');