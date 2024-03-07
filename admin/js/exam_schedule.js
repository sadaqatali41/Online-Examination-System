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
                "data": "duration"
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
                    let currYear = new Date().getFullYear(); 
                    if(currYear != row['for_year']) return manage;
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

    $('#regis_last_date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        sideBySide: true,
        minDate: moment().add(1, 'days'),
        maxDate: moment().endOf('year'),
        useCurrent: false,
        keepInvalid: false
    }).on('dp.change', function(e){
        $('#exam_date').val('');
    });

    $('#exam_date').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '+1w',
        autoclose: true
    });

    $(document).on('change', '#exam_date', function(){
        let exam_date = $(this).val();
        let regis_last_date = $('#regis_last_date').val() || new Date();

        let d1 = moment(regis_last_date);
        let d2 = moment(exam_date);
        let diffInDays = d2.diff(d1, 'days');

        if(diffInDays < 5) {
            alert(`Exam Date ${exam_date} should be 5 days greater than the Last Registration Date ${regis_last_date}`);
            $(this).val('');
        }
    });

    $('#start_time').timepicker({
        showInputs: false,
        template: 'dropdown',
        showMeridian: true,
        defaultTime: '10:00 AM'
    });

    $('#end_time').timepicker({
        showInputs: false,
        template: 'dropdown',
        showMeridian: true,
        defaultTime: '01:00 PM'
    });

    $(document).on('change', '#start_time, #end_time', function(){
        fetch_time_diff();
    });

    function fetch_time_diff() {
        let start_time = $('#start_time').val();
        let end_time = $('#end_time').val();
        if(start_time == '' || end_time == '') {
            return false;
        }
        let t1 = moment(start_time, 'h:mm A').format('HH:mm');
        let t2 = moment(end_time, 'h:mm A').format('HH:mm');
        
        // Example time values
        var startTime = moment('2024-02-01T' + t1);
        var endTime = moment('2024-02-01T' + t2);

        // Calculate the difference
        var duration = moment.duration(endTime.diff(startTime));

        // Get the difference in hours, minutes, and seconds
        var hours = duration.hours();
        var minutes = duration.minutes();
        var seconds = duration.seconds();

        if(hours < 2) {
            alert('Minimum Diff. between Exam Start Time & Exam End Time should be 2 Hours.');
            $('#end_time').val('');
        }
        // Display the result
        console.log('Time Difference: ' + hours + ' hours, ' + minutes + ' minutes, ' + seconds + ' seconds');
    }

    $(document).on('submit', '#examScheduleAddForm', function(){
        
        $.ajax({
            url: 'ajax/exam_schedule.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'examScheduleAddSubmit'}),
            beforeSend: function() {
                $('#examScheduleAddFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#examScheduleAddFormBtn').html('Save').attr('disabled', false);
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

    $(document).on('submit', '#examScheduleEditForm', function(){
        
        $.ajax({
            url: 'ajax/exam_schedule.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'examScheduleEditSubmit'}),
            beforeSend: function() {
                $('#examScheduleEditFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#examScheduleEditFormBtn').html('Save').attr('disabled', false);
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