<?php 
/************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: dash.php
 *  
 *  Dashboard for admin panel.
 * 
 ************************************************************/

require_once 'includes/connect.php';
require_once 'includes/functions.php';

$page_title = "Dash";

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
} 

$sites = getSitesDash();
$proj_cnt = getProjectCount();
$hit_cnt = getHitCount();

require_once 'includes/admin_header.inc'; 
?>

        <h2>Dashboard</h2>
        
        <p>There are <code><?php echo $proj_cnt; ?></code> sites being tracked, with a total of <code><?php echo $hit_cnt; ?></code> hits.</p>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Site</th>
                    <th>Today</th>
                    <th>Total</th>
                    <th>Settings</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sites as $s) {
                    echo '<tr>' .
                         '<td>' . $s['site_id'] . '</td>' .
                         '<td><a href="hits.php?id=' . $s['site_id'] . '">' . $s['site_name'] . '</a></td>' .
                         '<td>' . getHitsToday($s['site_id']) . '</td>' .
                         '<td>' . $s['count'] . '</td>' .
                         '<td><a href="edit.php?site_id=' . $s['site_id'] . '">Edit</a></td>' .
                         '</tr>' . "\n";
                } ?>
            </tbody>
        </table>


<?php 
require_once 'includes/admin_footer.inc'; 
?>