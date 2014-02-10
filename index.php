<?php
set_include_path(dirname(__FILE__));

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET USER DATA
$user = new userAccess();
if(!$user->exists()) {
    // IF NO USER EXISTS SET USER TYPE TO GUEST
    $usertype = 'Guest';
} else {
    // IF USER DOES EXIST SET USER TYPE
    if($user->hasPermission('manager')) {
        $usertype = 'Manager';
    } elseif($user->hasPermission('sales')) {
        $usertype = 'Sales';
    } elseif($user->hasPermission('type2')) {
        $usertype = 'Type 2';
    } else {
        $usertype = 'Type 1';
    }
    // INSTANTIATE THE USER CLASS AND GET USER ID
    $myuserdata = $user->data();
    $myid = $myuserdata->id;
}

// SET MY ID TO 0 IF NOT LOGGED IN
if(!$myid) {
    $myid = 0;
}

// GET SITE DATA
$sitedata = DB::getInstance();
$sitedata->query('SELECT * FROM site_data');

if(!$sitedata->count()) {
    echo 'error';
} else {
    foreach($sitedata->results() as $siteinfo) {
        $sitename = $siteinfo->name;
        if(!$sitename) {
            $sitename = 'No Site Name Entered!';
        }
        $sitedescription = $siteinfo->description;
        if(!$sitedescription) {
            $sitedescription = 'No Site Description Entered!';
        }
        $logo = $siteinfo->logo;
        if(!$logo) {
            $logo = '/images/logo/defaultlogo.jpg';
        }
        $sitelogo = "<img id=\"site_logo\" src=\"".$logo."\" alt=\"".$sitename."\" title=\"".$sitename."\" />";
    }
}

// GET TEMPLATE
require 'template/template.php';

// SET THE PAGE TOKEN
echo "<input type=\"hidden\" id=\"token\" value=\"" . Token::generate() . "\">";

// SET MY ID
echo "<input type=\"hidden\" id=\"myid\" value=\"" . $myid . "\">";
?>