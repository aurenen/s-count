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
 * Check if page is current url for navigation
 * http://blog.aurenen.org/2015/11/php-dynamic-navigationpage-title/
 * 
 * @param  string $title : filename without extension
 * @return boolean 
 */
function is_current($title) {
    $url = $_SERVER['REQUEST_URI'];

    if (strlen($_SERVER['QUERY_STRING']) > 0) 
        $page = (strpos($url, "=") > 0) ? (substr($url, strpos($url, "=") + 1)) : (substr($url, strpos($url, "?") + 1));
    
    else {
        preg_match('~\/(.*?)\.php~', $url, $output);
        $page = $output[1];
        while (strpos($page, "/") !== false)
            $page = substr($page, strpos($page, "/") + 1);
    }

    if ($page == null || strlen($page) == 0) {
        $page = "index";
    }

    if ($page === $title) {
        echo " active";
    }
}

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
        (SELECT `set_value` FROM `" . DB_PREFIX . "settings` WHERE `set_key` = 'username') u,
        (SELECT `set_value` FROM `" . DB_PREFIX . "settings` WHERE `set_key` = 'password') p";

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

function getCount($id) {
    if (!isset($db))
        $db = db_connect();

    // select hit count for this site
    $stmt = $db->prepare("SELECT `count` FROM `" . DB_PREFIX . "projects` WHERE `site_id` = :id;");
    try {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'];
    }
    catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

function addCount($id) {
    $db = db_connect();

    $count = intval(getCount($id)) + 1;

    // update hit count with $count
    $stmt = $db->prepare("UPDATE `" . DB_PREFIX . "projects` SET `count` = :count WHERE `site_id` = :id;");
    try {
        $stmt->bindParam(':count', $count, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $db = null;
        return $count;
    }
    catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

// http://php.net/manual/en/function.get-browser.php#101125
function getBrowser() { 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

// http://stackoverflow.com/a/13646735
function getUserIp()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP))
        $ip = $client;
    else if (filter_var($forward, FILTER_VALIDATE_IP))
        $ip = $forward;
    else
        $ip = $remote;

    return $ip;
}

function addHitInfo($id, $referrer, $page_hit, $ip_address, $browser_info, $time) {
    $db = db_connect();

    $query = "INSERT INTO `" . DB_PREFIX . "hits` (`site_id`, `referrer`, `page`, `ip_address`, `browser`, `time`)
        VALUES (:site_id, :referrer, :page, :ip_address, :browser, :time);";
    $stmt = $db->prepare($query);
    try {
        $stmt->bindParam(':site_id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':referrer', $referrer, PDO::PARAM_STR, 200);
        $stmt->bindParam(':page', $page_hit, PDO::PARAM_STR, 50);
        $stmt->bindParam(':ip_address', $ip_address, PDO::PARAM_STR, 20);
        $stmt->bindParam(':browser', $browser_info, PDO::PARAM_STR, 30);
        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
        $stmt->execute();
    }
    catch (Exception $ex) {
        echo $ex->getMessage();
    }

    $db = null;
}

function getSettings() {
    $db = db_connect();
    $query = "SELECT `set_key`, `set_value` FROM `" . DB_PREFIX . "settings`";
    $stmt = $db->prepare($query);
    try {
        $stmt->execute();
        $result = $stmt->fetchAll();
    }
    catch (Exception $ex) {
        echo 'ERROR: failed to get entry for edit. ' . $ex->getMessage();
        $result = null;
    }
    
    $db = null;
    return $result;
}

function addSite($name, $url, $count) {
    $db = db_connect();
    $query = "INSERT INTO `" . DB_PREFIX . "projects` (`site_name`, `site_url`, `count`)
        VALUES (:name, :url, :count);";
    $stmt = $db->prepare($query);
    try {
        $stmt->bindParam(':name', $name, PDO::PARAM_STR, 50);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR, 50);
        $stmt->bindParam(':count', $count, PDO::PARAM_INT);

        $stmt->execute();

        $status = '<div class="success">Successfully added.</div>';
    }
    catch (Exception $ex) {
        $status = '<div class="warning">ERROR: failed to insert settings. ' . 
                    $ex->getMessage() . '</div>';
    }
    return $status;
    $db = null;
}

function getSitesDash() {
    $db = db_connect();
    $query = "SELECT `site_id`, `site_name`, `site_url`, `count` FROM `" . DB_PREFIX . "projects`";
    $stmt = $db->prepare($query);
    try {
        $stmt->execute();
        $result = $stmt->fetchAll();
    }
    catch (Exception $ex) {
        echo 'ERROR: failed to get sites. ' . $ex->getMessage();
        $result = null;
    }
    
    $db = null;
    return $result;
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