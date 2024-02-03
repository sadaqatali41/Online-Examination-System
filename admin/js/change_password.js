$(function(){

    $(document).on('submit', '#changePasswordForm', function(){
        
        $.ajax({
            url: 'ajax/change_password.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'changePasswordFormSubmit'}),
            beforeSend: function() {
                $('#changePasswordFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#changePasswordFormBtn').html('Save').attr('disabled', false);
                } else {
                    alert(data.message);
                    window.location.reload();
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
        return false;
    });
});