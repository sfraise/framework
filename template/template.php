<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $sitename; ?></title>
    <meta name="description" content="<?php echo $sitedescription; ?>" />

    <!-- STYLESHEETS -->
    <link type="text/css" rel="stylesheet" href="template/css/style.css"/>

    <!-- SCRIPTS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="/js/colorbox/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="/js/modals.js"></script>
    <script type="text/javascript" src="/js/main.js"></script>
</head>
<body>
<div class="pagewrapper">
    <!-- HEADER (modules/header.php) -->
    <div class="header">
        <?php require_once 'modules/header.php'; ?>
    </div>
    <!-- MAIN OPTION VIEWS (views/'option'/index.php) -->
    <div class="main">
        <?php require_once 'helpers/router.php'; ?>
    </div>
    <!-- FOOTER (modules/footer.php) -->
    <div class="footer">
        <?php require_once 'modules/footer.php'; ?>
    </div>
</div>
</body>
</html>