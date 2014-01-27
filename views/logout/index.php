<?php
// LOGOUT
$user = new User();
$user->logout();
?>

<div class="loggingout">
    <img src="images/loading/loading7.gif" alt="Logging Out" /> Logging Out...
</div>

<script type="text/javascript">
    // REDIRECT TO HOMEPAGE
    setTimeout(function() {
        window.location.href = 'index.php';
    }, 1000);
</script>