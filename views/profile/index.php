<script type="text/javascript" src="/js/image_upload/scripts/ajaxupload.js"></script>
<?php
if (!$email = Input::get('user')) {
    Redirect::to('index.php');
} else {
    $user = new userDetails($email);

    if (!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
    }

    // DEFINE PROFILE OWNER'S USERID
    $userid = $data->id;

    // FORMAT THE MEMBER SINCE DATE
    $membersince = date('F jS, Y', strtotime($data->regdatetime));

    // GET THE USER'S IMAGE PATH
    $userimage = $data->image;
    if (!$userimage) {
        $userimage = '/images/user_images/default.jpg';
    }
    ?>

    <div class="userprofile">
        <h3><?php echo escape($data->email); ?></h3>

        <div>Member since: <?php echo $membersince; ?></div>

        <div>User Type: <?php echo $usertype; ?></div>

        <?php if($userid == $myid) { ?>
            <a id="profile_change_password" href="#">Change Password</a>
            <div class="profile_change_password_wrapper">
                <input type="password" id="profile_change_password_input" value="" placeholder="New Password" /> <a id="profile_change_password_submit" href="#">Submit</a> - <a id="profile_change_password_close" href="#">Cancel</a>
                <div id="profile_change_password_message"></div>
            </div>
        <?php } ?><br/><br/>

        <div class="userprofileleft">
            <!-- USER IMAGE/UPLOAD FORM -->
            <div id="profile_image_wrapper">
                <img class="profile_image" src="<?php echo $userimage; ?>" alt="Profile Image"/>

                <!-- SHOW IMAGE UPLOAD OPTION IF USER'S PROFILE -->
                <?php if ($userid == $myid) { ?>
                    <div id="profileimguploadform" style="display:none;position:absolute;">
                        <?php $newrelpath = "" . $_SERVER['DOCUMENT_ROOT'] . "/images/user_images/" . $myid . "/"; ?>
                        <form action="/js/image_upload/scripts/profileupload.php" method="POST"
                              name="unobtrusive" id="unobtrusive" enctype="multipart/form-data">
                            <input type="hidden" name="maxSize" value="9999999999"/>
                            <input type="hidden" name="maxW" value="250"/>
                            <input type="hidden" name="fullPath" value="/images/user_images/<?php echo $myid; ?>/"/>
                            <input type="hidden" name="relPath" value="<?php echo $newrelpath; ?>"/>
                            <input type="hidden" name="colorR" value="255"/>
                            <input type="hidden" name="colorG" value="255"/>
                            <input type="hidden" name="colorB" value="255"/>
                            <input type="hidden" name="maxH" value="250"/>
                            <input type="hidden" name="filename" value="filename"/>
                            <input type="hidden" name="profileimage" value="<?php echo $userimage; ?>"/>

                    <span class="profile_img_upload_buttons"><label for="filename"><img id="profile_img_upload_submit"
                                                                                        src="/js/image_upload/images/upload.png"
                                                                                        alt="Upload"
                                                                                        title="Upload"/></label><input
                            type="file" name="filename" id="filename" class="profileimginput" value="filename"
                            onchange="ajaxUpload(this.form,'/js/image_upload/scripts/profileupload.php?filename=filename&amp;maxSize=9999999999&amp;maxW=250&amp;fullPath=images/user_images/<?php echo $myid; ?>&amp;relPath=<?php echo $newrelpath; ?>&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=250&amp;profileimage=<?php echo $userimage; ?>&amp;myid=<?php echo $myid; ?>&amp;token=<?php echo Token::generate(); ?>','profile_img_upload_area','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'/images/loading/loading2.gif\' border=\'0\' /&gt;','&lt;img src=\'/js/image_upload/images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.');$('#profileimguploadform').hide(); return false;"/><img
                            id="profile_img_upload_cancel" src="/js/image_upload/images/cancel.png" alt="Cancel"
                            title="Cancel"/>
                    <p style="clear:both;"></p></span>
                        </form>
                    </div>
                    <div id="profile_img_upload_area">
                        Update Image
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="userprofileright">
            <div class="profile_fields">
                <div class="profile_field_wrapper_title">
                    First Name:
                </div>
                <div id="profile_field_wrapper_first_name">
                    <?php echo $user->userFields($myid, 'first_name', 'text'); ?>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="profile_fields">
                <div class="profile_field_wrapper_title">
                    Last Name:
                </div>
                <div id="profile_field_wrapper_last_name">
                    <?php echo $user->userFields($myid, 'last_name', 'text'); ?>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="profile_fields">
                <div class="profile_field_wrapper_title">
                    City:
                </div>
                <div id="profile_field_wrapper_city">
                    <?php echo $user->userFields($myid, 'city', 'text'); ?>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="profile_fields">
                <div class="profile_field_wrapper_title">
                    State:
                </div>
                <div id="profile_field_wrapper_state">
                    <?php echo $user->userFields($myid, 'state', 'text'); ?>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="profile_fields">
                <div class="profile_field_wrapper_title">
                    Country:
                </div>
                <div id="profile_field_wrapper_country">
                    <?php echo $user->userFields($myid, 'country', 'text'); ?>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="profile_fields">
                <div class="profile_field_wrapper_title">
                    Gender:
                </div>
                <div id="profile_field_wrapper_gender">
                    <?php echo $user->userFields($myid, 'gender', 'text'); ?>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="profile_fields">
                <div class="profile_field_wrapper_title">
                    Date of Birth:
                </div>
                <div id="profile_field_wrapper_dob">
                    <?php echo $user->userFields($myid, 'birth', 'text'); ?>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
<?php
}
?>