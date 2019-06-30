<?php

$sid = $_POST['sid'];

session_id($sid);
session_start();
include('functions.php');
include('lib/WideImage.php');

if(!logado()) exit;

$targetDir = $basePathTemp;
$idn = $_REQUEST["idn"];
$tabela = $_REQUEST["tabela"];

// Get a file name
if (isset($_REQUEST["name"])) {
	$fileName = $_REQUEST["name"];
} elseif (!empty($_FILES)) {
	$fileName = $_FILES["file"]["name"];
} else {
	$fileName = uniqid("file_");
}
$fileName = $sid . '-' . retirar_caracteres_especiais($fileName);

$filePath = $targetDir . $fileName;

// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

// Open temp file
if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
	die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Erro ao abrir o arquivo."}, "id" : "id"}');
}

if (!empty($_FILES)) {
	if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Erro ao mover o arquivo."}, "id" : "id"}');
	}

	// Read binary input stream and append it to temp file
	if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Erro ao abrir o arquivo."}, "id" : "id"}');
	}
} else {
	if (!$in = @fopen("php://input", "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Erro ao abrir o arquivo."}, "id" : "id"}');
	}
}

while ($buff = fread($in, 4096)) {
	fwrite($out, $buff);
}

@fclose($out);
@fclose($in);

$uploaded = false;

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
	// Strip the temp .part suffix off
	$filePath_ = str_replace($sid, time(), $filePath);
	rename("{$filePath}.part", $filePath_);

	if(file_exists($filePath_)){
		$file = pathinfo($filePath_);
		$fileName = $file['filename'] . '.' . $file['extension'];
		if($_POST['generate_thumb'] && in_array(strtolower($file['extension']), array('jpg', 'jpeg', 'png', 'gif'))){
			$thumb = $file['dirname'] . '/thumb-' . $file['filename'] . '.' . $file['extension'];
			$img = WideImage::load($filePath_);
			$img->resize(150, 150, 'outside')->crop('center', 'middle', 150, 150)->saveToFile($thumb);
		}
	}
	/**/

	$uploaded = true;
}

if($uploaded){

	if($_REQUEST['massive']==="true"){

		$tabela_id = explode("_", $tabela);
		if($tabela!='xml') $idf = proxID("{$tabela}_imagens");

		switch($tabela){
			case 'noticias':
				$newName = $idf."-".$fileName;
				$newNameT = "thmb-".$idf."-".$fileName;

				$targetFolder = $basePath.'media/noticias/'.$idn.'/';
				if(!file_exists($targetFolder)) mkdir($targetFolder);

				$tempFile = $basePathTemp.$fileName;

				$image = WideImage::load($tempFile);
				$image = $image->resize(612, 408, 'outside', 'down')->crop("center", "middle", 612, 480)->saveToFile($targetFolder.$newName);
				$image = WideImage::load($tempFile);
				$image = $image->resize(140, 125, 'outside', 'down')->crop("center", "middle", 140, 125)->saveToFile($targetFolder.$newNameT);

				unlink($tempFile);

				$q = mysql_query("SELECT MAX(ordem) FROM {$tabela}_imagens", $connect);
				$d = mysql_fetch_array($q);
				if ($d['MAX(ordem)']){
					$ordem = $d['MAX(ordem)'] + 1;
				}else{
					$ordem = 1;
				}

				$query = mysql_query("INSERT INTO {$tabela}_imagens(id, {$tabela_id[0]}_id, imagem, ordem) VALUES('$idf','$idn','$newName', '$ordem')",$connect);

				break;

			case 'picday':
				$newName = $idf."-".$fileName;
				$newNameM = "media-".$idf."-".$fileName;
				$newNameT = "thmb-".$idf."-".$fileName;
				$newNameS = "small-".$idf."-".$fileName;

				$targetFolder = $basePath.'media/picday/'.$idn.'/';
				if(!file_exists($targetFolder)) mkdir($targetFolder);

				$tempFile = $basePathTemp.$fileName;

				$image = WideImage::load($tempFile);
				$image = $image->resize(612, 408, 'outside', 'down')->crop("center", "middle", 612, 480)->saveToFile($targetFolder.$newName);
				$image = WideImage::load($tempFile);
				$image = $image->resize(322, 219, 'outside', 'down')->crop("center", "middle", 322, 219)->saveToFile($targetFolder.$newNameM);
				$image = WideImage::load($tempFile);
				$image = $image->resize(140, 125, 'outside', 'down')->crop("center", "middle", 140, 125)->saveToFile($targetFolder.$newNameT);
				$image = WideImage::load($tempFile);
				$image = $image->resize(106, 58, 'outside', 'down')->crop("center", "middle", 106, 58)->saveToFile($targetFolder.$newNameS);

				unlink($tempFile);

				$q = mysql_query("SELECT MAX(ordem) FROM {$tabela}_imagens", $connect);
				$d = mysql_fetch_array($q);
				if ($d['MAX(ordem)']){
					$ordem = $d['MAX(ordem)'] + 1;
				}else{
					$ordem = 1;
				}
				$query = mysql_query("INSERT INTO {$tabela}_imagens(id, {$tabela_id[0]}_id, imagem, ordem) VALUES('$idf','$idn','$newName', '$ordem')",$connect);

				break;

			case "midias":
				$newName = $idf."-".$fileName;
				$newNameT = "thmb-".$idf."-".$fileName;
				$newNameB = "big-".$idf."-".$fileName;
				$newNameC = "capa-".$idf."-".$fileName;

				$targetFolder = $basePath.'media/midias/'.$idn.'/';
				if(!file_exists($targetFolder)) mkdir($targetFolder);

				$tempFile = $basePathTemp.$fileName;

				$image = WideImage::load($tempFile);
				WideImage::load($tempFile)->resize(465, 350, 'outside','down')->crop("center", "middle", 465, 350)->saveToFile($targetFolder.$newName);
				$image = WideImage::load($tempFile);
				WideImage::load($tempFile)->resize(100, 100, 'outside', 'down')->crop("center", "middle", 100, 100)->saveToFile($targetFolder.$newNameT);
				$image = WideImage::load($tempFile);
				WideImage::load($tempFile)->resize(210, 185, 'outside', 'down')->crop("center", "middle", 210, 185)->saveToFile($targetFolder.$newNameC);

				unlink($tempFile);

				$q = mysql_query("SELECT MAX(ordem) FROM {$tabela}_imagens", $connect);
				$d = mysql_fetch_array($q);
				if ($d['MAX(ordem)']){
					$ordem = $d['MAX(ordem)'] + 1;
				}else{
					$ordem = 1;
				}

				$query = mysql_query("INSERT INTO {$tabela}_imagens(id, {$tabela_id[0]}_id, imagem, ordem) VALUES('$idf','$idn','$newName', '$ordem')",$connect);
				break;

			case "xml":
				$targetFile = $basePathTemp.$fileName;

				try {
					$object = simplexml_load_file($targetFile);
					$nota = $object->NFe;
					foreach ($nota as $key => $item) {
						if (isset($item->infNFe)) $numero_nota = $item->infNFe->ide->nNF;
						if (isset($item->infNFe)) $emit = $item->infNFe->emit->xNome;
						if (isset($item->infNFe)) $dest = $item->infNFe->dest->xNome;
						if (isset($item->infNFe)) $cnpj = $item->infNFe->dest->CNPJ;
						if (isset($item->infNFe)) $dataEmissao = $item->infNFe->ide->dEmi;
					}
					foreach ($object->protNFe as $key => $item) {
						if (isset($item->infProt)) $protocolo = $item->infProt->nProt;
						if (isset($item->infProt)) $origem = $item->infProt->dhRecbto;
					}
				} catch (Exception $e) {
					echo $e->getMessage();
				}

				$dest = substr($dest, 0, 3);
				$dest = strtolower($dest);
				$emit = substr($emit, 0, 3);
				$emit = strtolower($emit);
				if ($dest == 'pic') {
					$tabela = 'nota_fiscal_pic';
					$tipo = 'entrada';
				}
				else if ($dest == 'pha') {
					$tabela = 'nota_fiscal_pharma';
					$tipo = 'entrada';
				}
				else {
					$tipo = 'saida';
					if ($emit == 'pic') {
						$tabela = 'nota_fiscal_pic';
					}
					else if ($emit == 'pha') {
						$tabela = 'nota_fiscal_pharma';
					}
					else {
						echo "caiu aqui";
					}
				}

				$id = proxID($tabela);

				$query = mysql_query("INSERT INTO $tabela (id, arquivo, numero_nota, protocolo, tipo, data_emissao, cnpj, origem)
												VALUES('$id', '$fileName', '$numero_nota', '$protocolo', '$tipo', '$dataEmissao', '$cnpj', '$origem')", $connect) or die(mysql_error());

				$destino = $basePath.'media/nfe/'.$fileName;

				rename($targetFile, $destino);

				break;

			default:
				$newName = $idf."-".$fileName;
				$newNameM = "media-".$idf."-".$fileName;
				$newNameT = "thmb-".$idf."-".$fileName;
				$newNameS = "small-".$idf."-".$fileName;

				$tempFile = $basePathTemp.$fileName;

				$targetFolder = $basePath.'media/{$tabela}/'.$idn;
				if(!mkdir(targetFolder));
				$targetFolder = $targetFolder . '/';

				$nome = $_FILES['Filedata']['name'];
				$image = WideImage::load($tempFile.$nome);
				$image = $image->resize(612, 408, 'outside', 'down')->crop("center", "middle", 612, 480)->saveToFile($targetFolder.$newName);
				$image = WideImage::load($tempFile.$nome);
				$image = $image->resize(125, 83, 'outside', 'down')->crop("center", "middle", 125, 83)->saveToFile($targetFolder.$newNameT);

				if (file_exists($tempFile.$tempFile)) unlink($tempFile.$tempFile);
				if (file_exists($tempFile.$nome)) unlink($tempFile.$nome);


				$q = mysql_query("SELECT MAX(ordem) FROM {$tabela}_imagens", $connect);
				$d = mysql_fetch_array($q);
				if ($d['MAX(ordem)']){
					$ordem = $d['MAX(ordem)'] + 1;
				}else{
					$ordem = 1;
				}

				$query = mysql_query("INSERT INTO {$tabela}_imagens(id, {$tabela_id[0]}_id, imagem, ordem) VALUES('$idf','$idn','$newName', '$ordem')",$connect);
				break;
		}
	} else {

		$targetFile =  $basePathTemp . $fileName;
		if ($tabela == 'noticias') {
			$image = WideImage::load($targetFile);
			$image = $image->resize(510, null, 'outside', 'down')->saveToFile($targetFile);
		}
	}
}

// Return Success JSON-RPC response
die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "fileName": "'.$fileName.'"}');