<script type="text/javascript" src="js/admin.js"></script>
<?php
/**
 * Created by PhpStorm.
 * User: Spencer
 * Date: 1/13/14
 * Time: 5:49 PM
 */
session_start();

// INCLUDE INIT FILE
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

// GET VALUES
$token = escape($_POST['register_token']);
$email = escape($_POST['register_email']);
$name = escape($_POST['register_name']);
$password = escape($_POST['register_password']);
$passwordagain = escape($_POST['register_password_again']);

// REGISTER
if (Token::check($token)) {
    $user = new User();

    $salt = Hash::salt(32);

    try {
        $user->create(array(
            'username' => $email,
            'password' => Hash::make($password, $salt),
            'salt' => $salt,
            'name' => $name,
            'joined' => date('Y-m-d H:i:s'),
            'group' => 1
        ));

        // AUTO-LOGIN
        $rememberme = 'on';
        $remember = ($rememberme === 'on') ? true : false;
        $login = $user->login($email, $password, $remember);

        if ($login) {
            ?>
            <script type="text/javascript">
                parent.location.reload();
            </script>
        <?php
        } else {
            echo '<p>There was a problem logging in.</p>';
            ?>
            <script type="text/javascript">
                // RESET THE PARENT PAGE TOKEN IN ORDER TO VALIDATE ON NEXT TRY
                $('#token').val('<?php echo Token::generate(); ?>');
            </script>
        <?php
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}
?>