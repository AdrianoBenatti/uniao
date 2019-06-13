<?php
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
session_id($_POST['sid']);
session_start();
include "../../../functions.php";
include "../../../lib/WideImage.php";
$connect = conecta();

if(!logado()) exit();

if($_POST['massive']=="true"){
	$idn = $_POST['idn'];
	$tabela = $_POST['tabela'];
	$tabela_id = explode("_", $tabela);
	if($tabela!='xml') $idf = proxID("{$tabela}_imagens");

	echo "TABELA ".$tabela;

	$info_ = pathinfo(removeAndLowerSpecialChars($_FILES['Filedata']['name']));

	$newName = $info_['filename'].".".$info_['extension'];

	$newName = removeAndLowerSpecialChars($newName);
	$newName = strtolower($newName);

	$temp_files = "../../../../media/temp/";
	$local_files = "../../../../media/nfe/";

	if ($_FILES) {
		if (strtolower(substr($_FILES['Filedata']['name'], -3) == 'php')) exit;
		if ($tabela == "picday"){

			$info_ = pathinfo(retirar_caracteres_especiais($_FILES['Filedata']['name']));
			$fileName = $info_['filename'].".".$info_['extension'];

			$newName = $idf."-".$info_['filename'].".".$info_['extension'];
			$newNameM = "media-".$idf."-".$info_['filename'].".".$info_['extension'];
			$newNameT = "thmb-".$idf."-".$info_['filename'].".".$info_['extension'];
			$newNameS = "small-".$idf."-".$info_['filename'].".".$info_['extension'];

			$targetFolder = $basePath.'media/picday/'.$idn.'/';
			if(!file_exists($targetFolder)) mkdir($targetFolder);

			$tempFile = $basePathTemp.$fileName;

			move_uploaded_file($_FILES['Filedata']['tmp_name'], $tempFile);

			$image = WideImage::load($tempFile);
			$image = $image->resize(612, 408, 'outside', 'down')->crop("center", "middle", 612, 480)->saveToFile($targetFolder.$newName);
			$image = WideImage::load($tempFile);
			$image = $image->resize(322, 219, 'outside', 'down')->crop("center", "middle", 322, 219)->saveToFile($targetFolder.$newNameM);
			$image = WideImage::load($tempFile);
			$image = $image->resize(140, 125, 'outside', 'down')->crop("center", "middle", 140, 125)->saveToFile($targetFolder.$newNameT);
			$image = WideImage::load($tempFile);
			$image = $image->resize(106, 58, 'outside', 'down')->crop("center", "middle", 106, 58)->saveToFile($targetFolder.$newNameS);

			unlink($tempFile);
			echo $fileName;

			$q = mysql_query("SELECT MAX(ordem) FROM {$tabela}_imagens", $connect);
			$d = mysql_fetch_array($q);
			if ($d['MAX(ordem)']){
				$ordem = $d['MAX(ordem)'] + 1;
			}else{
				$ordem = 1;
			}

			$query = mysql_query("INSERT INTO {$tabela}_imagens(id, {$tabela_id[0]}_id, imagem, ordem) VALUES('$idf','$idn','$newName', '$ordem')",$connect);

		}  else if($tabela == "midias"){

			$info_ = pathinfo(retirar_caracteres_especiais($_FILES['Filedata']['name']));
			$fileName = $info_['filename'].".".$info_['extension'];

			$newName = $idf."-".$info_['filename'].".".$info_['extension'];
			$newNameT = "thmb-".$idf."-".$info_['filename'].".".$info_['extension'];
			$newNameB = "big-".$idf."-".$info_['filename'].".".$info_['extension'];
			$newNameC = "capa-".$idf."-".$info_['filename'].".".$info_['extension'];

			$targetFolder = $basePath.'media/midias/'.$idn.'/';
			if(!file_exists($targetFolder)) mkdir($targetFolder);

			$tempFile = $basePathTemp.$fileName;

			move_uploaded_file($_FILES['Filedata']['tmp_name'], $tempFile);

			$image = WideImage::load($tempFile);
			WideImage::load($tempFile)->resize(465, 350, 'outside','down')->crop("center", "middle", 465, 350)->saveToFile($targetFolder.$newName);
			$image = WideImage::load($tempFile);
			WideImage::load($tempFile)->resize(100, 100, 'outside', 'down')->crop("center", "middle", 100, 100)->saveToFile($targetFolder.$newNameT);
			$image = WideImage::load($tempFile);
			WideImage::load($tempFile)->resize(210, 185, 'outside', 'down')->crop("center", "middle", 210, 185)->saveToFile($targetFolder.$newNameC);

			unlink($tempFile);
			echo $fileName;

			$q = mysql_query("SELECT MAX(ordem) FROM {$tabela}_imagens", $connect);
			$d = mysql_fetch_array($q);
			if ($d['MAX(ordem)']){
				$ordem = $d['MAX(ordem)'] + 1;
			}else{
				$ordem = 1;
			}

			$query = mysql_query("INSERT INTO {$tabela}_imagens(id, {$tabela_id[0]}_id, imagem, ordem) VALUES('$idf','$idn','$newName', '$ordem')",$connect);

		} else if ($tabela == "noticias") {

			$info_ = pathinfo(retirar_caracteres_especiais($_FILES['Filedata']['name']));
			$fileName = $info_['filename'].".".$info_['extension'];

			$newName = $idf."-".$fileName;
			$newNameT = "thmb-".$idf."-".$fileName;

			$targetFolder = $basePath.'media/noticias/'.$idn.'/';
			if(!file_exists($targetFolder)) mkdir($targetFolder);

			$tempFile = $basePathTemp.$fileName;

			move_uploaded_file($_FILES['Filedata']['tmp_name'], $tempFile);

			$image = WideImage::load($tempFile);
			$image = $image->resize(612, 408, 'outside', 'down')->crop("center", "middle", 612, 480)->saveToFile($targetFolder.$newName);
			$image = WideImage::load($tempFile);
			$image = $image->resize(140, 125, 'outside', 'down')->crop("center", "middle", 140, 125)->saveToFile($targetFolder.$newNameT);

			unlink($tempFile);
			echo $fileName;

			$q = mysql_query("SELECT MAX(ordem) FROM {$tabela}_imagens", $connect);
			$d = mysql_fetch_array($q);
			if ($d['MAX(ordem)']){
				$ordem = $d['MAX(ordem)'] + 1;
			}else{
				$ordem = 1;
			}

			$query = mysql_query("INSERT INTO {$tabela}_imagens(id, {$tabela_id[0]}_id, imagem, ordem) VALUES('$idf','$idn','$newName', '$ordem')",$connect);


		} else if ($tabela == "xml") {

			$file = $_FILES['Filedata']['name'];
			$targetFile = $basePath.'media/nfe/'.$file;
			move_uploaded_file($_FILES['Filedata']['tmp_name'], $targetFile);

			try {
			    $object = simplexml_load_file($targetFile);
				/*
				TODO convert to array
			    print_r($object);
				exit;
			    if(!isset($object->NFe)){
			    	//1.1
			    	echo '1.1';
			    	$nota = $object;
			    }else{
			    	//2.0
			    	echo '2.0';
			    	$nota = $object->NFe;
			    }
				print_r($nota);
				*/
		    	$nota = $object->NFe;
			    foreach($nota as $key => $item) {
			        if(isset($item->infNFe)) {
						$numero_nota = $item->infNFe->ide->nNF;
			        }
			        if(isset($item->infNFe)) {
						$emit = $item->infNFe->emit->xNome;
			        }
			        if(isset($item->infNFe)) {
						$dest = $item->infNFe->dest->xNome;
			        }
			        if(isset($item->infNFe)) {
						$cnpj = $item->infNFe->dest->CNPJ;
			        }
			        if(isset($item->infNFe)) {
						$dataEmissao = $item->infNFe->ide->dEmi;
			        }
			    }
				foreach($object->protNFe as $key => $item) {
					if(isset($item->infProt)) {
						$protocolo = $item->infProt->nProt;
					}
					if(isset($item->infProt)) {
						$origem = $item->infProt->dhRecbto;
					}
				}
			}catch(Exception $e){
			    echo $e->getMessage();
			}

			$dest = substr($dest, 0,3);
			$dest = strtolower($dest);
			$emit = substr($emit, 0,3);
			$emit = strtolower($emit);
			if ($dest == 'pic') {
				$tabela = 'nota_fiscal_pic';
				$tipo = 'entrada';
			} else if ($dest == 'pha'){
				$tabela = 'nota_fiscal_pharma';
				$tipo = 'entrada';
			} else {
				$tipo = 'saida';
				if ($emit == 'pic'){
					$tabela = 'nota_fiscal_pic';
				} else if ($emit == 'pha'){
					$tabela = 'nota_fiscal_pharma';
				}else{
					echo "caiu aqui";
				}
			}

			$id = proxID($tabela);

			$query = mysql_query("INSERT INTO $tabela (id, arquivo, numero_nota, protocolo, tipo, data_emissao, cnpj, origem)
								  VALUES('$id', '$newName', '$numero_nota', '$protocolo', '$tipo', '$dataEmissao', '$cnpj', '$origem')", $connect) or die (mysql_error());

		} else {
			$newName = $idf."-".$info_['filename'].".".$info_['extension'];
			$newNameM = "media-".$idf."-".$info_['filename'].".".$info_['extension'];
			$newNameT = "thmb-".$idf."-".$info_['filename'].".".$info_['extension'];
			$newNameS = "small-".$idf."-".$info_['filename'].".".$info_['extension'];

			$temp_files = "../../../../media/temp/";
			if(!mkdir("../../../../media/{$tabela}/" . $idn));
			$local_files = "../../../../media/{$tabela}/" . $idn . '/';

			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
			$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
			move_uploaded_file($tempFile,$targetFile);

			$nome = $_FILES['Filedata']['name'];
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(612, 408, 'outside', 'down')->crop("center", "middle", 612, 480)->saveToFile($local_files.$newName);
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(125, 83, 'outside', 'down')->crop("center", "middle", 125, 83)->saveToFile($local_files.$newNameT);

			if (file_exists($temp_files.$tempFile)) unlink($temp_files.$tempFile);
			if (file_exists($temp_files.$nome)) unlink($temp_files.$nome);


			$q = mysql_query("SELECT MAX(ordem) FROM {$tabela}_imagens", $connect);
			$d = mysql_fetch_array($q);
			if ($d['MAX(ordem)']){
				$ordem = $d['MAX(ordem)'] + 1;
			}else{
				$ordem = 1;
			}

			$query = mysql_query("INSERT INTO {$tabela}_imagens(id, {$tabela_id[0]}_id, imagem, ordem) VALUES('$idf','$idn','$newName', '$ordem')",$connect);

		}



		echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	}
}elseif (!empty($_FILES)) {
	if (strtolower(substr($_FILES['Filedata']['name'], -3) == 'php')) exit;
	$tabela = $_POST['tabela'];
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$file = time().'-'. retirar_caracteres_especiais($_FILES['Filedata']['name']);
	$targetFile =  $basePathTemp . $file;
	move_uploaded_file($tempFile, $targetFile);

	if ($tabela == 'noticias') {
		$newFile  = $local_files . $nome_imagem;
		$image = WideImage::load($targetFile);
		$image = $image->resize(510, null, 'outside', 'down')->saveToFile($targetFile);
	}

	echo $file;
}
?>