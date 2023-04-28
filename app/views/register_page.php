<script src="js/scripts/formHandler/register.js"></script>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans">
<link rel="stylesheet" href="styles/components/googleButton.css">
</head>

<body>
    <div class="registerForm" style="display:flex; justify-content:center;">
        <form action="register" method="POST">
            <input type="text" name="username" id="usernameSignUp" placeholder="username" autocomplete="off" required>
            <span id="errorUsername"></span><br>
            <input type="email" name="email" id="emailSignUp" placeholder="email" autocomplete="off" required>
            <span id="errorEmail"></span><br>
            <input type="password" name="password" id="passwordSignUp" placeholder="password" autocomplete="off" required>
            <span id="errorPassword"></span><br>
            <input type="password" name="passwordConfirm" id="passwordSignUpVerify" placeholder="verify password" autocomplete="off" required>
            <span id="errorPasswordVerify"></span><br>
            <input type="submit" value="Sign Up" id="submitForm" disabled>
            <a href="login" id="">Already signed up?</a><br><br>
            <span id="errors"></span>
            <div class="google-btn" onclick="location.href='<?= $googleController->getUrl() ?>'">
                <div class="google-icon-wrapper">
                    <img class="google-icon" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" />
                </div>
                <p class="btn-text">Sign up with Google</p>
            </div>
            <?php if (isset($errorRegistration)) : ?>
                <h1>Problem with the registration</h1>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>