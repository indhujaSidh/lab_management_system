$(document).ready(function () {
    let section = $('body');
    getData();

    function getData() {
        section.LoadingOverlay("show");
        $('#table_appointment_list').DataTable().destroy();
        var tbl = $('#table_appointment_list').DataTable({
            "pageLength": 10,
            "searching": true,
            "scrollY": false,
            "ajax": {
                "url": ajax_get_appointment_list,
                "data": {},
                dataSrc: function (response) {
                    var data = [];
                    for (var i = 0, ien = response['payload']['dataSet'].length; i < ien; i++) {
                        var row = [];
                        row[0] = response['payload']['dataSet'][i].firstName;
                        row[1] = response['payload']['dataSet'][i].contactNo;
                        row[2] = response['payload']['dataSet'][i].refNo;
                        row[3] = response['payload']['dataSet'][i].timeSlotName;
                        if (response['payload']['dataSet'][i].doctorName) {
                            row[4] = response['payload']['dataSet'][i].doctorName;
                        } else{
                            if(response['payload']['dataSet'][i].refDoctor)
                            {
                                row[4] = response['payload']['dataSet'][i].refDoctor;
                            }
                            else{
                                row[4] = '-';
                            }
                        }
                        row[5] = response['payload']['dataSet'][i].amount;
                        row[6] = response['payload']['dataSet'][i].paymentStatus;
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
                {"orderable": true},
                {"orderable": true},
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
            },

        });
    }

    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 2500);

});