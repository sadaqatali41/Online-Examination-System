$(function(){

    $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "ajax": {
            "url": "ajax/center.php",
            "data": {
                "act": "center_list"
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
            "data": "center_name"
        }, {
            "data": "center_code"
        }, {
            "data": "center_city"
        }, {
            "data": "center_address"
        }, {
            "data": "center_status",
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
                manage += '<a href="center.php?act=edit&id=' + row['id'] + '" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>';
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
});