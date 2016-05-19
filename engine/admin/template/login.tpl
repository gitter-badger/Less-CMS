<html>
<head>
  <meta charset="utf-8">
  <title>Login page - {title}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{THEME}/images/favicon.png" rel="shortcut icon" type="image/x-icon" />
  <link href="{THEME}/css/materialize.min.css" rel="stylesheet" type="text/css">
  <link href="{THEME}/css/scroll.css" rel="stylesheet" type="text/css">
  <link href="{THEME}/css/style.min.css" rel="stylesheet" type="text/css">
</head>

<body>
  <div class="login_page">
    <div class="logo"><img src="{THEME}/images/less.png"></div>
    <div class="messagge">{lang-signtocont}</div>

    <div class="form">
      <div>
        <img src="{THEME}/images/noimage.jpg" id="photo">
      </div>
      <div id="name"></div>
      <div id="email_addr"></div>
      <div class="inps">
        <form method="POST" action="?do=signin">
          <input type="email" name="email" id="email" placeholder="Email">
          <input type="password" id="password" name="password" placeholder="{lang-password}">
          <input type="submit" id="send">
        </form>
      </div>
      <div id="errors">{error}</div>
      <div>
        <a id="nextlogin">{lang-next}</a>
      </div>
    </div>
    <div class="return">
      <a href="/" id="backbtn">{lang-show_front}</a>
    </div>
  </div>
  <script src="{THEME}/js/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script src="{THEME}/js/materialize.min.js" type="text/javascript"></script>
  <script src="{THEME}/js/scroll.js" type="text/javascript"></script>
  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script src="{THEME}/js/main.min.js" type="text/javascript"></script>
</body>
</html>
