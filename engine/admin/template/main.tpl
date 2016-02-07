<html>
<head>
  <meta charset="utf-8">
  <title>{title} - {lang-dashboard}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{THEME}/images/favicon.png" rel="shortcut icon" type="image/x-icon" />
  <link href="{THEME}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="{THEME}/css/jmcsc.min.css" rel="stylesheet" type="text/css">
  <link href="{THEME}/css/bisfont.min.css" rel="stylesheet" type="text/css">
  <link href="{THEME}/css/bisquit.min.css" rel="stylesheet" type="text/css">
  <link href="{THEME}/css/style.min.css" rel="stylesheet" type="text/css">
</head>

<body>

  <div class="left_side">
    <div class="small_header">
      <a href="#"><img src="{THEME}/images/logo.png"></a>
      {mode_on}
    </div>
    <div class="menu_list">
      <ul>
        <li [is=main]class="active"[/is]><a href="/admin.php"><i class="mi">home</i>Home</a></li>
        {main_modules}
      </ul>
    </div>
  </div>
  <div class="main_side">
    <div class="header">
      <div class="hright">
        <a href="/">{lang-show_front}</a>
        <span class="dp_holder">
          <a href="#" data-toggle="dropdown">{lang-language}</a>
          <div class="dp_menu">
            <span class="dp_header">
              <i class="mi">translate</i> {lang-language}
            </span>
            <ul>
              {langs_list}
            </ul>
          </div>
        </span>

        <a href="?system=status">{lang-sys_status}</a>
        <a href="?system=modules">{lang-modules}</a>
        <a href="?system=settings">{lang-system_config}</a>
        <span class="dp_holder">
          <a href="#" class="member" data-toggle="dropdown"><i class="mi">face</i> {member-fname} {member-lname}</a>
          <div class="dp_menu">
            <span class="dp_header">
              <i class="mi">face</i> {member-fname} {member-lname}
            </span>
            <ul>
              <li><a href="?do=membership&user={member-id}"><i class="mi">person</i>{lang-profile}</a></li>
              <li><a href="?do=signout"><i class="mi">power_settings_new</i>{lang-signout}</a></li>
            </ul>
          </div>
        </span>
      </div>
    </div>
    <div class="container-fluid">
      [is=main]
      {dashboard}
      [/is]
      [not=main]
      {info}
      {content}
      [/not]
    </div>
  </div>

  <script src="{THEME}/js/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script src="{THEME}/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="{THEME}/js/jmcsc.min.js" type="text/javascript"></script>
  <script src="{THEME}/js/main.js" type="text/javascript"></script>
  <!-- Page loaded in {loading_time} seconds -->
</body>
</html>
