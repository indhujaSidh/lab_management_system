$(document).ready(function () {
    let section = $('body');
    getData();

    function getData() {
        section.LoadingOverlay("show");
        $('#table_test').DataTable().destroy();
        var tbl = $('#table_test').DataTable({
            "pageLength": 10,
            "searching": true,
            "scrollY": false,
            "ajax": {
                "url": ajax_get_tests_list,
                "data": {},
                dataSrc: function (response) {
                    var data = [];
                    for (var i = 0, ien = response['payload']['dataSet'].length; i < ien; i++) {
                        var row = [];
                        row[0] = response['payload']['dataSet'][i].name ?? "-";
                        row[1] = response['payload']['dataSet'][i].categoryName ?? "-";
                        row[2] = response['payload']['dataSet'][i].price;
                        row[3] = response['payload']['dataSet'][i].processingPeriod;
                        if (response['payload']['dataSet'][i].isActive) {
                            row[4] = '<div class="form-switch">\n' +
                                '<input class="is-active-btn form-check-input"' +
                                'type="checkbox"' +
                                'value="' + response['payload']['dataSet'][i].id + '" checked>\n' +
                                '</div>';
                        } else {
                            row[4] = '<div class="form-switch">\n' +
                                '<input class="is-active-btn form-check-input"' +
                                'type="checkbox"' +
                                'value="' + response['payload']['dataSet'][i].id + '">\n' +
                                '</div>';
                        }
                        row[5] = '';
                        row[5] += '<a type="button" title="Edit" class="edit-doctor edit-icon" href="'+edit_test_info+'?id='+ response['payload']['dataSet'][i].id +'"><i class="fa fa-pen edit-icon" aria-hidden="true"></i></a>';
                        row[5] += '&nbsp;&nbsp;';
                        row[5] += '<button type="button" class="delete-tests trash-icon" value="' + response['payload']['dataSet'][i].id + '" title="Delete"><i class="fa fa-trash delete-icon" aria-hidden="true"></i></button>';
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

    $(document).on('click', '.delete-tests', function () {
        if ($(this).val()) {
            let value = $(this).val();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: ajax_delete_test_records,
                        type: "POST",
                        data: {
                            id: $(this).val()
                        },
                        success: function (result) {
                            if (result === 'success') {
                                showSuccessAlert("Deleted Successfully");
                                getData();
                            }

                        }
                    });
                }
            });
        }
    });


    $(document).on('click', '.is-active-btn', function () {
        let isActive = 0;
        if ($(this).val()) {
            if ($(this).is(':checked')) {
                isActive = 1;
            }
            changeActiveStatus($(this).val(), isActive);
        }

    });

    /**
     * function for change the isActive status of the data record
     * @param id
     * @param isActive
     */
    function changeActiveStatus(id, isActive) {
        var text = '';
        if (isActive) {
            text = "Do you really want to activate?"
        } else {
            text = "Do you really want to deactivate?";
        }
        Swal.fire({
            title: 'Are you sure?',
            text: text,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                section.LoadingOverlay("show");
                $.post(ajax_change_test_isActive_status, {
                    id: id,
                    isActive: isActive,
                }, function (result) {
                    section.LoadingOverlay("hide");
                    if (result === 'success') {
                        showSuccessAlert('Saved successfully');
                    }
                    if (result === 'error') {
                        showAlert('Oops! Something went wrong. Please try again.');
                    }
                });
            }
            else{
                $('.is-active-btn[value="' + id + '"]').prop('checked', !isActive);
            }

        });
    }


    function showAlert(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            confirmButtonText: 'OK'
        });
    }

    function showSuccessAlert(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: message,
            showConfirmButton: false,
            timer: 2000
        });
    }

});
