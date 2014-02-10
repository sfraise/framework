<script type="text/javascript" src="/js/main.js"></script>
<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/20/14
 * Time: 8:02 PM
 */
set_include_path('../../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$myid = Input::get('myid');
$field = Input::get('field');
$newvalue = Input::get('newvalue');

if (Token::check(Token::generate())) {
    $user = new userDetails($myid);

    try {
        $user->update(array(
            $field => $newvalue
        ), $myid);
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

$user = new userDetails($myid);

echo $user->userFields($myid, $field, 'text');
?>