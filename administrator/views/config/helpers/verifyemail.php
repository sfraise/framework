<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/26/14
 * Time: 5:34 PM
 */
set_include_path('../../../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$email = Input::get('email');

// MAKE SURE THE PAGE TOKEN IS VALID
if (Token::check(Token::generate())) {
    // UPDATE THE DATABASE
    try {
        $siteData = DB::getInstance();
        // UPDATE THE DATABASE
        $siteData->update('site_data', '1', array(
            'verify_email' => $email
        ));
        echo 'The email has been updated';
    } catch(Exception $e) {
        die($e->getMessage());
    }
} else {
    echo 'The token is invalid';
}
?>