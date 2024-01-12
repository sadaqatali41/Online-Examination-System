$(document).ready(function(){
    // 

    var url = document.URL;
    var id = url.substring(url.lastIndexOf('#') + 1);
    if(id == "forgot"){
        $('#forgot-pass-box').css("z-index", "1");
        $('#forgot-pass-box').css("transform", "rotateY(0deg)");
        $('#login-box').css("z-index", "0");
        $('#login-box').css("transform", "rotateY(180deg)");
    }

    $("#forgot_pass_btn").on("click", function(e) {
        e.preventDefault();
        $('#login-box').css("transform", "rotateY(180deg)");
        $('#login-box').css("z-index", "0");
        $('#forgot-pass-box').css("transform", "rotateY(0deg)");
        $('#forgot-pass-box').css("z-index", "1");
    });

    $("#back_to_login_btn").on("click", function(e) {
        e.preventDefault();
        $('#login-box').css("transform", "rotateY(0deg)");
        $('#login-box').css("z-index", "1");
        $('#forgot-pass-box').css("transform", "rotateY(180deg)");
        $('#forgot-pass-box').css("z-index", "0");
    });

});