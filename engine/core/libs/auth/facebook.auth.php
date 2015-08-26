<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}
/*
 * Facebook Sign In class
 */
class facebookAuth extends Auth
{
  function __construct()
  {
    $this->db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_NAME);
    $this->db->set_charset("utf8");
    $app_id = $GLOBALS['config']->fb['app_id'];
    $secur_key = $GLOBALS['config']->fb['secur_key'];

    $request['client_id'] = $app_id;
    $request['client_secret'] = $secur_key;
    $request['code'] = $_GET['param'];
    $request['sdsdsds'] = 'http://' . $_SERVER['SERVER_NAME'] . '/return/facebook.php';

    $respost = Connections::cGET('https://graph.facebook.com/oauth/access_token', $request, true);
    parse_str($resp, $token);

    if(empty($token['access_token']))
    {
      $this->return->error = true;
      $this->return->reason = 'Social API error';
      return $this->return;
    }
    $params['access_token'] = $token['access_token'];
    $user_data = Connections::cGET('https://graph.facebook.com/me', $params);
    if (empty($user_data->id))
    {
      $this->return->error = true;
      $this->return->reason = 'Social API error';
      return $this->return;
    }

    mysql::select("members_social", "social_id ='fb".$user_data->id."'");
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
