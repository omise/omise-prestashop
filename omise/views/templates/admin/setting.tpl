{if isset($confirmation)}{$confirmation}{/if}

<form class="defaultForm form-horizontal" method="post">
  <div class="panel">
    <div class="panel-heading">{l s='Settings' mod='omise'}</div>
    <div class="form-wrapper">
      <div class="form-group">
        <label class="control-label col-lg-3">{l s='Sandbox' mode='omise'}</label>
        <div class="col-lg-9">
          <span class="switch prestashop-switch fixed-width-lg">
            <input id="sandbox_status_enabled" name="sandbox_status" type="radio" value="1" {if $cfg['sandbox_status'] == 1}checked="checked"{/if}>
            <label for="sandbox_status_enabled">{l s='Yes' mode='omise'}</label>
            <input id="sandbox_status_disabled" name="sandbox_status" type="radio" value="0" {if $cfg['sandbox_status'] == 0}checked="checked"{/if}>
            <label for="sandbox_status_disabled">{l s='No' mode='omise'}</label>
            <a class="slide-button btn"></a>
          </span>
          <p class="help-block">{l s='Enabling sandbox means that all your transactions will be in TEST mode.' mode='omise'}</p>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3" for="test_public_key">{l s='Public key for test' mode='omise'}</label>
        <div class="col-lg-9">
          <input id="test_public_key" name="test_public_key" type="text" value="{$cfg['test_public_key']}">
          <p class="help-block">{l s='The "Test" mode public key can be found in Omise Dashboard.' mode='omise'}</p>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3" for="test_secret_key">{l s='Secret key for test' mode='omise'}</label>
        <div class="col-lg-9">
          <input id="test_secret_key" name="test_secret_key" type="password" value="{$cfg['test_secret_key']}">
          <p class="help-block">{l s='The "Test" mode secret key can be found in Omise Dashboard.' mode='omise'}</p>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3" for="live_public_key">{l s='Public key for live' mode='omise'}</label>
        <div class="col-lg-9">
          <input type="text" id="live_public_key" name="live_public_key" value="{$cfg['live_public_key']}">
          <p class="help-block">{l s='The "Live" mode public key can be found in Omise Dashboard.' mode='omise'}</p>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3" for="live_secret_key">{l s='Secret key for live' mode='omise'}</label>
        <div class="col-lg-9">
          <input id="live_secret_key" name="live_secret_key" type="password" value="{$cfg['live_secret_key']}">
          <p class="help-block">{l s='The "Live" mode secret key can be found in Omise Dashboard.' mode='omise'}</p>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3" for="webhooks_endpoint">{l s='Webhooks endpoint' mode='omise'}</label>
        <div class="col-lg-9">
          <p class="form-control-static">
            <code>{$webhooks_endpoint}</code>
          </p>
        </div>
      </div>

      {foreach from=OmisePaymentMethods::$list item=method}
        {$dets = $methodObjects[$method]->getAdminDetails()}
        {include file="{$method_admintemplate_path}_all.tpl"}
        {$adminTpl = $dets['adminTemplate']}
        {if $adminTpl ne ''}
          {include file="$method_admintemplate_path$adminTpl.tpl"}
        {/if}
      {/foreach}

    </div>
    <div class="panel-footer">
      <button class="btn btn-default pull-right" name="{$submit_action}" type="submit" value="1">
        <i class="process-icon-save"></i>{l s='Save' mode='omise'}
      </button>
    </div>
  </div>
</form>
