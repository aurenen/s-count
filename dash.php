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
                <tr>
                    <td>1</td>
                    <td><a href="">Shinshoku</a></td>
                    <td>2</td>
                    <td>46,000</td>
                    <td><a href="">Edit</a></td>
                </tr>
            </tbody>
        </table>


<?php 
require_once 'includes/admin_footer.inc'; 
?>