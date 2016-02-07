<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}

$settings_modules .= '<label><i class="mi">label</i> '.$lang->membership.'</label>';
$m_login = "";
if($config->m_login) $m_login = "checked";

$settings_modules .= '
<div class="inrow">
  <span title="'.$lang->email_or_login_def.'">'.$lang->email_or_login.'</span>
  <div class="switch">
    <div>
      <input type="checkbox" class="swinp" name="config[m_login]" id="m_login" '.$m_login.'>
      <label for="m_login" class="lbl"> </label>
    </div>
  </div>
</div>';
