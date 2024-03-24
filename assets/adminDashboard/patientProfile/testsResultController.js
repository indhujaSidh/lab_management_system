$(document).ready(function () {
    let section = $('body');
    var appointmentId = $('#test-appointment-id').val();

    getData(appointmentId);
    function getData(appointmentId) {
        section.LoadingOverlay("show");
        $('#table_test_results').DataTable().destroy();
        let fileIconSrc = $('#file-icon').attr('src');
        var tbl = $('#table_test_results').DataTable({
            "pageLength": 10,
            "searching": true,
            "scrollY": false,
            "ajax": {
                "url": ajax_get_patient_test_result,
                "data": {
                    appointmentId : appointmentId,
                },
                dataSrc: function (response) {
                    console.log(response);
                    var data = [];
                    for (var i = 0, ien = response['payload']['dataSet'].length; i < ien; i++) {
                        var row = [];
                        row[0] = response['payload']['dataSet'][i].testName;
                        if(response['payload']['dataSet'][i].printedDate)
                        {
                            row[1] = response['payload']['dataSet'][i].printedDate.date.split(' ')[0];
                        }
                        else {
                            row[1] = ' <label class="badge badge-danger">Pending</label>';
                        }
                        if(response['payload']['dataSet'][i].reportFile)
                        {
                            row[2] = '<a type="button" title="download" class="download-file" href="'+download_patient_report_file+'?fileName='+ response['payload']['dataSet'][i].reportFile +'"><img src="' + fileIconSrc + '" alt="Download" class="download-icon" style="width: 20px"></a>';
                        }
                        else{
                            row[2] = '-'
                        }
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
                $(row).find('td:eq(2)').addClass('text-center');
                $(row).find('td:eq(1)').addClass('text-center');
            },

        });
    }

    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 2500);


});