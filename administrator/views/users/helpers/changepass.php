<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
<?php
/**
 * Created by PhpStorm.
 * User: Spencer
 * Date: 1/25/14
 * Time: 7:38 PM
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
    $user = new User($userid);

    // UPDATE THE DATABASE
    try {
        $user->update(array(
            'current_password' => $hashpass,
            'salt' => $salt
        ), $userid);

        echo 'The password has been updated';
    } catch (Exception $e) {
        die($e->getMessage());
    }
} else {
    echo 'The token is invalid';
}
?>