$(function(){

    var course_id = '';
    var center_id = '';
    var status = '';

    student_list(course_id, center_id, status);

    function student_list(course_id, center_id, status) {

        $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            'serverMethod': 'post',
            'destroy': true,
            "ajax": {
                "url": "ajax/student.php",
                "data": {
                    "act": "student_list",
                    course_id,
                    center_id, 
                    status
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
                "data": "full_name"
            },  {
                "data": "email"
            },  {
                "data": "password"
            },  {
                "data": "phone"
            },  {
                "data": "gender",
                "render": function(data) {
                    if(data === 'M') {
                        return 'Male';
                    } else {
                        return 'Female';
                    }
                }
            },  {
                "data": "course_name"
            },  {
                "data": "center_name"
            },  {
                "data": "p_address"
            },  {
                "data": "address"
            },  {
                "data": "avatar",
                "render": function(data, type, row, cellIndex) {
                    if(data !== null) {
                        return '<a target="_blank" href="../students/' + row.id + '/' + data + '"><img src="../students/' + row.id + '/' + data + '" class="img-md img-circle"></a>';
                    } else {
                        return 'N/A';
                    }
                }
            }, {
                "data": "status",
                "render": function(data, type, row, cell) {
                    if(data == 1) {
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
        var center_id = $('#center_id').val();
        var status = $('#status').val();

        student_list(course_id, center_id, status);
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
    
    $(document).on('submit', '#studentEditForm', function(){
        var formData = new FormData(this);
        formData.append('act', 'studentEditSubmit');
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
                $('#studentEditFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#studentEditFormBtn').html('Save').attr('disabled', false);
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