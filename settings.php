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

$page_title = "Settings";

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
} 

$setting = getSettings();

foreach ($setting as $s) {
    if ($s['set_key'] == "email")
        $email = $s['set_value'];
    if ($s['set_key'] == "full_path")
        $full_path = $s['set_value'];
    if ($s['set_key'] == "full_url")
        $full_url = $s['set_value'];
}

if (isset($_POST['submit'])) {
    // update settings
}

require_once 'includes/admin_header.inc'; 
?>

        <h2>Settings</h2>

        <form action="sesttings.php" method="post">
            <div class="form-row">
              <label>Email</label>
              <input name="email" type="text" value="<?php echo $email; ?>" />
            </div>

            <div class="form-row">
                <label>Install full path</label>
                <input name="full_path" type="text" value="<?php echo $full_path; ?>" placeholder="Full server path">
            </div>

            <div class="form-row">
                <label>Full URL</label>
                <input name="full_url" type="text" value="<?php echo $full_url; ?>" placeholder="Full URL">
            </div>

            <div class="form-row">
              <input class="bt"  name="submit" type="submit" value="Save">
            </div>
        </form>

        <h2>Usage</h2>
        
        <p>To use on your site, either use PHP include or Javascript include. Change the <code>site_id</code> value to the site you're tracking.</p>

        <textarea>&lt;?php 
    $site_id = 1;
    include '<?php echo $full_path; ?>count.php';
?&gt;

OR

&lt;script src="<?php echo $full_url; ?>count.php?site_id=1"&gt;&lt;/script&gt;</textarea>

        <p>To track detailed visitor info, put this where you'd like to track.</p>

        <textarea>&lt;img src="<?php echo $full_url; ?>track.php?site_id=1"&gt;</textarea>

<?php 
require_once 'includes/admin_footer.inc'; 
?>