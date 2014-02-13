<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/24/14
 * Time: 9:39 PM
 */

// CHECK PERMISSION TO ACCESS
if ($user->hasPermission('manager')) {
    // GET CURRENT VERIFICATION OPTION (O or 1)
    $verify = $siteinfo->verify;
    if ($verify == 0) {
        $ischecked = '';
        $vedisplay = 'display:none';
    } elseif ($verify == 1) {
        $ischecked = "<img src=\"/images/checkmark.png\" alt=\"Selected\" />";
        $vedisplay = 'display:block';
    } else {
        $ischecked = 'Error';
    }

    // GET CURRENT EMAIL
    $verifyemail = $siteinfo->verify_email;
    if (!$verifyemail) {
        $verifyemail = "
        [firstname] [lastname],<br /><br />
        Thank you for joining [sitename]!<br /><br />
        Please click the link below to activate your account:<br />
        [activationlink]
        ";
    }

    // GET ALL SALT PREFIX AND SUFFIX RECORDS
    $saltData = DB::getInstance();
    try {
        $salts = array();
        $saltinfo = $saltData->get('salts', array('id', '!=', '0'));
        if (!$saltinfo->count()) {
            $salts[] = 'No Salts Exist';
        } else {
            foreach ($saltinfo->results() as $salt) {
                $newprefix = $salt->prefix;
                $newsuffix = $salt->suffix;
                $newfromdate = date('M, dS Y g:ia', strtotime($salt->from_datetime));
                $newtodate = $salt->to_datetime;
                if (!$newtodate) {
                    $newtodate = 'Current';
                } else {
                    $newtodate = date('M, dS Y g:ia', strtotime($salt->to_datetime));
                }
                $salts[] = "Prefix: " . $newprefix . " Suffix: " . $newsuffix . " From Date: " . $newfromdate . " To Date: " . $newtodate . "";
            }
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }

    echo 'Configuration';
    ?>

    <div class="admin_config">
        <div class="admin_config_verify">
            <div class="admin_config_verify_label">
                Require Registration Verification
            </div>
            <div id="admin_config_verify_button">
                <?php echo $ischecked; ?>
            </div>
            <input type="hidden" id="admin_config_verify_checked" value="<?php echo $verify; ?>"/>

            <div style="clear:both;"></div>
            <div id="admin_config_verify_email" style="<?php echo $vedisplay; ?>;">
                <textarea id="admin_config_verify_email_textarea" class="wysiwyg" placeholder="Verification Email">
                    <?php echo $verifyemail; ?>
                </textarea>

                <div class="admin_config_verify_email_note">
                    * Activation link will be embeded at the end of the email
                </div>
                <div id="admin_config_verify_email_submit">
                    Submit
                </div>
                <div style="clear:both;"></div>
                <div id="admin_config_verify_message"></div>
            </div>
        </div>
        <div class="admin_salt">
            <div id="admin_salt_existing">
                <?php echo implode('<br />', $salts); ?>
            </div>
            <div class="admin_salt_add_label">
                Add new salt extension:
            </div>
            <div class="admin_salt_add_inputs">
                <input type="text" id="admin_salt_prefix_input" value="" placeholder="Salt Prefix"/> <input type="text"
                                                                                                            id="admin_salt_suffix_input"
                                                                                                            value=""
                                                                                                            placeholder="Salt Suffix"/>
                <a href="#" class="submit_button" id="admin_salt_ext_submit">Submit</a> <span
                    id="admin_salt_ext_message"></span>
            </div>
        </div>
    </div>
<?php
} else {
    echo 'You\'re not authorized to access this section';
}
?>