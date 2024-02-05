$(function(){

    $("#city").select2({
        placeholder: "Search City...",
        width: '100%',
        ajax: {
            url: "ajax/profile.php",
            type: 'POST',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                    page: params.page,
                    act: "findCity"
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 10) < data.count_filtered
                    }
                };
            },
            cache: false
        }
    });

    $(document).on('submit', '#profileUpdateForm', function(){
        var formData = new FormData(this);
        formData.append('act', 'profileUpdateSubmit');
        $.ajax({
            url: 'ajax/student.php',
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            beforeSend: function() {
                $('#profileUpdateFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#profileUpdateFormBtn').html('Save').attr('disabled', false);
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