<?php

/** 
 * This class can execute DBMS scripts with PDO Statement.
 * 
 * It can prepare statement and execute functions of databeses, like: select, update, delete, create and drop.
 * This class are generic, and can be used in all 'PDO Drivers' <http://php.net/manual/pt_BR/pdo.drivers.php>.
 * 
 * @author Leonardo Mauro <leo.mauro.desenv@gmail.com>
 * @link https://github.com/leomaurodesenv/Class_Con_PHP_Examples GitHub
 * @link https://opensource.org/licenses/MIT MIT License (MIT)
 * @version 1.2.1 17-12-29
 * @copyright 2017 Leonardo Mauro
 * @license MIT
 * @package Connection
 * @access public
 */ 

namespace Connection;

use \PDO;

class PDOInstruction{
	
	/** @var object $stmt Represents a prepared statement. */
	protected $stmt;
	/** @var object $pdo PDO connection. */
	private $pdo;
	/** @var array $resp This is associated result of statement. */
	protected $resp;
	
	/**
	 * PDO Connection.
	 * @access public
	 * @param string|null $database Set DBMS
	 * @return bool
	 */
	function connect($database='mysql'){
		$host_sql = "127.0.0.1";
		$user_sql = "root";
		$pass_sql = "";
		$bd_sql = "database";
		
		try{
			$this->pdo = new PDO($database.':host='.$host_sql.';dbname='.$bd_sql, $user_sql, $pass_sql);
		}
		catch(PDOException $e){
			echo 'Error!: '.$e->getMessage().'<br>';
			return false; die();
		}
		return true;
	}
	
	/**
	 * Close PDO Connection.
	 * @access public
	 * @return void
	 */
	function end(){
		$this->stmt = null;
		$this->pdo = null;
	}
	
	/**
	 * SELECT functions (DBMS), this function prepare and execute statements, return array of SELECT results.
	 * @access public
	 * @param string $query Query of DBMS. Setin bind params use '?', example <http://php.net/manual/pt_BR/pdostatement.bindparam.php>
	 * @param array|null $params Params bind query | null if don't have any param.
	 * @return false|array
	 */
	function select($query='', $params=[]){
		$this->resp = array();
		
		$this->stmt = $this->pdo->prepare($query);
		/* http://php.net/manual/pt_BR/pdostatement.bindparam.php */
		$this->stmt->execute($params);
		
		$error = $this->stmt->errorInfo();
		if($error[0] != 0) 
			echo 'Error Query: ('.$this->stmt->errorCode().') '.$error[2].'<br>';
		
		while($result = $this->stmt->fetch(PDO::FETCH_ASSOC)){
			$this->resp[] = $result;
		}
		
		if(!$this->resp) return false;
		else return $this->resp;
	}
	
	/**
	 * Another functions (DBMS), like: DELETE, UPDATE, CREATE (TABLE) and DROP (TABLE).
	 * @access public
	 * @param string $query Query of DBMS. Setin bind params use '?', example <http://php.net/manual/pt_BR/pdostatement.bindparam.php>
	 * @param array|null $params Params bind query | null if don't have any param.
	 * @return bool
	 */
	function generic($query='', $params=[]){
		$this->resp = false;
		
		$this->stmt = $this->pdo->prepare($query);
		/* http://php.net/manual/pt_BR/pdostatement.bindparam.php */
		$this->resp = $this->stmt->execute($params);
		
		$error = $this->stmt->errorInfo();
		if(!$this->resp) echo 'Error Query: ('.$this->stmt->errorCode().') '.$error[2].'<br>';
		return $this->resp;
	}

}


/* Example (PDO_instruction) *

$pdo = new PDOInstruction();
$pdo->connect();
$resp = $pdo->select('SELECT * FROM table WHERE id < ?', array('10'));
$resp = $pdo->generic('DELETE FROM table WHERE id = ?', array('2'));
var_dump($resp);
$pdo->end();

//*/
?>