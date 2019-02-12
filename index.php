<?php
session_start();
include("admin/functions.php");
$page = $_GET['page']? $_GET['page']: 'home';
$url = getConfig('url_site');
$mediaUrl = $url.'media/';
if(APPLICATION_ENV=='development'){
    ob_start();
}else{
    ob_start("sanitize_output"); // Novo buffer
}
if(!file_exists("$page.php")){
    header('HTTP/1.0 404 Not Found');
    $page = 'error404';
}
include("$page.php");
$content = ob_get_contents(); // Armazena o content
ob_end_clean(); // Limpa o buffer e fecha
if(APPLICATION_ENV=='development'){
    ob_start();
}else{
    ob_start("sanitize_output"); // Novo buffer
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

    <link href="https://fonts.googleapis.com/css?family=Hind+Madurai" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Baloo+Thambi" rel="stylesheet">



	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#5d4332">

    <meta name="description" content="<?php echo isset($description) ?  $description : utf8_encode("") ?>" />
    <meta name="keywords" content="<?php echo isset($keywords) ? $keywords : ""; ?>" />

    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?php echo isset($image) ? $image : $url."images/logo.png";  ?>"/>
    <meta property="og:url" content="http://<?php echo $_SERVER['SERVER_NAME'] ?><?php echo $_SERVER['REQUEST_URI'] ?>" />
    <meta property="og:site_name" content="<?php echo isset($title) ? $title." - " : ""; ?>" />
    <meta property="og:title" content="<?php echo isset($title) ? $title." - " : ""; ?>" />
    <meta property="og:description" content="<?php echo isset($description) ?  $description : utf8_encode("") ?>" />
    <link rel="image_src" href="<?php echo isset($image) ? $image : $url."images/logo.png";  ?>" />

    <title><?php echo isset($title) ? $title."E.C. União Santa Luiza" : "E.C. União Santa Luiza"; ?></title>
    <link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />
    <link rel="shortcut icon" href="images/ico.ico" type="image/x-icon">
    <script src="js/jquery-1.11.0.min.js" type="text/javascript"></script>


    <!--    --><?php //echo $configuracoes['analytics'] ?>



    <script type='text/javascript'>
        window.q=[];
        window.$=function(f){
            q.push(f)
        }
    </script>

</head>

<body>
<div id="wrapper">
    <div class="bg_wrap">
        <div class="wrap w_">
            <header>
                <?php include("_header.php"); ?>
            </header>
            <div class="content">
                <?php echo $content; // Monta o Conteudo ?>
            </div>
            <footer>
                <?php include("_footer.php");  ?>
            </footer>
        </div>
    </div>
</div>


<link property="stylesheet" rel="stylesheet" type="text/css" media="all" href="<?php echo $url ?>css/colorbox.css"/>
<link property="stylesheet" rel="stylesheet" type="text/css" media="all" href="<?php echo $url ?>css/reset/cssreset-min.css"/>
<link property="stylesheet" rel="stylesheet" type="text/css" media="all" href="<?php echo $url ?>css/reset/cssfonts-min.css"/>
<link property="stylesheet" rel="stylesheet" type="text/css" media="all" href="<?php echo $url ?>css/reset/cssbase-min.css"/>
<link property="stylesheet" rel="stylesheet" type="text/css" media="all" href="<?php echo $url ?>css/owl.carousel.css"/>
<link property="stylesheet" rel="stylesheet" type="text/css" media="all" href="<?php echo $url ?>css/jquery.alerts.css"/>

<script src="<?php echo $url ?>js/jquery-3.3.1.min.js" type="text/javascript"></script>
<script src="<?php echo $url ?>js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

<script src="<?php echo $url ?>js/jquery.colorbox-min.js" type="text/javascript"></script>
<script src="<?php echo $url ?>js/jquery.meio.mask.js" type="text/javascript"></script>
<script src="<?php echo $url ?>js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo $url ?>js/jquery.validate.additional-methods.min.js" type="text/javascript"></script>
<script src="<?php echo $url ?>js/owl.carousel.min.js" type="text/javascript"></script>
<script src="<?php echo $url ?>js/tools.js" type="text/javascript"></script>
<script src="<?php echo $url ?>js/jquery.animateNumber.min.js" type="text/javascript"></script>
<script src="<?php echo $url ?>js/jAlert.min.js" type="text/javascript"></script>



<script type="text/javascript">
	$.each(q,function(index,f){
		$(f);
	});
	var url = "<?php echo $url ?>";
</script>
</body></html>
<?php
ob_flush();
ob_end_clean();
