{* First 'Used Setting' is always the one for enabled/disabled *}
{$enabled_var_name = $dets['usedSettings'][0]}

<div class="form-group">
  <label class="control-label col-lg-3"><b>
	  {l s=$dets['title'] mode='omise'} 
	  {if $dets['currencies']|count gt 0}({', '|implode:$dets['currencies']|upper}){/if} 
	</b></label>
</div>

<div class="form-group">
  <label class="control-label col-lg-3">{l s='Enabled?' mode='omise'}</label>
  <div class="col-lg-9">
    <span class="switch prestashop-switch fixed-width-lg">
      <input id="{$enabled_var_name}_enabled" name="{$enabled_var_name}" type="radio" value="1" {if $cfg[$enabled_var_name] == 1}checked="checked"{/if}>
      <label for="{$enabled_var_name}_enabled">{l s='Yes' mode='omise'}</label>
      <input id="{$enabled_var_name}_disabled" name="{$enabled_var_name}" type="radio" value="0" {if $cfg[$enabled_var_name] == 0}checked="checked"{/if}>
      <label for="{$enabled_var_name}_disabled">{l s='No' mode='omise'}</label>
      <a class="slide-button btn"></a>
    </span>
    <p class="help-block">{l s=$dets['switchDescription'] mode='omise'}</p>
  </div>
</div>
