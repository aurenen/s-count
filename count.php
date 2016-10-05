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
}
// otherwise assume loaded through PHP includes, and variables set
// if not set
if (!isset($site_id)) {
    echo "<strong>Failed to load.</strong>";
    exit();
}

$hits = getCount($site_id);

// print count
echo number_format($hits); // comma separated (i.e. 1,000)
?>