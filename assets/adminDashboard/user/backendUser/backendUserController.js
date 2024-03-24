$(document).ready(function () {
    $('#table_backend_users_list').DataTable({
        paging: true,
        ordering: false,
        info: true,
        searching: true,
        lengthChange: true,
        bInfo: true,
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
                $.post(ajax_change_backend_user_active_status, {
                    id: id,
                    isActive: isActive,
                }, function (result) {
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


    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 2500);

});