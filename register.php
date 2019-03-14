<?php

    include 'services/validator.php';
$timeTarget = 0.05; // 50 milliseconds

    if(isset($_POST['register']) && !empty($_POST['register'])){
        $username = $_POST['username'];
        $email    = $_POST['email'];
        $password = $_POST['password'];

        if(empty($_POST['username'])){
            $nameErr = 'Name is required';
        }else{
            validate_inputs($username);
        }

        if(empty($email)){
            $emailErr = 'Email is required';
        }else{
            validate_inputs($email);
        }

        if(empty($password)){
            $passErr = 'Password is required';
        }else{
            validate_inputs($password);
        }

        if(mb_strlen($username, 'UTF-8' ) < 5){
            $usernameErr = 'The username is to short';
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $emailErr = 'Invalid email format';
        }

        if(mb_strlen($password, 'UTF-8') < 4 && mb_strlen($password) > 8){
            $passwordErr = 'The password is incorect';
        }else {
            validate_inputs($password);
            $password = password_hash($password, PASSWORD_DEFAULT);

        }

        if(empty($nameErr) && empty($emailErr) && empty($passErr)){
            $data = file('storage/user.txt');
            foreach($data as $user){
                $userData = explode('|', $user);
                if($userData[2] == $email){
                    echo 'The email already exists';
                    exit();
                }
            }
            $userData = time() . '|' . $username . '|' . $email . '|' . $password . "\n";
            file_put_contents('storage/user.txt', $userData, FILE_APPEND);
            $registerIsSuccess = 'The register is successful';
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Document</title>
</head>
<body>
<form action="" method="post">
    <?php
        if(isset($registerIsSuccess)){
            echo '<span class="success">The register was successful you can login here</span></br> <a href="login.php">Login</a></br>';
        }
    ?>
    <label for="username">Username</label>
    <input type="text" name="username" id="username"></br>
    <span><?php if(isset($nameErr)) echo $nameErr; ?></span>
    <label for="email">Email</label>
    <input type="email" name="email" id="email"></br>
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <input type="submit" name="register" value="регистрирай се" >
</form>
<div class="have-account">
    <p>You already have an account? Login here:</p>
    <a href="login.php">Login</a>
</div>
</body>
</html>