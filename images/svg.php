<?php
$imagem = $_GET['imagem'];
$color = $_GET['color'];

header('Content-type: '.mime_content_type($imagem));

$content = file_get_contents($imagem);
$content = preg_replace('/#(.){6}/', '#'.$color, $content);

echo $content;
