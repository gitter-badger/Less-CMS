<html lang="{lang}">
<head>
	<meta charset="utf-8">
	<title>Demo - {title}</title>
	<script src="{THEME}/js/jquery-2.1.1.min.js" type="text/javascript"></script>
	<link href="{THEME}/css/style.min.css" rel="stylesheet" type="text/css">
	<link href="{THEME}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="{THEME}/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="{THEME}/css/material.min.css" rel="stylesheet" type="text/css">
	{headers}
</head>

<body>
	[is=main]
<div class="section header">
	<div class="holder"></div>
	<div class="scontent">
		<h2>{lang-welcome_in}</h2>
		<p> {lang-welcome_mess}</p>
		<div class="social">
			<a href="https://github.com/CodeBurgerINT" target="_blank"><i class="fa fa-github"></i></a>
			<a href="https://www.fb.com/codeburgerint" target="_blank"><i class="fa fa-facebook"></i></a>
			<a href="https://twitter.com/codeburgerint" target="_blank"><i class="fa fa-twitter"></i></a>
			<a href="https://vk.com/codeburgerint" target="_blank"><i class="fa fa-vk"></i></a>
			<a href="https://plus.google.com/+CodeburgerItint" target="_blank"><i class="fa fa-google-plus"></i></a>
		</div>
	</div>
</div>
<div class="section">
	<div class="container">
		<h1> {lang-engine_info}</h1>

		<div class="col-md-12">
			<div class="col-md-4">
				<div class="icon">
					<i class="fa fa-database"></i>
				</div>

				<h4> {lang-database}</h4>
				<p>
					 {lang-database_info}
				</p>

			</div>
			<div class="col-md-4">
				<div class="icon">
					<i class="fa fa-language"></i>
				</div>

				<h4> {lang-multilingual}</h4>
				<p>
					 {lang-multilingual_info}
				</p>

			</div>
			<div class="col-md-4">
				<div class="icon">
					<i class="fa fa-server"></i>
				</div>

				<h4> {lang-simple_fast}</h4>
				<p>
					 {lang-simple_fast_info}
				</p>
			</div>
		</div>


		<div class="col-md-12">
			<div class="col-md-4">
				<div class="icon">
					<i class="fa fa-magic"></i>
				</div>

				<h4> {lang-templates}</h4>
				<p>
					 {lang-templates_info}
				</p>
			</div>
			<div class="col-md-4">
				<div class="icon">
					<i class="fa fa-code"></i>
				</div>

				<h4> {lang-open_source}</h4>
				<p>
					 {lang-open_source_info}
				</p>
			</div>
			<div class="col-md-4">
				<div class="icon">
					<i class="fa fa-heartbeat"></i>
				</div>

				<h4>{lang-stability}</h4>
				<p>
					{lang-stability_info}
				</p>
			</div>
		</div>

	</div>
</div>

<div class="section engine">
	<div class="holder"></div>

	<div class="small_scontent">
		<h2> {lang-thx_mess}</h2>
	</div>
</div>

<div class="section">
	<div class="container last">
		<h1> {lang-log_to_customize}</h1>
		 [logged=false]<a href="#" id="logIn" class="button blue bg" data-toggle="modal" data-target="#loginModal" data-toggle="tooltip" data-placement="right" title="{lang-customize_login}">{lang-signin}</a>[/logged]
 		 [logged=true]<a href="/signout" id="logIn" class="button blue bg" data-toggle="tooltip" data-placement="right" title="{lang-customize_login}">{lang-signout}</a>[/logged]
	</div>
</div>
[/is]
[not=main]
	 {info}
	 {content}
[/not]
<div class="section footer">

	<div class="copy">
		CherryPie Engine. &copy;2013-{year}. All rights reserved. Developed by <a href="http://www.n3sty.com" target="_blank">N3stY</a>. Powered by <a href="http://code.n3sty.com" target="_blank">CODE&trade;</a>
	</div>
</div>


<div id="loginModal" class="modal fade material">
	<div class="modal-dialog">
		<div class="modal-content">
		<form method="post" action="/signin">
			<div class="modal-body">
				<h2>{lang-signin}</h2>

				<div class="login_cont">
					<div class="normalLogin">
						{login_mode_inp}
						<input type="password" name="password" placeholder="{lang-password}">
					</div>
					<div class="socialLogin">
						<small><a class="signlink" href="/signup" rel="nofollow">{lang-signup}</a> {lang-or}</small>
						<h5>{lang-social_login}</h5>
						<a class="socialButton" href="https://www.facebook.com/dialog/oauth?client_id={fb_appid}&redirect_uri=http://{server_name}/return/facebook.php&response_type=code&scope=email,public_profile,user_about_me" rel="nofollow"><i class="fa fa-facebook"></i></a>
						<a class="socialButton" href="https://oauth.vk.com/authorize?client_id={vk_appid}&redirect_uri=http://{server_name}/return/vk.php&response_type=code&%20v=5.31" rel="nofollow"><i class="fa fa-vk"></i></a>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="button" data-dismiss="modal">{lang-cancel}</button>
				<button type="submit" class="button blue">{lang-signin}</button>
			</div>
		</form>
		</div>
	</div>
</div>

<script src="{THEME}/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{THEME}/js/default.js"></script>
</body>
</html>
