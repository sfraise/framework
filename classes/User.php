<?php
class User {
	private $_db,
			$_sessionName = null,
			$_cookieName = null,
			$_data = array(),
			$_isLoggedIn = false;

	public function __construct($user = null) {
		$this->_db = DB::getInstance();
		
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		// Check if a session exists and set user if so.
		if(Session::exists($this->_sessionName) && !$user) {
			$user = Session::get($this->_sessionName);

			if($this->find($user)) {
				$this->_isLoggedIn = true;
			} else {
				$this->logout();
			}
		} else {
			$this->find($user);
		}
	}

	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	public function find($user = null) {
		// Check if user_id specified and grab details
		if($user) {
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('users', $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}

	public function update($fields = array(), $id = null) {
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}
		
		if(!$this->_db->update('users', $id, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public function login($username = null, $password = null, $remember = false) {

		if(!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);
		} else {
			$user = $this->find($username);

			if($user) {
				if($this->data()->password === Hash::make($password, $this->data()->salt)) {
					Session::put($this->_sessionName, $this->data()->id);

					if($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));

						if(!$hashCheck->count()) {
							$this->_db->insert('users_session', array(
								'user_id' => $this->data()->id,
								'hash' => $hash
							));
						} else {
							$hash = $hashCheck->first()->hash;
						}

						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}

					return true;
				}
			}
		}

		return false;
	}

	public function hasPermission($key) {
		$group = $this->_db->query("SELECT * FROM groups WHERE id = ?", array($this->data()->user_group));
		
		if($group->count()) {
			$permissions = json_decode($group->first()->permissions, true);

			if($permissions[$key] === 1) {
				return true;
			}
		}

		return false;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

	public function data() {
		return $this->_data;
	}

	public function logout() {
		$this->_db->delete('users_session', array('user_id', '=', $this->data()->id));

		Cookie::delete($this->_cookieName);
		Session::delete($this->_sessionName);
	}

    // USER PROFILE FIELDS
    /*
     * $myid = the viewer's id
     * $field = users table column name (eg. firstname, lastname, city, etc)
     * $type = input type (eg. text, textarea, image)
     */
    public function userFields($myid, $field, $type) {
        if($this->data()->id == $myid) {
            $divid = "my_profile_field_".$field."";
            $imgid = "my_profile_image";
        } else {
            $divid = "profile_field_".$field."";
            $imgid = "profile_image";
        }
        if($field == 'image') {
            $field_data = "<img id=\"".$imgid."\" src=\"\" alt=\"".$this->data()->firstname."\" title=\"".$this->data()->firstname."\" />";
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

        if($field == 'password' || $field == 'salt' || $field == 'joined' || $field == 'user_group') {
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