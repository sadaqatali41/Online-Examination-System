$(function(){

    $(document).on('click', '#addNewRow', () => {
        var clonedDiv = $("#inputsTbl tr:last");
        var NewDiv = clonedDiv.clone();
        NewDiv.find(".cc_email").val("");
        NewDiv.find(".cc_email_name").val("");
        NewDiv.find(".cc_id").val("");
        NewDiv.find(".removeRow").attr("data-id", 0);
        NewDiv.insertAfter(clonedDiv);
    });

    let ccDelArr = [];

    $(document).on('click', '.removeRow', function(){
        let row = $('.removeRow').length;
        let id = parseInt($(this).data('id'));
        if(row == 1) {
            alert('Sorry! you can`t remove all rows.');
            return false;
        } else if(id > 0) {
            ccDelArr.push(id);
        }
        $(this).closest('tr').remove();
    });

    $(document).on('submit', '#emailSettingForm', function(){
        
        $.ajax({
            url: 'ajax/email_setting.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'emailSettingUpdateSubmit', 'del_arr': ccDelArr}),
            beforeSend: function() {
                $('#emailSettingFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#emailSettingFormBtn').html('Save').attr('disabled', false);
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