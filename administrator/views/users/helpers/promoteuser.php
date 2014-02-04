<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
<?php
session_start();

// INCLUDE INIT FILE
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

// GET VALUES
$token = Input::get('token');
$userid = Input::get('userid');
$type = Input::get('type');

if(Token::check($token)) {
    $userdata = DB::getInstance();
    // UPDATE THE DATABASE
    try {
        $userdata->query("UPDATE users SET group = '$type' WHERE id = $userid");

        if ($type == 2) {
            $thisusertype = 'Super Administrator';
            $thisuserpromote = "
                            <div id=\"amu_promote_select_wrapper_".$userid."\">
                            <select id=\"amu_promote_select_".$userid."\" rel=\"".$userid."\" class=\"amu_promote_select\">
                                <option selected = \"selected\" value=\"\">Promote or Demote</option>
                                <option value=\"3\">Demote to Administrator</option>
                                <option value=\"4\">Demote to Moderator</option>
                                <option value=\"1\">Demote to Registered</option>
                            </select> - <a id=\"amu_promote_select_close_".$userid."\" class=\"amu_promote_select_close\" rel=\"".$userid."\">Hide</a>
                            </div>";
        } elseif ($type == 3) {
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
        } elseif ($type == 4) {
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
    } catch(Exception $e) {
        die($e->getMessage());
    }
    ?>
    <script type="text/javascript">
        // RESET THE PARENT PAGE TOKEN IN ORDER TO VALIDATE ON NEXT TRY
        $('#token').val('<?php echo Token::generate(); ?>');
    </script>
    <?php
} else {
    echo 'The token is invalid';
}
?>