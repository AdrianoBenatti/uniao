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

	//$info_ = pathinfo(removeSpecialChars($_FILES['Filedata']['name']));

	$info_ = pathinfo(removeAndLowerSpecialChars($_FILES['Filedata']['name']));

	$newName = $idf."-".$info_['filename'].".".$info_['extension'];
	$newNameM = "media-".$idf."-".$info_['filename'].".".$info_['extension'];
	$newNameT = "thmb-".$idf."-".$info_['filename'].".".$info_['extension'];
	$newNameS = "small-".$idf."-".$info_['filename'].".".$info_['extension'];

	$temp_files = "../../../../media/temp/";
	if(!mkdir("../../../../media/{$tabela}/" . $idn));
	$local_files = "../../../../media/{$tabela}/" . $idn . '/';


	if ($_FILES) {
		echo "1";
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
		$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
		move_uploaded_file($tempFile,$targetFile);

		if ($tabela == "picday"){
			$nome = $_FILES['Filedata']['name'];
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(612, 408, 'outside', 'down')->crop("center", "middle", 612, 480)->saveToFile($local_files.$newName);
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(322, 219, 'outside', 'down')->crop("center", "middle", 322, 219)->saveToFile($local_files.$newNameM);
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(140, 125, 'outside', 'down')->crop("center", "middle", 140, 125)->saveToFile($local_files.$newNameT);
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(106, 58, 'outside', 'down')->crop("center", "middle", 106, 58)->saveToFile($local_files.$newNameS);
		} else {
			$nome = $_FILES['Filedata']['name'];
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(612, 408, 'outside', 'down')->crop("center", "middle", 612, 480)->saveToFile($local_files.$newName);
			$image = WideImage::load($temp_files.$nome);
			$image = $image->resize(125, 83, 'outside', 'down')->crop("center", "middle", 125, 83)->saveToFile($local_files.$newNameT);	
		}


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
		/*
		echo "INSERT INTO {$tabela}_imagens(id, {$tabela_id[0]}_id, imagem, ordem) VALUES('$idf','$idn','$newName', '$ordem')";
		die();
		*/
		echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	}
}elseif (!empty($_FILES)) {
	echo "2";
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$file = removeAndLowerSpecialChars($_FILES['Filedata']['name']);
	$targetFile =  str_replace('//','/',$targetPath) . $file;

	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	// $fileTypes  = str_replace(';','|',$fileTypes);
	// $typesArray = split('\|',$fileTypes);
	// $fileParts  = pathinfo($_FILES['Filedata']['name']);

	// if (in_array($fileParts['extension'],$typesArray)) {
		// Uncomment the following line if you want to make the directory if it doesn't exist
		// mkdir(str_replace('//','/',$targetPath), 0755, true);

		move_uploaded_file($tempFile,$targetFile);
		echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	// } else {
	// 	echo 'Invalid file type.';
	// }
}
?>