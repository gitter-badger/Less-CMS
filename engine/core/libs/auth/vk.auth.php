<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}
/*
 * VKontakte Sign In class
 */
class vkAuth extends Auth
{
  function __construct()
  {
    $this->db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_NAME);
    $this->db->set_charset("utf8");
    $app_id = $GLOBALS['config']->vk['app_id'];
    $secur_key = $GLOBALS['config']->vk['secur_key'];

    $step1['client_id']     = $app_id;
    $step1['client_secret'] = $secur_key ;
    $step1['code']          = $_GET['param'];
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

    mysql::select("members_social", "social_id ='vk".$user_data->response{0}->uid."'");
    if(mysql::numRows())
    {
      $row = mysql::getObject();
      Engine::addSkey('logged',true);
      Engine::addSkey('member_id',$row->user_id);
      Engine::addSkey('social',true);
      header("Location: /");
    }
    else
    {
      echo raw;
    }
    mysql::free();
  }
}
