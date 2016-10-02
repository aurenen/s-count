<?php
/************************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: install/index.php
 *
 *  Controls the install directory.
 *  
 ************************************************************************/

require_once '../includes/config.php';
require_once '../includes/connect.php';

$page_title = "Install s-count";

include_once 'header.inc';
?>

    <h2>Install</h2>
    <p>Make sure you've filled out config.php properly.</p>
    <p><a href="create.php" class="bt">Start Installation</a></p>

<?php 
include_once 'footer.inc';
?>