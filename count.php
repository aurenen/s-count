<?php
/************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: count.php
 *  
 *  Counts page hits, prints if visible.
 * 
 ************************************************************/

require_once 'includes/connect.php';
require_once 'includes/functions.php';

// if loaded through javascript GET parameters
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $site_id = intval($_GET['id']);
    if (isset($_GET['v']) && $_GET['v'] == 0)
        $visible = false;
    else
        $visible = true;
}
// otherwise assume loaded through PHP includes, and variables set
// defaults to visible
if (!isset($visible) || $visible != false)
    $visible = true;

// connect to db
$db = db_connect();
// select hit count for this site
$stmt = $db->prepare("SELECT `count` FROM `" . DB_PREFIX . "projects` WHERE `site_id` = :id;");
$stmt->bindParam(':id', $site_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$count = intval($result['count']) + 1;

// update hit count with $count
$stmt = $db->prepare("UPDATE `" . DB_PREFIX . "projects` SET `count` = :count WHERE `site_id` = :id;");
$stmt->bindParam(':count', $count, PDO::PARAM_INT);
$stmt->bindParam(':id', $site_id, PDO::PARAM_INT);
$stmt->execute();

$db = null;
// print count
if ($visible)
    echo number_format($count); // comma separated (i.e. 1,000)
?>