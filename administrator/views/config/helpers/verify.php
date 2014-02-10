<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/25/14
 * Time: 2:04 PM
 */
set_include_path('../../../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// CHECK TO MAKE SURE A TOKEN WAS PASSED
if(Token::check(Token::generate())) {
    // GET SITE DATA
    $siteData = DB::getInstance();
    $option = '';
    $siteinfo = $siteData->get('site_data', array('id', '=', '1'));
    if($siteinfo->count()) {
        $option = $siteinfo->first()->verify;
    }
    if($option == 0) {
        $newoption = '1';
    } elseif($option == 1) {
        $newoption = '0';
    } else {
        $newoption = 'Error';
    }

    // UPDATE THE DATABASE
    try {
        // UPDATE THE DATABASE
        $siteData->update('site_data', '1', array(
            'verify' => $newoption
        ));

        // UPDATE THE CHECKBOX
        if($newoption == 1) {
            echo "<img src=\"/images/checkmark.png\" alt=\"Selected\" />";
        } elseif($newoption == 0) {
            echo '';
        }
    } catch(Exception $e) {
        die($e->getMessage());
    }
} else {
    echo 'The token is invalid';
}
?>