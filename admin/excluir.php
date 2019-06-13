<?php
session_start();
include"functions.php";
if (!logado()){
	header("Location: $url");
	exit();
}

$ids = $_GET['id']? $_GET['id']: $_POST['id'];
$id_imagem = $_GET['id_imagem'];
$tabela = $_GET['tabela'];
$tabela_id = explode("_", $tabela);
conecta();

if($id_imagem){
	$query = mysql_query("SELECT imagem FROM {$tabela}_imagens WHERE id = $id_imagem");
	$d = mysql_fetch_array($query);
	if($d){
		$arquivo = "../media/{$tabela}/".$ids."/".$d['imagem'];
		if (file_exists($arquivo)) unlink($arquivo);
		$arquivo = "../media/{$tabela}/".$ids."/thmb-".$d['imagem'];
		if (file_exists($arquivo)) unlink($arquivo);
		$arquivo = "../media/{$tabela}/".$ids."/thumb-".$d['imagem'];
		if (file_exists($arquivo)) unlink($arquivo);
		$arquivo = "../media/{$tabela}/".$ids."/big-".$d['imagem'];
		if (file_exists($arquivo)) unlink($arquivo);
		$arquivo = "../media/{$tabela}/".$ids."/p-".$d['imagem'];
		if (file_exists($arquivo)) unlink($arquivo);
		#$query = mysql_query("DELETE FROM {$tabela}_imagens WHERE id = $id_imagem");

		excluir("{$tabela}_imagens", "id = $id_imagem");
		echo "1";
		exit();
	}
}

switch ($tabela){
	case("banners"):
		foreach($ids as $id){
			$query = mysql_query("SELECT imagem FROM banners WHERE id = $id");
			$d = mysql_fetch_array($query);
			$arquivo = "../media/banners/".$d['imagem'];
			if (file_exists($arquivo) && !is_dir($arquivo)) unlink($arquivo);
			$thmb = "../media/banners/thmb-".$d['imagem'];
			if (file_exists($thmb) && !is_dir($thmb)) unlink($thmb);
			#mysql_query("DELETE FROM banners WHERE id = $id");
			excluir("banners", "id = $id");
		}
	break;
	case("campanhas"):
		foreach($ids as $id){
			$query = mysql_query("SELECT * FROM campanhas WHERE id = $id");
			$d = mysql_fetch_array($query);

			$arquivo = "../media/campanhas/".$d['imagem'];
			if (file_exists($arquivo) && !is_dir($arquivo)) unlink($arquivo);

			$thmb = "../media/campanhas/thumb-".$d['miniatura'];
			if (file_exists($thmb) && !is_dir($thmb)) unlink($thmb);

			excluir("campanhas", "id = $id");
		}
	break;
	case("categorias"):
		foreach($ids as $id){
			$query = mysql_query("SELECT * FROM categorias WHERE id = $id");
			$d = mysql_fetch_array($query);
			$arquivo = "../media/categorias/".$d['banner'];
			if (file_exists($arquivo) && !is_dir($arquivo)) unlink($arquivo);
			$thmb = "../media/categorias/thmb-".$d['banner'];
			if (file_exists($thmb) && !is_dir($thmb)) unlink($thmb);
			mysql_query("DELETE FROM categorias WHERE id = $id");
			excluir("categorias", "id = $id");
		}
	break;
	case("produtos"):
		foreach($ids as $id){
			$q = mysql_query("SELECT * FROM produtos WHERE id = $id");
			$d = mysql_fetch_array($q);
			$arquivo = "../media/produtos/".$d['literatura'];
			if (file_exists($arquivo) && !is_dir($arquivo)) unlink($arquivo);
			rmdir("../media/produtos/$id/");
			#mysql_query("DELETE FROM produtos_preparos WHERE produtos_id = $id");
			#mysql_query("DELETE FROM produtos_substancias WHERE produtos_id = $id");
			#mysql_query("DELETE FROM produtos WHERE id = $id");
			excluir("produtos_preparos", "produtos_id = $id");
			excluir("produtos_substancias", "produtos_id = $id");
			excluir("produtos", "id = $id");
		}
	break;
	case("produtos_pic"):
		foreach($ids as $id){
			excluir("produtos_pic_categorias", "produtos_pic_id = $id");
			excluir("produtos_pic_aplicacoes", "produtos_pic_id = $id");
			excluir("produtos_pic", "id = $id");
		}
	break;
	case("trabalhe_conosco"):
		foreach($ids as $id){
			$q = mysql_query("SELECT arquivo FROM trabalhe_conosco WHERE id = $id");
			$d = mysql_fetch_array($q);
			$arquivo = "../media/trabalheconosco/".$d['arquivo'];
			if (file_exists($arquivo) && !is_dir($arquivo)) unlink($arquivo);	
			
			excluir("trabalhe_conosco", "id = $id");
		}
	break;
	case("vagas"):
		$arr_query = array();
		mysql_query("START TRANSACTION");
		foreach($ids as $id){
			$delete = mysql_query("DELETE FROM vagas WHERE id = $id");
			
			$arr_query[] = $delete == 1 ? 1 : 0;
		}
		
		if (in_array("0", $arr_query)){
			echo "fkVagas";
			mysql_query("ROLLBACK");
		} else {
			mysql_query("COMMIT");
		}
	break;
	default:
		if($_POST){
			foreach($ids as $id){
				mysql_query("DELETE FROM $tabela WHERE id = $id") or die (mysql_error());
				//excluir("$tabela", "id = $id");
			}
		}else{
			mysql_query("DELETE FROM $tabela WHERE id = $ids") or die (mysql_error());
			//excluir("$tabela", "id = $ids");
		}
	break;
}
?>