<?php
$text = $text ?: "Access denied";
echo <<<HTML
<html>
<head>
  <meta charset="utf-8">
  <title>Error</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="images/favicon.png" rel="shortcut icon" type="image/x-icon" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <style media="screen">
    .err_container a,h2{text-transform:uppercase;font-weight:700}body{background:#EEE}h2{color:#9099a3;margin:0 0 20px}.err_container{text-align:center;padding-top:15%}.err_container textarea{resize:none;display:block;margin:0 auto;width:400px;height:200px;padding:20px;border-radius:4px;outline:0;background:#fff;border:1pt solid #E8E4DE}.err_container a{display:inline-block;height:40px;line-height:40px;margin:30px auto;background:#607D8B;color:#fff;text-decoration:none;padding:0 15px;border-radius:4px}.err_container a:hover{background:#455A64}
  </style>
</head>

<body>

  <div class="err_container">
    <h2>Error reporting</h2>
    <textarea readonly>$text</textarea>
    <a href="/">back to site</a>
  </div>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
</body>
</html>
HTML;
exit;
?>
