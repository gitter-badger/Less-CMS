<div class="row">
	<div class="col s12 m7">
		<div class="card">
			<div class="card-content">
				<table>
					<thead>
						<tr>
							<th>{lang-title}</th>
							<th class="text-center">{lang-type}</th>
							<th class="text-center">{lang-version}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{md_list}
					</tbody>
				</table>
				<div class="col s12 m12">{paginator}</div>
			</div>
		</div>
	</div>
	<div class="col s12 m5 chlog">
		<div class="card">
			<div class="card-head light-green lighten-1">
				<div class="white-text">{lang-inst_newmd}</div>
				<a class="btn-floating btn-large waves-effect waves-light light-green right" onclick="$('#md_input').click();"><i class="mi">add</i></a>
				<form id="jinstall" style="display:none">
					<input type="file" name="zip_md" id="md_input" onchange="installExt();">
				</form>
			</div>
			<div class="changelog">
				<div class="changelist">
					{lang-install_mdmess}
				</div>
			</div>
		</div>
	</div>
</div>
