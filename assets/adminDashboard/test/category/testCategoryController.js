$(document).ready(function () {
    $('#table_category_list').DataTable({
        paging: true,
        ordering: false,
        info: true,
        searching: true,
        lengthChange: true,
        bInfo: true,
    });


    $(document).on('click', '.add-new-test-category', function () {
        $('#city-id').val('');
        viewDataFormTestCategory();
    });

    /**
     * function for view data insert form
     * @param id
     */
    function viewDataFormTestCategory(id = null) {
        clearFromModalTestCategory();
        $.get(ajax_test_category_form, {
            id: id,
        }, function (reply) {
            if (reply['status']) {
                $('#categoryEntryModal ').modal('show');
                $('#categoryName').val(reply['payload']['dataSet']['name']);
            } else {
                showAlert('Oops! Something went wrong. Please try again.')
            }
        });
    }


    function clearFromModalTestCategory() {
        $('#categoryName').val('');
        clearFromModalCityMessages();
    }

    function clearFromModalCityMessages() {
        $('#categoryEntryModal *').filter(':input').each(function () {
            $(this).next('.warning-msg').remove();
        });
    }

    $('#categoryEntryModal').on('hidden.bs.modal', function () {
        clearFromModalTestCategory();
    });


    $('#categoryEntryModal').on('click', '.data-save-btn', function () {
        clearFromModalTestCategory();
        let isFormValid = true;

        let name = $('#categoryName');
        if (name.val().length == 0) {
            name.after('<div class="warning-msg text-purple">Category name is required</div>');
            isFormValid = false;
        } else {
            name.next('.warning-msg').remove();
        }

        if (isFormValid) {
            alert('hi');
            $.post(ajax_test_category_form, {
                id: $('#city-id').val(),
                name: name.val(),
            }, function (reply) {
                if (reply['status']) {
                    $('#categoryEntryModal').modal('hide');
                    showSuccessAlert(reply['message'])
                } else {
                    showAlert(reply['message']);
                }
            });
        }
    });


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