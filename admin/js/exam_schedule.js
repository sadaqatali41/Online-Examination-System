$(function(){

    var course_id = '';
    var es_status = '';

    exam_schedule_list(course_id, es_status);

    function exam_schedule_list(course_id, es_status) {

        $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            'serverMethod': 'post',
            'destroy': true,
            "ajax": {
                "url": "ajax/exam_schedule.php",
                "data": {
                    "act": "exam_schedule_list",
                    course_id,
                    es_status
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
                "data": "course_name"
            }, {
                "data": "for_year"
            }, {
                "data": "regis_last_date"
            },  {
                "data": "exam_date"
            },  {
                "data": "start_time"
            },  {
                "data": "end_time"
            },  {
                "data": "es_status",
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
                    manage += '<a href="exam_schedule.php?act=edit&id=' + row['id'] + '" class="btn btn-primary btn-xs edit"><i class="fa fa-edit"></i></a>';
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
        var es_status = $('#es_status').val();

        exam_schedule_list(course_id, es_status);
    });


    $("#course_id").select2({
        placeholder: "Search Course...",
        width: '100%',
        ajax: {
            url: "ajax/exam_schedule.php",
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

    $('#exam_date').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '+1w',
        autoclose: true
    });

    $('#start_time').timepicker({
        showInputs: false
    });

    $('#end_time').timepicker({
        showInputs: false
    });

    $(document).on('submit', '#ecAddForm', function(){
        
        $.ajax({
            url: 'ajax/exam_schedule.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'ecAddSubmit'}),
            beforeSend: function() {
                $('#ecAddFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#ecAddFormBtn').html('Save').attr('disabled', false);
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

    $(document).on('submit', '#ecEditForm', function(){
        
        $.ajax({
            url: 'ajax/exam_schedule.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'ecEditSubmit'}),
            beforeSend: function() {
                $('#ecEditFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#ecEditFormBtn').html('Save').attr('disabled', false);
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