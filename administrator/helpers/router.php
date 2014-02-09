<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/11/14
 * Time: 5:42 PM
 */

$option = $_GET['option'];
$task = $_GET['task'];

if($option) {
    require 'administrator/views/'.$option.'/index.php';
} else {
    require 'administrator/views/index.php';
}
?>