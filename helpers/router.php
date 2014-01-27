<?php
/**
 * Created by PhpStorm.
 * User: Spencer
 * Date: 1/11/14
 * Time: 5:42 PM
 */

$option = $_GET['option'];
$task = $_GET['task'];

if($option) {
    require 'views/'.$option.'/index.php';
} else {
    require 'views/index.php';
}
?>