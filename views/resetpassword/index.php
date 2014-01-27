<?php

$email = escape($_GET['email']);
$token = escape($_GET['token']);

if (Token::check(Token::generate())) {
    $user = new User($email);
    $userdata = $user->data();
    $userid = $userdata->id;
    $salt = $userdata->salt;
    $resettoken = Hash::make($userdata->reset, $salt);
    $token = Hash::make($token, $salt);
    $resettime = strtotime($userdata->reset_time);
    $endtime = time();
    $starttime = strtotime('-1 day', $endtime);

    if ($token === $resettoken) {
        if ($resettime > $starttime && $resttime < $endtime) {
            ?>
            <div class="resetpass_title">
                Reset the password for <?php echo $email; ?>
            </div>
            <div class="resetpass_input">
                <input type="hidden" id="resetpass_input_userid" value="<?php echo $userid; ?>" />
                <input type="password" id="resetpass_input_newpass" value="" placeholder="New Password" /> <a id="resetpass_submit" href="#">Submit</a>
            </div>
            <div id="resetpass_message"></div>
            <?php
        } else {
            echo 'The reset password link expires after 24 hours, please complete the forgot password form again to receive a new link.';
        }
    } else {
        echo 'The link is invalid';
    }
} else {
    echo 'The token is invalid';
}
?>