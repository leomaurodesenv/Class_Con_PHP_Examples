<?php

/*--- Classe responsável por Instruções Sql ---*/
class PDO_instruction{
/*--- Váriaveis de escopo ---*/
	protected $stmt, $pdo, $resp;
	/*--- Função responsável pela conexao com o banco de dados ---*/
	function con_pdo(){
		$database = 'mysql';
		$host_sql = "127.0.0.1";
		$user_sql = "root";
		$pass_sql = "";
		$bd_sql = "banco_de_dados";
		
		try{
			$this->pdo = new PDO($database.':host='.$host_sql.';dbname='.$bd_sql, $user_sql, $pass_sql);
		}
		catch(PDOException $e){
			echo 'Error!: '.$e->getMessage().'<br>';
			return false; die();
		}
		return true;
	}
	/*--- Função responsável por encerrar a conexão ---*/
	function end_con_pdo(){
		$this->stmt = null;
		$this->pdo = null;
	}
	/*--- Função responsável por selecionar campos no banco de dados ---*/
	function select_pdo($query='', $params=[]){
		$this->resp = array();
		
		$this->stmt = $this->pdo->prepare($query);
		/* mark placeholders: $sth->bindParam(1, $calories, PDO::PARAM_INT); */
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
	
	/*--- Função para inserir, editar e excluir no banco de dados ---*/
	function generic_sql_pdo($query='',$params=[]){
		$this->resp = false;
		
		$this->stmt = $this->pdo->prepare($query);
		/* mark placeholders: $sth->bindParam(1, $calories, PDO::PARAM_INT); */
		$this->resp = $this->stmt->execute($params);
		
		$error = $this->stmt->errorInfo();
		if(!$this->resp) echo 'Error Query: ('.$this->stmt->errorCode().') '.$error[2].'<br>';
		return $this->resp;
	}

}


/* Teste da classe PDO_instruction */
$pdo = new PDO_instruction();
$pdo->con_pdo();
$resp = $pdo->select_pdo('SELECT * FROM tabela WHERE id_tabela < ?', array('10'));
var_dump($resp);
$pdo->end_con_pdo();

//*/
?>