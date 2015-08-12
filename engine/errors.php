<?php
if( !defined( 'E_DEPRECATED' ) ) {
	@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
	@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );
} else {
	@error_reporting ( E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );
	@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );
}
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );

if(!$err_type)
{
	$title = 'Unknown error';	
	$err_type = 'Unknown error...';	
}
	echo <<<HTML
<html lang="en">
<head>
<meta charset="utf-8">
<title>{$title}</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/template/system/css/fonts.css">
<style type="text/css">
@import url(http://fonts.googleapis.com/css?family=Roboto:400,300,500,700&subset=cyrillic,latin);
html, body {
	padding: 0px;
	margin: 0px;
	color: #FFF;
	background: #031F3C;
	cursor: default;
}
.content {
	text-align: center;
	position: absolute;
	height: 100%;
	left: 0px;
	top: 0px;
	right: 0px;
}
.center {
	display: inline-block;
	position:relative;
	width: 45%;
	min-height: 77px;
	top: calc(50% - 177px);
	~top: 50%;
}
.center > header {
	font-weight: 100;
	font-size: 60px;
	line-height: 35px;
	font-family: 'Roboto', sans-serif;
}
.center > header > textarea {
	color: #3498db;
	margin-top: 15px;
	background: #021528;
	border-radius: 3px;
	font: 400 12px 'Roboto', sans-serif;
	text-align: left;
	height: 150px;
	width: 100%;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	outline: none;
	padding: 24px;
	resize: vertical;
	cursor: text;
}
.center > header > img {
	height: 200px;
	display: inline-block;
	margin-bottom: 10px;
}
</style>
</head>

<body>
<div class="content">
 <div class="center">
 	<header>
	<img src="/uploads/system/octop.png"><br>
	 CodeBurger&copy; Int.
	 <br>
	 <br>
	 <textarea readonly>{$err_type}</textarea>
	</header>	
 </div>
</div>

</body>
</html>		
HTML;
?>