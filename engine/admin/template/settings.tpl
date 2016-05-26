<div class="row">
  <div class="col s12 m12">
    <div class="card">
      <ul class="tabs">
        <li class="tab col s3"><a href="#application" class="active"><i class="mi">build</i>{lang-st_application}</a></li>
        <li class="tab col s3"><a href="#security"><i class="mi">security</i>{lang-st_security}</a></li>
        <li class="tab col s3"><a href="#communication"><i class="mi">swap_horiz</i>{lang-st_communication}</a></li>
        <li class="tab col s3"><a href="#social"><i class="mi">vpn_lock</i>{lang-st_social}</a></li>
        <li class="tab col s3 {md_tab}"><a href="#modules"><i class="mi">power</i>{lang-st_modules}</a></li>
      </ul>
      <form method="post" id="jform">
        <div class="card-content">
          <div id="application">
            <label class="section_devider"><i class="mi">label</i> {lang-st_general}</label>
            <div>
              <label>{lang-sitename}</label>
              <input type="text" name="config[title]" value="{cf_title}">
            </div>
            <div>
              <label>{lang-description}</label>
              <input type="text" name="config[description]" value="{cf_description}">
            </div>
            <div>
              <label>{lang-keywords}</label>
              <input type="text" name="config[keywords]" value="{cf_keywords}">
            </div>
            <div>
              <label>{lang-database}</label>
              <select name="config[database]">
                <option value="" disabled selected>{lang-choose_db}</option>
                {cf_database_list}
              </select>
            </div>
            <div>
              <label>{lang-template}</label>
              <select name="config[template]">
                <option value="" disabled selected>{lang-choose_tpl}</option>
                {cf_templates_list}
              </select>
            </div>
            <div>
              <label>{lang-timezone}</label>
              <select name="config[timezone]">
                <option value="" disabled selected>{lang-timezone}</option>
                {cf_timezones}
              </select>
            </div>
            <p class="swinp">
              <input type="checkbox" class="filled-in filled-in" name="config[compcfg]" id="compcfg" {compcfg_status}>
              <label for="compcfg" class="tooltipped" data-position="right" data-delay="50" data-tooltip="{lang-compcfgmsg}">{lang-st_enable_compcfg}</label>
            </p>
            <p class="swinp">
              <input type="checkbox" class="filled-in" name="config[debug]" id="debug" {debug_status}>
              <label for="debug" class="tooltipped" data-position="right" data-delay="50" data-tooltip="{lang-debugmsg}">{lang-st_enable_debugmode}</label>
            </p>
            <p class="swinp">
              <input type="checkbox" class="filled-in" name="config[offline]" id="offline" {offline_status}>
              <label for="offline" class="tooltipped" data-position="right" data-delay="50" data-tooltip="{lang-offlinemsg}">{lang-st_enable_offline}</label>
            </p>
          </div>
          <div id="security">
            <label class="section_devider"><i class="mi">label</i>reCaptcha</i></label>
            <p class="swinp">
              <input type="checkbox" class="filled-in" name="config[status_captcha]" id="captcha" {recapthca_status}>
              <label for="captcha">{lang-st_enable_captcha}</label>
            </p>
            <div>
              <label>{lang-captcha_pkey}</label>
              <input type="text" name="config[p_key]" value="{captcha_pkey}">
            </div>
            <div>
              <label>{lang-captcha_skey}</label>
              <input type="text" name="config[s_key]" value="{captcha_skey}">
            </div>
          </div>
          <div id="communication">
            <label class="section_devider"><i class="mi">label</i>{lang-smtp_post}</i></label>
            <div>
              <label>{lang-smtp_host}</label>
              <input type="text" name="config[smtp_host]" value="{smtp_host}">
            </div>
            <div>
              <label>{lang-smtp_port}</label>
              <input type="text" name="config[smtp_port]" value="{smtp_port}">
            </div>
            <div>
              <label>{lang-smtp_user}</label>
              <input type="text" name="config[smtp_user]" value="{smtp_user}">
            </div>
            <div>
              <label>{lang-smtp_pass}</label>
              <input type="text" name="config[smtp_pass]" value="{smtp_pass}">
            </div>
            <div>
              <label>{lang-smtp_from}</label>
              <input type="text" name="config[smtp_from]" value="{smtp_from}" placeholder="({lang-optional})">
            </div>
            <div>
              <label>{lang-smtp_mode}</label>
              <select name="config[smtp_html]">
              {smtp_html_opts}
              </select>
            </div>
            <div>
              <label>{lang-secure_mode}</label>
              <select name="config[smtp_secure]">
                {smtp_secure_opts}
              </select>
            </div>
          </div>
          <div id="social">
          <p class="swinp">
              <input type="checkbox" class="filled-in" name="config[social_api]" id="social_api" {social_plug_status}>
              <label for="social_api">Social<b>API</label>
            </p>
            <label title="vk.com"><i class="mi">label</i>VKontakte</i></label>
            <div>
              <label>{lang-app_id}</label>
              <input type="text" name="config[vk][app_id]" value="{vk-app_id}">
            </div>
            <div>
              <label>{lang-secure_key}</label>
              <input type="text" name="config[vk][secur_key]" value="{vk-secur_key}">
            </div>
            <label><i class="mi">label</i>Facebook</i></label>
            <div>
              <label>{lang-app_id}</label>
              <input type="text" name="config[fb][app_id]" value="{fb-app_id}">
            </div>
            <div>
              <label>{lang-secure_key}</label>
              <input type="text" name="config[fb][secur_key]" value="{fb-secur_key}">
            </div>
          </div>
          <div id="modules">{settings_modules}</div>

        </div>

        <div class="card-action">
          <input type="hidden" name="AJAX" value="true">
          <button type="button" id="jsave" class="waves-effect waves-light btn light-green lighten-1">{lang-save}</button>
        </div>
      </form>
    </div>
  </div>
</div>
