function checkUsernameAjax(){
    $.ajax({
        url: "index.php",
        type: "POST",
        data: {
            request: "checkUsername",
            username: $("#usernameSignUp").val()
        },
        dataType: "json",
        success: function(data){

        }
    })
}

function insertUserAjax(){
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
        success: function(data){
            alert("CIao")
        }
    })
}