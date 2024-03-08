$(function(){

    $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "ajax": {
            "url": "ajax/course_category.php",
            "data": {
                "act": "course_category_list"
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
            "data": "cc_code"
        }, {
            "data": "cc_name"
        }, {
            "data": "created_by"
        }, {
            "data": "updated_by"
        }, {
            "data": "cc_status",
            "render": function(data, type, row, cell) {
                if(data == 'A') {
                    return '<label class="badge bg-green">Active</label>';
                } else {
                    return '<label class="badge bg-red">Inactive</label>';
                }
            }
        }, {
            "data": "tot_course",
            render: function(data) {
                return '<span class="badge bg-blue">'+ data +'</span>';
            }
        }, {
            "data": null,
            "render": function(data, type, row, cell) {
                let manage = ''; 
                manage += '<div class="btn-group" style="display: flex;">';
                manage += '<a href="course_category.php?act=edit&id=' + row['id'] + '" class="btn btn-primary btn-xs edit"><i class="fa fa-edit"></i></a>';
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

    $(document).on('submit', '#courseCategoryEditForm', function(){
        
        $.ajax({
            url: 'ajax/course_category.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'centerEditSubmit'}),
            beforeSend: function() {
                $('#courseCategoryEditFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#courseCategoryEditFormBtn').html('Save').attr('disabled', false);
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