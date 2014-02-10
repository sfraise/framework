<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/26/14
 * Time: 5:34 PM
 */
set_include_path('../../../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$token = Input::get('token');
$email = Input::get('email');

// MAKE SURE THE PAGE TOKEN IS VALID
if (Token::check($token)) {
    // UPDATE THE DATABASE
    try {
        $sitedata = DB::getInstance();
        $sitedata->query("UPDATE site_data SET verify_email = '$email' WHERE id = 1");
        echo 'The email has been updated';
    } catch(Exception $e) {
        die($e->getMessage());
    }
} else {
    echo 'The token is invalid';
}
?>
<script type="text/javascript">
    // RESET THE PARENT PAGE TOKEN IN ORDER TO VALIDATE ON NEXT TRY
    $('#token').val('<?php echo Token::generate(); ?>');
</script>