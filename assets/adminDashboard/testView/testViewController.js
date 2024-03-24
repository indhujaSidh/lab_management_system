$(document).ready(function () {
    let section = $('body');
    var appointmentId = $('#test-appointment-id').val();

    getData(appointmentId);
    function getData(appointmentId) {
        section.LoadingOverlay("show");
        $('#table_test_results_view_overall').DataTable().destroy();
        var tbl = $('#table_test_results_view_overall').DataTable({
            "pageLength": 10,
            "searching": true,
            "scrollY": false,
            "ajax": {
                "url": ajax_get_patient_test_result,
                "data": {

                },
                dataSrc: function (response) {
                    console.log(response);
                    var data = [];
                    for (var i = 0, ien = response['payload']['dataSet'].length; i < ien; i++) {
                        var row = [];
                        row[0] = response['payload']['dataSet'][i].refNo;
                        row[1] = response['payload']['dataSet'][i].contactNo;
                        row[2] = response['payload']['dataSet'][i].firstName+' '+response['payload']['dataSet'][i].lastName;
                        row[3] = response['payload']['dataSet'][i].testName;
                        if(response['payload']['dataSet'][i].printedDate)
                        {
                            row[4] = response['payload']['dataSet'][i].printedDate;
                        }
                        else {
                            row[4] = ' <label class="badge badge-danger">Pending</label>';
                        }
                        row[5] = '';
                        if(!(response['payload']['dataSet'][i].reportFile))
                        {
                            row[5] += '<a type="button" title="Edit" class="edit-test-results edit-icon" target="_blank" href="'+upload_test_report+'?id='+ response['payload']['dataSet'][i].id +'"><i class="fa fa-pen edit-icon" aria-hidden="true"></i></a>';
                        }
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