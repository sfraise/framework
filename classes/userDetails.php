<?php
class userDetails extends userAccess {
    public function find($user = null) {
        // Check if user_id specified and grab details
        if($user) {
            $field = (is_numeric($user)) ? 'id' : 'email';
            $data = $this->_db->get('user_details', array($field, '=', $user));

            if($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function update($fields = array(), $id = null) {
        if(!$id && $this->isLoggedIn()) {
            $id = $this->data()->user_id;
        }

        if(!$this->_db->update('user_details', $id, $fields)) {
            throw new Exception('There was a problem updating.');
        }
    }

    public function create($fields = array()) {
        if(!$this->_db->insert('user_details', $fields)) {
            throw new Exception('There was a problem creating an account.');
        }
    }

    // USER PROFILE FIELDS
    /*
     * $myid = the viewer's id
     * $field = user_details table column name (eg. first_name, last_name, city, etc)
     * $type = input type (eg. text, textarea, image)
     */
    public function userFields($myid, $field, $type) {
        if($this->data()->user_id == $myid) {
            $divid = "my_profile_field_".$field."";
            $imgid = "my_profile_image";
        } else {
            $divid = "profile_field_".$field."";
            $imgid = "profile_image";
        }
        if($field == 'image') {
            $field_data = "<img id=\"".$imgid."\" src=\"\" alt=\"".$this->data()->first_name."\" title=\"".$this->data()->first_name."\" />";
        } else {
            $field_data = $this->data()->$field;
        }

        if(!$field_data) {
            $field_data = 'NA';
        }

        if($type == 'text') {
            $field_input = "<input type=\"text\" id=\"edit_profile_".$field."\" value=\"".$field_data."\" placeholder=\"".$field."\" /> - <a id=\"submit_profile_input_".$field."\" class=\"submit_profile_input\" rel=\"".$field."\">Submit</a> <a id=\"cancel_profile_input_".$field."\" class=\"cancel_profile_input\" rel=\"".$field."\">Cancel</a>";
        } elseif($type == 'textarea') {
            $field_input = "<textarea id=\"edit_profile_".$field."\" cols=\"30\" placeholder=\"".$field."\">".$field_data."</textarea> - <a id=\"submit_profile_input_".$field."\" class=\"submit_profile_input\" rel=\"".$field."\">Submit</a> <a id=\"cancel_profile_input_".$field."\" class=\"cancel_profile_input\" rel=\"".$field."\">Cancel</a>";
        } elseif($type == 'image') {
            $field_input = "Not built yet - <a id=\"cancel_profile_input_".$field."\" rel=\"".$field."\">Cancel</a>";
        } else {
            $field_input = 'Please enter the type method';
        }

        if($field == 'current_password' || $field == 'salt' || $field == 'regdatetime' || $field == 'user_group') {
            $field_display = 'This field is private';
        } else {
            $field_display = "
                        <span id=\"".$divid."\" class=\"my_profile_field\" rel=\"".$field."\">
                            ".$field_data."
                        </span>
                        <span id=\"profile_input_".$field."\" class=\"profile_input\" style=\"display:none;\">
                                ".$field_input."
                        </span>
                        ";
        }

        return $field_display;
    }
}
?>