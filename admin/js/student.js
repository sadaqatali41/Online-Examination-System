$(function(){

    var course_id = '';
    var ec_status = '';

    eligibility_criteria_list(course_id, ec_status);

    function eligibility_criteria_list(course_id, ec_status) {

        $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            'serverMethod': 'post',
            'destroy': true,
            "ajax": {
                "url": "ajax/student.php",
                "data": {
                    "act": "eligibility_criteria_list",
                    course_id,
                    ec_status
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
                "data": "eligibility_criteria"
            }, {
                "data": "ec_status",
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
                    manage += '<a href="student.php?act=edit&id=' + row['id'] + '" class="btn btn-primary btn-xs edit"><i class="fa fa-edit"></i></a>';
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
        var ec_status = $('#ec_status').val();

        eligibility_criteria_list(course_id, ec_status);
    });


    $("#country").select2({
        placeholder: "Search Country...",
        width: '100%',
        ajax: {
            url: "ajax/student.php",
            type: 'POST',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                    page: params.page,
                    act: "findCountry"
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
    }).on('select2:select', function(){
        $('#state').val(null).trigger('change.select2');
        $('#city').val(null).trigger('change.select2');
    });

    $("#state").select2({
        placeholder: "Search State...",
        width: '100%',
        ajax: {
            url: "ajax/student.php",
            type: 'POST',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                    page: params.page,
                    country: $('#country').val(),
                    act: "findState"
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
    }).on('select2:opening', function(){
        let country = $('#country').val();
        if(country === null) {
            alert('Select Country First.');
            return false;
        }
    }).on('select2:select', function(){
        $('#city').val(null).trigger('change.select2');
    });

    $("#city").select2({
        placeholder: "Search City...",
        width: '100%',
        ajax: {
            url: "ajax/student.php",
            type: 'POST',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                    page: params.page,
                    state: $('#state').val(),
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
    }).on('select2:opening', function(){
        let state = $('#state').val();
        if(state === null) {
            alert('Select State First.');
            return false;
        }
    });

    $("#course_id").select2({
        placeholder: "Search Course...",
        width: '100%',
        ajax: {
            url: "ajax/student.php",
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

    $("#center_id").select2({
        placeholder: "Search Center...",
        width: '100%',
        ajax: {
            url: "ajax/student.php",
            type: 'POST',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                    page: params.page,
                    act: "findCenter"
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

    $('#dob').datepicker({
        format: 'yyyy-mm-dd',
        endDate: '+0d',
        orientation: 'bottom auto',
        autoclose: true,
        todayHighlight: true
    });

    $(document).on('submit', '#studentAddForm', function(){
        var formData = new FormData(this);
        formData.append('act', 'studentAddSubmit');
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
                $('#studentAddFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#studentAddFormBtn').html('Save').attr('disabled', false);
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
            url: 'ajax/student.php',
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