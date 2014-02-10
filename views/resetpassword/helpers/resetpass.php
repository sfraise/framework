<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/26/14
 * Time: 3:25 PM
 */
set_include_path('../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$userid = Input::get('userid');
$newpass = Input::get('newpass');

// MAKE SURE THE PAGE TOKEN IS VALID
if (Token::check(Token::generate())) {
    try {
        // SET THE SALT AND HASH
        $salt = Hash::salt(32);
        $hashpass = Hash::make($newpass, $salt);

        // UPDATE THE USER'S PASSWORD AND REMOVE THE RESET TOKEN AND RESET TIME
        $userdata = DB::getInstance();
        $userdata->query("UPDATE users SET password = '$hashpass', salt = '$salt', reset = NULL, reset_time = NULL WHERE id = $userid");

        echo 'The password has been updated';
    } catch (Exception $e) {
        die($e->getMessage());
    }
} else {
    echo 'The token is invalid';
}
?>