<div class="row">
  <div class="col-xs-12">
    <p class="payment_module">
      <div class="box">
        <div class="row">
          <div class="col-sm-12">
            <h3>{l s='Alipay' mod='omise'}</h3>
          </div>
          <div class="col-sm-12">
            <form id="omiseAlipayCheckoutForm" method="post" action="{$link->getModuleLink('omise', 'alipaypayment', [], true)|escape:'html'}">
              <button class="button btn btn-default standard-checkout button-medium" id="omiseAlipayCheckoutButton">
                <span>{l s='Submit Payment' mod='omise'}</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </p>
  </div>
</div>

