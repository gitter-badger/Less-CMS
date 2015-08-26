<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

include CORE . 'libs/auth/vk.auth.php';
include CORE . 'libs/auth/facebook.auth.php';
include CORE . 'libs/auth/registration.php';
/*
 * Auth core class
 */
class Auth
{

  var $logged;
  var $return;

  public function __construct($mode)
  {
    $this->logged = $GLOBALS['logged'];
    $this->return = new stdClass();

    $this->db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_NAME);
    $this->db->set_charset("utf8");

    switch($mode)
    {
      case 'signin':
        if($this->logged) return false;
        if(!$this->logged && empty($_GET['sub']))
        {
          return self::signIn();
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
    if(empty($_POST['email']) or empty($_POST['password']))
    {
      $this->return->error = true;
      $this->return->reason = 'Email or password can not be empty!';
      return $this->return;
    }

    if(!preg_match("/^[a-zA-Z0-9@.-_]+$/",$_POST['email']))
    {
      $this->return->error = true;
      $this->return->reason = 'Email may only consist of letters of the alphabet, numbers and symbol @';
      return $this->return;
    }
    mysql::select("members", "email = '".mysql::real_escape_string($_POST['email'])."' AND password = '".engine::encode($_POST['password'])."'");
    if(!mysql::numRows())
    {
      $this->return->error = true;
      $this->return->reason = 'Wrong email or password.';
      return $this->return;
    }
    $row = mysql::getObject();
    mysql::free();

    Engine::addSkey('logged',true);
    Engine::addSkey('member_id',$row->id);
    Engine::addSkey('social',false);
    header("Location: /");
  }

  private function signOut()
  {
    if(!$this->logged) header("Location: /");
    Engine::rmSkey('logged');
    Engine::rmSkey('member_id');
    Engine::rmSkey('social');
    header("Location: /");
  }

  private function signUp()
  {
    if(!$this->logged) return false;

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
    switch ($_GET['sub'])
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
