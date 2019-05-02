<?php
session_start();
require_once ('header.html'); 

if (isset($_SESSION['logged']) && $_SESSION['logged']) { ?>
    <div class="header-block">
        <div>
            <h3 style="color: black">You are logged in!</h3>
            <a href="index.php" class="sign btn btn-primary">Home page</a>
            <a href="logout.php" class="sign btn btn-warning">Logout</a>
        </div>
    </div>
 <?php }
else {

    $error = false;
    $loginErr = false;
    $passErr = false;
    $errorMsg = '';
    $logged = false;
    $login = '';
    $pass = '';


    if (isset($_POST['sign-in'])) {
        $login = trim($_POST['login']);
        $pass = trim($_POST['password']);
        if ($login!=='' && $pass!== '') {

            require_once('huffman.php');
            require_once('encryption.php');

            $encryptedData = addCrc(huffmannEncode(transposition($pass)));

            $conn = connectDatabase();
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            $sql = "SELECT user_login, user_pass FROM users WHERE user_login='$login' and user_pass='$encryptedData'";
            $result = $conn->query($sql);
            if ($result->num_rows != 1) {
                $errorMsg = 'Not correct login or password!';
                $error = true;
            };
            
            $conn->close();
            
        }
        else {
            if ($login=='') {
                $loginErr = 'Enter login!';
                $error = true;
            }
            if ($pass == ''){
                $passErr = 'Enter password!';
                $error = true;
            }
        }
        if (!$error) {
            $logged = true;
            $_SESSION['logged'] = true;
        }
    }

    if (!$logged) {
    ?>
    <div class="form-container" id="sign-in">
        <form method="post" action="signin.php">
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" class="form-control <?php if ($loginErr!='' || $errorMsg!='') echo 'error' ?>" name="login" id="_login" placeholder="Enter login" value=<?php echo $login; ?>>
            <?php if ($loginErr!=''){ ?> <p class="error-txt"><?php echo $loginErr ?></p> <?php } ?>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control <?php if ($passErr!='' || $errorMsg!='') echo 'error' ?>" id="_password" placeholder="Password" <?php if ($pass!='') {?> value=<?php echo $pass; }?> />
                <?php if ($passErr!=''){ ?> <p class="error-txt"><?php echo $passErr ?></p> <?php } ?>
            </div>
            <?php if ($errorMsg!='') {?> <p class="error-txt"><?php echo $errorMsg; ?></p><?php } ?>
            <button type="submit" name="sign-in" class="btn btn-primary">Sign in</button>
        </form>
        <p>Don't have account? <a href="registration.php">Sign up</a></p>
    </div>

    <?php
    }
    else { ?>
        <div class="header-block">
            <div>
                <h3 style="color: black">You are logged in!</h3>
                <a href="index.php" class="sign btn btn-primary">Home page</a>
                <a href="logout.php" class="sign btn btn-warning">Logout</a>
            </div>
        </div
    <?php
    }
}

require_once ('footer.html');