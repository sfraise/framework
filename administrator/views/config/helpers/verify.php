<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/25/14
 * Time: 2:04 PM
 */
set_include_path('../../../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$token = Input::get('token');

// GET SITE DATA
$sitedata = DB::getInstance();
$sitedata->query('SELECT * FROM site_data');
if(!$sitedata->count()) {
    echo 'error';
} else {
    foreach($sitedata->results() as $siteinfo) {
        $option = $siteinfo->verify;
    }
}
if($option == 0) {
    $newoption = '1';
} elseif($option == 1) {
    $newoption = '0';
} else {
    $newoption = 'Error';
}

// CHECK TO MAKE SURE A TOKEN WAS PASSED
if(Token::check($token)) {
    // UPDATE THE DATABASE
    try {
        $sitedata->query("UPDATE site_data SET verify = '$newoption' WHERE id = 1");
        if($newoption == 1) {
            echo "<img src=\"/images/checkmark.png\" alt=\"Selected\" />";
        } elseif($newoption == 0) {
            echo '';
        }
    } catch(Exception $e) {
        die($e->getMessage());
    }
} else {
    echo 'The token is invalid';
}
?>
<script type="text/javascript">
    // RESET THE PARENT PAGE TOKEN IN ORDER TO VALIDATE ON NEXT TRY
    $('#token').val('<?php echo Token::generate(); ?>');
</script>