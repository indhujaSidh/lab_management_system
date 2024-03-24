$(document).ready(function () {
    let section = $('body');
    getData();

    function getData() {
        section.LoadingOverlay("show");
        $('#table_pre_request').DataTable().destroy();
        let fileIconSrc = $('#file-icon').attr('src');
        var tbl = $('#table_pre_request').DataTable({
            "pageLength": 10,
            "searching": true,
            "scrollY": false,
            "ajax": {
                "url": ajax_get_pre_request_info,
                "data": {},
                dataSrc: function (response) {
                    var data = [];
                    for (var i = 0, ien = response['payload']['dataSet'].length; i < ien; i++) {
                        var row = [];
                        row[0] = response['payload']['dataSet'][i].firstName ?? "-";
                        row[1] = response['payload']['dataSet'][i].contactNo ?? "-";
                        row[2] = response['payload']['dataSet'][i].doctorName ?? "-";
                        row[3] = response['payload']['dataSet'][i].stateName ?? "-";
                        if(response['payload']['dataSet'][i].fileName)
                        {
                            row[4] = '<a type="button" title="download" class="download-file" href="'+download_pre_request_file+'?fileName='+ response['payload']['dataSet'][i].fileName +'"><img src="' + fileIconSrc + '" alt="Download" class="download-icon" style="width: 20px"></a>';
                        }
                        else{
                            row[4] = '-'
                        }

                        row[5] = '';
                        row[5] += '<a type="button" title="Edit" class="edit-doctor edit-icon" href="'+edit_pre_request_info+'?id='+ response['payload']['dataSet'][i].id +'"><i class="fa fa-pen edit-icon" aria-hidden="true"></i></a>';
                        row[5] += '';
                        data.push(row);
                    }
                    return data;
                }
            },

            "order": [], //Initial no order.
            "columns": [
                {"orderable": true},
                {"orderable": true},
                {"orderable": true},
                {"orderable": true},
                {"orderable": true},
                {"orderable": false},

            ],
            "info": true,
            "lengthChange": true,
            "bInfo": true,
            "paging": true,
            "initComplete": function (settings, json) {
                section.LoadingOverlay("hide");
            },
            "createdRow": function (row, data, dataIndex) {
                $(row).attr('data-dataIndex', dataIndex).data('data-row-id', dataIndex);
                $(row).find('td:eq(4)').addClass('text-center');
                $(row).find('td:eq(5)').addClass('text-center');
            },

        });
    }


    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 2500);
});