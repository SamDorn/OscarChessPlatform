<?php
    require "user.php";
    if(isset($_POST['username'])){
        $user = new User($_POST["username"],$_POST["email"],$_POST["password"]);
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="username" id="username" placeholder="Username">
        <input type="email" name="email" id="email" placeholder="Email">
        <input type="password" name="password" id="password">
        <input type="submit" value="Sign-Up">

    </form>
</body>
</html>