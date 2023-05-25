<script src="js/scripts/formHandler/register.js"></script>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans">
<link rel="stylesheet" href="styles/components/googleButton.css">
<style>
body {
    background-color: #262626;
    font-family: 'Open Sans', sans-serif;
    display:flex; 
    justify-content:center;
    height: 100vh;
    align-items: center;
    overflow-y: hidden;
}

.registerForm {
    padding: 10px;
    margin-top: 70px;
    box-shadow: 0 0 50px rgba(0,0,0,0.8);
    width: 500px;
    height: fit-content;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 300px;
    padding: 10px;
    margin: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

input[type="submit"] {
    background-color: #fff000;
    color: black;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    margin-top: 10px;
    font-size: 16px;
    cursor: pointer;
}

input[type="submit"]:disabled {
    background-color: #ccc;
    cursor: not-allowed;
    color: white
}

a {
    color: #fff000;
    text-decoration: none;
    font-size: 14px;
    margin-top: 10px;
}

a:hover {
    text-decoration: underline;
}

.google-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin-top: 20px;
    width: 300px;
    cursor: pointer;
}

.google-icon-wrapper {
    background-color: #fff;
    border-radius: 5px;
    display: inline-block;
    height: 30px;
    width: 30px;
    margin-right: 10px;
}

.google-icon {
    display: block;
    height: 30px;
    margin-left: auto;
    margin-right: auto;
    width: 30px;
}

.btn-text {
    color: #737373;
    font-size: 14px;
    font-weight: bold;
    margin: auto;
}

#errors {
    color: red;
    margin-top: 10px;
    text-align: center;
}
h1{
    color: #fff000;
    text-align: center;
}

#errorUsername,
#errorEmail,
#errorPassword,
#errorPasswordVerify {
    color: red;
    font-size: 14px;
    margin-bottom: 5px;
    text-align: center;
}
</style>
</head>

<body>
    <div class="registerForm">
        <form action="register" method="POST">
            <input type="text" name="username" id="usernameSignUp" placeholder="username" autocomplete="off" required>
            <span id="errorUsername">
            <?php if (isset($_GET['uau'])) : ?>
                    <h4>The username provided is already in use</h4>
                <?php endif; ?>
            </span><br>
            <input type="email" name="email" id="emailSignUp" placeholder="email" autocomplete="off" required>
            <span id="errorEmail">
                <?php if (isset($_GET['we'])) : ?>
                    <h4>The email provided is not a valid email</h4>
                <?php endif; ?>
                <?php if (isset($_GET['eau'])) : ?>
                    <h4>The email provided is already in use</h4>
                <?php endif; ?>
            </span><br>
        
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
        <a href="home">Homepage</a>
        <?php if (isset($errorRegistration)) : ?>
            <h1>Problem with the registration</h1>
        <?php endif; ?>

        </form>
    </div>
</body>

</html>