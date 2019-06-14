<?php header('Content-Type: text/html; charset=utf-8'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php $url = getConfig("url_admin_uniao"); ?>
<!--
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
-->

<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>

<script type="text/javascript" src="<?php echo $url; ?>js/jquery-1.8.2.min.js"></script>

<script type="text/javascript" src="<?php echo $url; ?>js/jquery-ui-1.9.1.full.min.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/jquery.easing.1.3.js"></script>

<script type="text/javascript" src="<?php echo $url; ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/jquery.validate.additional-methods.min.js?q=1"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/jquery.ui.datepicker-pt-BR.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/jquery.ui.timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/jquery.ui.timepicker-pt-BR.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/jquery.ui.datepicker.validation.min.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/jquery.meio.mask.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/jquery.dataTables.sortTypes.js"></script>

<script type="text/javascript" src="<?php echo $url; ?>js/tools/uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/tools/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/tools.js?_=1"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/jquery.Jcrop.js"></script>
<script src="<?php echo $url ?>js/jquery.noty.packaged.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/plupload.full.min.js?_=1"></script>
<script type="text/javascript" src="<?php echo $url; ?>js/jquery.alerts.js"></script>

<script type="text/javascript" src="<?php echo $url; ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>ckeditor/adapters/jquery.js"></script>

<link type="text/css" rel="stylesheet" href="<?php echo $url; ?>css/jquery-ui-1.9.1.custom.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $url; ?>js/tools/uploadify/uploadify.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $url; ?>css/jquery.Jcrop.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $url; ?>css/jquery.alerts.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $url; ?>css/styles.css" />

<title>Admin - Área Administrável</title>
