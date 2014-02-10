<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
<?php
set_include_path('../../../../');

// INCLUDE INIT FILE
include_once 'core/init.php';

// GET VALUES
$token = Input::get('token');
$userid = Input::get('userid');
$type = Input::get('type');

if(Token::check($token)) {
    $user = new userAccess($userid);

    // UPDATE THE DATABASE
    try {
        $user->update(array(
            'user_group' => $type
        ), $userid);

        echo 'The password has been updated';
    } catch (Exception $e) {
        die($e->getMessage());
    }

    // GET NEW USER DATA
    $newuser = new userAccess($userid);
    $newuserdata = $newuser->data();
    $newtype = $newuserdata->user_group;
    if ($newtype == 2) {
        $thisusertype = 'new Super Administrator';
        $thisuserpromote = "
                            <div id=\"amu_promote_select_wrapper_".$userid."\">
                            <select id=\"amu_promote_select_".$userid."\" rel=\"".$userid."\" class=\"amu_promote_select\">
                                <option selected = \"selected\" value=\"\">Promote or Demote</option>
                                <option value=\"3\">Demote to Administrator</option>
                                <option value=\"4\">Demote to Moderator</option>
                                <option value=\"1\">Demote to Registered</option>
                            </select> - <a id=\"amu_promote_select_close_".$userid."\" class=\"amu_promote_select_close\" rel=\"".$userid."\">Hide</a>
                            </div>";
    } elseif ($newtype == 3) {
        $thisusertype = 'Administrator';
        $thisuserpromote = "
                            <div id=\"amu_promote_select_wrapper_".$userid."\">
                            <select id=\"amu_promote_select_".$userid."\" rel=\"".$userid."\" class=\"amu_promote_select\">
                                <option selected = \"selected\" value=\"\">Promote or Demote</option>
                                <option value=\"2\">Promote to Super Administrator</option>
                                <option value=\"4\">Demote to Moderator</option>
                                <option value=\"1\">Demote to Registered</option>
                            </select> - <a id=\"amu_promote_select_close_".$userid."\" class=\"amu_promote_select_close\" rel=\"".$userid."\">Hide</a>
                            </div>";
    } elseif ($newtype == 4) {
        $thisusertype = 'Moderator';
        $thisuserpromote = "
                            <div id=\"amu_promote_select_wrapper_".$userid."\">
                            <select id=\"amu_promote_select_".$userid."\" rel=\"".$userid."\" class=\"amu_promote_select\">
                                <option selected = \"selected\" value=\"\">Promote or Demote</option>
                                <option value=\"2\">Promote to Super Administrator</option>
                                <option value=\"3\">Promote to Administrator</option>
                                <option value=\"1\">Demote to Registered</option>
                            </select> - <a id=\"amu_promote_select_close_".$userid."\" class=\"amu_promote_select_close\" rel=\"".$userid."\">Hide</a>
                            </div>";
    } else {
        $thisusertype = 'Registered';
        $thisuserpromote = "
                            <div id=\"amu_promote_select_wrapper_".$userid."\">
                            <select id=\"amu_promote_select_".$userid."\" rel=\"".$userid."\" class=\"amu_promote_select\">
                                <option selected = \"selected\" value=\"\">Promote or Demote</option>
                                <option value=\"2\">Promote to Super Administrator</option>
                                <option value=\"3\">Promote to Administrator</option>
                                <option value=\"4\">Promote to Moderator</option>
                            </select> - <a id=\"amu_promote_select_close_".$userid."\" class=\"amu_promote_select_close\" rel=\"".$userid."\">Hide</a>
                            </div>";
    }
    echo $thisuserpromote;
    ?>
    <script type="text/javascript">
        // RESET THE PARENT PAGE TOKEN IN ORDER TO VALIDATE ON NEXT TRY
        $('#token').val('<?php echo Token::generate(); ?>');
        // UPDATE THE USER TYPE ON PARENT PAGE
        $('#amu_type_<?php echo $userid; ?>').html('<?php echo $thisusertype; ?>');
    </script>
    <?php
} else {
    echo 'The token is invalid';
}
?>