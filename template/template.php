<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $sitename; ?></title>
    <meta name="description" content="<?php echo $sitedescription; ?>" />

    <!-- STYLESHEETS -->
    <?php if(Input::get('view') !== 'iframe') { ?>
        <link type="text/css" rel="stylesheet" href="template/css/style.css"/>
    <?php } else { ?>
        <link type="text/css" rel="stylesheet" href="template/css/iframestyle.css"/>
    <?php } ?>

    <!-- SCRIPTS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="/js/colorbox/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="/js/modals.js"></script>
    <script type="text/javascript" src="/js/main.js"></script>
</head>
<body>
<input type="hidden" id="myid" value="<?php echo $myid; ?>" />
<div class="pagewrapper">
    <!-- HEADER (modules/header.php) -->
    <div class="header">
        <?php
            if(Input::get('view') !== 'iframe') {
                require_once 'modules/header.php';
            }
        ?>
    </div>
    <!-- MAIN OPTION VIEWS (views/'option'/index.php) -->
    <div class="main">
        <?php if(Input::get('view') == 'iframe') { ?>
            <div class="standardlbtop">
                <span class="ifcolorboxclose"></span>
                <div style="clear:both;"></div>
            </div>
        <?php } ?>
        <?php require_once 'helpers/router.php'; ?>
    </div>
    <!-- FOOTER (modules/footer.php) -->
    <div class="footer">
        <?php
        if(Input::get('view') !== 'iframe') {
                require_once 'modules/footer.php';
        }
        ?>
    </div>
</div>
</body>
</html>