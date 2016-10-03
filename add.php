<?php 
/************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: settings.php
 *  
 *  Dashboard for admin panel.
 * 
 ************************************************************/

require_once 'includes/connect.php';
require_once 'includes/functions.php';

$page_title = "Add Site";

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
} 

if (isset($_POST['submit'])) {
    if (strlen($_POST['site_name']) > 50 || strlen($_POST['site_url']) > 50 || !is_numeric($_POST['count'])) {
        $add_msg = '<div class="warning">ERROR: site name and url must be less than 50 characters, and count must be numeric.</div>';
    }
    else {
        // add site
        $add_msg = addSite($_POST['site_name'], $_POST['site_url'], $_POST['count']);
    }
}

require_once 'includes/admin_header.inc'; 
?>

        <h2>Add Site</h2>

        <?php echo $add_msg; ?>

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
              <input class="bt"  name="submit" type="submit" value="Add">
            </div>
        </form>


<?php 
require_once 'includes/admin_footer.inc'; 
?>