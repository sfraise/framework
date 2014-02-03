<script type="text/javascript" src="/js/image_upload/scripts/ajaxupload.js"></script>
<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/14/14
 * Time: 10:13 PM
 */

echo 'Edit Site Data<br /><br />';
?>

<!-- USER IMAGE/UPLOAD FORM -->
<div id="update_logo_wrapper">
    <img class="update_logo" src="<?php echo $logo; ?>" alt="Site Logo"/>

        <div id="updatelogouploadform" style="display:none;position:absolute;">
            <?php $newrelpath = "" . $_SERVER['DOCUMENT_ROOT'] . "/images/logo/"; ?>
            <form action="/js/image_upload/scripts/logoupload.php" method="POST"
                  name="unobtrusive" id="unobtrusive" enctype="multipart/form-data">
                <input type="hidden" name="maxSize" value="9999999999"/>
                <input type="hidden" name="maxW" value="250"/>
                <input type="hidden" name="fullPath" value="/images/logo/"/>
                <input type="hidden" name="relPath" value="<?php echo $newrelpath; ?>"/>
                <input type="hidden" name="colorR" value="255"/>
                <input type="hidden" name="colorG" value="255"/>
                <input type="hidden" name="colorB" value="255"/>
                <input type="hidden" name="maxH" value="250"/>
                <input type="hidden" name="filename" value="filename"/>
                <input type="hidden" name="sitelogo" value="<?php echo $logo; ?>"/>

                    <span class="update_logo_upload_buttons"><label for="filename"><img id="update_logo_upload_submit"
                                                                                        src="/js/image_upload/images/upload.png"
                                                                                        alt="Upload" title="Upload"/></label><input
                            type="file" name="filename" id="filename" class="updatelogoinput" value="filename"
                            onchange="ajaxUpload(this.form,'/js/image_upload/scripts/logoupload.php?filename=filename&amp;maxSize=9999999999&amp;maxW=250&amp;fullPath=images&amp;relPath=<?php echo $newrelpath; ?>&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=250&amp;profileimage=<?php echo $logo; ?>&amp;myid=<?php echo $myid; ?>&amp;token=<?php echo Token::generate(); ?>','update_logo_upload_area','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'/images/loading/loading2.gif\' border=\'0\' /&gt;','&lt;img src=\'/js/image_upload/images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.');$('#updatelogouploadform').hide(); return false;"/><img
                            id="update_logo_upload_cancel" src="/js/image_upload/images/cancel.png" alt="Cancel"
                            title="Cancel"/>
                    <p style="clear:both;"></p></span>
            </form>
        </div>
        <div id="update_logo_upload_area">
            Update Image
        </div>
</div>

<input type="text" id="editsitename" value="<?php echo $sitename; ?>" placeholder="Site Name" /><br />
<textarea id="editsitedesc" cols="30" placeholder="Site Description"><?php echo $sitedescription; ?></textarea><br /><br />
<?php echo $updatesiteinfo; ?>
<div id="editsite_submit" class="submit_button">Submit</div><br /><br />
<div id="editsite_message"></div>