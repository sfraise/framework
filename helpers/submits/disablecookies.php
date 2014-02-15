<script type="text/javascript" src="/js/main.js"></script>
<?php
/**
 * Created by PhpStorm.
 * User: Spencer
 * Date: 2/14/14
 * Time: 6:21 PM
 */
set_include_path('../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$myid = Input::get('myid');

if (Token::check(Token::generate())) {
    $user = new userAccess($myid);

    // UPDATE THE DATABASE
    try {
        $user->update(array(
            'accept_cookies' => '0'
        ),$myid);

        // ENSURE THE DB WAS UPDATED AND RETURN PROPER MESSAGE
        $user = new userAccess($myid);
        $data = $user->data();
        $newvalue = $data->accept_cookies;
        if($newvalue == 0) {
            echo 'We use cookies to improve your experience. To enable using cookies on this site please click here <a href="#" id="footer_enable_cookies">ENABLE COOKIES</a>.';
        } else {
            echo 'We use cookies to improve your experience. To disable using cookies on this site please click here <a href="#" id="footer_prevent_cookies">DISABLE COOKIES</a>.';
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}
?>