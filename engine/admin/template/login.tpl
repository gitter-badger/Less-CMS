<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="{THEME}/images/favicon.png" rel="shortcut icon" type="image/x-icon" />

	<title>{lang-session_locked} - {title}</title>
	<script src="{THEME}/js/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="{THEME}/js/bootstrap.min.js" type="text/javascript"></script>
	<link href="{THEME}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="{THEME}/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="{THEME}/css/material.min.css" rel="stylesheet" type="text/css">
	<link href="{THEME}/css/session_locked.css" rel="stylesheet" type="text/css">
	<link href="{THEME}/css/jquery.gritter.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div class="dark_hover"></div>
	<div class="main_body">
		<div class="locer_cont">
			<div class="avatar"><div><img src="/uploads/avatars/{member-login}/{member-avatar}"></div></div>
			<h3>{lang-welcome} - <span class="name">{member-fname} {member-lname}</span></h3>
			<h5>{lang-block_info}</h5>
			<input id="logon" type="password" placeholder="{lang-password}" autofocus>
			<button type="submit" class="button green bg">{lang-signin}</button>
		</div>
	</div>

	<script type="text/javascript" src="{THEME}/js/jquery.gritter.js"></script>
	<script type="text/javascript" src="{THEME}/js/cherry_pie.js"></script>
	<script type="text/javascript">
		$('body').on('click', 'button', function()
		{
			if($('input').val() == '')
			{
				$.gritter.add(
					{
						title: lang.error,
						text: lang.pass_not_be_empty,
						class_name: 'cherrypie'
					}
				);
				return false;
			}
			else if($('input').val().length < 4)
			{
				$.gritter.add(
					{
						title: lang.error,
						text: lang.pass_not_be_empty,
						class_name: 'cherrypie'
					}
				);
				return false;
			}

			$.ajax({
				type: 'POST',
				data: {"logon":$('#logon').val(),"AJAX" : true},
				cache: false,
				beforeSend: function()
				{
					$('button').html('<i class="fa fa-spinner fa-pulse"></i>');
				},
				success: function(html)
				{
					json = $.parseJSON(html);
					if(json.status == 'passed')
					{
						$('button').html(lang.signin);
						location.reload();
					}
					else
					{
						$('button').html(lang.signin);
						$.gritter.add(
							{
								title: lang.error,
								text: lang.pass_err,
								class_name: 'cherrypie'
							}
						);
					}
				}
			});
		});
	</script>
</body>
</html>
