<?php
/**
 * Created by PhpStorm.
 * User: Spencer
 * Date: 1/25/14
 * Time: 5:12 PM
 */
?>
<div class="logo">
            <a href="../index.php"><?php echo $sitelogo; ?></a>
</div>
<div class="logreg">
    <?php
    $newRegister = new logReg();
    $newRegister->set_logreg();
    $register = $newRegister->get_logreg('register');
    $login = $newRegister->get_logreg('login');
    $logout = $newRegister->get_logreg('logout');
    $changepassword = $newRegister->get_logreg('changepassword');
    $forgotpassword = $newRegister->get_logreg('forgotpassword');
    ?>
    <?php if ($user->isLoggedIn()) { ?>
        <div class="loginmessage">
            Hello <a href="../index.php?option=profile&user=<?php echo escape($user->data()->id); ?>"><?php echo escape($user->data()->firstname); ?></a>! - <?php echo $usertype; ?>
        </div>
        <div class="loginlinks">
            <?php echo $logout; ?> <a href="../index.php?option=profile&user=<?php echo $myid; ?>">My Profile</a> <a href="../index.php">Main Site</a>
        </div>
    <?php } else { ?>
        <div class="loginlinks">
            You need to <?php echo $login; ?> or <?php echo $register; ?>!<br />(<?php echo $forgotpassword; ?>)
        </div>
    <?php } ?>
    <div style="clear:both;"></div>
</div>
<div style="clear:both;"></div>