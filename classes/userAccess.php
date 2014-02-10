<?php
class userAccess {
	protected $_db,
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
			$field = (is_numeric($user)) ? 'id' : 'email';
			$data = $this->_db->get('user_access', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('user_access', $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}

	public function update($fields = array(), $id = null) {
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}

		if(!$this->_db->update('user_access', $id, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public function login($email = null, $password = null, $remember = false) {

		if(!$email && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);
		} else {
			$user = $this->find($email);

			if($user) {
                // GET USER'S REGISTRATION AND PASSWORD RESET DATETIMES
                $userregdate = $this->data()->regdatetime;
                $passresettime = $this->data()->reset_time;
                if($passresettime) {
                    $passworddate = $passresettime;
                } else {
                    $passworddate = $userregdate;
                }

                // GET SALT EXTENSIONS BASED ON REGISTRATION DATE OR PASSWORD RESET TIME
                $saltdata = DB::getInstance();
                try {
                    $saltdata->query("SELECT * FROM salts WHERE from_datetime <= '$passworddate' AND (to_datetime >= '$passworddate' OR to_datetime IS NULL)");
                    if ($saltdata->count()) {
                        $saltresult = $saltdata->first();
                        $prefix = $saltresult->prefix;
                        $suffix = $saltresult->suffix;
                        $password = $prefix . $password . $suffix;
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }

				if($this->data()->current_password === Hash::make($password, $this->data()->salt)) {
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
}
?>