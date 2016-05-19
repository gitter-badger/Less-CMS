<div class="row">
	<div class="col s12 m6">
			<div class="card">
				<div class="card-content">
					<h4>{lang-local_stats}</h4>
					<div class="sp_row {vst}"><i class="mi">memory</i> <b>{lang-engine_version}:</b> <span id="verison">{version}</span></div>
					<div class="sp_row"><i class="mi">next_week</i> <b>{lang-os}:</b> <span title="{php_os_name}">{php_os}</span></div>
					<div class="sp_row"><i class="mi">settings</i> <b>{lang-version} PHP:</b> <span>{php_version}</span></div>
					<div class="sp_row {curl_err}"><i class="mi">extension</i> <b>cURL:</b> <span>{curl_st}</span></div>
				</div>
			</div>
		</div>
		<div class="col s12 m5 chlog">
			<div class="card">
				<div class="card-head light-green lighten-1">
					<div class="white-text">{version_inform}</div>
					{upd_btn}
				</div>
				<div class="changelog">
					<h5>{lang-changelog}</h5>
					<div class="changelist">
						{change_log}
					</div>
				</div>
			</div>
		</div>
	</div>
