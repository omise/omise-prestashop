{if isset($confirmation)}{$confirmation}{/if}

<form class="defaultForm form-horizontal" method="post">
  <div class="panel">
    <div class="panel-heading">{l s='Settings' mod='omise'}</div>
    <div class="form-wrapper">
{*       <div class="form-group">
        <label class="control-label col-lg-3">{l s='Enable/Disable' mode='omise'}</label>
        <div class="col-lg-9">
          <span class="switch prestashop-switch fixed-width-lg">
            <input id="module_status_enabled" name="module_status" type="radio" value="1" {if $cfg['module_status'] == 1}checked="checked"{/if}>
            <label for="module_status_enabled">{l s='Yes' mode='omise'}</label>
            <input id="module_status_disabled" name="module_status" type="radio" value="0" {if $cfg['module_status'] == 0}checked="checked"{/if}>
            <label for="module_status_disabled">{l s='No' mode='omise'}</label>
            <a class="slide-button btn"></a>
          </span>
          <p class="help-block">{l s='Enable Omise Payment Module.' mode='omise'}</p>
        </div>
      </div>
 *}      <div class="form-group">
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
      <div class="form-group">
        <label class="control-label col-lg-3"><b>{l s='Advance Settings' mode='omise'}</b></label>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3" for="title">{l s='Title' mode='omise'}</label>
        <div class="col-lg-9">
          <input id="title" name="title" type="text" value="{$cfg['title']}">
          <p class="help-block">{l s='This controls the title which the user sees during checkout.' mode='omise'}</p>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3">{l s='3-D Secure support' mode='omise'}</label>
        <div class="col-lg-9">
          <span class="switch prestashop-switch fixed-width-lg">
            <input id="three_domain_secure_status_enabled" name="three_domain_secure_status" type="radio" value="1" {if $cfg['three_domain_secure_status'] == 1}checked="checked"{/if}>
            <label for="three_domain_secure_status_enabled">{l s='Yes' mode='omise'}</label>
            <input id="three_domain_secure_status_disabled" name="three_domain_secure_status" type="radio" value="0" {if $cfg['three_domain_secure_status'] == 0}checked="checked"{/if}>
            <label for="three_domain_secure_status_disabled">{l s='No' mode='omise'}</label>
            <a class="slide-button btn"></a>
          </span>
          <p class="help-block">{l s='Enable or disable 3-D Secure for the account. (Japan-based accounts are not eligible for the service.)' mode='omise'}</p>
        </div>
      </div>
{*       <div class="form-group">
        <label class="control-label col-lg-3">{l s='Internet Banking' mode='omise'}</label>
        <div class="col-lg-9">
          <span class="switch prestashop-switch fixed-width-lg">
            <input id="internet_banking_status_enabled" name="internet_banking_status" type="radio" value="1" {if $cfg['internet_banking_status'] == 1}checked="checked"{/if}>
            <label for="internet_banking_status_enabled">{l s='Yes' mode='omise'}</label>
            <input id="internet_banking_status_disabled" name="internet_banking_status" type="radio" value="0" {if $cfg['internet_banking_status'] == 0}checked="checked"{/if}>
            <label for="internet_banking_status_disabled">{l s='No' mode='omise'}</label>
            <a class="slide-button btn"></a>
          </span>
          <p class="help-block">{l s='Enables customers of a bank to easily conduct financial transactions through a bank-operated website (only available in Thailand).' mode='omise'}</p>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3">{l s='Alipay' mode='omise'}</label>
        <div class="col-lg-9">
          <span class="switch prestashop-switch fixed-width-lg">
            <input id="alipay_status_enabled" name="alipay_status" type="radio" value="1" {if $cfg['alipay_status'] == 1}checked="checked"{/if}>
            <label for="alipay_status_enabled">{l s='Yes' mode='omise'}</label>
            <input id="alipay_status_disabled" name="alipay_status" type="radio" value="0" {if $cfg['alipay_status'] == 0}checked="checked"{/if}>
            <label for="alipay_status_disabled">{l s='No' mode='omise'}</label>
            <a class="slide-button btn"></a>
          </span>
          <p class="help-block">{l s='Enables payments by Alipay (currently only available in Thailand).' mode='omise'}</p>
        </div>
      </div>
 *}

      {foreach from=OmisePaymentMethods::$list item=method}
        {$class = OmisePaymentMethods::className($method)}
        {include file="{$method_admintemplate_path}_admin.tpl"}
      {/foreach}

    </div>
    <div class="panel-footer">
      <button class="btn btn-default pull-right" name="{$submit_action}" type="submit" value="1">
        <i class="process-icon-save"></i>{l s='Save' mode='omise'}
      </button>
    </div>
  </div>
</form>
