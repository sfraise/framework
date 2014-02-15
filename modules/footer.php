<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/25/14
 * Time: 5:10 PM
 */
?>
<div class="footerwrapper">
    <?php if($user->exists() && $acceptcookies == 1) { ?>
    <div class="footer_cookie_notice">
        We use cookies to improve your experience. To disable using cookies on this site please <a href="#" id="footer_prevent_cookies">Click Here</a>.
    </div>
    <?php } ?>
    <div class="footer_tos">
        <a href="#" class="toslb">Terms of Service</a>
    </div>
</div>
<div style="clear:both;"></div>