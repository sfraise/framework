<?php
// INCLUDE INIT FILE
include_once 'core/init.php';

// GET USER DATA
$user = new userAccess();

if(Session::exists('home')) {
    echo '<p>', Session::flash('home'), '</p>';
}

if(!$user->exists()) {
    $usertype = 'Guest';
} else {
    if($user->hasPermission('manager')) {
        $usertype = 'Manager';
    } elseif($user->hasPermission('sales')) {
        $usertype = 'Sales';
    } elseif($user->hasPermission('type2')) {
        $usertype = 'Type 2';
    } else {
        $usertype = 'Type 1';
    }
    $myuserdata = $user->data();
    $myid = $myuserdata->id;
}

// GET SITE DATA
$sitedata = DB::getInstance();
$sitedata->query('SELECT * FROM site_data');

if(!$sitedata->count()) {
    echo 'error';
} else {
    foreach($sitedata->results() as $siteinfo) {
        $sitename = $siteinfo->name;
        $sitedescription = $siteinfo->description;
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