<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php
/**
 * Created by PhpStorm.
 * User: Spencer
 * Date: 2/3/14
 * Time: 12:52 PM
 */

set_include_path('../../../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$prefix = Input::get('prefix');
$suffix = Input::get('suffix');
$datetime = date('Y-m-d H:i:s');

// CHECK TO MAKE SURE A TOKEN WAS PASSED
if (Token::check(Token::generate())) {
    try {
        $saltData = DB::getInstance();
        $null = NULL;
        // GET LAST SALT RECORD
        $lastsalt = $saltData->get('salts', array('to_datetime', '<=>', $null));
        if($lastsalt->count()) {
            $lastsaltid = $lastsalt->last()->id;

            // UPDATE TO_DATETIME IN LAST RECORD
            $saltData->update('salts', $lastsaltid, array(
                'to_datetime' => $datetime
            ));
        }

        // INSERT THE NEW RECORD
        $saltData->insert('salts', array(
            'prefix' => $prefix,
            'suffix' => $suffix,
            'from_datetime' => $datetime,
            'method' => 'default'
        ));

        // GET NEW VALUES
        $salts = array();
        $saltinfo = $saltData->get('salts', array('id', '!=', '0'));
        if(!$saltinfo->count()) {
            $salts[] = 'No Salts Exist';
        } else {
            foreach($saltinfo->results() as $salt) {
                $newprefix = $salt->prefix;
                $newsuffix =  $salt->suffix;
                $newfromdate = date('M, dS Y g:ia', strtotime($salt->from_datetime));
                $newtodate = $salt->to_datetime;
                if(!$newtodate) {
                    $newtodate = 'Current';
                } else {
                    $newtodate = date('M, dS Y g:ia', strtotime($salt->to_datetime));
                }
                $salts[] = "Prefix: " . $newprefix . " Suffix: " . $newsuffix . " From Date: " . $newfromdate . " To Date: " . $newtodate . "";
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
</script>