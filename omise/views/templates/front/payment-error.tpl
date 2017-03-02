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
    {l s='The error occurred during process payment. Please contact our' mod='omise'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='customer support.' mod='omise'}</a>
</div>

<p class="cart_navigation clearfix">
    <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Previous'}" class="button-exclusive btn btn-default">
        <i class="icon-chevron-left"></i>
        {l s='Back to payment'}
    </a>
</p>
