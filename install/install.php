<?php
if (!defined('E_DEPRECATED'))
{
  @error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
  @ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);
}
else
{
  @error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
  @ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
}

@ini_set('display_errors', true);
@ini_set('html_errors', false);
define("ROOT", $_SERVER['DOCUMENT_ROOT']);
define("VERSION", "4.0");
session_start();

function encode($str)
{
  return eval(base64_decode('JHBhc3N3ID0gJF9TRVNTSU9OWydsa2V5J107DQokc2FsdCA9ICJEbjgqIzJuITlqIjsNCiRsZW4gPSBzdHJsZW4oJHN0cik7DQokZ2FtbWEgPSAnJzsNCiRuID0gJGxlbj4xMDAgPyA4IDogMjsNCndoaWxlKCBzdHJsZW4oJGdhbW1hKTwkbGVuICkNCnsNCiAgJGdhbW1hIC49IHN1YnN0cihwYWNrKCdIKicsIHNoYTEoJHBhc3N3LiRnYW1tYS4kc2FsdCkpLCAwLCAkbik7DQp9DQpyZXR1cm4gYmFzZTY0X2VuY29kZSgkc3RyXiRnYW1tYSk7DQo='));
}

if ($_POST)
{
  switch ($_POST['action'])
  {
    case 'create_bd':
      $_POST['params']['host'] = $_POST['params']['host'] ? : "localhost";
      $_POST['params']['table'] = $_POST['params']['table'] ? : "undefined";
      $_POST['params']['user'] = $_POST['params']['user'] ? : "root";
      $fp = fopen(ROOT . "/engine/configs/drivers/MySql.json", 'w');
      fwrite($fp, json_encode($_POST['params'], JSON_PRETTY_PRINT));
      fclose($fp);
      $db = new mysqli($_POST['params']['host'] ? : "localhost", $_POST['params']['user'] ? : "root", $_POST['params']['password'], $_POST['params']['table'] ? : "undefined");
      if ($db->connect_errno)
      {
        unlink(ROOT . "/engine/configs/drivers/MySql.json");
        $_SESSION['dberr'] = $db->connect_error;
        if (file_exists(ROOT . "/engine/configs/drivers")) header("Location: ?error=db_query_error");
        exit;
      }

      $create[] = "CREATE TABLE `extensions` (`id` int(11) NOT NULL, `sort` varchar(11) NOT NULL, `title` varchar(30) NOT NULL, `link` varchar(20) NOT NULL, `type` varchar(20) NOT NULL, `lang_code` varchar(3) NOT NULL, `version` varchar(5) NOT NULL, `public` int(11) NOT NULL DEFAULT '1') ENGINE=MyISAM DEFAULT CHARSET=utf8";
      $create[] = "CREATE TABLE `members` (`id` int(11) NOT NULL, `group_id` varchar(1) NOT NULL DEFAULT '0', `regdate` text NOT NULL, `login` varchar(20) NOT NULL, `password` varchar(200) NOT NULL, `email` varchar(40) NOT NULL, `fname` varchar(40) NOT NULL, `lname` varchar(100) NOT NULL, `avatar` varchar(100) NOT NULL, `lang` varchar(3) NOT NULL, `aboutme` varchar(150) NOT NULL, `status` int(11) NOT NULL, `login_hash` varchar(150) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8";
      $create[] = "CREATE TABLE `members_social` (`id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `social_id` varchar(200) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8";
      $create[] = "CREATE TABLE `system_stats` (`id` int(11) NOT NULL, `ip` text NOT NULL, `date` text NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8";
      $create[] = "INSERT INTO `extensions` (`id`, `sort`, `title`, `link`, `type`, `lang_code`, `version`, `public`) VALUES (NULL, '', 'Russian', 'ru_lang', 'langpack', 'RU', '1.0', '0'), (NULL, '', 'English', 'en_lang', 'langpack', 'EN', '1.0', '0'), (NULL, '5', 'Users manager', 'membership', 'module', '', '1.0', '1')";
      $create[] = "ALTER TABLE `extensions` ADD PRIMARY KEY (`id`)";
      $create[] = "ALTER TABLE `members` ADD UNIQUE KEY `id` (`id`)";
      $create[] = "ALTER TABLE `members_social` ADD PRIMARY KEY (`id`)";
      $create[] = "ALTER TABLE `system_stats` ADD PRIMARY KEY (`id`)";
      $create[] = "ALTER TABLE `extensions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";
      $create[] = "ALTER TABLE `members` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";
      $create[] = "ALTER TABLE `members_social` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";
      $create[] = "ALTER TABLE `system_stats` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";
      foreach($create as $sql)
      {
        $db->query($sql);
      }

      header("Location: ?step=3");
      break;

    case 'create_acc':
      $dbc = json_decode(file_get_contents(ROOT . "/engine/configs/drivers/MySql.json"));
      $db = new mysqli($dbc->host, $dbc->user, $dbc->password, $dbc->table);
      $db->set_charset("utf8");
      $db->query("INSERT INTO `members` (`group_id`, `regdate`, `login`, `password`, `email`, `status`) VALUES
  (1, '" . time() . "', '" . $db->real_escape_string($_POST['params']['login']) . "', '" . $db->real_escape_string(encode($_POST['params']['password'])) . "', '" . $db->real_escape_string($_POST['params']['email']) . "', 1)");
      header("Location: ?step=4");
      break;

    case 'setup_app':
      $fp = fopen(ROOT . "/engine/configs/config.ini", 'w');
      $fw = "version = \"" . VERSION . "\"\n\nlkey = \"LessCMS-Secure\"\n\ntitle = \"" . $_POST['title'] . "\"\n\ndatabase = \"MySql\"\n\ntemplate = \"Default\"\n\n";
      fwrite($fp, $fw);
      fclose($fp);
      header("Location: ?step=5");
      break;

    case 'remove_intaller':
      unset($_SESSION['lkey']);
      unset($_SESSION['dberr']);
      $dir = scandir(ROOT . "/install/assets/");
      foreach($dir as $file)
      {
        if ($file !== ".." && $file !== ".")
        {
          unlink(ROOT . "/install/assets/" . $file);
        }
      }

      rmdir(ROOT . "/install/assets");
      unlink(ROOT . "/install/install.php");
      rmdir(ROOT . "/install");
      $json['success'] = true;
      echo json_encode($json);
      break;
  }
}
else
{
  $welcome_c = "";
  $database_c = "";
  $admin_acc_c = "";
  $base_config_c = "";
  $clean_c = "";
  $welcome_i = "label";
  $database_i = "label";
  $admin_acc_i = "label";
  $base_config_i = "label";
  $clean_i = "label";
  $step = $_GET['step'];
  if (!isset($_GET['error']))
  {
    if (!isset($_GET['step'])) $step = 1;
  }

  switch ($step)
  {
  case "1":
    $pstat = "";
    $mistat = "";
    $upstat = "";
    $safestat = "";
    $upstat_t = "Enabled";
    $safestat_t = "Disabled";
    if (phpversion() < "5.6.0") $pstat = "off";
    if (!phpversion('mysqli')) $mistat = "off";
    if (!ini_get('file_uploads'))
    {
      $upstat = "off";
      $upstat_t = "Disabled";
    }

    if (ini_get('safe_mode'))
    {
      $safestat = "off";
      $safestat_t = "Enabled";
    }

    $welcome_c = ' class="current"';
    $page = '
      <h2 class="step_title">Server parameters<small>If at least one icon discolored continue not recommend</small></h2>
      <div class="sv_parameters">
        <div class="param_qube ' . $pstat . '"><i class="mi">code</i> <span> PHP 5.6.0+</span></div>
        <div class="param_qube ' . $mistat . '"><i class="mi">storage</i> <span> MySQLi</span></div>
        <div class="param_qube ' . $upstat . '"><i class="mi">file_upload</i> <span> Files Uploading<small>' . $upstat_t . '</small></span></div>
        <div class="param_qube ' . $safestat . '"><i class="mi">lock_outline</i> <span> Safe Mode<small>' . $safestat_t . '</small></span></div>
      </div>
        <a href="?step=2" class="continue">Continue</a>';
    break;

  case '2':
    if (!file_exists(ROOT . "/engine/configs/")) header("Location: ?error=archive");
    if (file_exists(ROOT . "/engine/configs/drivers/MySql.json")) header("Location: ?error=db_cfg_exists");
    $welcome_c = ' class="complite"';
    $welcome_i = 'check';
    $database_c = ' class="current"';
    $page = '<h2 class="step_title">Database Setup<small>To continue, you must setup the database</small></h2>
      <form method="POST">
        <div class="inrow">
          <label>DB Host</label>
          <input type="text" name="params[host]" placeholder="localhost">
        </div>
        <div class="inrow">
          <label>DB Table</label>
          <input type="text" name="params[table]">
        </div>
        <div class="inrow">
          <label>DB User</label>
          <input type="text" name="params[user]">
        </div>
        <div class="inrow">
          <label>DB Password</label>
          <input type="text" name="params[password]">
        </div>
        <div class="inrow btns">
          <button type="submit" class="continue">Test & Save</button>
        </div>
        <input type="hidden" name="action" value="create_bd">
      </form>';
    break;

  case '3':
    if (!file_exists(ROOT . "/engine/configs/")) header("Location: ?error=not_installed");
    $welcome_c = ' class="complite"';
    $welcome_i = 'check';
    $database_c = ' class="complite"';
    $database_i = 'check';
    $admin_acc_c = ' class="current"';
    $page = '<h2 class="step_title">First admin account<small>To continue, you must setup the first administrator account</small></h2>
      <form method="POST">
        <div class="inrow">
          <label>Email</label>
          <input type="text" name="params[email]" required>
        </div>
        <div class="inrow">
          <label>Login</label>
          <input type="text" name="params[login]" required>
        </div>
        <div class="inrow">
          <label>Password</label>
          <input type="text" name="params[password]" id="password" required>
        </div>
        <div class="inrow">
          <label>Re type password</label>
          <input type="text" name="params[password2]" id="password2" required>
        </div>
        <div class="inrow btns">
          <button type="submit" class="continue">Create</button>
        </div>
        <input type="hidden" name="action" value="create_acc">
      </form>';
    break;

  case '4':
    if (!file_exists(ROOT . "/engine/configs/")) header("Location: ?error=not_installed");
    $welcome_c = ' class="complite"';
    $welcome_i = 'check';
    $database_c = ' class="complite"';
    $database_i = 'check';
    $admin_acc_c = ' class="complite"';
    $admin_acc_i = 'check';
    $base_config_c = ' class="current"';
    $page = '<h2 class="step_title">First app configuration<small>To continue, you must configure the app</small></h2>
      <form method="POST">
        <div class="inrow">
          <label>Site name (title)</label>
          <input type="text" name="title" placeholder="CherryPie Engine Demo" required>
        </div>
        <div class="inrow btns">
          <button type="submit" class="continue">Save</button>
        </div>
        <input type="hidden" name="action" value="setup_app">
      </form>';
    break;

  case '5':
    if (!file_exists(ROOT . "/engine/configs/")) header("Location: ?error=not_installed");
    $welcome_c = ' class="complite"';
    $welcome_i = 'check';
    $database_c = ' class="complite"';
    $database_i = 'check';
    $admin_acc_c = ' class="complite"';
    $admin_acc_i = 'check';
    $base_config_c = ' class="complite"';
    $base_config_i = 'check';
    $clean_c = ' class="current"';
    $page = '<h2 class="step_title">Congratulations!<small>Installation complete. Now you can remove installator folder in automatic/manual mode</small></h2>

      <div class="inrow finish" id="rm_cnt">
        <button id="rm_int" class="continue">Remove installator</button>
      </div>
      <h2 class="step_title">First steps<small>Go to...</small></h2>
      <div class="inrow finish">
        <a href="/admin.php" class="continue">Admin area</a>
        <a href="/" class="continue">Site</a>
      </div>';
    break;

  default:
    if ($_GET['error'] == "archive")
    {
      $page = '<h2 class="step_title">Installer error<small>Engine files not found, please download it & unzip in site root folder</small></h2>

        <div class="inrow finish">
          <a href="/install/install.php" class="continue">Try again</a>
        </div>';
    }
    elseif ($_GET['error'] == "db_cfg_exists")
    {
      $page = '<h2 class="step_title">Installer error<small>Database config already exists. Remove him before proceed.</small></h2>
        <a href="/install/install.php" class="continue">Back to step 1</a>';
    }
    elseif ($_GET['error'] == "db_query_error")
    {
      $page = '<h2 class="step_title">MySQL error<small>' . $_SESSION['dberr'] . '</small></h2>

        <div class="inrow finish">
          <a href="/install/install.php?step=2" class="continue">Back to step 2</a>
        </div>';
    }
    elseif ($_GET['error'] == "not_installed")
    {
      $page = '<h2 class="step_title">Installer error<small>To continue, the engine should be set. Perhaps there was an error during the registration server, or you missed this step (#1)</small></h2>

        <div class="inrow finish">
          <a href="/install/install.php" class="continue">Back to step 1</a>
        </div>
        ';
    }

    break;
  }

  echo <<<html
<html>
<head>
  <meta charset="utf-8">
  <title>Less CMS&trade; Installator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="assets/favicon.png" rel="shortcut icon" type="image/x-icon" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"
  <link href="assets/bisquit.min.css" rel="stylesheet" type="text/css">
  <link href="assets/style.min.css" rel="stylesheet" type="text/css">
</head>

<body>
  <div class="tiles main">
    <div class="steps_list">
      <ul>
        <li class="logo"><img src="assets/logo.png"></li>
        <li$welcome_c><i class="mi">$welcome_i</i>Welcome</li>
        <li$database_c><i class="mi">$database_i</i>Database</li>
        <li$admin_acc_c><i class="mi">$admin_acc_i</i>Admin account</li>
        <li$base_config_c><i class="mi">$base_config_i</i>Base config</li>
        <li$clean_c><i class="mi">$clean_i</i>Clean</li>
      </ul>
    </div>
    <div class="page_cnt">
      $page
    </div>

  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="assets/jquery.maskedinput.min.js" type="text/javascript"></script>
  <script src="assets/main.min.js" type="text/javascript"></script>
</body>
</html>

html;
}
