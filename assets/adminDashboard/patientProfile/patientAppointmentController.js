$(document).ready(function () {
    let section = $('body');
    getData();

    function getData() {
        section.LoadingOverlay("show");
        $('#table_my_appointment_list').DataTable().destroy();
        var tbl = $('#table_my_appointment_list').DataTable({
            "pageLength": 10,
            "searching": true,
            "scrollY": false,
            "ajax": {
                "url": ajax_my_appointment_list,
                "data": {},
                dataSrc: function (response) {
                    var data = [];
                    for (var i = 0, ien = response['payload']['dataSet'].length; i < ien; i++) {
                        var row = [];
                        row[0] = '<a type="button" title="Edit" target="_blank" href="'+patient_test_results+'?appointmentId='+ response['payload']['dataSet'][i].id +'">' + response['payload']['dataSet'][i].refNo + '</a>';
                        if (response['payload']['dataSet'][i].doctorName) {
                            row[1] = response['payload']['dataSet'][i].doctorName;
                        } else{
                            if(response['payload']['dataSet'][i].refDoctor)
                            {
                                row[1] = response['payload']['dataSet'][i].refDoctor;
                            }
                            else{
                                row[1] = '-';
                            }
                        }
                        row[2] = response['payload']['dataSet'][i].timeSlotName;
                        row[3] = response['payload']['dataSet'][i].amount;
                        row[4] = response['payload']['dataSet'][i].paymentStatus;
                        if(response['payload']['dataSet'][i].paymentMeta === 'PAYMENT_PENDING')
                        {
                            row[5] = '';
                            row[5] += '<button type="button" class="btn btn-payment make_payment" value="' + response['payload']['dataSet'][i].id + '" title="Make Payment">Payment</button>';
                            row[5] += '';
                        }
                        else {
                            if(response['payload']['dataSet'][i].paymentMeta === 'PAYMENT_DONE')
                            {
                                row[5] = ' <label class="badge badge-success">Payment Done</label>';
                            }
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
                $(row).find('td:eq(5)').addClass('text-center');
            },

        });
    }

    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 2500);


    $(document).on('click','.make_payment',function(){
        let id = $(this).val();
        window.location.href = "/myAppointments/list/redirect/to/payment?id=" + id;
    })

});