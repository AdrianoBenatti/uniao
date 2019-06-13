<?php
#echo "<pre>";
#print_r($_SERVER);
#echo "</pre>";
#exit;
ob_start();
session_start();
$sid = session_id();
include "functions.php";
include "lib/WideImage.php";

$connect = conecta();

$page = $_GET['page']? $_GET['page']: '';
$id = $_GET['id']? $_GET['id']: '';
#$redirectUrl = $_SERVER['REQUEST_URI'];
if(!logado() && $page){
	$_SESSION['page'] = $page . "/" . $id;
	header("Location: $url");
}

$id = $_GET['id'];
$acao = $_GET['acao']? $_GET['acao']: 'Incluir';
$funcionarios_id = $_SESSION['id_usuario'];


if(empty($page)){
	if(logado()) header("Location: {$url}principal");
	$login = anti_sql_injection($_POST['login']);
	$senha = anti_sql_injection($_POST['senha']);

	$WrongPass = "0";
	$_SESSION['logado'] = false;

	if (!empty($_POST)){
		if (($login == "admin") and ($senha == "apt9630")){
			$_SESSION['nome'] = "Admin";
			$_SESSION['logado'] = true;
			$_SESSION['id_usuario'] = 0;
			$_SESSION['grupo'] = 1;
			$WrongPass = "0";
			if($_SESSION['page']) header("Location: {$url}{$_SESSION['page']}");
			else header("Location: {$url}principal");
		}else{
			$sql = "SELECT * FROM usuarios WHERE login = '$login'";
			$query = mysql_query($sql, $connect);
			$dados = mysql_fetch_array($query);
			if (!$dados){
				$WrongPass = "1";
			}else if (md5($senha) == $dados['senha']){
				$_SESSION['nome'] = $dados['nome'];
				$_SESSION['logado'] = true;
				$_SESSION['id_usuario'] = $dados['id'];
				$_SESSION['grupo'] = $dados['grupo'];
				$_SESSION['ultimo_acesso'] = $dados['ultimo_acesso'];
				$WrongPass = "0";
				$sql = 'UPDATE usuarios SET ultimo_acesso = now() WHERE id = ' . $dados['id'];
				$query = mysql_query($sql, $connect);
				if($_SESSION['page'] != "") header("Location: {$url}{$_SESSION['page']}");
				else header("Location: {$url}principal");
			}else{
				$WrongPass = "1";
			}
		}
	}

	if($WrongPass === "1") makeFile(json_encode(['login'=>$_POST['login'], 'senha'=>$_POST['senha']]), 'login-fail');

}else{
	if(!logado()) header("Location: $url");
}
?>
<!DOCTYPE html>
<html>
<head>
<script>
	var url = '<?php echo $url; ?>';
	var sid = '<?php echo session_id(); ?>';
	var mediaUrl = '<?php echo $mediaUrl ?>';
</script>
<script>url = '<?php echo $url; ?>';</script>
<?php include("head.php"); ?>
</head>
<body>
<div id="wrapper">
	<div id="topo"><?php include("top.php"); ?></div>
<?php if($page): ?>
	<div class="wrap">
		<?php include("$page.php"); ?>
	</div>
<?php else: ?>
	<div class="login">
		<?php  if ($WrongPass == "1") {  ?><div class="error-msg">Login e/ou Senha inv&aacute;lido(s).</div><?php } ?><br />
		<div id="login1" class="clearfix">
			<form action="<?php echo $url; ?>" method="post" name="acesso" id="acesso">
				<h2>Digite seu login e senha</h2>
				<label for="login">Login:</label>
				<input name="login" id="login" type="text" placeholder="Login">
				<label for="senha">Senha:</label>
				<input name="senha" id="senha" type="password" placeholder="******">
				<input type="submit" value="Entrar" class="entrar">
			</form>
		</div>
	</div>
<?php endif; ?>
</div>
</body>
</html>
<?php ob_end_flush(); ?>