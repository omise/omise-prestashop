{extends file='page.tpl'}

{block name='page_content_container' prepend}
  <section id="content" class="page-content page-order-confirmation card">
    <div class="card-block">
      <div class="row">
        <div class="col-md-12">

          <aside id="notifications">
            <div class="container">
              <article class="alert alert-danger" role="alert" data-alert="danger">
                <ul>
                  <li>{$error_message}</li>
                </ul>
              </article>
            </div>
          </aside>

          <div class="box order-confirmation">
            {if ! empty($order_reference)}
              <p>{l s='Your order reference is ' mod='omise'}<strong>{$order_reference}</strong>.</p>
            {/if}
            <p>{l s='An error occurred during the payment process. Please contact our' mod='omise'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='customer support.' mod='omise'}</a></p>
          </div>

        </div>
      </div>
    </div>
  </section>
{/block}

{block name='page_content_container'}
{/block}
