<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/25/14
 * Time: 8:02 PM
 */
session_start();

// INCLUDE INIT FILE
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

// GET VALUES
$token = Input::get('token');
$userid = Input::get('userid');
$newpass = Input::get('newpass');

if (Token::check($token)) {
    // GET SALT EXTENSIONS FROM LATEST RECORD
    $prefix = '';
    $suffix = '';
    $db = DB::getInstance();
    $saltdata = $db->get('salts', array('id', '!=', '0'));
    if($saltdata->count()) {
        // IF EXTENSIONS ARE SET COMBINE THEM WITH THE STRING
        $db->_saltdata = $saltdata->last();
        $prefix = $db->_saltdata->prefix;
        $suffix = $db->_saltdata->suffix;
    }

    // SALT AND HASH THE PASSWORD
    $salt = Hash::salt(32);
    $fullpass = $prefix . $newpass . $suffix;
    $hashpass = Hash::make($fullpass, $salt);
    $datetime = date('Y-m-d H:i:s');
echo $fullpass;
    $user = new userAccess();

    // UPDATE THE DATABASE
    try {
        $user->update(array(
            'current_password' => $hashpass,
            'salt' => $salt,
            'current_passdate' => $datetime,
            'reset_time' => $datetime
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