<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php
/**
 * Created by PhpStorm.
 * User: Spencer
 * Date: 1/25/14
 * Time: 8:02 PM
 */
session_start();

// INCLUDE INIT FILE
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

// GET VALUES
$token = escape($_POST['token']);
$userid = escape($_POST['userid']);
$newpass = escape($_POST['newpass']);

if (Token::check($token)) {
    $salt = Hash::salt(32);
    $hashpass = Hash::make($newpass, $salt);
    $user = new User();

    // UPDATE THE DATABASE
    try {
        $user->update(array(
            'current_password' => $hashpass,
            'salt' => $salt
        ));

        echo 'The password has been updated';
    } catch (Exception $e) {
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