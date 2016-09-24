{capture name=path}
    {l s='Order result' mod='omise'}
{/capture}

<h2>{l s='Order result' mod='omise'}</h2>

{if $payment_success == 'true'}
  <p>
    Payment Success
  </p>
{else}
  <p>
    Payment Failed, {$error_message}
  </p>
{/if}
