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

// hit count++
addCount($site_id);

// grab visitor info and inserts a new row with it, associated with the site id

// print image
$image = @imagecreate(1, 1) or die ("Error creating image.");
$transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>