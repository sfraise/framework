<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/13/14
 * Time: 8:13 PM
 */
set_include_path('../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$email = Input::get('login_email');
$password = Input::get('login_password');
$rememberme = Input::get('login_remember');

// LOGIN
if (Token::check(Token::generate())) {
    $user = new userAccess($email);
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
        }
    }
}
?>