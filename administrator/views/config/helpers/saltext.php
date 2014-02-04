<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php
/**
 * Created by PhpStorm.
 * User: Spencer
 * Date: 2/3/14
 * Time: 12:52 PM
 */

session_start();

// INCLUDE INIT FILE
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

// GET VALUES
$token = Input::get('token');
$prefix = Input::get('prefix');
$suffix = Input::get('suffix');

// CHECK TO MAKE SURE A TOKEN WAS PASSED
if (Token::check($token)) {
    try {
        $saltdata = DB::getInstance();
        // UPDATE TO_DATETIME IN PREVIOUS RECORD
        $saltdata->query("UPDATE salts SET to_datetime = NOW() WHERE to_datetime IS NULL");

        // ADD NEW SALT EXTENSIONS TO DB
        $saltdata->query("INSERT INTO salts (prefix, suffix, from_datetime, method) VALUES ('$prefix', '$suffix', NOW(), 'default')");

        // GET ALL SALT PREFIX AND SUFFIX RECORDS
        $salts = array();
        $saltdata->query('SELECT * FROM salts');
        if (!$saltdata->count()) {
            $salts[] = 'No Salts Exist';
        } else {
            $i = 1;
            foreach ($saltdata->results() as $salt) {
                $prefix = $salt->prefix;
                $suffix = $salt->suffix;
                $fromdate = date('M, dS Y g:ia', strtotime($salt->from_datetime));
                $salts[] = "Prefix: " . $prefix . " Suffix: " . $suffix . " From Date: " . $fromdate . "";
            }
        }
        echo 'The new salt extension has been added';
    } catch (Exception $e) {
        die($e->getMessage());
    }
}
?>
<script type="text/javascript">
    // UPDATE THE SALT LIST
    $('#admin_salt_existing').html('<?php echo implode('<br />', $salts); ?>');
    // RESET THE PARENT PAGE TOKEN IN ORDER TO VALIDATE ON NEXT TRY
    $('#token').val('<?php echo Token::generate(); ?>');
</script>