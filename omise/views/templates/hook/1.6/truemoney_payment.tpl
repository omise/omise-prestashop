<div class="row">
  <div class="col-xs-12">
    <p class="payment_module">
      <div class="box">
        <div class="row">
          <div class="col-sm-12">
            <h3>{l s=$omise_title mod='omise'}</h3>
          </div>
          <div class="col-sm-12">
            <form id="omiseTrueMoneyCheckoutForm" method="post" action="{$action|escape:'html'}">
              <div class="row">
                <div class="form-group col-sm-2">
                  <label for="true_number">{l s='Phone number' mod='omise'}</label>
                  <input class="form-control" id="true_number" type="text" maxlength="10" placeholder="{l s='Phone number' mod='omise'}">
                </div>
              </div>
              <button class="button btn btn-default standard-checkout button-medium" id="omiseInternetBankingCheckoutButton">
                <span>{l s='Submit Payment' mod='omise'}</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </p>
  </div>
</div>
