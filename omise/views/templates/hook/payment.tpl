<div class="row">
  <div class="col-xs-12">
    <p class="payment_module">
      <div class="box">
        <div class="row">
          <div class="col-sm-12">
            <h3>{$omise_title}</h3>
          </div>
          <div class="col-sm-8 col-md-5 col-lg-4">
            <form id="omise_checkout_form" method="post" action="{$action|escape:'html'}">
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
            <button class="button btn btn-default standard-checkout button-medium" id="omise_checkout_button" onclick="omiseCheckout();">
              <span id="omise_checkout_text">{l s='Submit Payment' mod='omise'}</span>
            </button>
          </div>
        </div>
      </div>
    </p>
  </div>
</div>

<script src="https://cdn.omise.co/omise.js.gz"></script>

<script>
  const omiseCheckout = function omiseCheckout() {
    if (typeof Omise === 'undefined') {
      alert('{l s='Unable to process the payment, loading the external card processing library is failed. Please contact the merchant.' mod='omise'}');
      return false;
    }

    omiseLockCheckoutForm(omiseCheckoutForm);

    const card = {
      name: omiseCheckoutForm.name.value,
      number: omiseCheckoutForm.number.value,
      expiration_month: omiseCheckoutForm.expiration_month.value,
      expiration_year: omiseCheckoutForm.expiration_year.value,
      security_code: omiseCheckoutForm.security_code.value,
    };

    Omise.setPublicKey('{$omise_public_key}');
    Omise.createToken('card', card, omiseCreateTokenCallback);
  }

  const omiseCreateTokenCallback = function omiseCreateTokenCallback(statusCode, response) {
    if (statusCode === 200) {
      document.getElementById('omise_card_token').value = response.id;
      document.getElementById('omise_checkout_form').submit();
    } else {
      alert(response.message);
      omiseUnlockCheckoutForm(omiseCheckoutForm);
    }
  };

  const omiseCheckoutForm = {
    name: document.getElementById('omise_card_holder_name'),
    number: document.getElementById('omise_card_number'),
    expiration_month: document.getElementById('omise_card_expiration_month'),
    expiration_year: document.getElementById('omise_card_expiration_year'),
    security_code: document.getElementById('omise_card_security_code'),
    checkout_button: document.getElementById('omise_checkout_button'),
    checkout_text: document.getElementById('omise_checkout_text'),
  };

  const omiseLockCheckoutForm = function omiseLockCheckoutForm(form) {
    form.name.disabled = true;
    form.number.disabled = true;
    form.expiration_month.disabled = true;
    form.expiration_year.disabled = true;
    form.security_code.disabled = true;
    form.checkout_button.disabled = true;
    form.checkout_text.innerHTML = '{l s='Processing' mod='omise'}';
  };

  const omiseUnlockCheckoutForm = function omiseUnlockCheckoutForm(form) {
    form.name.disabled = false;
    form.number.disabled = false;
    form.expiration_month.disabled = false;
    form.expiration_year.disabled = false;
    form.security_code.disabled = false;
    form.checkout_button.disabled = false;
    form.checkout_text.innerHTML = '{l s='Submit Payment' mod='omise'}';
  };
</script>
