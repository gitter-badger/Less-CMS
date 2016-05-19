<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}

$settings_modules .= '<label class="section_devider"><i class="mi">label</i> '.$lang->membership.'</label>';
$m_login = "";
if($config->m_login) $m_login = "checked";

$settings_modules .= '
<p>
  <input type="checkbox" id="m_login" name="config[m_login]" />
  <label for="m_login" title="'.$lang->email_or_login_def.'">'.$lang->email_or_login.'</label>
</p>';
