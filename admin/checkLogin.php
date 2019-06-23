<?php
include "functions.php";

$id = $_GET['id'];
$login = $_GET['login'];


$connect = conecta();

if($acao == "Incluir"){
	$query = mysql_query("SELECT id FROM $tabela where login = '$login'", $connect);
	if (mysql_num_rows($query)) echo "false";
	else echo "true";
}else{
	$query = mysql_query("SELECT id FROM $tabela where login = '$login'", $connect);
	$d = mysql_fetch_array($query);
	if($d['id'] != $id && $d['id']){
		echo "false";
	}else{
		echo "true";
	}
}

?>
