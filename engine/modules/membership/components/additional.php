<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}
/*
 * Developers members class
 */
class memberShip
{
  function shortNumeric($num)
  {
    if($num >= 1000000)
    {
      return substr($num, 0, -6) . "M";
    }
    if($num >= 1000 && $num < 1000000)
    {
      return substr($num, 0, -3) . "K";
    }
    if($num < 1000)
    {
        return $num;
    }
  }
  function getPercent($total, $value)
  {
    $formula = $value*100/$total;
    if(!$formula) return 0;
    return $value*100/$total;
  }
  function getIcon($user)
  {
    if($user->group_id == -2)
    {
      return '<i id="rang" class="mi mi-dred" data-toggle="tooltip" data-placement="right" title="Bannato">report</i>';
    }
    if($user->group_id == -1)
    {
      return '<i id="rang" class="mi mi-red" data-toggle="tooltip" data-placement="right" title="Violatore">mood_bad</i>';
    }
    if($user->group_id == 1)
    {
      return '<i id="rang" class="mi mi-green" data-toggle="tooltip" data-placement="right" title="Proprietario">stars</i>';
    }
    if($user->group_id == 2)
    {
      return '<i id="rang" class="mi mi-staff" data-toggle="tooltip" data-placement="right" title="Amministratore">star</i>';
    }
    if($user->group_id == 3)
    {
      return '<i id="rang" class="mi mi-green" data-toggle="tooltip" data-placement="right" title="Stagista">star_half</i>';
    }
    if($user->group_id == 4)
    {
      return '<i id="rang" class="mi mi-gold" data-toggle="tooltip" data-placement="right" title="Gold Moderatore">security</i>';
    }
    if($user->group_id == 5)
    {
      return '<i id="rang" class="mi mi-staff" data-toggle="tooltip" data-placement="right" title="Moderatore">security</i>';
    }
    if($user->contribution >= 150000 && $user->group_id == 0)
    {
      return '<i id="rang" class="mi" data-toggle="tooltip" data-placement="right" title="Esperto">school</i>';
    }
    if($user->contribution >= 500000 && $user->group_id == 0)
    {
      return '<i id="rang" class="mi mi-green" data-toggle="tooltip" data-placement="right" title="Esperto">school</i>';
    }
  }
}
$mship = new memberShip();


$mdata->edit_btn = '<br><br><a href="/membership/settings" class="button dorange bg">'.$lang->edit_profile.'</a>';
