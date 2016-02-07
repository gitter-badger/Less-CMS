<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}
/*
* Connections class
*/
class Connections
{

  public function cPOST($url, $params, $parse = false)
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);
    curl_close($curl);
    if ($parse)
    {
      return json_decode($result, true);
    }
    if ($result) return json_decode($result);
    return (object)$result['error'] = true;
  }
  public function cGET($url, $params, $parse = false)
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url . '?' . urldecode(http_build_query($params)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);
    curl_close($curl);
    if ($parse)
    {
      return json_decode($result, true);
    }
    if ($result) return json_decode($result);
    return (object)$result['error'] = true;
  }

  /* ### CodeBurger International ### */
  /* ################################ */
  /* # API functions are not public # */
  /* ################################ */
  /* ###### www.codeburger.it ####### */

  function getPackage($request, $pname)
  {
    $archive = @fopen(ROOT . "/uploads/temp/{$pname}.zip", "w");
    $request['method'] = 'cherryHub';
    $request['key'] = $GLOBALS['config']->lkey;
    $init = curl_init();
    curl_setopt_array($init, array(
      CURLOPT_URL => "http://api.codeburger.it/?" . urldecode(http_build_query($request)),
      CURLOPT_HEADER => 0,
      CURLOPT_FILE => $archive
    ));
    $return = curl_exec($init);
    fclose($archive);
    curl_close($init);
    $back = json_decode($return);
    if ($json->error)
    {
      return $json;
    }
    return true;
  }
  public function api($request)
  {
    $request['key'] = $GLOBALS['config']->lkey;
    $request['domain'] = $_SERVER['SERVER_NAME'];
    return self::cGET("http://api.codeburger.it/", $request);
  }
}
$connect = new Connections();
