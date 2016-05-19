<html>
<head>
  <meta charset="utf-8">
  <title>{title} - {lang-dashboard}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{THEME}/images/favicon.png" rel="shortcut icon" type="image/x-icon" />
  <link href="{THEME}/css/materialize.min.css" rel="stylesheet" type="text/css">
  <link href="{THEME}/css/scroll.css" rel="stylesheet" type="text/css">
  <link href="{THEME}/css/gritter.min.css" rel="stylesheet" type="text/css">
  <link href="{THEME}/css/style.min.css" rel="stylesheet" type="text/css">
</head>

<body>
  <ul id="menu_left" class="side-nav fixed">
    <div class="logo">
      <a href="#"><img src="{THEME}/images/logo.png"></a>&nbsp;
    </div>
    <ul class="menu_list">
      <li class="avatar">
        <img src="{photos}{member-avatar}">
      </li>
      <li class="name"><a class="dropdown-button" data-activates="mobile_user_menu">{member-showName}<i class="mi">chevron_right</i></a></li>
      <ul id='mobile_user_menu' class='dropdown-content'>
        <li><a href="?do=membership&user={member-id}">{lang-profile}</a></li>
        <li><a href="?do=signout">{lang-signout}</a></li>
        <li class="divider"></li>
        <li><a href="/admin.php">{lang-home}</a></li>
        <li><a href="/">{lang-show_front}</a></li>
        <li><a href="?system=modules">{lang-modules}</a></li>
        <li><a href="?system=sys_status">{lang-sys_status}</a></li>
        <li><a href="?system=settings">{lang-settings}</a></li>
      </ul>
    </ul>
    {main_modules}
  </ul>
  <ul id="menu_right" class="side-nav">
    {langs_list}
  </ul>

  <!-- Dropdown Structure -->
<ul id="user_menu" class="dropdown-content">
  <li><a href="?do=membership&user={member-id}">{lang-profile}</a></li>
  <li class="divider"></li>
  <li><a href="?do=signout">{lang-signout}</a></li>
</ul>
<div class="navbar-fixed">
  <nav class="header">
    <div class="nav-wrapper">
      <a id="show_left" data-activates="menu_left" class="button-collapse menu_call"><i class="mi">menu</i></a>
      <a id="show_right" data-activates="menu_right" class="button-collapse menu_call"><i class="mi">translate</i></a>

      <a href="#" class="brand-logo center"><img src="{THEME}/images/less.png"></a>
      <ul class="right hide-on-med-and-down">
        <li><a href="/admin.php">{lang-home}</a></li>
        <li><a href="/">{lang-show_front}</a></li>
        <li><a href="?system=modules">{lang-modules}</a></li>
        <li><a href="?system=sys_status">{lang-sys_status}</a></li>
        <li><a href="?system=settings">{lang-settings}</a></li>
        <!-- Dropdown Trigger -->
        <li><a class="dropdown-button" data-activates="user_menu">{member-showName}<i class="mi right">arrow_drop_down</i></a></li>
      </ul>
    </div>
  </nav>
</div>
{breadcrumbs}
<div class="main_container">
  [is=main]
    {dashboard}
  [/is]
  [not=main]
    {info}
    {content}
  [/not]
</div>

  <script src="{THEME}/js/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script src="{THEME}/js/materialize.min.js" type="text/javascript"></script>
  <script src="{THEME}/js/scroll.js" type="text/javascript"></script>
  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script src="{THEME}/js/gritter.min.js" type="text/javascript"></script>
  <script src="{THEME}/js/main.min.js" type="text/javascript"></script>
</body>
</html>
