<?php
/**
 * Created by PhpStorm.
 * User: Spencer
 * Date: 1/13/14
 * Time: 8:13 PM
 */
session_start();

// INCLUDE INIT FILE
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

// GET VALUES
$token = escape($_POST['login_token']);
$email = escape($_POST['login_email']);
$password = escape($_POST['login_password']);
$rememberme = escape($_POST['login_remember']);

// LOGIN
if (Token::check($token)) {
    $user = new User($email);
    $userdata = $user->data();
    $userid = $userdata->id;
    $active = $userdata->account_status;

    if (!$userid) {
        echo '<div class="loginerror">Sorry, that email and password wasn\'t recognised.</div>';
    } else {
        if ($active == 1) {
            $remember = ($rememberme === 'on') ? true : false;
            $login = $user->login($email, $password, $remember);

            if ($login) {
                ?>
                <script type="text/javascript">
                    parent.location.reload();
                </script>
            <?php
            } else {
                echo '<div class="loginerror">Sorry, that email and password wasn\'t recognised.</div>';
                ?>
                <script type="text/javascript">
                    // RESET THE PARENT PAGE TOKEN IN ORDER TO VALIDATE ON NEXT TRY
                    $('#token').val('<?php echo Token::generate(); ?>');
                </script>
                <?php
            }
        } else {
            echo '<div class="loginerror">You need to activate your account before logging in</div>';
            ?>
            <script type="text/javascript">
                // RESET THE PARENT PAGE TOKEN IN ORDER TO VALIDATE ON NEXT TRY
                $('#token').val('<?php echo Token::generate(); ?>');
            </script>
            <?php
        }
    }
}
?>