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

error_reporting(E_ALL);
ini_set('display_errors', '1');

// GET VALUES
$token = escape($_POST['register_token']);
$email = escape($_POST['register_email']);
$firstname = escape($_POST['register_firstname']);
$lastname = escape($_POST['register_lastname']);
$password = escape($_POST['register_password']);
$passwordagain = escape($_POST['register_password_again']);

// REGISTER
if (Token::check($token)) {
    // GET SITE DATA
    $sitedata = DB::getInstance();
    $sitedata->query('SELECT * FROM site_data');
    if (!$sitedata->count()) {
        echo 'error';
    } else {
        foreach ($sitedata->results() as $siteinfo) {
            $sitename = $siteinfo->name;
            $sitedescription = $siteinfo->description;
            $logo = $siteinfo->logo;
            if (!$logo) {
                $logo = '/images/logo/defaultlogo.jpg';
            }
            $sitelogo = "<img id=\"site_logo\" src=\"" . $logo . "\" alt=\"" . $sitename . "\" title=\"" . $sitename . "\" />";
            $verify = $siteinfo->verify;
            if($verify == 0) {
                $active = 1;
            } else {
                $active = 0;
            }
            $verify_email = $siteinfo->verify_email;
            $welcome = $siteinfo->welcome;
            $welcome_email = $siteinfo->welcome_email;
        }
    }

    $user = new User();

    $salt = Hash::salt(32);
    $hashpass = Hash::make($password, $salt);
    $date = date('Y-m-d H:i:s');

    try {
        $user->create(array(
            'email' => $email,
            'current_password' => $hashpass,
            'salt' => $salt,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'regdatetime' => $date,
            'account_status' => $active,
            'user_group' => 1
        ));

        // IF EMAIL VERIFICATION DISABLED
        if ($verify == 0) {
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
        // IF EMAIL VERIFICATION ENABLED
        } else {
            $newuser = new User($email);
            // SEND ACTIVATION LINK IN AN EMAIL
            if (!$newuser->exists()) {
                // IF EMAIL DOESN'T EXIST
                echo "Email doesn't exist";
            } else {
                // IF EMAIL DOES EXIST GET NEW USER DATA
                $userdata = $newuser->data();
                $id = $userdata->id;
                $firstname = $userdata->firstname;
                $lastname = $userdata->lastname;
                $salt = $userdata->salt;
                $datetime = date('Y-m-d H:i:s');

                // CREATE THE RESET CODE
                $code = Hash::make(rand(100, 900), $salt);

                // ADD CODE AND CURRENT TIMESTAMP TO USER'S DATABASE TABLE
                try {
                    $user->update(array(
                        'activation_code' => $code
                    ), $id);
                } catch (Exception $e) {
                    die($e->getMessage());
                }

                // CREATE THE RESET PASSWORD LINK
                $activationlink = '<a href="http://www.mysite.com/index.php?option=activate&amp;email='.$email.'&amp;token='.$code.'">Activate Your Account</a>';

                // SET THE RECIPIENT EMAIL AND SUBJECT
                define("RECIPIENT_EMAIL", $email);
                define("EMAIL_SUBJECT", "Activate Your Account!");
                $success = false;

                // SET THE SENDER NAME AND EMAIL
                $senderName = $sitename;
                $senderEmail = 'contact@codemonkeys.com';

                // SET THE MESSAGE
                if(!$verify_email) {
                    $verify_email = "
                                ".$firstname." ".$lastname.",<br /><br />
                                Thank you for joining ".$sitename."!<br /><br />
                                Please click the link below to activate your account:<br />
                                [activationlink]
                                ";
                }
                $message = "
                            ".$verify_email."<br />
                            ".$activationlink."
                            ";


                // If all values exist, send the email
                if ($senderName && $senderEmail && $message) {
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                    $headers .= "From: " . $senderName . " <" . $senderEmail . ">";
                    $success = mail(RECIPIENT_EMAIL, EMAIL_SUBJECT, $message, $headers);
                }

                if ($success) {
                    ?>
                    <div class="registration_success_message">
                        Thank you for registering with <?php echo $sitename; ?>!<br />An email has been sent to <?php echo $email; ?> to activate your account
                    </div>
                <?php } else { ?>
                    <div class="registration_success_error">
                        There was an error sending the message!
                    </div>
                <?php
                }
            }
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}
?>