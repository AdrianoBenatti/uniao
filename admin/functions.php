<?php

include_once('env.php');

$is_apta = false;
if(isset($_SERVER['REMOTE_ADDR'])){
	$is_apta = $_SERVER['REMOTE_ADDR']=='177.94.202.250'? true: false;
}

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $env));
defined('IS_APTA') || define('IS_APTA', (getenv('IS_APTA') ? getenv('IS_APTA') : $is_apta));

defined('APP_URL') || define('APP_URL', (getenv('APP_URL') ? getenv('APP_URL') : getConfig("url_site")));
$url = getConfig("url_admin");
defined('APP_URL_ADMIN') || define('APP_URL_ADMIN', (getenv('APP_URL_ADMIN') ? getenv('APP_URL_ADMIN') : $url));

$url = getConfig("url_admin_pharma");
$url_pic = getConfig("url_admin_pic");
$url_site = getConfig("url_site_pharma");
$temp_files = "../media/temp/";
$basePath = str_replace('admin', '', realpath(dirname(__FILE__)));
$basePathTemp = $basePath.'media/temp/';
$mediaUrl = $url.'../media/';
$configuracoes = getAllConfigs();

function conecta(){
	$conexao = null;
	if(APPLICATION_ENV=='development'){
		$conexao = @mysql_pconnect("localhost", "root", "");
		mysql_select_db("uniao_db", $conexao); #desenvolvimento
	}
	if(APPLICATION_ENV=='testing'){
		$conexao = @mysql_pconnect("localhost", "", "");
		mysql_select_db("", $conexao); #teste
	}
	if(APPLICATION_ENV=='production'){
		$conexao = mysql_pconnect("localhost", "", "");
		mysql_select_db("", $conexao); #producao
	}
	return $conexao;
}

$connect = conecta();

function getConfig($nome) {
	$q = mysql_query("SELECT parametro FROM config WHERE nome = '$nome'", conecta());
	$d = mysql_fetch_array($q);
	return $d['parametro'];
}

function getAllConfigs() {
	$q = mysql_query("SELECT nome, parametro FROM config", conecta());
	while($d = mysql_fetch_array($q, MYSQL_ASSOC)){
		$conf[$d['nome']] = $d['parametro'];
	}
	return $conf;
}

function autorizar($acao) {
	if($_SESSION['id_usuario'] == 0){
		return;
	}else{
		$sql = "SELECT up.id FROM usuarios_permissoes up
				INNER JOIN tabelas t ON t.id = up.tabelas_id
				WHERE t.tabela = '$acao' AND up.usuarios_id = {$_SESSION['id_usuario']}";
		$query = mysql_query($sql,conecta());
		if(mysql_num_rows($query)>0){
			return;
		}else{
			header("Location: {$url}acessonegado");
		}
	}
}

function getNextOrdem($id, $tabela, $where){
	$q = mysql_query("SELECT max(ordem) FROM $tabela WHERE $where = '$id'");
	$d = mysql_fetch_array($q);
	return $d['max(ordem)']+1;
}

function save($data, $table, $id=null, $where='', $debug=false, $makelog=true){
	$tipo = $id==null? 1: 2;
	$connect = conecta();
	if ($id==null){
		$id = proxID($table);
		$sql .= "INSERT INTO $table (id, ";
		$fields = count ($data);
		$i=0;
		foreach ($data as $key => $value) {
			$i++;
			$sql .= $key;
			$sql .= $i < $fields ? ', ' : '';
		}
		$sql .= ") VALUES ($id, ";

		$i=0;
		foreach ($data as $key => $value) {
			$i++;
			$sql .= "'$value'";
			$sql .= $i < $fields ? ',' : '';
		}
		$sql .= ')';
	} else {
		$sql .= "UPDATE $table SET ";
		$fields = count ($data);
		$i=0;
		foreach ($data as $key => $value) {
			$i++;
			$sql .= $key . " = '$value'";
			$sql .= $i < $fields ? ', ' : '';
		}
		$sql .= " WHERE id = $id";
	}

	if($table != "log" && $makelog) makeLog($data, $table, $id, $tipo);

	mysql_query($sql, $connect) or die(mysql_error());

	/*
	$debug? mysql_query($sql, $connect) or die(mysql_error()): mysql_query($sql, $connect);
	$error = mysql_error();
	if($error){
		$sql = str_replace("'", "\'", $sql);
		$dados = json_encode($_SESSION);
		$dados = str_replace("'", "\'", $dados);
		mysql_query("INSERT INTO sqls(id, `data`, `sql`, `dados`, `mysql_error`) VALUES('".proxID('sqls')."', now(), '$sql', '$dados', '".str_replace("'", "\'", $error)."')", conecta());
	}
	*/
	return $id;
}

function excluir($table, $where){
	$q = mysql_query("SELECT * FROM $table WHERE $where", conecta()) or die (mysql_error());
	if(mysql_num_rows($q)>1){
		while($dados = mysql_fetch_array($q, MYSQL_ASSOC)){
			foreach($dados as $k => $v){
				$d[$k] = utf8_encode($v);
			}
			$data[] = $d;
		}
		$data = jsonRemoveUnicodeSequences($data);
	}else{
		$data = mysql_fetch_array($q, MYSQL_ASSOC);
	}
	#makeLog($data, $table, $where, 3);
	mysql_query("DELETE FROM $table WHERE $where", conecta());
}

function jsonRemoveUnicodeSequences($struct) {
	return utf8_decode(preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct)));
}

function makeLog($dadosAtual=array(), $tabela, $id_where, $tipo=1){
	foreach($dadosAtual as $k => $v){
		$json[$k] = utf8_encode($v);
	}
	$new = jsonRemoveUnicodeSequences($json);
	$new = str_replace("\\", "", $new);
	$new = str_replace("'", "\'", $new);
	$data = array(
		"usuarios_id" => $_SESSION['id_usuario'],
		"data" => date('Y-m-d H:i:s'),
		"tabela" => $tabela,
		"tipo" => $tipo,
		"json_pos" => $new,
		"browser" => $_SERVER['HTTP_USER_AGENT']
	);
	if($tipo==1){
		$data["observacoes"] = "INSERT na tabela: $tabela, id: $id_where";
	}elseif($tipo==2){
		$q = mysql_query("SELECT * FROM $tabela WHERE id = $id_where");
		$dadosAntigos = mysql_fetch_array($q, MYSQL_ASSOC);
		foreach($dadosAntigos as $k => $v){
			$json[$k] = utf8_encode($v);
		}
		$old = jsonRemoveUnicodeSequences($json);
		$old = str_replace("\\", "", $old);
		$old = str_replace("'", "\'", $old);
		$data["json_pre"] = $old;
		$data["observacoes"] = "UPDATE na tabela: $tabela, id: $id_where";
	}elseif($tipo==3){
		$data['json_pre'] = $data['json_pos'];
		unset($data['json_pos']);
		$data["observacoes"] = utf8_decode("DELETE na tabela: $tabela, where: $id_where");
	}
	save($data, "log");
}

function upperAll($string){
	$string = utf8_encode(strtoupper($string));
	$low = array("á","à","ã","â","é","è","ê","í","ì","î","ó","ò","õ","ô","ú","ù","û","ç","ª","º","°");
	$up =  array("Á","À","Ã","Â","É","È","Ê","Í","Ì","Î","Ó","Ò","Õ","Ô","Ú","Ù","Û","Ç","ª","º","°");
	$string = str_replace($low, $up, $string);
	return utf8_decode($string);
}

function removeAndLowerSpecialChars($imageName) {
	$remove = array(
		"á"=>"a", "à"=>"a", "ã"=>"a", "â"=>"a", "ä"=>"a", "Á"=>"a", "À"=>"a", "Ã"=>"a", "Â"=>"a", "Ä"=>"a", "é"=>"e", "è"=>"e", "ê"=>"e", "ë"=>"e", "É"=>"e", "È"=>"e", "Ê"=>"e", "Ë"=>"e",
		"í"=>"i", "ì"=>"i", "î"=>"i", "ï"=>"i", "Í"=>"i", "Ì"=>"i", "Î"=>"i", "Ï"=>"i", "ó"=>"o", "ò"=>"o", "õ"=>"o", "ô"=>"o", "ö"=>"o", "Ó"=>"o", "Ò"=>"o", "Õ"=>"o", "Ô"=>"o", "Ö"=>"o",
		"ú"=>"u", "ù"=>"u", "û"=>"u", "ü"=>"u", "Ú"=>"u", "Ù"=>"u", "Û"=>"u", "Ü"=>"u", "ç"=>"c", "Ç"=>"c", "ñ"=>"n", "Ñ"=>"n", " "=>"_", "&"=>"_", "%"=>"_", "$"=>"_",
		"A"=>"a", "B"=>"b", "C"=>"c", "D"=>"d", "E"=>"e", "F"=>"f", "G"=>"g", "H"=>"h", "I"=>"i", "J"=>"j", "K"=>"k", "L"=>"l", "M"=>"m", "N"=>"n", "O"=>"o", "P"=>"p", "Q"=>"q", "R"=>"r",
		"S"=>"s", "T"=>"t", "U"=>"u", "V"=>"v", "W"=>"w", "X"=>"x", "Y"=>"y", "Z"=>"z", "/"=>"-", "º"=>"_", "ª"=>"_", "°"=>"_"
	);
	return strtr($imageName, $remove);
}

function proxID($tabela, $where='') {
	$s = "Select max(id) from $tabela $where";
	$connect = conecta();
	$query = mysql_query($s, $connect);
	if($dados = mysql_fetch_array($query)) {
		if($dados['max(id)'] < 1) {
			$proxID = 1;
		} else {
			$proxID = $dados['max(id)'] + 1;
		}
	} else {
		$proxID = 1;
	}
	return $proxID;
}

function maxID($tabela) {
	$sqlid = "Select max(id) from $tabela";
	$connect = conecta();
	$query = mysql_query($sqlid, $connect);
	$dados = mysql_fetch_array($query);
	$maxID = $dados['max(id)'];

	return $maxID;
}

function logado() {
	if((!isset($_SESSION['nome']) or empty($_SESSION['nome'])) or (!isset($_SESSION['logado']) or empty($_SESSION['logado']))) {
		if($_GET['page'] === "aprovar") return true;
		return false;

	} else {
		return true;
	}
}

function is_valid_youtube($link) {
	if(preg_match('/youtube.com\\/watch\\?.*v=.*$/', $link)) {
		return true;
	} else {
		return false;
	}
}

function retirar_caracteres_especiais($frase) {
	$a = 'áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ &%$QWERTYUIOPASDFGHJKLZXCVBNM';
	$b = 'aaaaeeiooouucaaaaeeiooouuc____qwertyuiopasdfghjklzxcvbnm';
	$frase = utf8_decode($frase);
	$frase = strtr($frase, utf8_decode($a), $b);
	$frase = preg_replace("/[^a-zA-Z0-9\_\.\-]/", "", $frase);
	return (utf8_decode($frase));
}

function tempFiles() {
	return 'media/temp/';
}

function formatDate($date, $format) {
	return $date != '' ? date($format, strtotime(str_replace('/', '-', $date))) : '';
}

function translateDay($day) {
	if($day == "Monday") {
		$day = "Segunda-feira";
	} elseif($day == "Tuesday") {
		$day = "Terça-feira";
	} elseif($day == "Wednesday") {
		$day = "Quarta-feira";
	} elseif($day == "Thursday") {
		$day = "Quinta-feira";
	} elseif($day == "Friday") {
		$day = "Sexta-feira";
	} elseif($day == "Saturday") {
		$day = "Sábado";
	} elseif($day == "Sunday") {
		$day = "Domingo";
	}
	return $day;
}

function translateDayShort($day) {
	if($day == "Monday") {
		$day = "Seg";
	} elseif($day == "Tuesday") {
		$day = "Ter";
	} elseif($day == "Wednesday") {
		$day = "Qua";
	} elseif($day == "Thursday") {
		$day = "Qui";
	} elseif($day == "Friday") {
		$day = "Sex";
	} elseif($day == "Saturday") {
		$day = "Sáb";
	} elseif($day == "Sunday") {
		$day = "Dom";
	}
	return $day;
}

function translateMonth($month) {
	if($month == "January") {
		$month = "Janeiro";
	} elseif($month == "February") {
		$month = "Fevereiro";
	} elseif($month == "March") {
		$month = "Março";
	} elseif($month == "April") {
		$month = "Abril";
	} elseif($month == "May") {
		$month = "Maio";
	} elseif($month == "June") {
		$month = "Junho";
	} elseif($month == "July") {
		$month = "Julho";
	} elseif($month == "August") {
		$month = "Agosto";
	} elseif($month == "September") {
		$month = "Setembro";
	} elseif($month == "October") {
		$month = "Outubro";
	} elseif($month == "November") {
		$month = "Novembro";
	} elseif($month == "December") {
		$month = "Dezembro";
	}
	return $month;
}

function translateShortMonth($month) {
	if($month == "Jan") {
		$month = "Jan";
	} elseif($month == "Feb") {
		$month = "Fev";
	} elseif($month == "March") {
		$month = "Mar";
	} elseif($month == "Apr") {
		$month = "Abr";
	} elseif($month == "May") {
		$month = "Mai";
	} elseif($month == "Jun") {
		$month = "Jun";
	} elseif($month == "Jul") {
		$month = "Jul";
	} elseif($month == "Aug") {
		$month = "Ago";
	} elseif($month == "Sep") {
		$month = "Set";
	} elseif($month == "Oct") {
		$month = "Out";
	} elseif($month == "Nov") {
		$month = "Nov";
	} elseif($month == "Dec") {
		$month = "Dez";
	}
	return $month;
}

if(!function_exists('imageconvolution')) {
	function imageconvolution($src, $filter, $filter_div, $offset) {
		if($src == NULL) {
			return 0;
		}

		$sx = imagesx($src);
		$sy = imagesy($src);
		$srcback = ImageCreateTrueColor($sx, $sy);
		ImageCopy($srcback, $src, 0, 0, 0, 0, $sx, $sy);

		if($srcback == NULL) {
			return 0;
		}

		#FIX HERE
		#$pxl array was the problem so simply set it with very low values
		$pxl = array(1, 1);
		#this little fix worked for me as the undefined array threw out errors

		for($y = 0; $y < $sy; ++$y) {
			for($x = 0; $x < $sx; ++$x) {
				$new_r = $new_g = $new_b = 0;
				$alpha = imagecolorat($srcback, $pxl[0], $pxl[1]);
				$new_a = $alpha>>24;

				for($j = 0; $j < 3; ++$j) {
					$yv = min(max($y - 1 + $j, 0), $sy - 1);
					for($i = 0; $i < 3; ++$i) {
						$pxl = array(min(max($x - 1 + $i, 0), $sx - 1), $yv);
						$rgb = imagecolorat($srcback, $pxl[0], $pxl[1]);
						$new_r += (($rgb>>16) & 0xFF) * $filter[$j][$i];
						$new_g += (($rgb>>8) & 0xFF) * $filter[$j][$i];
						$new_b += ($rgb & 0xFF) * $filter[$j][$i];
					}
				}

				$new_r = ($new_r / $filter_div) + $offset;
				$new_g = ($new_g / $filter_div) + $offset;
				$new_b = ($new_b / $filter_div) + $offset;

				$new_r = ($new_r > 255) ? 255 : (($new_r < 0) ? 0 : $new_r);
				$new_g = ($new_g > 255) ? 255 : (($new_g < 0) ? 0 : $new_g);
				$new_b = ($new_b > 255) ? 255 : (($new_b < 0) ? 0 : $new_b);

				$new_pxl = ImageColorAllocateAlpha($src, (int)$new_r, (int)$new_g, (int)$new_b, $new_a);
				if($new_pxl == -1) {
					$new_pxl = ImageColorClosestAlpha($src, (int)$new_r, (int)$new_g, (int)$new_b, $new_a);
				}
				if(($y >= 0) && ($y < $sy)) {
					imagesetpixel($src, $x, $y, $new_pxl);
				}
			}
		}
		imagedestroy($srcback);
		return 1;
	}

}

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

function object2Array($obj) {
	if(is_object($obj)) $obj = (array) $obj;
	if(is_array($obj)) {
		$new = array();
		foreach($obj as $key => $val) {
			$new[$key] = object_to_array($val);
		}
	}
	else $new = $obj;
	return $new;
}

function eco($el){
	echo utf8_encode($el);
}

function numFBR($numero) {
	return number_format($numero, 2, ',', '.');
}

function removeWordChars($string) {
	if(!is_array($string)){
		$search = array('“'=>'\"','”'=>'\"',"‘"=>"\'","’"=>"\'","–"=>"-","'"=>"\'","•"=>"&bull;", "\"" => "\\\"");
		$string = strtr($string, $search);
	}
	return $string;
}

function breakTitleB($title){
	$tituloArray = explode(" ", $title);
	$titleB = "<b>";
	$i = 1;
	$c = 1;
	$r = count($tituloArray);
	foreach($tituloArray as $t){
		$titleB .= $t." ";
		if($i == 5){
			if($c <> $r) $titleB .= "</b><b>";
			$i = 1;
		}
		$i++;
		$c++;
	}
	$titleB .= "</b>";
	return $titleB;
}

function shortenText($string, $size=50, $ret='...'){
	$text = explode(" ", $string);
	$c = 0;
	$a = count(str_split($string));
	foreach ($text as $s){
		$nC = str_split($s, 1);
		$n = count($nC);
		if($c + $n > $size) {$c--; break;}
		else $c += $n + 1;
	}
	if($a <= $size) $ret = "";
	return substr($string, 0, $c).$ret;
}

function truncate($text, $length = 150, $ending = '...', $exact = false, $considerHtml = false) {
	if ($considerHtml) {
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}

		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length = strlen($ending);
		$open_tags = array();
		$truncate = '';

		foreach ($lines as $line_matchings) {
			if (!empty($line_matchings[1])) {
				if (preg_match('/^<(s*.+?/s*|s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(s.+?)?)>$/is', $line_matchings[1])) {
				} else if (preg_match('/^<s*/([^s]+?)s*>$/s', $line_matchings[1], $tag_matchings)) {
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {                            unset($open_tags[$pos]);
					}
				} else if (preg_match('/^<s*([^s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				$truncate .= $line_matchings[1];
			}
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			if ($total_length + $content_length > $length) {
				$left = $length - $total_length;
				$entities_length = 0;
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					foreach ($entities[0] as $entity) {
						if ($entity[1] + 1 - $entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							break;
						}
					}
				}
				$truncate .= substr($line_matchings[2], 0, $left + $entities_length);
				break;
			} else {
				$truncate .= $line_matchings[2];
				$total_length += $content_length;
			}
			if ($total_length >= $length) {
				break;
			}
		}
	} else {
		if (strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = substr($text, 0, $length - strlen($ending));
		}
	}

	if (!$exact) {
		$spacepos = strrpos($truncate, ' ');
		if (isset($spacepos)) {
			$truncate = substr($truncate, 0, $spacepos);
		}
	}
	$truncate .= $ending;

	if ($considerHtml) {
		foreach ($open_tags as $tag) {
			$truncate .= '</' . $tag . '>';
		}
	}

	return $truncate;
}

function getYoutubeVideoId($link){
	parse_str(parse_url($link, PHP_URL_QUERY), $yt);
	return $yt['v'];
}

function debug($texto){
	mysql_query("INSERT INTO debug(id,log) VALUES(".proxID('debug').", '$texto')", conecta());
}

function logadoSite($tipo){
	switch ($tipo) {
		case 'modelos':
			if($_SESSION['site']['modelo_id']) return true;
			else return false;
		break;
		case 'contratantes':
			if($_SESSION['site']['contratantes_id']) return true;
			else return false;
		break;
	}
}

function getAge($birthday){
	$dN = explode("-", formatDate($birthday, "m-d-Y"));
	return (date("md", date("U", mktime(0, 0, 0, $dN[0], $dN[1], $dN[2]))) > date("md") ? ((date("Y")-$dN[2])-1) : (date("Y")-$dN[2]));
}

function getUf($uf=''){
	$ufs = array("AC","AL","AP","AM","BA","CE","DF","ES","GO","MA","MT","MS","MG","PA","PB","PR","PE","PI","RJ","RN","RS","RO","RR","SC","SP","SE","TO");
	foreach($ufs as $u){
		$estados .= "<option value=\"$u\""; if($uf==$u) $estados .= ' selected="selected"';	$estados .= ">$u</option>\n";
	}
	return $estados;
}

function getFirstThumb($id, $tabela){
	$q = mysql_query("SELECT imagem FROM {$tabela}_imagens WHERE {$tabela}_id = $id ORDER BY ordem ASC LIMIT 1");
	$d = mysql_fetch_array($q);
	return $d['imagem'];
}

function formatDecimal($value){
	$value = str_replace(".", "", $value);
	$value = str_replace(",", ".", $value);
	return $value;
}

function getField($campo, $tabela, $where){
	$q = mysql_query("SELECT $campo FROM $tabela WHERE $where LIMIT 1");
	$d = mysql_fetch_array($q);
	return utf8_encode($d["$campo"]);
}

function generateSelect($campo, $titulo, $query, $compare=null){
	$select = "<select name=\"$campo\" id=\"$campo\">";
	$select .= "<option value=\"\"></option>";
	while($dados = mysql_fetch_array($query)){
		$select .= "<option";
		if($dados["id"] == $compare) $select .= ' selected="selected"';
		$select .= " value=\"{$dados["id"]}\">".$dados[$titulo]."</option>";
	}
	$select .= "</select>";
	return utf8_encode($select);
}

function anti_sql_injection($str) {
    if (!is_numeric($str)) {
        $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
        $str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str, conecta()) : mysql_escape_string($str);
        $str = preg_replace("/(from|select|insert|delete|where|drop table|show tables|union|table_schema|concat|information_schema|#|\*|--|\\\\)/i","",$str);
    	$str = trim($str);
    	$str = strip_tags($str);
    	$str = addslashes($str);
    }else{
        $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
        $str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str, conecta()) : mysql_escape_string($str);
    	$str = trim($str);
    	$str = strip_tags($str);
    	$str = addslashes($str);
    }
    return $str;
}

function isMobile(){
	$useragent=$_SERVER['HTTP_USER_AGENT'];
	return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
}

function sendEmail($addres, $subject, $body, $reply_to="pharmaspecial@pharmaspecial.com.br") {
	require_once '../mail/PHPMailerAutoload.php';

	$url_site = getConfig("url_site_pharma");

	$addres = !is_array($addres) ? [$addres] : $addres;

	$body = "	<html>
					<head>
						<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
						<style type='text/css'>
						body,p,table {color: #3F3F3F;font: 12px 'Trebuchet MS', Arial, Helvetica, sans-serif;}
						p {padding: 0; margin: 1em 0;}
						.geral table {border:1px #ccc solid; padding: 10px;}
						</style>
					</head>
					<body>
						<p align='center'>
						<img src='{$url_site}admin/images/logo.jpg'>
						</p>
						<table width='650' border='0' align='center' class='geral'>
							<tr>
								<td>
									{$body}							
					
									<p>Se voc&ecirc; tiver qualquer d&uacute;vida, por favor, sinta-se &agrave; vontade para contatar-nos no e-mail <a href='mailto:contato@pharmaspecial.com.br'>contato@pharmaspecial.com.br</a></p>
									<p>Obrigado,<br /><strong>PharmaSpecial</strong></p>
								</td>
							</tr>
						</table>
					</body>
				</html>";

	$mail = new PHPMailer(true);

	try {
		$mail->IsSMTP();
		$mail->IsHTML(true);
		$mail->SMTPAuth = true;
		$mail->Host = 'mail.aptaweb.net';
		$mail->Username = 'webmaster@pharmaspecial.com.br';
		$mail->Password = 'H9cr@tS*';
		$mail->setFrom('webmaster@pharmaspecial.com.br', "PharmaSpecial");
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';
		#$mail->SMTPDebug = 2;
		$mail->Subject = "PharmaSpecial : $subject";
		$mail->CharSet = 'utf-8';

		foreach($addres as $key => $value){
			$mail->AddAddress($value);
		}

		$mail->AddReplyTo($reply_to);

		$mail->MsgHTML($body);
		$enviado = $mail->Send();

		if ($enviado){
			return true;
		}  else {
			return false;
		}
	} catch (phpmailerException $e) {
		return $e->errorMessage();
	} catch (Exception $e) {
		return $e->getMessage();
	}
}

function sendEmailPic($addres, $subject, $body, $reply_to="contato@pic-web.com.br") {
	require_once '../mail/PHPMailerAutoload.php';

	$url_site = getConfig("url_site_pharma");

	$addres = !is_array($addres) ? [$addres] : $addres;

	$body = "	<html>
					<head>
						<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
						<style type='text/css'>
						body,p,table {color: #3F3F3F;font: 12px 'Trebuchet MS', Arial, Helvetica, sans-serif;}
						p {padding: 0; margin: 1em 0;}
						.geral table {border:1px #ccc solid; padding: 10px;}
						</style>
					</head>
					<body>
						<p align='center'>
						<img src='{$url_site}admin/images/logoPic.png'>
						</p>
						<table width='650' border='0' align='center' class='geral'>
							<tr>
								<td>
									{$body}							
					
									<p>Se voc&ecirc; tiver qualquer d&uacute;vida, por favor, sinta-se &agrave; vontade para contatar-nos no e-mail <a href='mailto:contato@pic-web.com.br'>contato@pic-web.com.br</a></p>
									<p>Obrigado,<br /><strong>Pic Química</strong></p>
								</td>
							</tr>
						</table>
					</body>
				</html>";

	$mail = new PHPMailer(true);

	try {
		$mail->IsSMTP();
		$mail->IsHTML(true);
		$mail->SMTPAuth = true;
		$mail->Host = 'mail.aptaweb.net';
		$mail->Username = 'webmaster@pic-web.com.br';
		$mail->Password = 'B48w6y*P';
		$mail->setFrom('webmaster@pic-web.com.br', "PIC");
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';
		#$mail->SMTPDebug = 2;
		$mail->Subject = "Pic Química : $subject";
		$mail->CharSet = 'utf-8';

		foreach($addres as $key => $value){
			$mail->AddAddress($value);
		}

		$mail->AddReplyTo($reply_to);

		$mail->MsgHTML($body);
		$enviado = $mail->Send();

		if ($enviado){
			return true;
		}  else {
			return false;
		}
	} catch (phpmailerException $e) {
		return $e->errorMessage();
	} catch (Exception $e) {
		return $e->getMessage();
	}
}


function generatePassword($length = 8) {
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$count = mb_strlen($chars);

	for ($i = 0, $result = ''; $i < $length; $i++) {
		$index = rand(0, $count - 1);
		$result .= mb_substr($chars, $index, 1);
	}

	return $result;
}

function makeFile($content, $fileName) {
	$basePath = str_replace('admin', '', realpath(dirname(__FILE__)));

	$_fileName = time()."-$fileName.txt";
	$filePath = $basePath.'media/login/';

	$file = fopen($filePath.$_fileName, "w");
	fwrite($file, $content);
	fclose($file);
}

