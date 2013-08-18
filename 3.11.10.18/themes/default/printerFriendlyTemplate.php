<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
	<head>
		<title>Fast Track Sites Trouble Ticket System - <? $page->printTemplateVar("PageTitle");  ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="en-us" />
		<!--Stylesheets Begin-->
			<link rel="stylesheet" type="text/css" href="themes/<?= $tts_config['ftstts_theme']; ?>/printerFriendly.css" />
			<!--[if lt IE 7]>
				<style>
				</style>
			<![endif]-->			
		<!--Stylesheets End-->
		<!--Javascripts Begin-->
			<script type="text/javascript" src="javascripts/jquery-1.3.2.js"></script>
			<script type="text/javascript" src="javascripts/jquery-ui-1.7.2.js"></script>
			<script type="text/javascript" src="javascripts/jquery.form.js"></script>
			<script type="text/javascript" src="javascripts/jquery.validate.js"></script>
			<script type="text/javascript" src="javascripts/jquery.jeditable.mini.js"></script>
			<script type="text/javascript" src="javascripts/jquery.tablesorter.min.js"></script>
			<script type="text/javascript" src="javascripts/swfobject.js"></script>
			<script type="text/javascript" src="javascripts/jquery.uploadify.v2.1.0.min.js"></script>
			<script type="text/javascript" src="javascripts/functions.js"></script>	
			<script type="text/javascript" src="javascripts/FusionCharts.js"></script>
			<script type="text/javascript">
				$(document).ready(function(){
					<? $page->printTemplateVar('JQueryReadyScript'); ?>
				});	
			</script>
	<!--Javascripts End-->
	</head>
	<body>
		<div id="container">
			<div id="page">
				<div id="header">
					<img src="images/printerFriendlyFtsLogo.png" alt="Fast Track Sites Logo" />
				</div>
				<div id="content">
					<? $page->printTemplateVar('PageContent'); ?>	
				</div>
				
				<div id="footer">
					<div style="float: right; padding-right: 5px;">
						Powered By: <a href="http://www.fasttracksites.com">Fast Track Sites Trouble Ticket System</a>
					</div>
					Copyright &copy; 2006 Fast Track Sites
				</div>
			</div>
		</div>
	</body>
</html>
