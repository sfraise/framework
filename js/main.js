/**
 * Created by Spencer on 1/13/14.
 */
$(document).ready(function() {
    // REGISTER
    $('#register_submit').click(function() {
        var register_token = $('#token').val();
        var register_email = $('#register_email').val();
        var register_firstname = $('#register_firstname').val();
        var register_lastname = $('#register_lastname').val();
        var register_password = $('#register_password').val();
        var register_password_again = $('#register_password_again').val();

        $('#register_message').html('<img id="ajaxloading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: 'helpers/submits/register.php',
            type: 'POST',
            data: {register_token: register_token, register_email: register_email, register_firstname: register_firstname, register_lastname: register_lastname, register_password: register_password, register_password_again: register_password_again},
            success: function (data) {
                $('#register_message').html(data);
            },
            error: function (errorThrown) {
                $('#register_message').html(errorThrown);
            }
        });
        return false;
    });

    // LOGIN
    $('#login_submit').click(function() {
        var login_token = $('#token').val();
        var login_email = $('#login_email').val();
        var login_password = $('#login_password').val();
        var login_remember = $('#login_remember').val();

        $('#login_message').html('<img id="ajaxloading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: 'helpers/submits/login.php',
            type: 'POST',
            data: {login_token: login_token, login_email: login_email, login_password: login_password, login_remember: login_remember},
            success: function (data) {
                $('#login_message').html(data);
            },
            error: function (errorThrown) {
                $('#login_message').html(errorThrown);
            }
        });
        return false;
    });

    // FORGOT PASSWORD
    $('#forgotpass_submit').click(function() {
        var forgotpass_token = $('#token').val();
        var forgotpass_email = $('#forgotpass_email').val();

        $('#forgotpass_message').html('<img id="ajaxloading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: 'helpers/submits/forgotpass.php',
            type: 'POST',
            data: {forgotpass_token: forgotpass_token, forgotpass_email: forgotpass_email},
            success: function (data) {
                $('#forgotpass_message').html(data);
            },
            error: function (errorThrown) {
                $('#forgotpass_message').html(errorThrown);
            }
        });
        return false;
    });

    // RESET PASSWORD
    $('#resetpass_submit').click(function () {
        var token = $('#token').val();
        var userid = $('#resetpass_input_userid').val();
        var newpass = $('#resetpass_input_newpass').val();
        if(!token) {
            $('#resetpass_message').html('The token is invalid!');
        } else if(!userid) {
            $('#resetpass_message').html('The user doesn\'t exist!');
        } else if(!newpass) {
            $('#resetpass_message').html('Please enter a new password!');
        } else {
            $('#resetpass_message').html('<img class="profile_change_password_loading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
            $.ajax({
                url: '/views/resetpassword/helpers/resetpass.php',
                type: 'POST',
                data: {token: token, userid: userid, newpass: newpass},
                success: function (data) {
                    $('#resetpass_input_newpass').val('');
                    $('#resetpass_message').html('');
                    $('.resetpass_input').html(data);
                },
                error: function (errorThrown) {
                    $('#resetpass_message').html(errorThrown);
                }
            });
        }
        return false;
    });

    /*** EDIT PROFILE ***/
    // SHOW CHANGE PASSWORD INPUT
    $('#profile_change_password').click(function() {
        $('.profile_change_password_wrapper').slideToggle();
    });

    // HIDE CHANGE PASSWORD INPUT
    $('#profile_change_password_close').click(function() {
        $('#profile_change_password_input').val('');
        $('.profile_change_password_wrapper').slideToggle();
        $('#profile_change_password_message').html('');
    });

    // PROFILE CHANGE PASSWORD
    $('#profile_change_password_submit').click(function () {
        var token = $('#token').val();
        var userid = $('#myid').val();
        var newpass = $('#profile_change_password_input').val();

        $('#profile_change_password_message').html('<img class="profile_change_password_loading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: '/views/profile/helpers/changepass.php',
            type: 'POST',
            data: {token: token, userid: userid, newpass: newpass},
            success: function (data) {
                $('#profile_change_password_message').html(data);
                $('#profile_change_password_input').val('');
            },
            error: function (errorThrown) {
                $('#profile_change_password_message').html(errorThrown);
            }
        });
        return false;
    });

    // IMAGE UPLOAD TOGGLE
    $('#profile_img_upload_area').click(function () {
        $('#profileimguploadform').show();
        $('#profile_img_upload_area').hide();
    });
    $('#profile_img_upload_cancel').click(function () {
        $('#profileimguploadform').hide();
        $('#profile_img_upload_area').show();
    });

    // SHOW FIELD INPUT
    $('span[id^=my_profile_field_]').on('click', function() {
        var field = $(this).attr('rel');

        $('#my_profile_field_' + field).hide();
        $('#profile_input_' + field).show();
    });

    // HIDE FIELD INPUT
    $('a[id^=cancel_profile_input_]').click(function() {
        var field = $(this).attr('rel');

        $('#profile_input_' + field).hide();
        $('#my_profile_field_' + field).show();
    });

    // SUBMIT FIELD CHANGE
    $('a[id^=submit_profile_input_]').click(function() {
        var token = $('#token').val();
        var myid = $('#myid').val();
        var field = $(this).attr('rel');
        var newvalue = $('#edit_profile_' + field).val();

        $('#profile_field_wrapper_' + field).html('<img id="ajaxloading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: '/views/profile/helpers/updateprofile.php',
            type: 'POST',
            data: {token: token, myid: myid, field: field, newvalue: newvalue},
            success: function (data) {
                $('#profile_field_wrapper_' + field).html(data);
            },
            error: function (errorThrown) {
                $('#profile_field_wrapper_' + field).html(errorThrown);
            }
        });
        return false;
    });
});