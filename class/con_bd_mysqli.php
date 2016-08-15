<?php

/** 
 * This class can bind params, used in prepare statement of MySQLi.
 * 
 * It can bind params.
 * 
 * @author Leonardo Mauro <leo.mauro.desenv@gmail.com>
 * @link https://github.com/leomaurodesenv/Class_Con_PHP_Examples GitHub
 * @link https://opensource.org/licenses/MIT MIT License (MIT)
 * @version 1.0.1 16-08-15
 * @copyright 2016 Leonardo Mauro
 * @license MIT
 * @package CON
 * @access public
 */ 

class MYSQLI_bind_param{ 
	
	/** @var array $values Represents values to be bind. */
	private $values = array();
	/** @var string $types Represents type of each value, see more about: <http://php.net/manual/pt_BR/mysqli-stmt.bind-param.php>. */
	private $types = '';
	
	/**
	 * Add values.
	 * @access public
	 * @param string $type Types of values: i (int); d (double); s (string); and b (blob).
	 * @param array &$value Values to be bind
	 * @return void
	 */
	public function add($type, &$value){
		$this->values[] = &$value;
		$this->types .= $type;
	}
	
	/**
	 * Get bind values.
	 * @access public
	 * @param void
	 * @return array
	 */
	public function get(){
		return array_merge(array($this->types), $this->values);
	}
}

/** 
 * This class can execute DBMS scripts with MySQLi Statement.
 * 
 * It can prepare statement and execute functions of databeses, like: select, update, delete, create and drop.
 * This class are generic, and can be used for all MySQL querys Like <http://php.net/manual/pt_BR/mysqli.query.php>.
 * 
 * @author Leonardo Mauro <leo.mauro.desenv@gmail.com>
 * @link https://github.com/leomaurodesenv/Class_Con_PHP_Examples GitHub
 * @link https://opensource.org/licenses/MIT MIT License (MIT)
 * @version 1.0.2 16-08-15
 * @copyright 2016 Leonardo Mauro
 * @license MIT
 * @package CON
 * @access public
 */ 
class MYSQLI_instruction{

	/** @var object $stmt Represents a prepared statement. */
	protected $stmt;
	/** @var object $mysqli MySQLi connection. */
	private $mysqli;
	/** @var array $resp This is associated result of statement. */
	protected $resp;
	
	/**
	 * MySQLi Connection.
	 * @access public
	 * @param void
	 * @return bool
	 */
	function con_mysqli(){
		$host_sql = "127.0.0.1";
		$user_sql = "root";
		$pass_sql = "";
		$bd_sql = "banco_de_dados";
		$this->mysqli = new mysqli($host_sql, $user_sql, $pass_sql, $bd_sql);
		
		if($this->mysqli->connect_errno || !$this->mysqli->set_charset("utf8")) return false;
			/* echo "Error MySQL: ".$mysqli->error; */
		return true;
	}
	
	/**
	 * Close MySQLi Connection.
	 * @access public
	 * @param void
	 * @return void
	 */
	function end_con_sql(){
		$this->mysqli->close();
	}
	
	/**
	 * SELECT functions (DBMS), this function prepare and execute statements, return array of SELECT results.
	 * @access public
	 * @param string $query Query of DBMS. Example <http://php.net/manual/pt_BR/mysqli-stmt.bind-param.php>.
	 * @param string $types Types of values: i (int); d (double); s (string); and b (blob).
	 * @param array|null $params Params bind query | null if don't have any param.
	 * @return false|array
	 */
	function select_mysqli($query,$types='',$params=[]){
		$cont = count($params);
		$this->resp = array();
		
		if($cont > 0){
			$this->stmt = $this->mysqli->prepare($query);
			$bind_param = new MYSQLI_bind_param();
			for($i=0; $i<count($params); $i++){
				$bind_param->add($types[$i], $params[$i]);
			}
            $return = call_user_func_array(array($this->stmt,'bind_param'),$bind_param->get());
			$this->stmt->execute();
			$meta = $this->stmt->result_metadata();
			while($field = $meta->fetch_field()){
				$var = $field->name;
				$$var = null;
				$parameters[$field->name] = &$$var;
			}
			call_user_func_array(array($this->stmt, 'bind_result'), $parameters);
			while($this->stmt->fetch()){
				$data_par = array();
				foreach($parameters as $k1 => $v1) $data_par[$k1] = $v1;
				array_push($this->resp, $data_par);
			}
			$this->stmt->close();
		}
		else{
			$execute = $this->mysqli->query($query);
			while($row = mysqli_fetch_array($execute, MYSQLI_ASSOC)){
				array_push($this->resp, $row);
			}
		}
		if(!$this->resp) return false;
		else return $this->resp;
	}
	
	/**
	 * Another functions (DBMS), like: DELETE, UPDATE, CREATE (TABLE) and DROP (TABLE).
	 * @access public
	 * @param string $query Query of DBMS. Example <http://php.net/manual/pt_BR/mysqli-stmt.bind-param.php>.
	 * @param string $types Types of values: i (int); d (double); s (string); and b (blob).
	 * @param array|null $params Params bind query | null if don't have any param.
	 * @return bool
	 */
	function generic_sql_mysqli($query,$types='',$params=[]){
		$cont = count($params);
		$this->resp = false;
		if($cont > 0){
			$this->stmt = $this->mysqli->prepare($query);
			$bind_param = new MYSQLI_bind_param(); 
			for($i=0; $i<count($params); $i++){
				$bind_param->add($types[$i], $params[$i]);
			}
            $return = call_user_func_array(array($this->stmt,'bind_param'),$bind_param->get());
			$this->resp = $this->stmt->execute();			
		}
		else $this->resp = $this->mysqli->query($query);

		if(!$this->resp)
			return "Error Query MySQL: (".$this->stmt->errno.") ".$stmt->error;
		else
			return $this->resp;
	}

}


/* Example (MYSQLI_instruction) *

$mysqli = new MYSQLI_instruction();
$mysqli->con_mysqli();
$resp = $mysqli->select_mysqli('SELECT * FROM tabela WHERE id_tabela < ?', 'i', array('10'));
var_dump($resp);
$mysqli->end_con_sql();

//*/
?>