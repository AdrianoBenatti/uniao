<?php

include("../admin/functions.php");

require '../mail/PHPMailerAutoload.php';

foreach($_POST as $k => $v){
	$$k = $v;
}

if ($valida != 'validado'){
	echo "error";
	exit;
}

$mensagem = "	<html>
						<head>
							<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
							<style type='text/css'>
							body,p,table {color: #3F3F3F;font: 12px 'Trebuchet MS', Arial, Helvetica, sans-serif;}
							p {padding: 0; margin: 1em 0;}
							.geral table {border:1px #ccc solid; padding: 10px;}
							</style>
						</head>
						<body>
							<p align='center' style=''>
								<img src='{$url}/images/logo.png'>
							</p>
							<table width='650' border='0' align='center' class='geral'>
								<tr>
									<td>
										<p style='color:#3F3F3F;font: 12px Trebuchet MS, Arial, Helvetica, sans-serif;padding: 0; margin: 1em 0;'> %HEAD% entrou em contato conosco através do site e nos enviou as seguintes informações:</p>
										<br>
										<h3 style='color:#3F3F3F;font: 14px Trebuchet MS, Arial, Helvetica, sans-serif;'>Dados do Cliente</h3>
										<table width='100%' border:1px #ccc solid; padding: 10px;>
											<tr>
												<td style='color:#3F3F3F;font: 12px Trebuchet MS, Arial, Helvetica, sans-serif;'>
													<strong>Nome:</strong> {$nome}<br>
													<strong>E-mail:</strong> {$email} <br>
													<strong>Celular:</strong> {$celular} <br>
													<strong>Telefone:</strong> {$telefone} <br>

												</td>
											</tr>
										</table>
										
										<h3 style='color:#3F3F3F;font: 14px Trebuchet MS, Arial, Helvetica, sans-serif;'>Observações</h3>
										<table width='100%' border:1px #ccc solid; padding: 10px;>
											<tr>
												<td style='color:#3F3F3F;font: 12px Trebuchet MS, Arial, Helvetica, sans-serif;'>
													{$obs}
												</td>
											</tr>
										</table>
										
										<p style='color:#3F3F3F;font: 12px Trebuchet MS, Arial, Helvetica, sans-serif;padding: 0; margin: 1em 0;'>Obrigado,<br /><strong>E.C. União Santa Luiza</strong></p>
									</td>
								</tr>
							</table>
						</body>
					</html>";

$head_cliente = "<strong>Ol&aacute; {$nome}</strong>, você ";
$head_empresa = "<strong>Ol&aacute;</strong>, {$nome} ";

$mail = new PHPMailer();
$email_time = "uniaosantaluiza@gmail.com";


$mail->IsSMTP();
$mail->SMTPDebug = 1;
$mail->SMTPAuth = true;
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->Host = 'smtp.gmail.com';
$mail->Username = "uniaosantaluiza@gmail.com";
$mail->Password = "futebol321";
$mail->From =  'uniaosantaluiza@gmail.com';
$mail->FromName = 'Esporte Clube União Santa Luiza';

$mail->CharSet = 'UTF-8';
$mail->IsHTML(true);
$mail->Subject = 'Esporte Clube União Santa Luiza';
$mail->AddAddress($email_time);


$msg = str_replace("%HEAD%", $head_empresa, $mensagem);
$mail->Body = $msg;
$enviado = $mail->Send();

$mail->ClearAddresses();
$mail->AddAddress($email);

$msg = str_replace("%HEAD%", $head_cliente, $mensagem);
$mail->Body = $msg;


if ($enviado && $mail->Send()){
	echo "1";
} else {
	echo "0";
}

?>