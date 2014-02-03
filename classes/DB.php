<?php
class DB {
	public static $instance = null;

	private 	$_mysqli = null,
				$_query = null,
				$_error = false,
				$_results = null,
				$_count = 0;

	private function __construct() {
        define("MYSQL_CONN_ERROR", "Unable to connect to database.");

        // Ensure reporting is setup correctly
        mysqli_report(MYSQLI_REPORT_STRICT);

        try {
			$this->_mysqli = new mysqli(Config::get('mysql/host'), Config::get('mysql/email'), Config::get('mysql/password'), Config::get('mysql/db'));
		} catch(mysqli_sql_exception $e) {
			die($e->getMessage());
		}
	}

	public static function getInstance() {
		// Already an instance of this? Return, if not, create.
		if(!isset(self::$instance)) {
			self::$instance = new DB();
		}
		return self::$instance;
	}

	public function query($sql, $params = array()) {

		$this->_error = false;

		if($this->_query = $this->_mysqli->prepare($sql)) {
			if(count($params)) {
                $type = '';                             //initial string with types
                $values = array();
                foreach ($params as $key => $value) {   //for each element, determine type and add
                    if (is_int($value)) {
                        $type .= 'i';                   //integer
                    } elseif (is_float($value)) {
                        $type .= 'd';                   //double
                    } elseif (is_string($value)) {
                        $type .= 's';                   //string
                    } else {
                        $type .= 'b';                   //blob and unknown
                    }

                    $values[] = $value;                 //set the param value array
                }

                //  BUILD THE TYPE ARRAY
                $bind_names = array();
                $bind_names[] = $type;

                for ($i=0; $i<count($values);$i++) {    //go through incoming params and add them to array
                    $bind_name = 'bind' . $i;           //give them an arbitrary name
                    if(isset($values[$i])) {
                        $$bind_name = $values[$i];      //add the parameter to the variable variable
                    }
                    $bind_names[] = &$$bind_name;       //now associate the variable as an element in an array
                }

                // BIND THE DYNAMIC PARAMS AND TYPES
                call_user_func_array(array($this->_query,'bind_param'),$bind_names);
			}

			if($this->_query->execute()) {
                $results = $this->_query->get_result();

                if($results) {
                $this->_results = array();
                while ($result = $results->fetch_object()) {
                    $this->_results[] = $result;
                }

				$this->_count = $results->num_rows;
                }
			} else {
				$this->_error = true;
                printf($this->_mysqli->error);
			}
		}
		return $this;
	}

	public function get($table, $where) {
		return $this->action('SELECT *', $table, $where);
	}

	public function delete($table, $where) {
		return $this->action('DELETE', $table, $where);
	}

	public function action($action, $table, $where = array()) {
		if(count($where) === 3) {
			$operators = array('=', '>', '<', '>=', '<=');

			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];

			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

				if(!$this->query($sql, array($value))->error()) {
					return $this;
				}

			}

			return false;
		}
	}

	public function insert($table, $fields = array()) {
		$keys 	= array_keys($fields);
		$values = null;
		$x 		= 1;

		foreach($fields as $value) {
			$values .= "?";
			if($x < count($fields)) {
				$values .= ', ';
			}
			$x++;
		}

		$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

		if(!$this->query($sql, $fields)->error()) {
			return true;
		}

		return false;
	}

	public function update($table, $id, $fields = array()) {
		$set 	= null;
		$x		= 1;

		foreach($fields as $name => $value) {
			$set .= "{$name} = ?";
			if($x < count($fields)) {
				$set .= ', ';
			}
			$x++;
		}

		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

		if(!$this->query($sql, $fields)->error()) {
			return true;
		}

		return false;
	}

    public function getColumnNames($table) {
        $fields = array();
        $columns = DB::getInstance()->query("select column_name from information_schema.columns where table_name='$table'");
        if(!$columns->count()) {
            echo 'error';
        } else {
            foreach($columns->results() as $column) {
                if($column->column_name !== 'id') {
                    $fields[] = $column->column_name;
                }
            }
            return $fields;
        }
    }

    public function results() {
		// Return result object
		return $this->_results;
	}

	public function first() {
		return $this->_results[0];
	}

	public function count() {
		// Return count
		return $this->_count;
	}

	public function error() {
		return $this->_error;
	}
}