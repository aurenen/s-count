<?php
/************************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: install/success.php
 *
 *  Confirms successful installation.
 *  
 ************************************************************************/

require_once '../includes/config.php';
require_once '../includes/connect.php';

$page_title = "Success!";
$error = $_GET['error'];

include_once 'header.inc';
?>

    <?php if ($error == "exists"): ?>
    <h2>Error</h2>
    <p>Already installed. Please login instead.</p>
    <?php else: ?>
    <h2>Success</h2>
    <p>Installed correctly. To be safe, remove the <code>/install/</code> directory.</p>
    <?php endif; ?>
    <p><a href="../login.php" class="bt">Login to admin panel</a></p>

<?php 
include_once 'footer.inc';
?>