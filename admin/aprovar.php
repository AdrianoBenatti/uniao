<?php

$id = anti_sql_injection($_GET['id']);
$q = mysql_query("SELECT * FROM cadastros WHERE id = '$id'") or die(mysql_error());

if(mysql_num_rows($q) == 0) {
	echo "<div id='conteudo'><div class='wrapPrincipal'><h1 style='color: red;'>Não foi possivel encontrar nenhum registro</h1></div></div>";
	exit;
}

$row = mysql_fetch_row($q);

if($row[9] == "1") {
	echo "<div id='conteudo'><div class='wrapPrincipal'><h1 style='color: red;'>Este cadastro já está aprovado!</h1></div></div>";
	exit;
}

$data = [
	'aprovado' => '1',
	'data_aprovacao' => date('Y-m-d H:i:s')
];

save($data, 'cadastros', $row[0]);
echo "<div id='conteudo'><div class='wrapPrincipal'><h1>Registro aprovado com sucesso!</h1></div></div>";

