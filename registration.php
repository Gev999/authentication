<?php
session_start();

require_once ('header.html'); 

if (isset($_SESSION['logged']) && $_SESSION['logged']) { ?>
    <div class="header-block">
        <div>
            <h3 style="color: black">You are already logged in!</h3>
            <a href="index.php" class="sign btn btn-primary">Home page</a>
            <a href="logout.php" class="sign btn btn-warning">Logout</a>
        </div>
    </div>
 <?php }

else {

    $error = false;
    $loginErr = false;
    $passErr = false;
    $regist = false;
    $login = '';
    $pass = '';


    if (isset($_POST['sign-up'])) {
        $login = trim($_POST['login']);
        $pass = trim($_POST['password']);
        if ($login!=='' && $pass!== '' && strlen($pass) > 7 && strlen($login) > 4) {

            require_once('huffman.php');
            require_once('encryption.php');

            $encryptedData = addCrc(huffmannEncode(transposition($pass)));

            $conn = connectDatabase();
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            $sql = "SELECT user_login, user_pass FROM users WHERE user_login='$login'";
            $result = $conn->query($sql);
            if ($result->num_rows == 0) {
                $sql = "INSERT INTO users (user_login, user_pass) VALUES ('$login', '$encryptedData')";
                $conn->query($sql);
            }
            else {
                $loginErr = 'Sorry, the login you entered is already exist. Enter another login.';
                $error = true;
            }
            $conn->close();        
        }
        else {
            if ($login=='') {
                $loginErr = 'Enter login!';
                $error = true;
            }
            else if (strlen($login) < 5) {
                $loginErr = 'Login must be at least 5 characters';
                $error = true;
            }
            if ($pass == ''){
                $passErr = 'Enter password!';
                $error = true;
            }
            else if (strlen($pass) < 8) {
                $passErr = 'Password must be at least 8 characters';
                $error = true;
            }
        }
        if (!$error) {
            $regist = true;
            $_SESSION['logged'] = true;
        }
    }

    if (!$regist) {
    ?>

    <div class="form-container" id="sign-up">
        <form method="post" action="registration.php">
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" class="form-control <?php if ($loginErr!='') echo 'error' ?>" name="login" id="login" placeholder="Enter login" value=<?php echo $login; ?>>
            <?php if ($loginErr!=''){ ?> <p class="error-txt"><?php echo $loginErr ?></p> <?php } ?>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control <?php if ($passErr!='') echo 'error' ?>" id="password" placeholder="Password" <?php if ($pass!='') {?> value=<?php echo $pass; }?> />
                <?php if ($passErr!=''){ ?> <p class="error-txt"><?php echo $passErr ?></p> <?php } ?>
            </div>
            <button type="submit" name="sign-up" class="btn btn-success">Sign up</button>
        </form>
        <p>Already have account? <a href="signin.php">Sign in</a></p>
    </div>


    <?php
    }
    else { ?>
        <div class="header-block">
            <div>
                <h3 style="color: black">Registration is done!! <br /> You are already logged in!</h3>
                <a href="index.php" class="sign btn btn-primary">Home page</a>
                <a href="logout.php" class="sign btn btn-warning">Logout</a>
            </div>
        </div>
    <?php
    }
}

require_once ('footer.html');


