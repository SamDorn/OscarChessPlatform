<?php

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
        .secondary-button{
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
    .secondary-button:hover{
        box-shadow: 0 0 10px #1a98c1;
    }

    .hidden-buttons {
        display: inline-block;
    }

    h2 {
        text-align: center;
    }
    .disabled{
        background-color: #ccc;
        cursor: not-allowed;
    }
    .left-div {
        text-align: center;
        height:150px; 
        width:300px; 
        border: 2px solid black;
        left: 0 ;
}
.sub{
    text-align: center;
}
h2{
    font-size: 25px;
}
a:hover{
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
                <h1>NOT FOUND</h1> <br>
                <h2>THE RESOURCE YOU REQUESTED WAS NOT FOUND </h2>
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
                    <a href="login">
                        <span class="icon"><i class="fa-solid fa-user"></i></span>
                        <span class="item">
                            <?php if ($isLoggedIn) : ?>
                                Profile
                            <?php else : ?>
                                Login/Sign-up
                            <?php endif; ?>
                        </span>
                    </a>
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
        connect("home")
        socket.onmessage = function(e) {
            let object = JSON.parse(e.data)
            if (object.disconnect === 'disconnect') {
                $.ajax({
                    type: "GET",
                    url: "logout",
                    success: function (response) {
                        location.href = "login?de"
                    }
                });
            }
        }
    } catch (error) {
        console.log(error)
    }


    <?php if ($isLoggedIn) : ?>
        $(".secondary-button").removeClass("disabled");
    <?php endif; ?>
    $.ajax({
        type: "GET",
        url: "player/" + userId,
        dataType: "json",
        success: function(response) {
            console.log(response)
            $("#img-profile").attr("src", "images/avatars/" + response.avatar);
            $("h3").html(response.username);
        }
    });
</script>
</html>
