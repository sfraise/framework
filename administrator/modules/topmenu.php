<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/25/14
 * Time: 5:25 PM
 */
?>
<a href="index.php">Admin Home</a> <?php if($user->hasPermission('manager')) { ?>- <a href="index.php?option=config">Site Config</a> - <a href="index.php?option=sitedata">Edit Site Info</a> - <a href="index.php?option=users">Manage Users</a><?php } ?>