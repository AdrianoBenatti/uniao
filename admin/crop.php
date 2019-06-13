<?php
if ($_POST){
	header("Content-type: image/jpeg");
	$basePath = str_replace('admin', '', realpath(dirname(__FILE__)));

	$targ_w = 340;
	$targ_h = 300;

	$src = $_POST['imagem'];

	$ext = pathinfo($src);
	$extension = $ext['extension'];

	if ($extension == 'jpg' || $extension == 'jpeg'){
		$img_r = imagecreatefromjpeg($src);
		$dst_r = imagecreatetruecolor( $targ_w, $targ_h );

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);
		imagejpeg($dst_r, $src, 100);
	} else if ($extension == 'png'){
		$img_r = imagecreatefrompng($src);
		$dst_r = imagecreatetruecolor( $targ_w, $targ_h );

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);
		imagepng($dst_r, $src, 9);
	}
	//copy($src, '.$ext['filename'].$ext['extension']);
	//move_uploaded_file($ext['filename'], $basePath.'media/noticias/');
	exit;
}
?>
