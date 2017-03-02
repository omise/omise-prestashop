<p class="alert alert-success">
  {l s='Your order on %s has been received.' sprintf=$shop_name mod='omise'}
</p>
<div class="box order-confirmation">
  {l s='Your order reference is ' mod='omise'}<strong>{$order_reference}</strong>.
  <br />{l s='For any questions or for further information, please contact our' mod='omise'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='customer support.' mod='omise'}</a>
</div>
