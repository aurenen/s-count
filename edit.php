<?php 
/************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: edit.php
 *  
 *  Takes care of editing project site info.
 * 
 ************************************************************/

require_once 'includes/connect.php';
require_once 'includes/functions.php';

$page_title = "Edit Project";

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
} 


require_once 'includes/admin_header.inc'; 
?>

        <h2>Edit Project</h2>

        <form action="add.php" method="post">
            <div class="form-row">
              <label>Site Name</label>
              <input name="site_name" type="text" placeholder="" />
            </div>

            <div class="form-row">
                <label>Site URL</label>
                <input name="site_url" type="text" placeholder="Include http://">
            </div>

            <div class="form-row">
                <label>Start Count</label>
                <input name="count" type="text" value="0">
            </div>

            <div class="form-row">
              <input class="bt"  name="submit" type="submit" value="Edit">
            </div>
        </form>

<?php 
require_once 'includes/admin_footer.inc'; 
?>