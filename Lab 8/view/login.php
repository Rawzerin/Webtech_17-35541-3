<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../styles/login_page_style.css">
    <script type="text/javascript" src="../scripts/login.js"></script>
</head>
<body>
<?php
session_start();

include "header.php";

$email = $password = "";
$emailErr = $passwordErr = null;

if (isset($_COOKIE['email'])) {
    $email = trim($_COOKIE['email']);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['email'])) {
        $email = test_input($_POST['email']);
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            } else {
                $emailErr = false;
            }
        } else {
            $emailErr = "Cannot be empty";
        }

    }

    if (isset($_POST['password'])) {
        $password = test_input($_POST['password']);
        if (empty($password)) {
            $passwordErr = "Password cannot be empty";
        }
    }

    if (!$emailErr && !$passwordErr) {
        require $_SERVER['DOCUMENT_ROOT'] . "/controller/login_controller.php";
        $userID = signIn($email, $password);
        if ($userID != null) {
            $_SESSION['userId'] = $userID;
            $_SESSION['email'] = $email;
            header('Location: profile.php');
        } else {
            echo "<script>alert('Wrong Credentials. Couldn\'t Sign In!')</script>";
        }
    }
}

if (isset($_POST['rememberMe'])) {
    setcookie("email", $email);
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<form method="post" action="<?php echo htmlspecialchars(@$_SERVER['PHP_SELF']); ?>" onsubmit="return validateForm()">
    <div class="content">
        <div class="sign-in">
            <h2>Sign In</h2>
            <div class="input-field-margin">
                <input id="email" class="rounded-input-field " type="text" name="email" value="<?php echo $email ?>"
                       placeholder="Your Email"><br>
                <p id="emailErr" class="error"><?php echo $emailErr ?></p><br>
            </div>
            <div class="input-field-margin">
                <input id="password" class="rounded-input-field " type="password" name="password" value="<?php echo $password ?>"
                       placeholder="Password"><br>
                <p id="password" class="error"><?php echo $passwordErr ?></p><br>
            </div>
            <input class="input-field-margin checkbox-style" type="checkbox" name="rememberMe"><span
                    class="checkbox-style">Remember Me</span><br>
            <input class="rectangular-button input-field-margin" type="submit" name="submit" value="Login"><br>
            <a class="input-field-margin" href="ForgetPassword.php" >Forget Password?</a>
        </div>
    </div>
</form>
<?php include "footer.php" ?>

</body>

</html>
