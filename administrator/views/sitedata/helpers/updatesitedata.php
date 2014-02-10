<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/15/14
 * Time: 6:02 PM
 */
set_include_path('../../../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$name = Input::get('editsitename');
$description = Input::get('editsitedesc');

// CHECK TO MAKE SURE A TOKEN WAS PASSED
if(Token::check(Token::generate())) {
    try {
        $siteData = DB::getInstance();

        // UPDATE THE DATABASE
        $siteData->update('site_data', '1', array(
            'name' => $name,
            'description' => $description
        ));

        // GET NEW VALUES
        $sitename = '';
        $sitedescription = '';
        $siteinfo = $siteData->get('site_data', array('id', '=', '1'));
        if($siteinfo->count()) {
            $sitename = $siteinfo->first()->name;
            $sitedescription = $siteinfo->first()->description;
        }
        if(!$sitename) {
            $sitename = 'No Site Name Entered!';
        }
        if(!$sitedescription) {
            $sitedescription = 'No Site Description Entered!';
        }

        echo 'The site info has been updated.';
    } catch(Exception $e) {
        die($e->getMessage());
    }
}
?>
<script type="text/javascript">
    $('#editsitename').val('<?php echo $sitename; ?>');
    $('#editsitedesc').val('<?php echo $sitedescription; ?>');
</script>