<script type="text/javascript" src="js/main.js"></script>
<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/20/14
 * Time: 8:02 PM
 */
session_start();

// INCLUDE INIT FILE
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

// GET VALUES
$token = escape($_POST['token']);
$myid = escape($_POST['myid']);
$field = escape($_POST['field']);
$newvalue = escape($_POST['newvalue']);

if (Token::check($token)) {
    $user = new userDetails($myid);

    try {
        $user->update(array(
            $field => "$newvalue"
        ), $myid);
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

$user = new userDetails($myid);

echo $user->userFields($myid, $field, 'text');
?>
<script type="text/javascript">
    // RESET THE PARENT PAGE TOKEN IN ORDER TO VALIDATE ON NEXT TRY
    $('#token').val('<?php echo Token::generate(); ?>');
</script>