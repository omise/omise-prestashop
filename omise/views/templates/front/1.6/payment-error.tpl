{capture name=path}
  {l s='Payment error' mod='omise'}
{/capture}

<h1 class="page-heading">{l s='Payment error' mod='omise'}</h1>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

<div class="alert alert-danger">
  <p>{$error_message}</p>
</div>

<div class="box order-confirmation">
  {if ! empty($order_reference)}
    {l s='Your order reference is ' mod='omise'}<strong>{$order_reference}</strong>.
    <br />
  {/if}
  {l s='An error occurred during the payment process. Please contact our' mod='omise'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='customer support.' mod='omise'}</a>
</div>
