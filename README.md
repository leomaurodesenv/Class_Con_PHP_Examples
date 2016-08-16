# Classes_Con_PHP #
   
This package have classes to connection and queries for Database Management System (DBMS [or SGBD, in Portuguese]).   
Currently it can connect to a database using MySQLi or PDO extension and execute several types of queries from parameters that define tables, fields, values and conditions.   
   
* Folder:
   * /class/class.mysqli.instruction.php `MYSQLI_instruction()`
   * /class/class.pdo.instruction.php `PDO_instruction()`
   
## Example  	

* MYSQLI_instruction()   
```php
$mysqli = new MYSQLI_instruction();
$mysqli->con_mysqli();
$resp = $mysqli->select_mysqli('SELECT * FROM table WHERE id_table < ?', 'i', array(10));
var_dump($resp);
$resp = $mysqli->generic_sql_mysqli('UPDATE table SET year = ? WHERE name = ?', 'is', array(12, 'bob'));
var_dump($resp);
$mysqli->end_con_sql(); 
```
   
   
* PDO_instruction()   
```php
$pdo = new PDO_instruction();
$pdo->con_pdo();
$resp = $pdo->select_pdo('SELECT * FROM table WHERE id_table < ?', array(10));
var_dump($resp);
$resp = $pdo->generic_sql_pdo('UPDATE table SET year = ? WHERE name = ?', array(12, 'bob'));
var_dump($resp);
$pdo->end_con_pdo(); 
```
   
## Also look ~  	
* [License MIT][mit]
* Create by Leonardo Mauro ([leo.mauro.desenv@gmail.com][email])
* Git: [leomaurodesenv][git]
* Site: [Portfolio][leomauro]
   
[mit]: https://opensource.org/licenses/MIT
[email]: leo.mauro.desenv@gmail.com
[git]: https://github.com/leomaurodesenv/
[leomauro]: http://leonardomauro.com