<?php
/************************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: includes/functions.php
 *
 *  General feature functions.
 *  
 ************************************************************************/


/**
 * Checks parameters with database values to verify if login matches
 * @param  string $user
 * @param  string $pass 
 * @return booloan 
 */
function verifyUser($user, $pass) {
    $db = db_connect();
    $verify = false;

    $sql = "SELECT u.`set_value` AS user, p.`set_value` AS pass FROM  
        (SELECT `set_value` FROM `settings` WHERE `set_key` = 'username') u,
        (SELECT `set_value` FROM `settings` WHERE `set_key` = 'password') p";

    $stmt = $db->prepare($sql);

    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // hash password before checking with db
        $hasher = new PasswordHash(8, FALSE);
        // if match, result[user] == 'username' and result[pass] == 'password'
        if (strcmp($user, $result['user']) === 0 && $hasher->CheckPassword($pass, $result['pass'])) {
            $verify = true;
        }
        unset($hasher);
    }
    catch (Exception $ex) {
        echo $ex->getMessage();
        $verify = false;
    }

    $db = null;
    return $verify;
}

// http://webcheatsheet.com/php/get_current_page_url.php
function curPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}