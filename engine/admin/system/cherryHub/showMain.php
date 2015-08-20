<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

$title = "CherryHub&reg;";
$param['method'] = "cherryHub";
$param['action'] = "cherryHub";
$data = $connect->api($param);

if($data->error)
{
	msg($lang['error'], $lang['sunav']);
}
if(!$data->error)
{
	$tpl->load('cherry_hub.tpl');
	$tpl->set('{title}', $title);
	$i = 0;
	while($i < $data->array_num)
	{
		if(!is_null($data->content{$i}->hubID))
		{
			if(empty($data->content{$i}->langCode))
			{
				$select = "link = '".$data->content{$i}->link."'";
			}
			else
			{
				$select = "lang_code = '".$data->content{$i}->langCode."'";
			}
			$db->select("extensions", $select );
			if($db->numRows() == 1)
			{
				$module = $db->getObject();
				$localVersion = str_replace('.',',', $module->version );
				$hubVersion = str_replace('.',',', $data->content{$i}->version );
				if(!empty($module->version)) $version = "v{$module->version}";
				if($hubVersion > $localVersion)
				{
					$status = 'arrow-up';
					$color = 'orange';
					$button = '<button onClick="updateModule(\''.$data->content{$i}->ext.'\',\''.$module->id.'\');" class="button yell text">'.$lang['update'].'</button>';
				}
				elseif($hubVersion === $localVersion)
				{
					$status = 'check';
					$color = 'green';
					$button = '<a href="/admin.php?system=cherryHub&sub=remove&id='.$module->id.'" class="button red text"> '.$lang['remove'].'</a>';
				}
				$rowID = $module->id;
			}
			else
			{
				$status = "download";
				$color = "#78909C";

				$button = '<button onClick="getModule(\''.$data->content{$i}->ext.'\',\''.$data->content{$i}->hubID.'\');" id="download_butt_'.$data->content{$i}->hubID.'" class="button green text"><span id="download_span_'.$data->content{$i}->hubID.'">'.$lang['install'].'</span></button>';
				$rowID = $data->content{$i}->hubID;
			}
				$hub_version = 'v' . $data->content{$i}->version;
		}
		else
		{
				$status = "download";
				$color = "#BDBDBD";
				$hub_version = '';

				$button = '<button class="button" disabled><span>'.$lang['install'].'</span></button>';
				$rowID = $data->content{$i}->hubID;
		}
			$db->free();

		$int .= '<tr>
				<td><i class="fa fa-'.$data->content{$i}->icon.'"></i> '.$data->content{$i}->title.'</td>
				<td>'.$data->content{$i}->description.'</td>
				<td>'.$data->content{$i}->type.'</td>
				<td style="color:'.$color.'" class="text-center"><i class="fa fa-'.$status.'"></i> ' . $version . '</td>
				<td style="color:green" class="text-center">'.$hub_version.'</td>
				<td class="text-right" id="buttons_'.$rowID.'">'.$button.'</td>
			  </tr>';

		$i++;
	}
	$tpl->set('{cherry_hub_content}', $int);
	$tpl->compile('content');
}
