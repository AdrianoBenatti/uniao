<?php 
session_start();
include "functions.php";
conecta();

$tabela = $_GET['tabela'];

$ordem = str_replace("||", "", $_GET['ordem']);
$ordem = explode("|", $ordem);

if($ordem <> "undefined" && $tabela <> "undefined"){
	foreach($ordem as $o){
		$ord = explode("-", $o);
		$q = mysql_query("UPDATE {$tabela}_imagens SET ordem = '".$ord[1]."' WHERE id = ".$ord[0]);
	}
}

?>