<div class="col-md-8">
  <div class="col-md-12 tiles npad">
    <ul class="cp_tabs">
      <li class="active"><a href="#application" data-toggle="tab"><i class="mi">build</i>{lang-st_application}</a></li>
      <li><a href="#security" data-toggle="tab"><i class="mi">security</i>{lang-st_security}</a></li>
      <li><a href="#communication" data-toggle="tab"><i class="mi">swap_horiz</i>{lang-st_communication}</a></li>
      <li><a href="#social" data-toggle="tab"><i class="mi">vpn_lock</i>{lang-st_social}</a></li>
      <li><a href="#modules" data-toggle="tab"><i class="mi">power</i>{lang-st_modules}</a></li>
    </ul>
    <form method="post">
      <div class="tab-content">
        <div class="tab-pane active" id="application">
          <label><i class="mi">label</i> {lang-st_general}</label>
          <div class="inrow">
            <span>{lang-sitename}</span>
            <input type="text" name="config[title]" value="{cf_title}">
          </div>
          <div class="inrow">
            <span>{lang-description}</span>
            <input type="text" name="config[description]" value="{cf_description}">
          </div>
          <div class="inrow">
            <span>{lang-keywords}</span>
            <input type="text" name="config[keywords]" value="{cf_keywords}">
          </div>
          <div class="inrow">
            <span>{lang-database}</span>
            <select name="config[database]">
            {cf_database_list}
            </select>
          </div>
          <div class="inrow">
            <span>{lang-template}</span>
            <select name="config[template]">
            {cf_templates_list}
            </select>
          </div>
          <div class="inrow">
            <span>{lang-st_enable_debugmode}</span>
            <div class="switch">
              <div>
                <input type="checkbox" class="swinp" name="config[debug]" id="debug" {debug_status}>
                <label for="debug" class="lbl"> </label>
              </div>
            </div>
          </div>
          <div class="inrow">
            <span>{lang-st_enable_offline}</span>
            <div class="switch">
              <div>
                <input type="checkbox" class="swinp" name="config[offline]" id="offline" {offline_status}>
                <label for="offline" class="lbl"> </label>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane" id="security">
          <label><i class="mi">label</i>reCaptcha</i></label>

          <div class="inrow">
            <span>{lang-st_enable_captcha}</span>
            <div class="switch">
              <div>
                <input type="checkbox" class="swinp" name="config[status_captcha]" id="captcha" {recapthca_status}>
                <label for="captcha" class="lbl"> </label>
              </div>
            </div>
          </div>
          <div class="inrow">
            <span>{lang-captcha_pkey}</span>
            <input type="text" name="config[p_key]" value="{captcha_pkey}">
          </div>
          <div class="inrow">
            <span>{lang-captcha_skey}</span>
            <input type="text" name="config[s_key]" value="{captcha_skey}">
          </div>
        </div>

        <div class="tab-pane" id="communication">
          <label><i class="mi">label</i>{lang-smtp_post}</i></label>
          <div class="inrow">
            <span>{lang-smtp_host}</span>
            <input type="text" name="config[smtp_host]" value="{smtp_host}">
          </div>
          <div class="inrow">
            <span>{lang-smtp_port}</span>
            <input type="text" name="config[smtp_port]" value="{smtp_port}">
          </div>
          <div class="inrow">
            <span>{lang-smtp_user}</span>
            <input type="text" name="config[smtp_user]" value="{smtp_user}">
          </div>
          <div class="inrow">
            <span>{lang-smtp_pass}</span>
            <input type="text" name="config[smtp_pass]" value="{smtp_pass}">
          </div>
          <div class="inrow">
            <span>{lang-smtp_from}</span>
            <input type="text" name="config[smtp_from]" value="{smtp_from}" placeholder="({lang-optional})">
          </div>
          <div class="inrow">
            <span>{lang-smtp_mode}</span>
            <select name="config[smtp_html]">
            {smtp_html_opts}
            </select>
          </div>
          <div class="inrow">
            <span>{lang-secure_mode}</span>
            <select name="config[smtp_secure]">
              {smtp_secure_opts}
            </select>
          </div>
        </div>

        <div class="tab-pane" id="social">
          <div class="inrow">
            <span>{lang-enable} Social<b>API</b></span>
            <div class="switch">
              <div>
                <input type="checkbox" class="swinp" name="config[social_api]" id="social_api" {social_plug_status}>
                <label for="social_api" class="lbl"> </label>
              </div>
            </div>
          </div>
          <label title="vk.com"><i class="mi">label</i>VKontakte</i></label>
          <div class="inrow">
            <span>{lang-app_id}</span>
            <input type="text" name="config[vk][app_id]" value="{vk-app_id}">
          </div>
          <div class="inrow">
            <span>{lang-secure_key}</span>
            <input type="text" name="config[vk][secur_key]" value="{vk-secur_key}">
          </div>
          <label><i class="mi">label</i>Facebook</i></label>
          <div class="inrow">
            <span>{lang-app_id}</span>
            <input type="text" name="config[fb][app_id]" value="{fb-app_id}">
          </div>
          <div class="inrow">
            <span>{lang-secure_key}</span>
            <input type="text" name="config[fb][secur_key]" value="{fb-secur_key}">
          </div>

        </div>

        <div class="tab-pane" id="modules">
          {settings_modules}
        </div>
      </div>
      <div class="inrow" style="padding-right:15px; padding-bottom:15px; text-align:right;">
        <button type="submit">{lang-save}</button>
      </div>
    </form>
  </div>
</div>
