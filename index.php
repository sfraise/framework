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
$sitename = '';
$sitedescription = '';
$logo = '';
$siteData = DB::getInstance();
$siteinfo = $siteData->get('site_data', array('id', '=', '1'));
if($siteinfo->count()) {
    $sitename = $siteinfo->first()->name;
    $sitedescription = $siteinfo->first()->description;
    $logo = $siteinfo->first()->logo;
}
if(!$sitename) {
    $sitename = 'No Site Name Entered!';
}
if(!$sitedescription) {
    $sitedescription = 'No Site Description Entered!';
}
if(!$logo) {
    $logo = '/images/logo/defaultlogo.jpg';
}
$sitelogo = "<img id=\"site_logo\" src=\"".$logo."\" alt=\"".$sitename."\" title=\"".$sitename."\" />";

// GET TEMPLATE
require 'template/template.php';
?>