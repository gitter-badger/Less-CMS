<div class="col m8">
  <div class="col m12 tile forms">
		<h5>
			{lang-permissions}
		</h5>
    <form method="post" id="jform">
      <ul class="clps" data-collapsible="accordion">
        {content}
      </ul>
			<div class="row savencomm">
        <div class="col m6 comment">
          {lang-perms_usage}
        </div>
        <div class="col m6 save">
  				<input type="hidden" name="validate">
          <input type="hidden" name="AJAX" value="true">
          <button type="button" id="jsave" class="waves-effect waves-light btn light-green lighten-1">{lang-save}</button>
        </div>
			</div>
    </form>
  </div>
</div>
<a href="?do=membership&case=permissions&new_perm=true" class="btn-floating btn-large waves-effect waves-light light-green add_button" title="{lang-add_group}"><i class="mi">add</i></a>
