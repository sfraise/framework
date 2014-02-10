/**
 * Created by Spencer on 1/15/14.
 */
$(document).ready(function () {
    // REGISTER
    $('#register_submit').click(function() {
        var register_email = $('#register_email').val();
        var register_firstname = $('#register_firstname').val();
        var register_lastname = $('#register_lastname').val();
        var register_password = $('#register_password').val();
        var register_password_again = $('#register_password_again').val();

        $('#register_message').html('<img id="ajaxloading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: '/administrator/helpers/submits/register.php',
            type: 'POST',
            data: {register_email: register_email, register_firstname: register_firstname, register_lastname: register_lastname, register_password: register_password, register_password_again: register_password_again},
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
        var login_email = $('#login_email').val();
        var login_password = $('#login_password').val();
        var login_remember = $('#login_remember').val();

        $('#login_message').html('<img id="ajaxloading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: '/administrator/helpers/submits/login.php',
            type: 'POST',
            data: {login_email: login_email, login_password: login_password, login_remember: login_remember},
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
        var forgotpass_email = $('#forgotpass_email').val();

        $('#forgotpass_message').html('<img id="ajaxloading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: '/administrator/helpers/submits/forgotpass.php',
            type: 'POST',
            data: {forgotpass_email: forgotpass_email},
            success: function (data) {
                $('#forgotpass_message').html(data);
            },
            error: function (errorThrown) {
                $('#forgotpass_message').html(errorThrown);
            }
        });
        return false;
    });

    /*** CONFIG SECTION ***/
    // SET VERIFICATION OPTION
    $('#admin_config_verify_button').click(function() {
        var checked = $('#admin_config_verify_checked').val();

        $('#admin_config_veirfy_button').html('<img id="ajaxloading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: '/administrator/views/config/helpers/verify.php',
            type: 'POST',
            data: {},
            success: function (data) {
                $('#admin_config_verify_button').html(data);
                if(!checked || checked == 0) {
                    $('#admin_config_verify_email').show();
                    $('#admin_config_verify_checked').val('1');
                } else if(checked == 1) {
                    $('#admin_config_verify_message').html('');
                    $('#admin_config_verify_email').hide();
                    $('#admin_config_verify_checked').val('0');
                }
            },
            error: function (errorThrown) {
                $('#admin_config_verify_button').html(errorThrown);
            }
        });
        return false;
    });

    // UPDATE VERIFICATION EMAIL
    $('#admin_config_verify_email_submit').click(function() {
        tinyMCE.get("admin_config_verify_email_textarea").save();
        var email = $('#admin_config_verify_email_textarea').val();

        $('#admin_config_verify_message').html('<img id="ajaxloading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: '/administrator/views/config/helpers/verifyemail.php',
            type: 'POST',
            data: {email: email},
            success: function (data) {
                $('#admin_config_verify_message').html(data);
            },
            error: function (errorThrown) {
                $('#admin_config_verify_message').html(errorThrown);
            }
        });
        return false;
    });

    // UPDATE SALT PREFIX
    $('#admin_salt_ext_submit').click(function() {
        var prefix = $('#admin_salt_prefix_input').val();
        var suffix = $('#admin_salt_suffix_input').val();

        $('#admin_salt_ext_message').html('<img id="ajaxloading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: '/administrator/views/config/helpers/saltext.php',
            type: 'POST',
            data: {prefix: prefix, suffix: suffix},
            success: function (data) {
                $('#admin_salt_ext_message').html(data);
                $('#admin_salt_prefix_input').val('');
                $('#admin_salt_suffix_input').val('');
            },
            error: function (errorThrown) {
                $('#admin_salt_ext_message').html(errorThrown);
            }
        });
        return false;
    });

    /*** SITE INFO SECTION ***/
    // LOGO UPLOAD TOGGLE
    $('#update_logo_upload_area').click(function () {
        $('#updatelogouploadform').show();
        $('#update_logo_upload_area').hide();
    });
    $('#update_logo_upload_cancel').click(function () {
        $('#updatelogouploadform').hide();
        $('#update_logo_upload_area').show();
    });

    // EDIT SITE INFO
    $('#editsite_submit').click(function () {
        var editsitename = $('#editsitename').val();
        var editsitedesc = $('#editsitedesc').val();

        $('#editsite_message').html('<img id="ajaxloading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: '/administrator/views/sitedata/helpers/updatesitedata.php',
            type: 'POST',
            data: {editsitename: editsitename, editsitedesc: editsitedesc},
            success: function (data) {
                $('#editsite_message').html(data);
            },
            error: function (errorThrown) {
                $('#editsite_message').html(errorThrown);
            }
        });
        return false;
    });

    /*** MANAGE MEMBERS ***/
    // MANAGE MEMBERS SHOW PROMOTE/DEMOTE SELECT BOX
    $('a[id^=amu_prodem_]').click(function() {
        var userid = $(this).attr('rel');

        $('#amu_promote_select_wrapper_' + userid).show();
    });

    // MANAGE MEMBERS HIDE PROMOTE/DEMOTE SELECT BOX
    $('a[id^=amu_promote_select_close_]').click(function() {
        var userid = $(this).attr('rel');

        $('#amu_promote_select_wrapper_' + userid).hide();
    });

    // MANAGE MEMBERS SHOW CHANGE PASSWORD
    $('a[id^=amu_changepass_]').click(function() {
        var userid = $(this).attr('rel');

        $('#amu_changepass_wrapper_' + userid).show();
    });

    // MANAGE MEMBERS HIDE CHANGE PASSWORD
    $('a[id^=amu_changepass_close_]').click(function() {
        var userid = $(this).attr('rel');

        $('#amu_changepass_wrapper_' + userid).hide();
    });

    // MANAGE MEMBERS PROMOTE/DEMOTE
    $('select[id^=amu_promote_select_]').on('change', function () {
        var userid = $(this).attr('rel');
        var type = $(this).val();
        if (type == 2) {
            var usertype = 'Super Administrator';
        } else if(type == 3) {
            var usertype = 'Administrator';
        } else if(type == 4) {
            var usertype = 'Moderator';
        } else {
            var usertype = 'Registered';
        }

        $('#amu_promote_select_wrapper_' + userid).html('<img id="amu_promote_loading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: '/administrator/views/users/helpers/promoteuser.php',
            type: 'POST',
            data: {userid: userid, type: type},
            success: function (data) {
                $('#amu_promote_select_wrapper_' + userid).html(data);
            },
            error: function (errorThrown) {
                $('#amu_promote_select_wrapper_' + userid).html(errorThrown);
            }
        });
        return false;
    });

    // MANAGE MEMBERS CHANGE PASSWORD
    $('a[id^=amu_changepass_submit_]').click(function () {
        var userid = $(this).attr('rel');
        var newpass = $('#amu_changepass_input_' + userid).val();

        $('#amu_changepass_message_' + userid).html('<img id="amu_promote_loading" src="/images/loading/loading35.gif" alt="Loading" title="Loading" />');
        $.ajax({
            url: '/administrator/views/users/helpers/changepass.php',
            type: 'POST',
            data: {userid: userid, newpass: newpass},
            success: function (data) {
                $('#amu_changepass_message_' + userid).html(data);
                $('#amu_changepass_input_' + userid).val('');
            },
            error: function (errorThrown) {
                $('#amu_changepass_message_' + userid).html(errorThrown);
            }
        });
        return false;
    });
});