<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/25/14
 * Time: 8:02 PM
 */
set_include_path('../../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$userid = Input::get('userid');
$newpass = Input::get('newpass');

if (Token::check(Token::generate())) {
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