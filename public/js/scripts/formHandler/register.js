var errors = []
$(document).ready(function () {

    $("#usernameSignUp").keyup(function (e) {
        e.preventDefault()
        checkUsernameAjax()
        canSubmit()
    })

    $("#passwordSignUp").keyup(function (e) {
        canSubmit()
        e.preventDefault()
        $("#passwordSignUpVerify").show()
        if ($(this).val().length < 8)
            $("#errorPassword").text("Password must be minimum 8 character long")
        else
            $("#errorPassword").text("")
    })
    $("#passwordSignUpVerify").keyup(function (e) {
        canSubmit()
        if ($(this).val() != $("#passwordSignUp").val()) {
            $("#errorPasswordVerify").text("Password doesn't match")

        }
        else {
            $("#errorPasswordVerify").text("")
        }

    })


})
function checkUsernameAjax() {
    if ($("#usernameSignUp").val() == "")
        $("#errorUsername").text("")
    if ($("#usernameSignUp").val().length > 2) {
        $.ajax({
            url: "checkUsername",
            type: "POST",
            data: {
                request: "checkUsername",
                username: $("#usernameSignUp").val()
            },
            dataType: "json",
            success: function (data) {
                console.log(data)
                $("#errorUsername").text(data)
                canSubmit()
            }
        })
    }

}
function canSubmit() {
    errors = []
    if ($("#passwordSignUp").val() !== $("#passwordSignUpVerify").val()) {
        errors.push("NotMatch")
    }
    if ($("#usernameSignUp").val().length < 3 || $("#errorUsername").html() === "Username already taken") {
        errors.push("Username")
    }
    if ($("#passwordSignUp").val().length < 8) {
        errors.push("LenghtPassword")
    }
    document.getElementById('submitForm').disabled = errors.length !== 0 ? true : false

}
