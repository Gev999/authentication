<?php 
session_start();
require_once ('header.html'); 

if (isset($_SESSION['logged']) && $_SESSION['logged']) { ?>
    <div class="menu-block">
        <a href="logout.php" class="sign btn btn-warning">Logout</a>
    </div>
<?php } 
else {
?>
    <div class="menu-block">
            <a href="signin.php" class="sign btn-primary" id="sign_in">Sign in</a>
            <a href="registration.php" class="sign btn-success" id="sign_up">Sign up</a>
    </div>

<?php
}
require_once('footer.html');