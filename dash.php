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

require_once 'includes/admin_header.inc'; 
?>

        <h2>Dashboard</h2>
        
        <p>There are <code>5</code> sites being tracked, with a total of <code>31441</code> hits.</p>

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
                         '<td><a href="' . $s['site_url'] . '">' . $s['site_name'] . '</a></td>' .
                         '<td>' . $s['today'] . '</td>' .
                         '<td>' . $s['count'] . '</td>' .
                         '<td><a href="edit.php?site_id=' . $s['site_id'] . '">Edit</a></td>' .
                         '</tr>' . "\n";
                } ?>
            </tbody>
        </table>


<?php 
require_once 'includes/admin_footer.inc'; 
?>