<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

function parse_url_if_valid($url)
{
  $arUrl = parse_url($url);
  if (!array_key_exists("scheme", $arUrl) || !in_array($arUrl["scheme"], array(
    "http",
    "https"
  ))) $arUrl["scheme"] = "http";
  if (array_key_exists("host", $arUrl) && !empty($arUrl["host"])) $ret = sprintf("%s://%s%s", $arUrl["scheme"], $arUrl["host"], $arUrl["path"]);
  else if (preg_match("/^\w+\.[\w\.]+(\/.*)?$/", $arUrl["path"])) $ret = sprintf("%s://%s", $arUrl["scheme"], $arUrl["path"]);
  if ($ret && empty($ret["query"])) $ret.= sprintf("?%s", $arUrl["query"]);
  if ($ret) return true;
  return false;
}

if(!$_POST)
{
  $title = $lang->order_title;
  $tpl->load("order.tpl");
  $tpl->set('{lang}', $language->getData()->code);
  $tpl->compile("content");
}
else
{
  if(isset($_POST['check']))
  {
    $json['price'] = 0;
    $json['discount'] = 0;
    $json['price_tot'] = 0;
    $json['domain'] = false;
    $discount = $_POST['discount'];

    switch($_POST['order'])
    {
      case "1":
        $json['price'] = 70;
      break;

      case "2":
        $json['price'] = 500;
      break;

      default:
        $json['price'] = 0;
        $discount = false;
      break;
    }
    $json['price_tot'] = $json['price'];
    if($discount)
    {
      $db->select("coupons","coupon='{$db->real_escape_string($_POST['discount'])}'");
      $row = $db->getObject();
      if($db->numRows())
      {
        $json['discount']  = $json['price'] * $row->disc / 100;
        $json['price_tot'] = $json['price'] - $json['discount'];
      }
      $db->free();
    }
    if(isset($_POST['domain']))
    {
      $url = $_POST['domain'];
      $url = str_ireplace(array("http://","https://"),"",$_POST['domain']);
      if(parse_url_if_valid($url))
        $json['domain'] = true;
    }

    echo json_encode($json);
  }
  if(isset($_POST['pay_go']))
  {
    $domdata = dns_get_record("www.codeburger.it",DNS_A);
    if(!empty($domdata)) $core->mess($lang->error);
  }
}
