<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/13/14
 * Time: 5:49 PM
 */
session_start();

// INCLUDE INIT FILE
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

// GET VALUES
$token = Input::get('register_token');
$email = Input::get('register_email');
$firstname = Input::get('register_firstname');
$lastname = Input::get('register_lastname');
$password = Input::get('register_password');
$passwordagain = Input::get('register_password_again');

// REGISTER
if (Token::check($token)) {
    // GET SITE DATA
    $db = DB::getInstance();
    $sitedata = $db->query('SELECT * FROM site_data');
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
            if ($verify == 0) {
                $active = 1;
            } else {
                $active = 0;
            }
            $verify_email = $siteinfo->verify_email;
            $welcome = $siteinfo->welcome;
            $welcome_email = $siteinfo->welcome_email;
        }
    }

    // GET SALT EXTENSIONS
    $prefix = '';
    $suffix = '';
    $saltdata = $db->get('salts', array('id', '!=', '0'));
    if($saltdata->count()) {
        // IF EXTENSIONS ARE SET COMBINE THEM WITH THE STRING
        $db->_saltdata = $saltdata->last();
        $prefix = $db->_saltdata->prefix;
        $suffix = $db->_saltdata->suffix;
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
            'group' => 1
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
                ?>
                <script type="text/javascript">
                    // RESET THE PARENT PAGE TOKEN IN ORDER TO VALIDATE ON NEXT TRY
                    $('#token').val('<?php echo Token::generate(); ?>');
                </script>
            <?php
            }
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}
?>