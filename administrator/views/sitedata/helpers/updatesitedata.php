<script type="text/javascript" src="js/main.js"></script>
<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/15/14
 * Time: 6:02 PM
 */
set_include_path('../../../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$token = Input::get('editsitetoken');
$name = Input::get('editsitename');
$description = Input::get('editsitedesc');

// GET SITE DATA
$sitedata = DB::getInstance();

// CHECK TO MAKE SURE A TOKEN WAS PASSED
if(Token::check($token)) {
    // UPDATE THE DATABASE
    try {
        $sitedata->query("UPDATE site_data SET name = '$name', description = '$description' WHERE id = 1");
    } catch(Exception $e) {
        die($e->getMessage());
    }
}

echo 'The site info has been updated.'
?>