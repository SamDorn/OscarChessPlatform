<script src="js/scripts/formHandler/register.js"></script>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans">
<link rel="stylesheet" href="styles/components/googleButton.css">
</head>

<body>
    <div class="registerForm" style="display:flex; justify-content:center;">
        <form action="register" method="POST">
            <input type="text" name="username" id="usernameSignUp" placeholder="username" required autocomplete="off">
            <span id="errorUsername"></span><br>
            <input type="email" name="email" id="emailSignUp" placeholder="email" required autocomplete="off">
            <span id="errorEmail"></span><br>
            <input type="password" name="password" id="passwordSignUp" placeholder="password" required autocomplete="off">
            <span id="errorPassword"></span><br>
            <input type="password" name="password2" id="passwordSignUpVerify" placeholder="verify password" required autocomplete="off">
            <span id="errorPasswordVerify"></span><br>
            <input type="submit" value="Registrati" id="">
            <a href="login" id="">Già registrato?</a><br><br>
            <div class="google-btn">
                <div class="google-icon-wrapper">
                    <img class="google-icon" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" />
                </div>
                <p class="btn-text">Sign up with Google</p>
            </div>
        </form>
    </div>
</body>

</html>