<?php 
/************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: login.php
 *  
 *  Authenticates user for admin panel access.
 * 
 ************************************************************/

require_once 'includes/connect.php';
require_once 'includes/functions.php';
require_once 'includes/PasswordHash.php';

$page_title = "Login";

session_start();

if (isset($_POST['login'])) {
    if (verifyUser($_POST['username'], $_POST['password'])) {
        $_SESSION['login'] = md5($_POST['username']);
        header('Location: dash.php');
        exit();
    }
}
require_once 'includes/admin_header.inc'; 
?>

        <h2>Login</h2>
        <form action="login.php" method="post">
            <div class="form-row">
                <label>Username</label>
                <input type="text" name="username">
            </div>
            <div class="form-row">
                <label>Passcode</label>
                <input type="password" name="password">
            </div>
            <div class="form-row">
                <label>&nbsp;</label>
                <input class="bt" type="submit" name="login" value="Login">
            </div>
        </form>

<?php 
require_once 'includes/admin_footer.inc'; 
?>