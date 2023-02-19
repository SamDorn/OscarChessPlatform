function checkUsernameAjax() {
    $.ajax({
        url: "index.php",
        type: "POST",
        data: {
            request: "checkUsername",
            username: $("#usernameSignUp").val()
        },
        dataType: "json",
        success: function (data) {
            $("#errorUsername").text(data)
            if($("#usernameSignUp").val() == "")
                $("#errorUsername").text("")
        }
    })
}

function checkEmailAjax(){
    $.ajax({
        url: "index.php",
        type: "POST",
        data: {
            request: "checkEmail",
            email: $("#emailSignUp").val()
        },
        dataType: "json",
        success: function (data) {
            $("#errorEmail").text(data)
        }
    })
}

function insertUserAjax() {
    $.ajax({
        url: "index.php",
        type: "POST",
        data: {
            request: "signUp",
            username: $("#usernameSignUp").val(),
            email: $("#emailSignUp").val(),
            password: $("#passwordSignUp").val()
        },
        dataType: "json",
        success: function () {
            window.location.href = "index.php?action=login"
        }
    })
}
function checkUserAjax() {
    $.ajax({
        url: "index.php",
        type: "POST",
        data: {
            request: "login",
            username: $("#usernameLogin").val(),
            password: $("#passwordLogin").val()
        },
        dataType: "json",
        success: function (data) {
            alert(data)
            console.log(data)
            if(data !== "Wrong credentials")
                window.location.href = "index.php"
            
        }
    })
}
