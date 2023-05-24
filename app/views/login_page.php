<?php
    if($isLoggedIn){
        header("Location: home");
    }
?>
<script src="js/scripts/formHandler/login.js"></script>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans">
<link rel="stylesheet" href="styles/components/googleButton.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    body {
        background-color: #262626;
        font-family: 'Open Sans', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        overflow-y: hidden !important;
    }

    #loginForm {
        background-color: #262626;
        border-radius: 5px;
        box-shadow: 0 0 50px rgba(0, 0, 0, 0.8);
        padding: 20px;
        width: 500px;

    }

    form input[type="email"],
    form input[type="password"] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    form input[type="submit"] {
        background-color: #fff000;
        color: black;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    form input[type="submit"]:hover {

        box-shadow: 0 0 10px rgba(255, 240, 0, 255);
    }

    form a {
        color: #fff000;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    h4 {
        color: red;
        font-size: 16px;
        margin-top: 10px;
    }

    .alert {
        position: absolute;
        top: 0px;
        margin: 0 auto;
        padding: 15px;
        border-radius: 4px;
        text-align: center;
        width: fit-content;
        margin: 0 auto;
        margin-bottom: 15px;
        font-size: 30px;
    }

    .green {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .orange {
        color: #ffa500;
        background-color: #ffe6cc;
        border-color: #ffcc99;
    }

    .hidden {
        display: none;
    }
    .close-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    cursor: pointer;
    font-size: 30px;
}
</style>

</head>

<body>
    <?php if (isset($_GET['es'])) : ?>
        <div class="alert green">Account created successfully. A verification email has been sent to your email address<i class="fa-solid fa-xmark close-btn"></i></div>
    <?php endif; ?>
    <?php if (isset($_GET['ese'])) : ?>
        <div class="alert orange">Account created successfully. However, there was an issue sending the verification email<i class="fa-solid fa-xmark close-btn"></i></div>
    <?php endif; ?>
    <?php if (isset($_GET['de'])) : ?>
        <div class="alert orange">Your account was disconnected due to a login from another device<i class="fa-solid fa-xmark close-btn"></i></div>
    <?php endif; ?>
    <div id="loginForm">
        <form action="login" method="POST">
            <input type="email" name="email" id="emailLogin" placeholder="email" required><br>
            <input type="password" name="password" id="passwordLogin" placeholder="password" required><br>
            <input type="submit" value="Login" id="submitLogin">
            <?php if (isset($_GET['wc'])) : ?>
                <h4>Wrong credentials</h4>
            <?php endif; ?>
            <a href="register" id="">Register now</a><br><br>
            <div class="google-btn" onclick="location.href='<?= $googleController->getUrl() ?>'">
                <div class="google-icon-wrapper">
                    <img class="google-icon" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" />
                </div>
                <p class="btn-text">Log in in with Google</p>
            </div>
            <a href="home">Homepage</a>
            <?php if (isset($_GET['po'])) : ?>
                <h4>You need to be logged to be able to play online</h4>
            <?php endif; ?>
        </form>
    </div>

</body>
<script>
    $(".close-btn").click(function (e) { 
        e.preventDefault();
        $(".alert").addClass("hidden");
        
    });
</script>

</html>