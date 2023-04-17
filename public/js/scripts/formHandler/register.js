$(document).ready(function() {
   
    $("#usernameSignUp").keyup(function(e) {
        e.preventDefault()
        checkUsernameAjax()
    })

    $("#passwordSignUp").keyup(function(e) {
        e.preventDefault()
        $("#passwordSignUpVerify").show()
        if ($(this).val().length < 8)
            $("#errorPassword").text("Password must be minimum 8 character long")
        else
            $("#errorPassword").text("")
    })
    $("#passwordSignUpVerify").keyup(function(e) {
        if ($(this).val() != $("#passwordSignUp").val())
            $("#errorPasswordVerify").text("Password doesn't match")
        else
            $("#errorPasswordVerify").text("")
    })

})
function checkUsernameAjax() {
    $.ajax({
        url: "/checkUsername",
        type: "POST",
        data: {
            request: "checkUsername",
            username: $("#usernameSignUp").val()
        },
        dataType: "json",
        success: function (data) {
            console.log(data)
            $("#errorUsername").text(data)
            if($("#usernameSignUp").val() == "")
                $("#errorUsername").text("")
        }
    })
}
