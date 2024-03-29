$(function(){

    var cc_id = '';
    var course_status = '';

    course_list(cc_id, course_status);

    function course_list(cc_id, course_status) {

        $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            'serverMethod': 'post',
            'destroy': true,
            "ajax": {
                "url": "ajax/course.php",
                "data": {
                    "act": "course_list",
                    cc_id,
                    course_status
                }
            },
            "lengthMenu": [25, 50, 75, 100],
            "drawCallback": function(settings) {
                $('.edit').tooltip({
                    container: 'body',
                    placement: 'top',
                    title: 'Edit'
                });
                $('.viewQuestion').tooltip({
                    container: 'body',
                    placement: 'top',
                    title: 'Questions'
                });
            },
            "columns": [{
                "data": "id"
            }, {
                "data": "cc_id"
            }, {
                "data": "course_name"
            }, {
                "data": "course_code"
            }, {
                "data": "course_status",
                "render": function(data, type, row, cell) {
                    if(data == 'A') {
                        return '<label class="badge bg-green">Active</label>';
                    } else {
                        return '<label class="badge bg-red">Inactive</label>';
                    }
                }
            }, {
                "data": "tot_question",
                render: function(data) {
                    return '<span class="badge bg-red">'+ data +'</span>';
                }
            }, {
                "data": "tot_student",
                render: function(data) {
                    return '<span class="badge bg-blue">'+ data +'</span>';
                }
            }, {
                "data": null,
                "render": function(data, type, row, cell) {
                    let manage = ''; 
                    manage += '<div class="btn-group">';
                    manage += '<a href="course.php?act=edit&id=' + row['id'] + '" class="btn btn-primary btn-xs edit"><i class="fa fa-edit"></i></a>';
                    manage += '<button type="button" class="btn btn-xs btn-info viewQuestion" data-id="'+ row.id +'" data-nm="'+ row.course_name +'"><i class="fa fa-question-circle"></i></button>';
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
        var cc_id = $('#cc_id').val();
        var course_status = $('#course_status').val();

        course_list(cc_id, course_status);
    });

    $(document).on('click', '.viewQuestion', function(){
        let el = $(this);
        let course_id = el.data('id');
        let course_name = el.data('nm');
        let question_status = $('#course_status').val();
        $('#questionModalTitle').html('Questions for : ' + course_name);
        $.when(question_list(course_id, question_status)).then(function(){
            $('#questionModal').modal('show');
        });
    });

    function question_list(course_id, question_status) {

        $('#questionTable').DataTable({
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
            "columns": [{
                "data": "id"
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
                "data": "marks"
            }, {
                "data": "question_status",
                "render": function(data, type, row, cell) {
                    if(data == 'A') {
                        return '<label class="badge bg-green">Active</label>';
                    } else {
                        return '<label class="badge bg-red">Inactive</label>';
                    }
                }
            }],
            "order": [0, 'desc'],
            dom: 'lBfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'print'
            ]
        });
    }

    bind_select2();

    $(document).on('click', '#addNewRow', () => {
        var clonedDiv = $("#inputsTbl tr:last");
        clonedDiv.find(".cc_id").select2("destroy").removeAttr('data-live-search').removeAttr('data-select2-id').removeAttr('aria-hidden').removeAttr('tabindex');
        var NewDiv = clonedDiv.clone();
        NewDiv.find(".cc_id").val(null).trigger("change.select2");
        NewDiv.find(".course_name").val("");
        NewDiv.find(".course_code").val("");
        NewDiv.find(".course_id").val("");
        NewDiv.find(".removeRow").attr("data-id", 0);
        NewDiv.insertAfter(clonedDiv);

        bind_select2();
    });

    $(document).on('click', '.removeRow', function(){
        let row = $('.removeRow').length;
        if(row == 1) {
            alert('Sorry! you can`t remove all rows.');
        } else {
            $(this).closest('tr').remove();
        }
    });

    function bind_select2() {

        $(".cc_id").select2({
            placeholder: "Search Course Category...",
            width: '100%',
            ajax: {
                url: "ajax/course.php",
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term, // search term
                        page: params.page,
                        act: "findCourseCat"
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
    }

    $(document).on('change', '.course_code', function(){
        let element = $(this);
        let course_code = element.val();
        if(isNaN(course_code)) {
            alert('[0-9] digits are allowed only.');
            element.val('');
            return false;
        }

        $('.course_code').not(element).each(function(i, val){
            let others = $(val).val();
            if(course_code === others) {
                alert('Duplicate Course Code is founded.');
                element.val('');
                return false;
            }
        });
    });

    $(document).on('submit', '#courseAddForm', function(){
        
        $.ajax({
            url: 'ajax/course.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'courseAddSubmit'}),
            beforeSend: function() {
                $('#courseAddFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#courseAddFormBtn').html('Save').attr('disabled', false);
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

    $(document).on('submit', '#courseEditForm', function(){
        
        $.ajax({
            url: 'ajax/course.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'courseEditSubmit'}),
            beforeSend: function() {
                $('#courseEditFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#courseEditFormBtn').html('Save').attr('disabled', false);
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