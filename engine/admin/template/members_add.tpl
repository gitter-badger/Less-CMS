<div class="col m8">
	<div class="col m12 tile forms">
		<h5>
			{lang-new_user}
		</h5>
		<form method="post">
			<div>
				<label>{lang-group}</label>
				<select name="group">
					<option disabled>{lang-selectgroup}</option>
					{options}
				</select>
			</div>
			<div>
				<label>{lang-login}</label>
				<input type="text" name="login" required>
			</div>
			<div>
				<label>Email</label>
				<input type="email" name="email" required>
			</div>
			<div>
				<label>{lang-password}</label>
				<input type="text" name="password" required>
			</div>
			<div>
				<label>{lang-password2}</label>
				<input type="email" name="password2" required>
			</div>
			<div style="padding-right:15px; padding-bottom:15px; text-align:right;">
				<input type="hidden" name="validate">
				<button type="submit" class="waves-effect waves-light btn light-green">{lang-save}</button>
			</div>
		</form>
	</div>
</div>
