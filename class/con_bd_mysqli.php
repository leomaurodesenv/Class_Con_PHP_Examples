<?php

/*--- Classe responsavel por executar o bind_param ---*/
class MYSQLI_bind_param{ 
	private $values = array(), $types = '';
	
	public function add($type, &$value){
		$this->values[] = &$value;
		$this->types .= $type;
	}
	public function get(){
		return array_merge(array($this->types), $this->values);
	}
}

/*--- Classe responsável por Instruções Sql ---*/
class MYSQLI_instruction{
/*--- Váriaveis de escopo ---*/
	protected $stmt, $mysqli, $resp;
	/*--- Função responsável pela conexao com o banco de dados ---*/
	function con_mysqli(){
		$host_sql = "127.0.0.1";
		$user_sql = "root";
		$pass_sql = "";
		$bd_sql = "banco_de_dados";
		$this->mysqli = new mysqli($host_sql, $user_sql, $pass_sql, $bd_sql);
		
		if($this->mysqli->connect_errno || !$this->mysqli->set_charset("utf8")) return false;
			//echo "Error MySQL: ".$mysqli->error;
		return true;
	}
	/*--- Função responsável por encerrar a conexão ---*/
	function end_con_sql(){
		$this->mysqli->close();
	}
	/*--- Função responsável por selecionar campos no banco de dados ---*/
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
	
	/*--- Função para inserir no banco de dados ---*/
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


/* Teste da classe MYSQLI_instruction */
$mysqli = new MYSQLI_instruction();
$mysqli->con_mysqli();
$resp = $mysqli->select_mysqli('SELECT * FROM tabela WHERE id_tabela < ?', 's', array('10'));
var_dump($resp);
$mysqli->end_con_sql();

//*/
?>
