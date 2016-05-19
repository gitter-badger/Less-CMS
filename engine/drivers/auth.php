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
        if($_COOKIE['de8f93a28b7d5a9be6b042630fb716fd'] || $_COOKIE['12d38208eda733ca66a6e9089502d8fc'])
        {
          if(!$GLOBALS['logged'])
          {
            unset($_COOKIE['de8f93a28b7d5a9be6b042630fb716fd']);
            unset($_COOKIE['12d38208eda733ca66a6e9089502d8fc']);
          }
          else return false;
        }
        if(!$_COOKIE['de8f93a28b7d5a9be6b042630fb716fd'] || !$_COOKIE['12d38208eda733ca66a6e9089502d8fc'] && empty($_GET['case']))
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
    if(!empty($GLOBALS['config']->m_login))
    {
      if(empty($_POST['login']) or empty($_POST['password']))
      {
        $this->return->error = true;
        $this->return->reason = 'Username or password can not be empty!';
				session_start();
				$_SESSION["last_err"] = $this->return->reason;
		    session_write_close();
        return $this->return;
      }

      if(!preg_match("/^[a-zA-Z0-9.-_]+$/",$_POST['login']))
      {
        $this->return->error = true;
        $this->return->reason = 'Username may only consist of letters of the alphabet, numbers and symbols -_.';
        return $this->return;
      }
      $GLOBALS["db"]->select("members", "login = '".$_POST['login']."' AND password = '".engine::encode($_POST['password'])."'");
      if(!$GLOBALS["db"]->numRows())
      {
        $this->return->error = true;
        $this->return->reason = 'Wrong username or password.';
				session_start();
				$_SESSION["last_err"] = $this->return->reason;
		    session_write_close();
        return $this->return;
      }
    }
    else
    {
      if(empty($_POST['email']) or empty($_POST['password']))
      {
        $this->return->error = true;
        $this->return->reason = 'Email or password can not be empty!';
				session_start();
				$_SESSION["last_err"] = $this->return->reason;
		    session_write_close();
        return $this->return;
      }

      if(!preg_match("/^[a-zA-Z0-9@.-_]+$/",$_POST['email']))
      {
        $this->return->error = true;
        $this->return->reason = 'Email may only consist of letters of the alphabet, numbers and symbols -_.@';
				session_start();
				$_SESSION["last_err"] = $this->return->reason;
		    session_write_close();
        return $this->return;
      }
      $GLOBALS["db"]->select("members", "email = '".$_POST['email']."' AND password = '".engine::encode($_POST['password'])."'");
      if(!$GLOBALS["db"]->numRows())
      {
        $this->return->error = true;
        $this->return->reason = 'Wrong email or password.';
				session_start();
				$_SESSION["last_err"] = $this->return->reason;
		    session_write_close();
        return $this->return;
      }
    }
    $row = $GLOBALS["db"]->getObject();

    session_start();
    $_SESSION['logged']    = true;
    $_SESSION['member_id'] = $row->id;
    $_SESSION['social']    = false;
    session_write_close();
    setcookie("de8f93a28b7d5a9be6b042630fb716fd", $row->id);
    setcookie("12d38208eda733ca66a6e9089502d8fc", md5($row->login.$GLOBALS['secure_key'].$row->login.$GLOBALS['secure_key'].$row->email));
    if(basename($_SERVER['SCRIPT_FILENAME']) == "admin.php")
    {
      header("Location: /admin.php");
    }
    else
    {
      header("Location: /");
    }
  }

  public function getMemData()
  {
    $rsp = new stdClass();
    unset($_SESSION['tmp_login']);
    if($GLOBALS['config']->m_login)
    {
      if(empty($_POST['login']))
      {
        $this->return->error = true;
        $this->return->reason = 'Username can not be empty!';
				session_start();
				$_SESSION["last_err"] = $this->return->reason;
		    session_write_close();
        return $this->return;
      }
      if(!preg_match("/^[a-zA-Z0-9.-_]+$/",$_POST['login']))
      {
        $this->return->error = true;
        $this->return->reason = 'Username may only consist of letters of the alphabet, numbers and symbols -_.';
				session_start();
				$_SESSION["last_err"] = $this->return->reason;
		    session_write_close();
        return $this->return;
      }
      $GLOBALS["db"]->select("members", "login = '".$_POST['login']."'");
      $arr = $GLOBALS["db"]->getObject();
      if(!$GLOBALS["db"]->numRows())
      {
        $this->return->error = true;
        $this->return->reason = 'Wrong username';
				session_start();
				$_SESSION["last_err"] = $this->return->reason;
		    session_write_close();
        return $this->return;
      }
      $_SESSION['tmp_login'] = $arr->email;
    }
    else
    {
      if(empty($_POST['email']))
      {
        $this->return->error = true;
        $this->return->reason = 'Email can not be empty!';
				session_start();
				$_SESSION["last_err"] = $this->return->reason;
		    session_write_close();
        return $this->return;
      }
      if(!preg_match("/^[a-zA-Z0-9@.-_]+$/",$_POST['email']))
      {
        $this->return->error = true;
        $this->return->reason = 'Email may only consist of letters of the alphabet, numbers and symbols -_.@';
				session_start();
				$_SESSION["last_err"] = $this->return->reason;
		    session_write_close();
        return $this->return;
      }
      $GLOBALS["db"]->select("members", "email = '".$_POST['email']."'");
      $arr = $GLOBALS["db"]->getObject();
      if(!$GLOBALS["db"]->numRows())
      {
        $this->return->error = true;
        $this->return->reason = 'Wrong email';
				session_start();
				$_SESSION["last_err"] = $this->return->reason;
		    session_write_close();
        return $this->return;
      }
      $_SESSION['tmp_login'] = $arr->email;
    }
    $rsp->success = true;
    if(empty($arr->fname) && empty($arr->lname))
    {
      $rsp->name = $arr->login;
    }
    elseif (!empty($arr->fname) && empty($arr->lname))
    {
      $rsp->name = $arr->fname . " " . $arr->login;
    }
    else
    {
      $rsp->name = $arr->fname . " " . $arr->lname;
    }
    $rsp->photo = engine::imgExist("uploads/photos/".$arr->avatar);
    $rsp->email = $_SESSION['tmp_login'];
    return $rsp;
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
    setcookie("de8f93a28b7d5a9be6b042630fb716fd");
    setcookie("12d38208eda733ca66a6e9089502d8fc");
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
