<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/25/14
 * Time: 5:10 PM
 */
?>
<div class="footerwrapper">
    <div id="footer_cookie_notice">
        <?php if($user->exists() && $acceptcookies == 1) { ?>
            We use cookies to improve your experience. To disable using cookies on this site please click here <a href="#" id="footer_prevent_cookies">DISABLE COOKIES</a>.
        <?php } elseif($user->exists() && $acceptcookies == 0) { ?>
            We use cookies to improve your experience. To enable using cookies on this site please click here <a href="#" id="footer_enable_cookies">ENABLE COOKIES</a>.
        <?php } ?>
    </div>
    <div class="footer_tos">
        <a href="#" class="toslb">Terms of Service</a>
    </div>
</div>
<div style="clear:both;"></div>