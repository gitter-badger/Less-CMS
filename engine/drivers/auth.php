<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

include DRIVERS . 'auth/vk.auth.php';
include DRIVERS . 'auth/facebook.auth.php';
include DRIVERS . 'auth/registration.php';
/*
 * Auth core class
 */
class Auth
{
  var $return;

  public function __construct($mode)
  {
    $this->return = new stdClass();

    switch($mode)
    {
      case 'signin':
        if($_SESSION["logged"]) return false;
        if(!$_SESSION["logged"] && empty($_GET['case']))
        {
          $act = self::signIn();
          if($act->error)
          {
            $GLOBALS['core']->mess($GLOBALS['lang']->error, $act->reason, "/");
          }
          return $act;
        }
        else
        {
          return self::socialSignIn();
        }
      break;

      case 'signout':
        return self::signOut();
  		break;

  		case 'signup':
        return self::signUp();
  		break;
    }
  }

  private function signIn()
  {
    if($GLOBALS['config']->m_login)
    {
      if(empty($_POST['login']) or empty($_POST['password']))
      {
        $this->return->error = true;
        $this->return->reason = 'Username or password can not be empty!';
        return $this->return;
      }

      if(!preg_match("/^[a-zA-Z0-9.-_]+$/",$_POST['login']))
      {
        $this->return->error = true;
        $this->return->reason = 'Username may only consist of letters of the alphabet, numbers and symbols -_.';
        return $this->return;
      }
      $GLOBALS["db"]->select("members", "login = '".$GLOBALS["db"]->real_escape_string($_POST['login'])."' AND password = '".engine::encode($_POST['password'])."'");
      if(!$GLOBALS["db"]->numRows())
      {
        $this->return->error = true;
        $this->return->reason = 'Wrong username or password.';
        return $this->return;
      }
    }
    else
    {
      if(empty($_POST['email']) or empty($_POST['password']))
      {
        $this->return->error = true;
        $this->return->reason = 'Email or password can not be empty!';
        return $this->return;
      }

      if(!preg_match("/^[a-zA-Z0-9@.-_]+$/",$_POST['email']))
      {
        $this->return->error = true;
        $this->return->reason = 'Email may only consist of letters of the alphabet, numbers and symbols -_.@';
        return $this->return;
      }
      $GLOBALS["db"]->select("members", "email = '".$GLOBALS["db"]->real_escape_string($_POST['email'])."' AND password = '".engine::encode($_POST['password'])."'");
      if(!$GLOBALS["db"]->numRows())
      {
        $this->return->error = true;
        $this->return->reason = 'Wrong email or password.';
        return $this->return;
      }
    }
    $row = $GLOBALS["db"]->getObject();
    $GLOBALS["db"]->free();
    var_dump($this->reason);

    session_start();
    $_SESSION['logged']    = true;
    $_SESSION['member_id'] = $row->id;
    $_SESSION['social']    = false;
    session_write_close();
    $data['login_hash'] = md5("$row->login::LessCMS-Secure::$row->email::lesscm");
    setcookie("3e4e7c615a6c1b9ad7fd7d7b4d0fa166", $data['login_hash'], "0", "/", $_SERVER['SERVER_NAME']);
    $GLOBALS["db"]->update("members", $data, "id='$row->id'");
    header("Location: /");
  }

  private function signOut()
  {
    if(!$_SESSION["logged"]) header("Location: /");
    $id = $_SESSION["member_id"];
  	session_start();
    unset($_SESSION["logged"]);
    unset($_SESSION["member_id"]);
    unset($_SESSION["social"]);
  	session_write_close();
    $data['login_hash'] = "";
    setcookie("3e4e7c615a6c1b9ad7fd7d7b4d0fa166", "");
    $GLOBALS["db"]->update("members", $data, "id='$id'");

    header("Location: /");
  }

  private function signUp()
  {
    if(!$_SESSION["logged"]) return false;

    if(!empty($_POST['uid']))
		{
  		$lib = new SocialReg();
		}
		else
		{
  		$lib = new DefaultReg();
		}
    return $lib;
  }

  private function socialSignIn()
  {
    switch ($_GET['case'])
  	{
  		case "vk":
  			$lib = new vkAuth();
  		break;

  		case "facebook":
  			$lib = new facebookAuth();
  		break;
  	}
    return $lib;
  }
}
?>
