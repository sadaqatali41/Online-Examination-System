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

    bind_select2();

    $(document).on('click', '#addNewRow', () => {
        var clonedDiv = $("#centerTbl tr:last");
        clonedDiv.find(".center_city").select2("destroy").removeAttr('data-live-search').removeAttr('data-select2-id').removeAttr('aria-hidden').removeAttr('tabindex');
        var NewDiv = clonedDiv.clone();
        NewDiv.find(".center_city").val(null).trigger("change.select2");
        NewDiv.find(".center_name").val("");
        NewDiv.find(".center_code").val("");
        NewDiv.find(".address").val("");
        NewDiv.find(".center_id").val("");
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

        $(".center_city").select2({
            placeholder: "Search City...",
            width: '100%',
            ajax: {
                url: "ajax/center.php",
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
    }

    $(document).on('submit', '#centerAddForm', function(){
        
        $.ajax({
            url: 'ajax/center.php',
            type: 'POST',
            data: $(this).serialize() + '&' + $.param({'act': 'centerSubmit'}),
            async: true,
            cache: false,
            contentType: "application/x-www-form-urlencoded",
            processData: false,
            beforeSend: function() {
                $('#centerAddFormBtn').html('Loading...').attr('disabled', true);
            },
            success: function(res) {
                let data = JSON.parse(res);
                if(data.status === 'error') {
                    let errors = '';
                    $.each(data.error, function(i, value){
                        errors += value + "\r\n";
                    });
                    alert(errors);
                    $('#centerAddFormBtn').html('Save').attr('disabled', false);
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