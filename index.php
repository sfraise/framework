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

    if(isset($myuserdata->id)) {
        $myid = $myuserdata->id;
    } else {
        $myid = 0;
    }

    // CHECK IF USER ACCEPTS COOKIES OR NOT (1 FOR YES, 0 FOR NO)
    $acceptcookies = $myuserdata->accept_cookies;
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
    // GET SITE NAME
    if(isset($siteinfo->first()->name)) {
        $sitename = $siteinfo->first()->name;
    } else {
        $sitename = 'No Site Name Entered!';
    }

    // GET SITE DESCRIPTION
    if(isset($siteinfo->first()->description)) {
        $sitedescription = $siteinfo->first()->description;
    } else {
        $sitedescription = 'No Site Description Entered!';
    }

    // GET SITE LOGO
    if(isset($siteinfo->first()->logo)) {
        $logo = $siteinfo->first()->logo;
    } else {
        $logo = '/images/logo/defaultlogo.jpg';
    }
} else {
    echo 'Error: No site data found';
}

// SET VARIABLES IN CASE DATA IS EMPTY
if(!$sitename) {
    $sitename = 'No Site Name Entered!';
}
if(!$sitedescription) {
    $sitedescription = 'No Site Description Entered!';
}
if(!$logo) {
    $logo = '/images/logo/defaultlogo.jpg';
}

// BUILD THE LOGO
$sitelogo = "<img id=\"site_logo\" src=\"".$logo."\" alt=\"".$sitename."\" title=\"".$sitename."\" />";

// GET TEMPLATE
require 'template/template.php';
?>