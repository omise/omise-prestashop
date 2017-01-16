{capture name=path}
    {l s='Order result' mod='omise'}
{/capture}

<h2>{l s='Order result' mod='omise'}</h2>

{if $error_message}
  <p>
    Payment Failed, {$error_message}
  </p>
{else}
  <p>
    Payment Success
  </p>
{/if}
