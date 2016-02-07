<div class="col-md-8 npad">
	<div class="tiles">
		<div class="changelog_header">
			<h2>{lang-local_stats}</h2>
			<small>{lang-local_stats_descr}</small>
		</div>
		<table class="table table-hover middle systate_tbl">
		 <tbody>
			 <tr>
 		   <td style="width:150px;">{lang-engine_version}: </td>
 		   <td id="version">{version} {button}</td>
 		  </tr>
 		  <tr>
 		   <td>{lang-os}:</td>
 		   <td title="{php_os_name}">{php_os}</td>
 		  </tr>
 		  <tr>
 		   <td>{lang-version} PHP:</td>
 		   <td>{php_version}</td>
 		  </tr>
 		  <tr>
 		   <td>{lang-version} MySQLi:</td>
 		   <td>{mysql_version}</td>
 		  </tr>
		 </tbody>
		</table>
	</div>
</div>

<div class="col-md-4">
		<div class="tiles">
			<div class="changelog_header">
				<h2>{lang-changelog}</h2>
				<small>{change_log_descr}</small>
			</div>
			<div class="changelog">
				{change_log}
			</div>
		</div>
	</div>
