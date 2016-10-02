<?php
/************************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: install/create.php
 *
 *  Creates tables and inserts initial settings.
 *   
 ************************************************************************/

require_once '../includes/config.php';
require_once '../includes/connect.php';
require_once '../includes/functions.php';
require_once '../includes/PasswordHash.php';

$page_title = "Install s-count: create tables";
$action = $_GET['action'];
$tables = false;
$full_install_path = str_ireplace('install', '', realpath(__DIR__));
$full_install_url = str_ireplace('install/create.php?action=create', '', curPageURL());
$db = db_connect();

$create_settings = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "settings` (
    `set_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `set_key` VARCHAR(50) NOT NULL,
    `set_value` VARCHAR(255) NOT NULL,
    `description` VARCHAR(200) NOT NULL
) ENGINE=MyISAM;";
$create_sites = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "sites` (
    `site_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `site_name` VARCHAR(50) NOT NULL,
    `site_url` VARCHAR(50) NOT NULL,
    `count` INT(10) UNSIGNED NOT NULL
) ENGINE=MyISAM;";
$create_hits = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "hits` (
    `hit_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `site_id` INT(10) UNSIGNED NOT NULL,
    `referrer` VARCHAR(200) NOT NULL,
    `page` VARCHAR(50) NOT NULL,
    `ip_address` VARCHAR(20) NOT NULL,
    `browser` VARCHAR(30) NOT NULL,
    `time` TIMESTAMP NOT NULL
) ENGINE=MyISAM;";

if ($action == 'create') {
    try {
        $db->query($create_settings);
        $db->query($create_sites);
        $db->query($create_hits);

        $tables = true;
    }
    catch (Exception $ex) {
        $tb_msg = '<div class="warning">There was an error running the query
                   with the exception:' . $ex->getMessage() . '</div>';
        $tables = false;
    }
} // if $action == 'create'
if (isset($_POST['submit'])) {
    // check if installer has already been used
    $exist = $db->query('SELECT COUNT(*) FROM `' . DB_PREFIX . 'settings`')->fetchColumn();

    if (intval($exist) > 0) {
        header('Location: success.php?error=exists');
        exit();
    }

    // hash password before inserting into db
    $hasher = new PasswordHash(8, FALSE);
    $hash = $hasher->HashPassword( $_POST['pass'] );
    if (strlen($hash) < 20)
        fail('Failed to hash new password');
    unset($hasher);

    $stmt = $db->prepare("INSERT INTO `" . DB_PREFIX . "settings` (`set_key`, `set_value`, `description`) VALUES 
        ('username', :username, 'Your login username.'),
        ('password', :password, 'Your login password.'),
        ('email', :email, 'Your email.'),
        ('full_path', :full_path, 'The full path in your server where this is installed.'),
        ('full_url', :full_url, 'The full url where this is located.');");

    $done = true;
    try {
        // process post request, insert info, redirect to success page.
        $stmt->bindParam(':username', $_POST['user'], PDO::PARAM_STR, 25);
        $stmt->bindParam(':password', $hash, PDO::PARAM_STR, 250);
        $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 100);
        $stmt->bindParam(':full_path', $_POST['full_path'], PDO::PARAM_STR, 250);
        $stmt->bindParam(':full_url', $_POST['full_url'], PDO::PARAM_STR, 250);

        $stmt->execute();
    }
    catch (Exception $ex) {
        $tb_msg = '<div class="warning">ERROR: failed to insert settings. ' . 
                    $ex->getMessage() . '</div>';
        $done = false;
    }
    if ($done) {
        header('Location: success.php');
        exit();
    }
} // if post submit

$db = null;

include_once 'header.inc';
?>

    <h2>Create tables</h2>
    <p>
        This will create the tables <code><?php echo DB_PREFIX; ?>settings</code>, 
        <code><?php echo DB_PREFIX; ?>sites</code>, and <code><?php echo DB_PREFIX; ?>hits</code>.
    </p>
    <p>
        If successful, you'll then be prompted to enter inital settings.
    </p>

    <?php if ($action == 'create' && $tables == true): ?>

        <hr>
        <h2>Successfully created tables</h2>
        
        <form action="create.php" method="post">
            <div class="form-row">
              <label>Username</label>
              <input name="user" type="text" value="" />
            </div>
            
            <div class="form-row">
              <label>Password</label>
              <input name="pass" type="password" value="" />
            </div>

            <div class="form-row">
              <label>Email</label>
              <input name="email" type="text" value="" />
            </div>

            <div class="form-row">
                <label>Install full path</label>
                <input name="full_path" type="text" value="<?php echo $full_install_path; ?>" placeholder="Full server path">
            </div>

            <div class="form-row">
                <label>Full URL</label>
                <input name="full_url" type="text" value="<?php echo $full_install_url; ?>" placeholder="Full URL">
            </div>

            <div class="form-row">
              <input class="bt"  name="submit" type="submit" value="Finish">
            </div>
        </form>

    <?php else: ?>

        <?php echo $tb_msg; ?>
        <p><a href="create.php?action=create" class="bt">Create tables</a></p>

    <?php endif; ?>


<?php 
include_once 'footer.inc';
?>