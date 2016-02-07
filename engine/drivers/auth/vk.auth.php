<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}
/*
 * VKontakte Sign In class
 */
class vkAuth extends Auth
{
  function __construct()
  {
    $app_id = $GLOBALS['config']->vk['app_id'];
    $secur_key = $GLOBALS['config']->vk['secur_key'];

    $step1['client_id']     = $app_id;
    $step1['client_secret'] = $secur_key ;
    $step1['code']          = $_GET['su'];
    $step1['redirect_uri']  = 'http://' . $_SERVER['SERVER_NAME'] . '/return/vk.php';

    $token = Connections::cGET('https://oauth.vk.com/access_token', $step1);
    if(empty($token->access_token))
    {
      $this->return->error = true;
      $this->return->reason = 'Social API error';
      return $this->return;
    }

    $step2['uids']         = $token->user_id;
    $step2['fields']       = 'uid,first_name,last_name,screen_name,sex,bdate,photo_max,city,country' ;
    $step2['access_token'] = $token->access_token;

    $user_data = Connections::cGET('https://api.vk.com/method/users.get', $step2);

    $GLOBALS['db']->select("members_social", "social_id ='vk".$user_data->response{0}->uid."'");
    if($GLOBALS['db']->numRows())
    {
      $row = $GLOBALS['db']->getObject();
      $_SESSION['logged']    = true;
      $_SESSION['member_id'] = $row->user_id;
      $_SESSION['social']    = true;
      header("Location: /");
    }
    else
    {
      echo "raw";
    }
    $GLOBALS['db']->free();
  }
}
