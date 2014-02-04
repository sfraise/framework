<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/14/14
 * Time: 4:21 PM
 */

if($user->hasPermission('manager')) {
    require 'homepages/manager.php';
} elseif($user->hasPermission('sales')) {
    require 'homepages/sales.php';
} elseif($user->hasPermission('type2')) {
    require 'homepages/type2.php';
} elseif($user->hasPermission('type1')) {
    require 'homepages/type1.php';
} else {
    require 'homepages/guest.php';
}
?>