<?php
    session_start();
    require 'config/data.php';
    include 'services/validator.php';
    if(isset($_POST['login']) && !empty($_POST['login'])){
        $username = validate_inputs($_POST['username']);
        $password = validate_inputs($_POST['password']);

        if(!empty($username) && !empty($password)){
            $data = file('storage/user.txt');
            foreach($data as $user){
                $userData = explode('|', $user);
                if(password_verify($password, trim($userData[3])) && (trim($userData[1]) == $username)){
                     $_SESSION['username'] = $username;
                     $_SESSION['isLogged'] = true;
                    header('Location: files.php');
                }else {
                    if(!next($data)){
                        echo "The password or username is incorect";
                    }
                }
            }
        }else{
            echo "The both fields are required";
        }




    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<form method="POST">
    <lable for="username">Име</lable>
    <input type="text" name="username" id="username"></br>
    <lable for="password">Парола</lable>
    <input type="password" name="password" id="password">
    <input type="submit" value="Изпрати" name="login">
</form>
</body>
</html>