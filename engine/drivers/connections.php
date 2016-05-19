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

  public function api($request)
  {
    return self::cGET("http://api.n3sty.com/", $request);
  }

  public function update()
  {
    $mrk = md5(time());
    $archive = @fopen(ROOT . "/uploads/temp/$mrk.zip", "w");
    $init = curl_init();
    curl_setopt_array($init, array(
      CURLOPT_URL => "http://api.n3sty.com/?update",
      CURLOPT_HEADER => 0,
      CURLOPT_FILE => $archive
    ));
    $return = curl_exec($init);
    fclose($archive);
    curl_close($init);
    if($return) return $mrk;
    return false;
  }
}
$connect = new Connections();
