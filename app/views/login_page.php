<script src="js/scripts/formHandler/login.js"></script>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans">
<link rel="stylesheet" href="styles/components/googleButton.css">
<style>
</style>
</head>
<body>
    <div id="loginForm" style="display:flex; justify-content:center;">
        <form action="login" method="POST">
            <input type="email" name="email" id="emailLogin" placeholder="email" required><br>
            <input type="password" name="password" id="passwordLogin" placeholder="password" required><br>
            <input type="submit" value="Accedi" id="submitLogin">
            <a href="register" id="">Nuovo utente?</a><br><br>
            <div class="google-btn" onclick="location.href='<?= $googleController->getUrl()?>'">
                <div class="google-icon-wrapper">
                    <img class="google-icon" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" />
                </div>
                <p class="btn-text">Log in in with Google</p>
            </div>
            <?php if(isset($emailSent)):?>
                <h1>Email sent</h1>
            <?php endif;?>
            <?php if(isset($_GET['wc'])):?>
                <h4>Wrong credentials</h4>
            <?php endif;?>
        </form>
    </div>
    
</body>
</html>