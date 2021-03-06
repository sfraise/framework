<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/13/14
 * Time: 5:49 PM
 */
set_include_path('../../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

// GET VALUES
$email = Input::get('register_email');
$firstname = Input::get('register_firstname');
$lastname = Input::get('register_lastname');
$password = Input::get('register_password');
$passwordagain = Input::get('register_password_again');
$regcookie = Input::get('register_cookies');

// REGISTER
if (Token::check(Token::generate())) {
    $db = DB::getInstance();

    // CHECK THAT EMAIL IS UNIQUE
    $emails = array();
    try {
        $userdata = $db->get('user_access', array('id', '!=', '0'));
        if ($userdata->count()) {
            foreach ($userdata->results() as $value) {
                $emails[] = $value->email;
            }
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
    if (in_array($email, $emails)) {
        echo 'The email is already in use!';
    } else {
        // GET SITE DATA
        try {
            $sitedata = $db->get('site_data', array('id', '=', '1'));
            if ($sitedata->count()) {
                $db->_sitedata = $sitedata->first();
                $verify = $db->_sitedata->verify;
                if ($verify == 0) {
                    $active = 1;
                } else {
                    $active = 0;
                }
                $verify_email = $db->_sitedata->verify_email;
                $welcome = $db->_sitedata->welcome;
                $welcome_email = $db->_sitedata->welcome_email;
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        // GET SALT EXTENSIONS
        $prefix = '';
        $suffix = '';
        try {
            $saltdata = $db->get('salts', array('id', '!=', '0'));
            if ($saltdata->count()) {
                // IF EXTENSIONS ARE SET COMBINE THEM WITH THE STRING
                $db->_saltdata = $saltdata->last();
                $prefix = $db->_saltdata->prefix;
                $suffix = $db->_saltdata->suffix;
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        // SALT AND HASH THE PASSWORD
        $salt = Hash::salt(32);
        $fullpass = $prefix . $password . $suffix;
        $hashpass = Hash::make($fullpass, $salt);
        $datetime = date('Y-m-d H:i:s');

        // INSTANTIATE THE USER CLASSES
        $useraccess = new userAccess();
        $userdetails = new userDetails();

        try {
            // CREATE THE USER ACCESS RECORD
            $useraccess->create(array(
                'email' => $email,
                'current_password' => $hashpass,
                'current_passdate' => $datetime,
                'salt' => $salt,
                'regdatetime' => $datetime,
                'account_status' => $active,
                'user_group' => 1,
                'accept_cookies' => $regcookie
            ));

            // GET NEW USER ACCESS DATA
            $newuseraccess = new userAccess($email);
            $useraccessdata = $newuseraccess->data();
            $id = $useraccessdata->id;
            $salt = $useraccessdata->salt;

            // CREATE THE USER DETAILS RECORD
            $userdetails->create(array(
                'id' => $id,
                'user_id' => $id,
                'first_name' => $firstname,
                'last_name' => $lastname,
                'regdatetime' => $datetime
            ));

            // GET NEW USER DETAILS DATA
            $newuserdetails = new userDetails($id);
            $userdetailsdata = $newuserdetails->data();
            $firstname = $userdetailsdata->first_name;
            $lastname = $userdetailsdata->last_name;

            if ($verify == 1) {
                /*** IF EMAIL VERIFICATION ENABLED ***/
                // SEND ACTIVATION LINK IN AN EMAIL
                if (!$newuserdetails->exists()) {
                    // IF EMAIL DOESN'T EXIST
                    echo "Email doesn't exist";
                } else {
                    // CREATE THE VERIFICATION CODE
                    $code = Hash::make(rand(100, 900), $salt);

                    // ADD VERIFICATION CODE TO USER'S DATABASE TABLE
                    try {
                        $newuseraccess->update(array(
                            'verification_code' => $code
                        ), $id);
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }

                    // CREATE THE RESET PASSWORD LINK
                    $activationlink = '<a href="http://www.mysite.com/index.php?option=activate&amp;email=' . $email . '&amp;token=' . $code . '">Activate Your Account</a>';

                    // SET THE RECIPIENT EMAIL AND SUBJECT
                    define("RECIPIENT_EMAIL", $email);
                    define("EMAIL_SUBJECT", "Activate Your Account!");
                    $success = false;

                    // SET THE SENDER NAME AND EMAIL
                    $senderName = $sitename;
                    $senderEmail = 'contact@codemonkeys.com';

                    // SET THE MESSAGE
                    if (!$verify_email) {
                        $verify_email = "
                                " . $firstname . " " . $lastname . ",<br /><br />
                                Thank you for joining " . $sitename . "!<br /><br />
                                Please click the link below to activate your account:<br />
                                [activationlink]
                                ";
                    }
                    $message = "
                            " . $verify_email . "<br />
                            " . $activationlink . "
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
                            Thank you for registering with <?php echo $sitename; ?>!<br/>An email has been sent
                            to <?php echo $email; ?> to activate your account
                        </div>
                    <?php } else { ?>
                        <div class="registration_success_error">
                            There was an error sending the message!
                        </div>
                    <?php
                    }
                }
            } else {
                /*** IF EMAIL VERIFICATION DISABLED ***/
                // SET USER'S VERIFICATION CODE TO NULL AND ADD VERIFICATION DATE
                try {
                    $newuseraccess->update(array(
                        'verification_code' => null,
                        'verification_date' => $datetime
                    ), $id);
                } catch (Exception $e) {
                    die($e->getMessage());
                }

                // AUTO-LOGIN
                $rememberme = 'on';
                $remember = ($rememberme === 'on') ? true : false;
                $login = $newuseraccess->login($email, $password, $remember);

                if ($login) {
                    ?>
                    <script type="text/javascript">
                        // REFRESH THE PARENT PAGE
                        parent.location.reload();
                    </script>
                <?php
                } else {
                    echo '<p>There was a problem logging in.</p>';
                }
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
?>