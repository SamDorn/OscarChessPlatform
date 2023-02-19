<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OscarChessPlatform</title>
    <script src="js/libraries/jQuery/jquery-3.6.3.min.js"></script>
    <script src="js/scripts/login.js"></script>
</head>
<body>
    <div id="loginForm" style="display:flex; justify-content:center;">
        <form action="">
            <input type="text" name="username" id="usernameLogin" placeholder="username" required><br>
            <input type="password" name="password" id="passwordLogin" placeholder="password" required><br>
            <input type="submit" value="Accedi" id="submitLogin">
            <a href="" id="goSignUp">Nuovo utente?</a>
        </form>
    </div>
    <div id="registerForm" style="display:flex; justify-content:center;">
        <form action="">
            <input type="text" name="username" id="usernameSignUp" placeholder="username" required autocomplete="off">
            <span id="errorUsername"></span><br>
            <input type="email" name="email" id="emailSignUp" placeholder="email" required autocomplete="off">
            <span id="errorEmail"></span><br>
            <input type="password" name="password" id="passwordSignUp" placeholder="password" required autocomplete="off">
            <span id="errorPassword"></span><br>
            <input type="password" name="password" id="passwordSignUpVerify" placeholder="verify password" required autocomplete="off">
            <span id="errorPasswordVerify"></span><br>
            <input type="submit" value="Registrati" id="submitSignUp">
            <a href="" id="goLogin">Già registrato?</a>
        </form>
    </div>
</body>
<script>
    $("#registerForm").hide()
    $("#passwordSignUpVerify").hide()

    $(document).ready(function() {
        $("#loginForm").submit(function(e) {
            e.preventDefault();
        })
        $("#goSignUp").click(function(e) {
            e.preventDefault()
            $("#loginForm").hide()
            $("#registerForm").show()
        })
        $("#goLogin").click(function(e) {
            e.preventDefault()
            $("#loginForm").show()
            $("#registerForm").hide()
        })
        $("#usernameSignUp").keyup(function(e) {
            e.preventDefault()
            checkUsernameAjax()
        })

        $("#emailSignUp").keyup(function(e) {
            e.preventDefault()
            checkEmailAjax()
        })

        $("#registerForm").submit(function(e) {
            e.preventDefault()
            insertUserAjax()
        })

        $("#loginForm").submit(function(e) {
            e.preventDefault()
            checkUserAjax()
        })
        $("#passwordSignUp").keyup(function(e) {
            e.preventDefault()
            $("#passwordSignUpVerify").show()
            if ($(this).val().length < 8)
                $("#errorPassword").text("Password must be minimum 8 character long")
            else
                $("#errorPassword").text("")
        })
        $("#passwordSignUpVerify").keyup(function(e){
            if($(this).val() != $("#passwordSignUp").val())
                $("#errorPasswordVerify").text("Password doesn't match")
            else
                $("#errorPasswordVerify").text("")
        })
        
    })
</script>

</html>