<?php
/**
 * Created by PhpStorm.
 * User: Spencer
 * Date: 1/11/14
 * Time: 7:43 PM
 */

class logReg
{
    public $register;
    public $login;
    public $logout;
    public $changepassword;
    public $forgotpassword;

    function set_logreg()
    {
        // SET REGISTRATION LINK AND MODAL WINDOW
        $reglink = "<a class=\"registerlb\" href=\"#\">Register</a>";
        $regmodal = "
                    <div style=\"display:none;\">
                        <div id=\"registerlbcontent\" class=\"standardlbcontent\">
                            <div class=\"standardlbtop\">
                                <span class=\"standardlbtitle\">Register</span><span class=\"colorboxclose\"></span>
                                <div style=\"clear:both;\"></div>
                            </div>
                            <div class=\"standardlbbottom\" id=\"registerlbbottom\">
		                        <input type=\"text\" name=\"register_email\" id=\"register_email\" value=\"\" placeholder=\"Email\"><br />
		                        <input type=\"text\" name=\"register_firstname\" id=\"register_firstname\" value=\"\" placeholder=\"First Name\"><br />
		                        <input type=\"text\" name=\"register_lastname\" id=\"register_lastname\" value=\"\" placeholder=\"Last Name\"><br />
                                <input type=\"password\" name=\"register_password\" id=\"register_password\" placeholder=\"Password\"><br />
                                <input type=\"password\" name=\"register_password_again\" id=\"register_password_again\" placeholder=\"Retype Password\"><br />
                                <div id=\"register_submit\" class=\"submit_button\">
                                    Submit
                                </div>
                                <div id=\"register_message\"></div>
                            </div>
                        </div>
                    </div>";

        $myregister = "" . $reglink . "" . $regmodal . "";

        // SET LOGIN LINK AND MODAL WINDOW
        $loginlink = "<a class=\"loginlb\" href=\"#\">Login</a>";
        $loginmodal = "
                    <div style=\"display:none;\">
                        <div id=\"loginlbcontent\" class=\"standardlbcontent\">
                            <div class=\"standardlbtop\">
                                <span class=\"standardlbtitle\">Login</span><span class=\"colorboxclose\"></span>
                                <div style=\"clear:both;\"></div>
                            </div>
                            <div id=\"loginlbbottom\" class=\"standardlbbottom\">
		                        <input type=\"text\" name=\"login_email\" id=\"login_email\" value=\"\" placeholder=\"Email\">
		                        <input type=\"password\" name=\"login_password\" id=\"login_password\" value=\"\" placeholder=\"Password\">
			                    <input type=\"checkbox\" name=\"login_remember\" id=\"login_remember\"> Remember me
                                <div id=\"login_submit\" class=\"submit_button\">
                                    Submit
                                </div>
                                <div id=\"login_message\"></div>
                            </div>
                        </div>
                    </div>";

        $mylogin = "" . $loginlink . "" . $loginmodal . "";

        // SET LOGOUT LINK AND MODAL WINDOW
        $mylogout = "<a class=\"logout_link\" href=\"index.php?option=logout\">Logout</a>";

        // SET CHANGE PASSWORD LINK AND MODAL WINDOW
        $changelink = "<a class=\"updatelb\" href=\"#\">Change Password</a>";
        $changemodal = "
                    <div style=\"display:none;\">
                        <div id=\"updatelbcontent\" class=\"standardlbcontent\">
                            <div class=\"standardlbtop\">
                                <span class=\"standardlbtitle\">Change Password</span><span class=\"colorboxclose\"></span>
                                <div style=\"clear:both;\"></div>
                            </div>
                            <div class=\"standardlbbottom\">
                                <input type=\"password\" name=\"myaccount_password_current\" id=\"myaccount_password_current\" value=\"\" placeholder=\"Current Password\">
                                <input type=\"password\" name=\"myaccount_password_new\" id=\"myaccount_password_new\" value=\"\" placeholder=\"New Password\">
                                <input type=\"password\" name=\"myaccount_password_new_again\" id=\"myaccount_password_new_again\" value=\"\" placeholder=\"Retype New Password\">
                            </div>
                            <div id=\"myaccount_submit\" class=\"submit_button\">
                                Submit
                            </div>
                            <div id=\"myaccount_message\"></div>
                        </div>
                    </div>";

        $mychange = "" . $changelink . "" . $changemodal . "";

        // SET FORGOT PASSWORD LINK AND MODAL WINDOW
        $forgotlink = "<a class=\"changepasswordlb\" href=\"#\">Forgot Password</a>";
        $forgotmodal = "
                    <div style=\"display:none;\">
                        <div id=\"changepasswordlbcontent\" class=\"standardlbcontent\">
                            <div class=\"standardlbtop\">
                                <span class=\"standardlbtitle\">Forgot Password</span><span class=\"colorboxclose\"></span>
                                <div style=\"clear:both;\"></div>
                            </div>
                            <div class=\"standardlbbottom\">
                                <input type=\"text\" name=\"forgotpass_email\" id=\"forgotpass_email\" value=\"\" placeholder=\"Email Address\">
                                <div id=\"forgotpass_submit\" class=\"submit_button\">
                                    Submit
                                </div>
                                <div id=\"forgotpass_message\"></div>
                            </div>
                        </div>
                    </div>";

        $myforgotpassword = "" . $forgotlink . "" . $forgotmodal . "";

        $this->register = $myregister;
        $this->login = $mylogin;
        $this->logout = $mylogout;
        $this->changepassword = $mychange;
        $this->forgotpassword = $myforgotpassword;
    }

    function get_logreg($action)
    {
        if ($action == 'register') {
            return $this->register;
        } elseif ($action == 'login') {
            return $this->login;
        } elseif ($action == 'logout') {
            return $this->logout;
        } elseif ($action == 'changepassword') {
            return $this->changepassword;
        } elseif ($action == 'forgotpassword') {
            return $this->forgotpassword;
        }
    }
}
?>