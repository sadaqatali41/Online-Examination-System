$(function(){

    $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        'destroy': true,
        "ajax": {
            "url": "ajax/about_us.php",
            "data": {
                "act": "about_us_list"
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
            "data": "name"
        }, {
            "data": "email"
        }, {
            "data": "mobile_no"
        },  {
            "data": "course_name"
        },  {
            "data": "branch_name"
        },  {
            "data": "institute_name"
        },  {
            "data": "city"
        },  {
            "data": "blog_website",
            "render": function(data) {
                if(data !== null) {
                    return '<a href="' + data + '" target="_blank">Click Me</a>';
                } else {
                    return 'N/A';
                }
            }
        },  {
            "data": "profile_pic",
            "render": function(data) {
                if(data !== null) {
                    return '<a target="_blank" href="about-us/'+ data +'"><img src="about-us/'+ data +'" class="img-md img-circle"></a>';
                }
                else {
                    return 'N/A';
                }
            }
        }, {
            "data": "status",
            "render": function(data, type, row, cell) {
                if(data == 'A') {
                    return '<label class="badge bg-green">Active</label>';
                } else {
                    return '<label class="badge bg-red">Inactive</label>';
                }
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
});