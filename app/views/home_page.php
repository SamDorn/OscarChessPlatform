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
</style>

<body>

    <div class="wrapper">
        <div class="section">
            <div class="top_navbar">
                <div class="hamburger">
                </div>
            </div>
            <div class="container">
                <h1>Welcome on OscarChessPlatform</h1> <br>

                <p class="sub">
                    OscarChessPlatform is the new best platform available where you can play chess or enjoy watching other people playing
                    These are some of the reviews left by our users who played on this platform <br> <br>
                </p>

                <h2>
                    It's time to play and shine <br><br>
                </h2>
                <div class="button-container">
                    <div class="hidden-buttons">
                        <button class="secondary-button" id="vsComputer" onclick="location.href = 'vsComputer'"><i class="fa-solid fa-computer"></i>&nbspPlay vs computer</button>
                        <button class="secondary-button disabled" id="online" onclick="location.href = 'vsPlayer'"><i class="fa-solid fa-globe"></i>&nbspPlay online</button> <br><br><br>
                        <h2>It's time to learn some new tactics</h2><br><br>
                        <button style="margin-left:150px" class="secondary-button" id="vspuzzle" onclick="location.href = 'puzzles'"><i class="fa-solid fa-graduation-cap"></i>&nbspResolve puzzles</button>
                    
                    </div>
                </div><br><br>
                
                <h2>Reviews</h2> <br>
                <div class="review">
                    <h4>Best chess platform by an amateur</h4>
                    <p>"Of course it isn't the best one on the market, but let's think about it for a second. Only one person was behind all of this and was able
                        to pull this masterpiece. Hats of for <a href="http://github.com/SamDorn" target="_blank">Sam Dorn</a>"</p>
                    <p class="author">- Mattia Rocchi</p>
                </div>
                <div class="review">
                    <h4>Something fresh</h4>
                    <p>"This came out of nowhere and it was the right thing at the right moment. The market of chess platform
                        was very static, with only 2 major sites, but <b>OscarChessPlatform</b> was able to change things and
                        save the day. A lot could be improved but the fact that is <a href="http://github.com/SamDorn/OscarChessPlatform" target="_blank">open source</a> make me feel safer
                        since I know my personal data will not be shared with 3-partyies "</p>
                    <p class="author">- Nigario</p>
                </div>
                <div class="review">
                    <h4>Not a bad platform</h4>
                    <p>"Really nice platform here. I'm a web developer and i'm really amazed by the quality of this site. Of course there are
                        things that could be done better but overall it's a pretty enjoyable experience. "</p>
                    <p class="author">- Riccardo Nasa</p>
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
                    <a href="">
                        <span class="icon"><i class="fas fa-home"></i></span>
                        <span class="item">Home</span>
                    </a>
                </li>
                <li>
                    <a href="#">
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
                        <a href="logout">
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
                location.href = "logout?de"
            }
            console.log(object.disconnect)
        }
    } catch (error) {
        console.log(error)
        console.log("You must login")
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
            $("#img-profile").attr("src", response.avatar);
            $("h3").html(response.username);
        }
    });
    $("#vsComputer").click(function(e) {
        location.href = "vsComputer"
    });
    $("#online").click(function(e) {
        location.href = "vsPlayer"
    });
</script>

</html>