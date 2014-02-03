<?php
/**
 * Created by PhpStorm.
 * userAccess: Spencer
 * Date: 1/15/14
 * Time: 8:51 PM
 */

// GET USERS DATA
$members = array();
$usersdata = DB::getInstance();
$usersdata->query('SELECT * FROM user_access LEFT JOIN user_details ON user_details.user_id = user_access.id');
if (!$usersdata->count()) {
    echo 'No Users Exist';
} else {
    $i = 1;
    foreach ($usersdata->results() as $user) {
        $thisuserid = $user->id;
        $thisuseremail = $user->email;
        $thisuserfirstname = $user->first_name;
        $thisuserlastname = $user->last_name;
        $thisusergroup = $user->user_group;
        $thisuserregdate = date('m-d-Y', strtotime($user->regdatetime));

        if ($thisusergroup == 2) {
            $thisusertype = 'Super Administrator';
            $thisuserpromote = "
                            <div id=\"amu_promote_select_wrapper_".$thisuserid."\" class=\"amu_promote_select_wrapper\">
                            <select id=\"amu_promote_select_".$thisuserid."\" rel=\"".$thisuserid."\" class=\"amu_promote_select\">
                                <option selected = \"selected\" value=\"\">Promote or Demote</option>
                                <option value=\"3\">Demote to Administrator</option>
                                <option value=\"4\">Demote to Moderator</option>
                                <option value=\"1\">Demote to Registered</option>
                            </select> - <a id=\"amu_promote_select_close_".$thisuserid."\" class=\"amu_promote_select_close\" rel=\"".$thisuserid."\">Hide</a>
                            </div>";
        } elseif ($thisusergroup == 3) {
            $thisusertype = 'Administrator';
            $thisuserpromote = "
                            <div id=\"amu_promote_select_wrapper_".$thisuserid."\" class=\"amu_promote_select_wrapper\">
                            <select id=\"amu_promote_select_".$thisuserid."\" rel=\"".$thisuserid."\" class=\"amu_promote_select\">
                                <option selected = \"selected\" value=\"\">Promote or Demote</option>
                                <option value=\"2\">Promote to Super Administrator</option>
                                <option value=\"4\">Demote to Moderator</option>
                                <option value=\"1\">Demote to Registered</option>
                            </select> - <a id=\"amu_promote_select_close_".$thisuserid."\" class=\"amu_promote_select_close\" rel=\"".$thisuserid."\">Hide</a>
                            </div>";
        } elseif ($thisusergroup == 4) {
            $thisusertype = 'Moderator';
            $thisuserpromote = "
                            <div id=\"amu_promote_select_wrapper_".$thisuserid."\" class=\"amu_promote_select_wrapper\">
                            <select id=\"amu_promote_select_".$thisuserid."\" rel=\"".$thisuserid."\" class=\"amu_promote_select\">
                                <option selected = \"selected\" value=\"\">Promote or Demote</option>
                                <option value=\"2\">Promote to Super Administrator</option>
                                <option value=\"3\">Promote to Administrator</option>
                                <option value=\"1\">Demote to Registered</option>
                            </select> - <a id=\"amu_promote_select_close_".$thisuserid."\" class=\"amu_promote_select_close\" rel=\"".$thisuserid."\">Hide</a>
                            </div>";
        } else {
            $thisusertype = 'Registered';
            $thisuserpromote = "
                            <div id=\"amu_promote_select_wrapper_".$thisuserid."\" class=\"amu_promote_select_wrapper\">
                            <select id=\"amu_promote_select_".$thisuserid."\" rel=\"".$thisuserid."\" class=\"amu_promote_select\">
                                <option selected = \"selected\" value=\"\">Promote or Demote</option>
                                <option value=\"2\">Promote to Super Administrator</option>
                                <option value=\"3\">Promote to Administrator</option>
                                <option value=\"4\">Promote to Moderator</option>
                            </select> - <a id=\"amu_promote_select_close_".$thisuserid."\" class=\"amu_promote_select_close\" rel=\"".$thisuserid."\">Hide</a>
                            </div>";
        }

        $members[] = "
                    <div id=\"admin_manage_user_".$thisuserid."\" class=\"admin_manage_user\">
                        " . $thisuserid . " " . $thisuserfirstname . " " . $thisuserlastname . " " . $thisuseremail . " " . $thisuserregdate . "
                        <span id=\"amu_type_".$thisuserid."\">" . $thisusertype . "</span>
                        <div class=\"amu_actions\">
                            <a id=\"amu_prodem_".$thisuserid."\" class=\"amu_prodem\" href=\"#\" rel=\"".$thisuserid."\">Promote/Demote</a> - <a id=\"amu_changepass_".$thisuserid."\" class=\"amu_changepass\" href=\"#\" rel=\"".$thisuserid."\">Change Password</a>
                        </div>
                        <div id=\"admin_manage_user_promote_".$thisuserid."\">
                            ".$thisuserpromote."
                        </div>
                        <div id=\"amu_changepass_wrapper_".$thisuserid."\" class=\"amu_changepass_wrapper\">
                            <input type=\"text\" id=\"amu_changepass_input_".$thisuserid."\" value=\"\" placeholder=\"New Password\" /> <a id=\"amu_changepass_submit_".$thisuserid."\" class=\"amu_changepass_submit\" href=\"#\" rel=\"".$thisuserid."\">Submit</a> - <a id=\"amu_changepass_close_".$thisuserid."\" class=\"amu_changepass_close\" href=\"#\" rel=\"".$thisuserid."\">Hide</a>
                            <div id=\"amu_changepass_message_".$thisuserid."\" class=\"amu_changepass_message\"></div>
                        </div>
                    </div>";
        $i++;
    }
}

$memberlist = implode('', $members);

echo 'Manage Users<br /><br />';

echo $memberlist;
?>

