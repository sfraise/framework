<?php
/**
 * Created by PhpStorm.
 * User: Spencer
 * Date: 1/27/14
 * Time: 2:33 PM
 */

// GET VALUES
$email = Input::get('email');
$token = Input::get('token');

$newuser = new User($email);
$newuserdata = $newuser->data();
$id = $newuserdata->id;
$salt = $newuserdata->salt;
$emailtoken = Hash::make($token, $salt);
$code = $newuserdata->verification_code;
$activationcode = Hash::make($code, $salt);
$datetime = date('Y-m-d H:i:s');

if ($code == 'active') {
    echo 'Your account is already active';
} else {
    if ($newuser && $emailtoken == $activationcode) {
        ?>
        <div class="breadcrumb">
            Activate
        </div>
        <?php
        try {
            $newuser->update(array(
                'active' => 1,
                'verification_code' => null,
                'verification_date' => $datetime
            ), $id);
            ?>
            <div class="message">
                Your account is now active, you can now log in.
            </div>
        <?php
        } catch (Exception $e) {
            die($e->getMessage());
        }
    } else {
        echo 'You\'re not authorized to view this page';
    }
}
?>