<?php
if (!$isLoggedIn)
    header("Location: home");


use App\utilities\Jwt;

if ($isLoggedIn) : ?>
    <script src="js/scripts/web_socket/connection.js"></script>
<?php endif; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');

    * {
        list-style: none;
        text-decoration: none;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Open Sans', sans-serif;
    }

    @media only screen and (max-width: 600px) {
        .wrapper .sidebar {
            max-width: 30%;
        }

        .secondary-button {
            margin-left: 0 auto;
        }

    }

    body {
        background: #262626;
    }

    .wrapper .sidebar {
        background: #262626;
        position: fixed;
        top: 0;
        left: 0;
        width: 225px;
        height: 100%;
        padding: 20px 0;
        transition: all 0.5s ease;
        box-shadow: 0 0 50px rgba(0, 0, 0, 0.8);

    }

    .wrapper .sidebar .profile {
        margin-bottom: 30px;
        text-align: center;
    }

    .wrapper .sidebar .profile img {
        display: block;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin: 0 auto;
    }

    .wrapper .sidebar .profile h3 {
        color: #fff000;
        margin: 10px 0 5px;
    }

    .wrapper .sidebar .profile p {
        color: #fff000;
        font-size: 14px;
    }

    .wrapper .sidebar ul li a {
        display: block;
        padding: 13px 30px;
        color: #fff000;
        font-size: 25px;
        position: relative;
    }

    .wrapper .sidebar ul li a .icon {
        color: #fff000;
        width: 30px;
        display: inline-block;
    }



    .wrapper .sidebar ul li a:hover,
    .wrapper .sidebar ul li a.active {
        color: #fff000;

        background: black;
        border-right: 1px solid #fff000;
        border-bottom: none;
    }

    .wrapper .sidebar ul li a:hover .icon,
    .wrapper .sidebar ul li a.active .icon {
        color: #fff000;
    }

    .wrapper .sidebar ul li a:hover:before,
    .wrapper .sidebar ul li a.active:before {
        display: block;
    }

    .wrapper .section {
        width: calc(100% - 225px);
        margin-left: 225px;
        transition: all 0.5s ease;
    }

    .wrapper .section .top_navbar {
        height: 50px;
        display: flex;
        align-items: center;
        padding: 0 30px;

    }

    .wrapper .section .top_navbar .hamburger a {
        font-size: 28px;
        color: #f4fbff;
    }

    .wrapper .section .top_navbar .hamburger a:hover {
        color: #a2ecff;
    }

    .wrapper .section .container {
        margin: 30px;
        background: #fff;
        padding: 50px;
        line-height: 28px;
        width: auto;
        min-width: 500px;
    }



    p {
        font-size: 25px;
    }

    h1 {
        text-align: center;
        font-size: 40px;
    }

    .review {
        border: 1px solid #ccc;
        padding: 20px;
        background-color: #f9f9f9;
        margin-bottom: 20px;
    }

    .review h3 {
        margin-top: 0;
        font-size: 1.2em;
    }

    .review p {
        font-size: 1.1em;
        line-height: 1.5;
    }

    .review .author {
        font-style: italic;
        text-align: right;
    }

    a {
        text-decoration: none;
    }

    .button-container {
        position: relative;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .main-button {
        padding: 20px 50px;
        font-size: 24px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .secondary-button {
        padding: 30px 60px;
        font-size: 24px;
        background-color: #008CBA;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .secondary-button:hover {
        box-shadow: 0 0 10px #1a98c1;
    }

    .hidden-buttons {
        display: inline-block;
    }

    h2 {
        text-align: center;
    }

    .disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .left-div {
        text-align: center;
        height: 150px;
        width: 300px;
        border: 2px solid black;
        left: 0;
    }

    .sub {
        text-align: center;
    }

    h2 {
        font-size: 25px;
    }

    a:hover {
        cursor: pointer;
    }


    .profile-picture {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .profile-picture img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
    }

    .custom-file-input {
        position: absolute;
        visibility: hidden;
    }

    .custom-file-label {
        display: inline-block;
        padding: 8px 20px;
        background-color: #faeb00;
        color: #262626;
        border-radius: 4px;
        cursor: pointer;
    }

    .custom-file-label:hover {
        background-color: #f8e144;
    }

    form {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    a.button {
        display: inline-block;
        padding: 0.46em 1.6em;
        border: 0.1em solid #000000;
        margin: 0 0.2em 0.2em 0;
        border-radius: 0.12em;
        box-sizing: border-box;
        text-decoration: none;
        font-weight: 300;
        color: #000000;
        text-shadow: 0 0.04em 0.04em rgba(0, 0, 0, 0.35);
        background-color: #fff000;
        text-align: center;
        transition: all 0.15s;
        letter-spacing: 2px;
        background-color: #fff000;
        font-family: 'sans-serif';
        cursor: pointer;
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
        left: 45%;

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
        display: none !important;
    }

    i:hover {
        cursor: pointer;
    }
</style>

<body>

    <div class="wrapper">
        <div class="section">
            <div class="top_navbar">
                <div class="hamburger">
                </div>
            </div>
            <div class="container">

                <div class="profile-container">
                    <h2>Edit Profile</h2>

                    <div class="profile-picture">
                        <img id="profile-picture-preview" src="" alt="Profile Picture">
                    </div>

                    <form method="POST" action="updateProfile" id="profile-form">
                        <label for="profile-picture-input" class="custom-file-label">Upload Picture</label>
                        <input type="file" id="profile-picture-input" class="custom-file-input" accept="image/*" onchange="previewProfilePicture(event)" name="avatar" required>
                        <input type="hidden" name="id" id="idUser">
                        <input type="submit" value="Save Changes">
                    </form>

                </div>
                <div class="prova" style="display: flex; justify-content:center;">
                    <h2 id="not-verified" class="hidden">Your account is not verified</h2>
                    <a id='send-email' class='button hidden'>Send verification email</a>
                    <h2 id="verified" class="hidden">Your account is verified</h2>
                </div>
            </div>
        </div>
        <div class="sidebar">
            <div class="profile">
                <img src="https://i.pinimg.com/originals/f1/0f/f7/f10ff70a7155e5ab666bcdd1b45b726d.jpg" alt="profile_picture" id="img-profile">
                <h3>Anonymous</h3>
            </div>
            <ul>
                <li>
                    <a href="home">
                        <span class="icon"><i class="fas fa-home"></i></span>
                        <span class="item">Home</span>
                    </a>
                </li>
                <li>
                    <a href="home">
                        <span class="icon"><i class="fas fa-desktop"></i></span>
                        <span class="item">Play</span>
                    </a>
                </li>
                <li>
                    <a href="puzzles">
                        <span class="icon"><i class="fa-solid fa-graduation-cap"></i></span>
                        <span class="item">Learn</span>
                    </a>
                </li>
                <?php if ($isLoggedIn) : ?>
                    <li>
                        <a href="#">
                            <span class="icon"><i class="fa-solid fa-user-group"></i></span>
                            <span class="item">Friends</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="#">
                        <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <span class="item">Search player</span>
                    </a>
                </li>
                <li>
                    <a href="api">
                        <span class="icon"><i class="fas fa-database"></i></span>
                        <span class="item">Api</span>
                    </a>
                </li>
                <li>

                    <?php if ($isLoggedIn) : ?>
                        <a href="profile">
                            <span class="icon"><i class="fa-solid fa-user"></i></span>
                            <span class="item">Profile</span></a>
                    <?php else : ?>
                        <a href="login">
                            <span class="icon"><i class="fa-solid fa-user"></i></span>
                            <span class="item">Login/Sign-up</span></a>
                    <?php endif; ?>


                </li>
                <?php if ($isLoggedIn) : ?>
                    <li>
                        <a id="logout">
                            <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                            <span class="item">Logout</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

    </div>

</body>

<script>
    try {
        var jwt = "<?= $_COOKIE['jwt'] ?? null ?>"
        var userId = "<?= Jwt::getPayload($_COOKIE['jwt'])['user_id'] ?>"
        $("#idUser").val(userId);
        connect("home")
        socket.onmessage = function(e) {
            let object = JSON.parse(e.data)
            if (object.disconnect === 'disconnect') {
                $.ajax({
                    type: "GET",
                    url: "logout",
                    success: function(response) {
                        location.href = "login?de"
                    }
                });
            }
        }
    } catch (error) {
        console.log(error)
    }
    $.ajax({
        type: "GET",
        url: "player/" + userId,
        dataType: "json",
        success: function(response) {
            console.log(response)
            $("#profile-picture-preview").attr("src", "images/avatars/" + response.avatar);
            $("#img-profile").attr("src", "images/avatars/" + response.avatar);
            $("h3").html(response.username);
            if (response.verified === 0) {
                $("#send-email").removeClass("hidden");
                $("#not-verified").removeClass("hidden");
            } else {
                $("#verified").removeClass("hidden");
            }
        }
    });
    $("#logout").click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "logout",
            success: function(response) {
                location.href = "login"
            }
        });
    });
    $("#send-email").click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "sendEmail",
            data: {
                id: userId
            },
            dataType: "json",
            success: function(response) {
                console.log(response)
                if (response === "email sent")
                    $("body").append("<div class='alert green'>Email sent. Check your email<i class='fa-solid fa-xmark close-btn'></i></div>");
                else {
                    $("body").append("<div class='alert orange'>Email not sent. There was a problem sending the email<i class='fa-solid fa-xmark close-btn'></i></div>");
                }

                $(".close-btn").click(function(e) {
                    e.preventDefault();
                    $(".alert").addClass("hidden");
                });
            }
        });
    });

    function previewProfilePicture(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('profile-picture-preview').src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</html>