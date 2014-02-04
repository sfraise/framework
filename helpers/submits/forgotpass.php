<script type="text/javascript" src="js/main.js"></script>
<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/14/14
 * Time: 5:40 PM
 */
session_start();

// INCLUDE INIT FILE
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

// GET VALUES
$token = Input::get('forgotpass_token');
$email = Input::get('forgotpass_email');

$user = new userAccess($email);

if (!$user->exists()) {
    // IF EMAIL DOESN'T EXIST
    echo "Email doesn't exist";
} else {
    // IF EMAIL DOES EXIST
    $data = $user->data();
    $id = $data->id;
    $name = $data->firstname;
    $datetime = date('Y-m-d H:i:s');

    // CREATE THE RESET CODE
    $salt = $data->salt;
    $code = Hash::make(rand(100, 900), $salt);

    // ADD CODE AND CURRENT TIMESTAMP TO USER'S DATABASE TABLE
    try {
        $user->update(array(
            'reset' => $code,
            'reset_time' => $datetime
        ), $id);
    } catch (Exception $e) {
        die($e->getMessage());
    }

    // CREATE THE RESET PASSWORD LINK
    $resetlink = '<a href="http://www.mysite.com/index.php?option=resetpassword&amp;email='.$email.'&amp;token='.$code.'">Reset Password</a>';

// SET THE RECIPIENT EMAIL AND SUBJECT
    define("RECIPIENT_EMAIL", $email);
    define("EMAIL_SUBJECT", "Password Reset");
    $success = false;

// SET THE SENDER NAME AND EMAIL
    $senderName = 'Code Monkeys LLC';
    $senderEmail = 'contact@codemonkeys.com';

// SET THE MESSAGE
    $message = "
    Hello " . $name . ",<br /><br />
    Please click on this link to reset your password:<br /> " . $resetlink . "<br />
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
        <div class="forgotpass_success_message">
            An emaill has been sent to <?php echo $email; ?> with a link to reset your password.
        </div>
    <?php } else { ?>
        <div class="forgotpass_success_error">
            There was an error sending the message!
        </div>
    <?php
    }
}
?>