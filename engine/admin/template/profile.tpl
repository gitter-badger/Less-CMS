<div class="row">
	<div class="col s12 m3">
		<div class="card">
			<div class="card-content mini_profile">
				<img src="{user-avatar}">
				<div>{user-showName}
					<div><i class="mi">{user-group_icon}</i>{user-group}</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col s12 m9">
		<div class="card">
			<div class="card-content">
				<form method="post" id="jform" enctype="multipart/form-data" files="true">
					<div>
						<label>{lang-photo}</label>
						<label class="file_seclect waves-effect waves-light btn light-green lighten-1" data-toggle="tooltip" data-placement="right" title="Your photo. The file size must not exceed 2 MB"><input type="file" name="photo" id="prof_photo">{lang-upload_new_photo}</label>
					</div>
					<div>
						<label>{lang-login}</label>
						<input type="text" name="login" value="{user-login}">
					</div>
					<div>
						<label>{lang-fname}</label>
						<input type="text" name="fname" value="{user-fname}">
					</div>
					<div>
						<label>{lang-lname}</label>
						<input type="text" name="lname" value="{user-lname}">
					</div>
					<div>
						<label>Email</label>
						<input type="text" name="email" value="{user-email}">
					</div>
					<h4 class="change_section"><i class="mi">lock</i> {lang-changepass_area}</h4>
					<div>
						<label>{lang-old_password}</label>
						<input type="password" name="old_psw">
					</div>
					<div>
						<label>{lang-new_password}</label>
						<input type="password" name="new_psw">
					</div>
					<div>
						<label>{lang-new_password2}</label>
						<input type="password" name="rpt_psw">
					</div>
					<div>
						<input type="hidden" name="check">
		        <input type="hidden" name="AJAX" value="true">
	          <button type="button" id="jsave" class="waves-effect waves-light btn light-green lighten-1">{lang-save}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
