<div class="row">
  <div class="col-xs-12">
    <p class="payment_module">
      <div class="box">
        <div class="row">
          <div class="col-sm-12">
            <h3>{$omise_title}</h3>
          </div>
          <div class="col-sm-8 col-md-5 col-lg-4">
              <form id="omise_checkout_form">
                <input id="omise_card_token" name="omise_card_token" type="hidden">
                <div class="row">
                  <div class="form-group col-sm-12">
                    <label for="omise_card_number">{l s='Card number' mod='omise'}</label>
                    <input class="form-control" id="omise_card_number" type="text" placeholder="{l s='Card number' mod='omise'}">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-12">
                      <label for="omise_card_holder_name">{l s='Name on card' mod='omise'}</label>
                      <input class="form-control" id="omise_card_holder_name" type="text" placeholder="{l s='Name on card' mod='omise'}">
                    </div>
                </div>
                <div class="row">
                  <div class="col-sm-5">
                    <div class="form-group">
                      <label for="omise_card_expiration_month">{l s='Expiration month' mod='omise'}</label>
                      <select class="form-control" id="omise_card_expiration_month">
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-5 pull-right">
                    <div class="form-group">
                      <label for="omise_card_expiration_year">{l s='Expiration year' mod='omise'}</label>
                      <select class="form-control" id="omise_card_expiration_year">
                        {html_options values=$list_of_expiration_year output=$list_of_expiration_year}
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-5">
                    <div class="form-group">
                      <label for="omise_card_security_code">{l s='Security code' mod='omise'}</label>
                      <input class="form-control" id="omise_card_security_code" type="password" placeholder="{l s='Security code' mod='omise'}">
                    </div>
                  </div>
                </div>
            </form>
            <button class="button btn btn-default standard-checkout button-medium" id="omise_checkout_button">
              <span id="omise_checkout_text">{l s='Submit Payment' mod='omise'}</span>
            </button>
          </div>
        </div>
      </div>
    </p>
  </div>
</div>
