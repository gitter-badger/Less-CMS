<div class="col-md-8 npad">
	<div class="tiles">
		<div class="profile_head">
			<div class="p_photo">
				<img src="{photos}{user-avatar}">
			</div>
			<div class="ps_info">
				<div class="p_name">{user-fname} {user-lname} <small>@{user-login}</small></div>
				<div class="p_description">{user-aboutme}</div>
			</div>
		</div>
	</div>
	<div class="tiles">
		<form method="post" enctype="multipart/form-data">
			<div class="inrow">
				<label><i class="mi">label</i> Фото</label>
				<label class="file_seclect" data-toggle="tooltip" data-placement="right" title="Your photo. The file size must not exceed 2 MB"><input type="file" name="photo">{lang-upload_new_photo}</label>
			</div>
			<div class="inrow">
				<label><i class="mi">label</i> {lang-login}</label>
				<input type="text" name="login" value="{user-login}">
			</div>
			<div class="inrow">
				<label><i class="mi">label</i> {lang-fname}</label>
				<input type="text" name="fname" value="{user-fname}">
			</div>
			<div class="inrow">
				<label><i class="mi">label</i> {lang-lname}</label>
				<input type="text" name="lname" value="{user-lname}">
			</div>
			<div class="inrow">
				<label><i class="mi">label</i> Email</label>
				<input type="text" name="email" value="{user-email}">
			</div>
			<h4><i class="mi">lock</i> {lang-changepass_area}</h4>
			<div class="inrow">
				<label><i class="mi">label</i> {lang-old_password}</label>
				<input type="password" name="old_psw">
			</div>
			<div class="inrow">
				<label><i class="mi">label</i> {lang-new_password}</label>
				<input type="password" name="new_psw">
			</div>
			<div class="inrow">
				<label><i class="mi">label</i> {lang-new_password2}</label>
				<input type="password" name="rpt_psw">
			</div>
			<div class="inrow">
				<input type="hidden" name="check">
				<button type="submit">{lang-save}</button>
			</div>
		</form>
	</div>
</div>
