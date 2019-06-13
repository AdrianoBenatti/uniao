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
	$idf = proxID("{$tabela}_imagens");


	$info_ = pathinfo(removeAndLowerSpecialChars($_FILES['Filedata']['name']));

	$newName = $info_['filename'].".".$info_['extension'];

	$newName = removeAndLowerSpecialChars($newName);
	$newName = strtolower($newName);

	$temp_files = "../../../../media/temp/";
	$local_files = "../../../../media/nfe/";

	if ($_FILES) {

		if ($tabela == "picday"){
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
			echo "--TEMP".$tempFile."--";
			echo "--target".$targetPath."--";
			move_uploaded_file($tempFile,$targetFile);

			$nome = $_FILES['Filedata']['name'];
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(612, 408, 'outside', 'down')->crop("center", "middle", 612, 480)->saveToFile($local_files.$newName);
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(322, 219, 'outside', 'down')->crop("center", "middle", 322, 219)->saveToFile($local_files.$newNameM);
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(140, 125, 'outside', 'down')->crop("center", "middle", 140, 125)->saveToFile($local_files.$newNameT);
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(106, 58, 'outside', 'down')->crop("center", "middle", 106, 58)->saveToFile($local_files.$newNameS);

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

		}  else if($tabela == "midias"){

			$newName = $idf."-".$info_['filename'].".".$info_['extension'];
			$newNameT = "thmb-".$idf."-".$info_['filename'].".".$info_['extension'];
			$newNameB = "big-".$idf."-".$info_['filename'].".".$info_['extension'];
			$newNameC = "capa-".$idf."-".$info_['filename'].".".$info_['extension'];

			$temp_files = "../../../../media/temp/";
			if(!mkdir("../../../../media/{$tabela}/" . $idn));
			$local_files = "../../../../media/{$tabela}/" . $idn . '/';

			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
			$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
			move_uploaded_file($tempFile,$targetFile);

			if(!file_exists($local_files.$id)) mkdir($local_files.$id);
			WideImage::load($temp_files.$nome)->resize(465, 347, 'outside','down')->crop("center", "middle", 465, 347)->saveToFile($local_files.$newName);
			WideImage::load($temp_files.$nome)->resize(100, 100, 'outside', 'down')->crop("center", "middle", 100, 100)->saveToFile($local_files.$newNameT);
			WideImage::load($temp_files.$nome)->resize(null, 181, 'outside', 'down')->crop("center", "middle", 234, 181)->saveToFile($local_files.$newNameC);

			$q = mysql_query("SELECT MAX(ordem) FROM {$tabela}_imagens", $connect);
			$d = mysql_fetch_array($q);
			if ($d['MAX(ordem)']){
				$ordem = $d['MAX(ordem)'] + 1;
			}else{
				$ordem = 1;
			}

			$query = mysql_query("INSERT INTO {$tabela}_imagens(id, {$tabela_id[0]}_id, imagem, ordem) VALUES('$idf','$idn','$newName', '$ordem')",$connect);

		} else if ($tabela == "noticias") {

			$newName = $idf."-".$info_['filename'].".".$info_['extension'];
			$newNameT = "thmb-".$idf."-".$info_['filename'].".".$info_['extension'];

			$temp_files = "../../../../media/temp/";
			if(!mkdir("../../../../media/{$tabela}/" . $idn));
			$local_files = "../../../../media/{$tabela}/" . $idn . '/';

			$tempFile = $temp_files.$info_['filename'].".".$info_['extension'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . '/nfe/admin/media/temp/';
			$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];

			$nome = $_FILES['Filedata']['name'];

			echo "nome - ".$nome;

			move_uploaded_file($_FILES['Filedata']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/nfe/media/temp/'.$nome);

			$nome = $_FILES['Filedata']['name'];
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(612, 408, 'outside', 'down')->crop("center", "middle", 612, 480)->saveToFile($local_files.$newName);
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(140, 125, 'outside', 'down')->crop("center", "middle", 140, 125)->saveToFile($local_files.$newNameT);

			//if (file_exists($temp_files.$tempFile)) unlink($temp_files.$tempFile);
			//if (file_exists($temp_files.$nome)) unlink($temp_files.$nome);


			$q = mysql_query("SELECT MAX(ordem) FROM {$tabela}_imagens", $connect);
			$d = mysql_fetch_array($q);
			if ($d['MAX(ordem)']){
				$ordem = $d['MAX(ordem)'] + 1;
			}else{
				$ordem = 1;
			}

			$query = mysql_query("INSERT INTO {$tabela}_imagens(id, {$tabela_id[0]}_id, imagem, ordem) VALUES('$idf','$idn','$newName', '$ordem')",$connect);


		} else if ($tabela = "xml") {

			//echo "1";
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . '/media/nfe/';
			$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];

			$nome =  strtolower($_FILES['Filedata']['name']);

			if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $targetPath.$newName)){
				echo "OK";
			} else {
				echo "ERRO";
			}

			//echo $_SERVER['DOCUMENT_ROOT'].'/media/nfe/'.$newName;


			try {
			    $object = simplexml_load_file( $targetPath.$newName);
				//$feed = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/nfe/media/nfe/'.$newName);
			    //$object = simplexml_load_string($feed);
			   	foreach($object->NFe as $key => $item) {
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

						if (empty($dataEmissao)){
							$dataEmissao = $item->infNFe->ide->dhEmi;
						}

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
			}
			catch(Exception $e){
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
				}
			}


			$id = proxID($tabela);

			$query = mysql_query("INSERT INTO $tabela (id, arquivo, numero_nota, protocolo, tipo, data_emissao, cnpj, origem)
								  VALUES('$id', '$newName', '$numero_nota', '$protocolo', '$tipo', '$dataEmissao', '$cnpj', '$origem')",$connect) or die (mysql_error());

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



		//echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	}
}elseif (!empty($_FILES)) {
	echo "2";
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$tempFile = retirar_caracteres_especiais($tempFile);
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$file = removeAndLowerSpecialChars($_FILES['Filedata']['name']);
	$file = strtolower($file);
	$targetFile =  str_replace('//','/',$targetPath) . $file;


	echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);

	$nome = $_FILES['Filedata']['name'];

	$nome = strtolower($nome);

	move_uploaded_file($_FILES['Filedata']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/media/temp/'.$nome);

	echo $_SERVER['DOCUMENT_ROOT'].'/media/temp';

}
?>