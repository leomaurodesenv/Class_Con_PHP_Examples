<?php

/* Autoload Include */
require('../dist/php/autoload.php');

use \Connection\MysqliInstruction;
use \Connection\PDOInstruction;

/** 
* Example: Litle example to show some methods
*/

/* Example (MYSQLI_instruction) */

$mysqli = new MysqliInstruction();
$mysqli->connect();
$resp = $mysqli->select('SELECT * FROM table WHERE id < ?', array('10'));
$resp = $mysqli->generic('DELETE FROM table WHERE id = ?', array('2'));
var_dump($resp);
$mysqli->end();


/* Example (PDO_instruction) */

$pdo = new PDOInstruction();
$pdo->connect();
$resp = $pdo->select('SELECT * FROM table WHERE id < ?', array('10'));
$resp = $pdo->generic('DELETE FROM table WHERE id = ?', array('2'));
var_dump($resp);
$pdo->end();

?>