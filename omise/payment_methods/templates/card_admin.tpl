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
