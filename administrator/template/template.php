<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $sitename; ?> - Admin Panel</title>
    <meta name="description" content="<?php echo $sitedescription; ?>" />

    <!-- STYLESHEETS -->
    <link type="text/css" rel="stylesheet" href="template/css/style.css"/>

    <!-- SCRIPTS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            mode : "specific_textareas",
            editor_selector : "wysiwyg"
        });
    </script>
    <script type="text/javascript" src="/js/colorbox/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="/js/modals.js"></script>
    <script type="text/javascript" src="/administrator/js/admin.js"></script>
</head>
<body>
<input type="hidden" id="myid" value="<?php echo $myid; ?>" />
<div class="pagewrapper">
    <!-- HEADER (administrator/modules/header.php) -->
    <div class="header">
        <?php require_once 'administrator/modules/header.php'; ?>
    </div>
    <div class="adminmain">
        <!-- IF USER IS LOGGED IN -->
        <?php if ($user->isLoggedIn()) { ?>
            <!-- IF USER IS AT LEAST A MOD USER TYPE -->
            <?php if($user->hasPermission('sales')) { ?>
                <!-- TOP MENU (administrator/modules/topmenu.php) -->
                <div class="admintopmenu">
                    <?php require_once 'administrator/modules/topmenu.php'; ?>
                </div>
                <!-- MAIN OPTION VIEWS (administrator/views/'option'/index.php) -->
                <div class="adminmainbody">
                    <?php require_once 'administrator/helpers/router.php'; ?>
                </div>
                <div style="clear:both;"></div>
            <!-- IF USER IS NOT AT LEAST A MOD USER TYPE -->
            <?php } else { ?>
                <div class="adminnotloggedin">
                    You are not authorized to view this page!
                </div>
            <?php } ?>
        <!-- IF USER IS NOT LOGGED IN -->
        <?php } else { ?>
            <div class="adminnotloggedin">
                You must log in!
            </div>
        <?php } ?>
    </div>
    <!-- FOOTER (administrator/modules/footer.php) -->
    <div class="footer">
        <?php require_once 'administrator/modules/footer.php'; ?>
    </div>
</div>
</body>
</html>