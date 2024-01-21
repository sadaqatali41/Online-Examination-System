$(function(){

    var course_id = '';
    var question_status = '';

    question_list(course_id, question_status);

    function question_list(course_id, question_status) {

        $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            'serverMethod': 'post',
            'destroy': true,
            "ajax": {
                "url": "ajax/question.php",
                "data": {
                    "act": "question_list",
                    course_id,
                    question_status
                }
            },
            "lengthMenu": [25, 50, 75, 100],
            "drawCallback": function(settings) {
                $('.edit').tooltip({
                    container: 'body',
                    placement: 'top',
                    title: 'Edit'
                });
            },
            "columns": [{
                "data": "id"
            }, {
                "data": "course_id"
            }, {
                "data": "question_name"
            }, {
                "data": "optionA"
            }, {
                "data": "optionB"
            }, {
                "data": "optionC"
            }, {
                "data": "optionD"
            }, {
                "data": "correct_option"
            }, {
                "data": "question_status",
                "render": function(data, type, row, cell) {
                    if(data == 'A') {
                        return '<label class="badge bg-green">Active</label>';
                    } else {
                        return '<label class="badge bg-red">Inactive</label>';
                    }
                }
            }, {
                "data": null,
                "render": function(data, type, row, cell) {
                    let manage = ''; 
                    manage += '<div class="btn-group" style="display: flex;">';
                    manage += '<a href="question.php?act=edit&id=' + row['id'] + '" class="btn btn-primary btn-xs edit"><i class="fa fa-edit"></i></a>';
                    manage += '</div>';
    
                    return manage;
                }
            }],
            "order": [0, 'desc'],
            "columnDefs": [{
                'targets': -1,
                'orderable': false
            }],
            dom: 'lBfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'print'
            ]
        });
    }

    $(document).on('click', '#search', function(){
        var course_id = $('#course_id').val();
        var question_status = $('#question_status').val();

        question_list(course_id, question_status);
    });


    $("#course_id").select2({
        placeholder: "Search Course...",
        width: '100%',
        ajax: {
            url: "ajax/question.php",
            type: 'POST',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                    page: params.page,
                    act: "findCourse"
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

    $(document).on('submit', '#questionAddForm', function(){
        
        $.ajax({
            url: 'ajax/question.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'questionAddSubmit'}),
            beforeSend: function() {
                $('#questionAddFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#questionAddFormBtn').html('Save').attr('disabled', false);
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

    $(document).on('submit', '#questionEditForm', function(){
        
        $.ajax({
            url: 'ajax/question.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'questionEditSubmit'}),
            beforeSend: function() {
                $('#questionEditFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#questionEditFormBtn').html('Save').attr('disabled', false);
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