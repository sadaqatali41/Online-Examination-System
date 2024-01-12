$(function(){

    $(document).on('submit', '#loginForm', function(){
        
        $.ajax({
            url: 'ajax/login.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'loginSubmit'}),
            async: true,
            cache: false,
            contentType: "application/x-www-form-urlencoded",
            processData: false,
            beforeSend: function() {
                $('#loginFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#loginFormBtn').html('Login').attr('disabled', false);
                } else {
                    alert(data.message);
                    window.location.href = 'index.php';
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
        return false;
    });
});