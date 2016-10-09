<?php
/************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: track.php
 *  
 *  Track visitor info, load as an image.
 * 
 ************************************************************/

require_once 'includes/connect.php';
require_once 'includes/functions.php';

$site_id = is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

if ($site_id == 0)
    exit();

// grab visitor info and inserts a new row with it, associated with the site id
if (strlen($_GET['ref']) < 1)
    $link_ref = "None";
else 
    $link_ref = $_GET['ref'];

if (strlen($_GET['page']) < 1)
    $link_hit = "None";
else
    $link_hit = $_GET['page'];
$user_ip = getUserIp();
$user_res = (preg_match('/[0-9]{3,4}x[0-9]{3,4}/i', $_GET['res']) && strlen($_GET['res']) < 10) ? $_GET['res'] : "Unknown";
$browser = getBrowser();

date_default_timezone_set('America/Los_Angeles');
$hit_time = date('Y-m-d H:i:s');
/*
    [name] => Google Chrome
    [version] => 53.0.2785.143
    [platform] => mac
 */
$browser_format = sprintf('%s %.1f (%s)', $browser['name'], $browser['version'], $browser['platform']);
addHitInfo($site_id, $link_ref, $link_hit, $user_ip, $user_res, $browser_format, $hit_time);

// hit count++
addCount($site_id);

// print image
$image = @imagecreate(1, 1) or die ("Error creating image.");
$transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>