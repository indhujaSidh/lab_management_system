$(document).ready(function () {
    $(document).on('click', '.reset-password', function () {
        $('#password_reset_oldPassword').val('');
        $('#password_reset_password_first').val('');
        $('#password_reset_password_second').val('');
        $('.password-reset-success-message').text('');
        $('.password-reset-warning-message').text('');
        $('#dataModalPasswordReset').modal('show');
    });

    $(document).on('click', '.btn-close-password-reset-modal', function () {
        $('#dataModalPasswordReset').modal('hide');
    })


    $(document).on('click', '.save-password-btn', function () {
        $('.password-reset-warning-message').text('');
        var oldPassword = $('#password_reset_oldPassword').val();
        var firstPass = $('#password_reset_password_first').val();
        var secondPass = $('#password_reset_password_second').val();

        if (!(oldPassword)) {
            $('.password-reset-warning-message').text('Old Password is required')
        } else {
            if (!(firstPass)) {
                $('.password-reset-warning-message').text('New Password is required')
            } else {
                if (firstPass !== secondPass) {
                    $('.password-reset-warning-message').text('The password fields must match')

                } else {
                    if (oldPassword && firstPass && secondPass) {
                        var userId = $('.backend_user_id').val();
                        if (userId) {
                            $.ajax({
                                url: ajax_backend_user_reset_password,
                                type: "POST",
                                data: {
                                    id: userId,
                                    old: oldPassword,
                                    pass: firstPass,
                                },
                                success: function (result) {
                                    if(result.status) {
                                        $('#password_reset_oldPassword').val('');
                                        $('#password_reset_password_first').val('');
                                        $('#password_reset_password_second').val('');
                                        $('.password-reset-success-message').text(result.message)
                                    } else {
                                        $('.password-reset-warning-message').text(result.message)
                                    }

                                }
                            });
                        }
                    }
                }
            }
        }

    });

});