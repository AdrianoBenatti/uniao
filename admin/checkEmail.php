<?php
include "functions.php";

$id = $_GET['id'];
$email = $_GET['email'];
$acao = $_GET['acao'];
$tabela = $_GET['tabela'];

$connect = conecta();

if($acao == "Incluir"){
	$query = mysql_query("SELECT id FROM $tabela where email = '$email'", $connect);
	if (mysql_num_rows($query)) echo "false";
	else echo "true";
}else{
	$query = mysql_query("SELECT id FROM $tabela WHERE email = '$email'", $connect);
	$d = mysql_fetch_array($query);
	if($d['id'] != $id && $d['id']){
		echo "false";
	}else{
		echo "true";
	}
}

?>
