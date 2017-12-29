# Connection Instruction #
   
This package have classes to connection and queries for Database Management System (DBMS [or SGBD, in Portuguese]).   
Currently it can connect to a database using MySQLi or PDO extension and execute several types of queries from parameters that define tables, fields, values and conditions.   
   
#### Folder:    
* /php/connection/MysqliInstruction.php `MysqliInstruction()`
* /php/connection/PDOInstruction.php `PDOInstruction()`
   
## Example  	

#### MysqliInstruction()   
```php
/* Autoload Include */
use \Connection\MysqliInstruction;

$mysqli = new MysqliInstruction();
$mysqli->connect();
$resp = $mysqli->select('SELECT * FROM table WHERE id < ?', array('10'));
$resp = $mysqli->generic('DELETE FROM table WHERE id = ?', array('2'));
var_dump($resp);
$mysqli->end();
```
   
#### PDOInstruction()   
```php
/* Autoload Include */
use \Connection\PDOInstruction;

$pdo = new PDOInstruction();
$pdo->connect();
$resp = $pdo->select('SELECT * FROM table WHERE id < ?', array('10'));
$resp = $pdo->generic('DELETE FROM table WHERE id = ?', array('2'));
var_dump($resp);
$pdo->end();
```
   
## Also look ~  	
* [License MIT](https://opensource.org/licenses/MIT)
* Create by Leonardo Mauro (leo.mauro.desenv@gmail.com)
* Git: [leomaurodesenv](https://github.com/leomaurodesenv/)
* Site: [Portfolio](http://leonardomauro.com/portfolio/)
