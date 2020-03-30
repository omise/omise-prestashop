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
                    <label for="omise_card_name">{l s='Name on card' mod='omise'}</label>
                    <input class="form-control" id="omise_card_name" type="text" placeholder="{l s='Name on card' mod='omise'}">
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
            <button class="button btn btn-default standard-checkout button-medium" id="omise_card_checkout_button" onclick="omiseCheckout();">
              <span id="omise_card_checkout_text">{l s='Submit Payment' mod='omise'}</span>
            </button>
          </div>
        </div>
      </div>
    </p>
  </div>
</div>

<script src="https://cdn.omise.co/omise.js.gz"></script>

<script>
  
  // IMPORTANT - this window.xxx stuff looks weird and unnecessary, but it's necessary to make
  // the JS work correctly when the checkout is in one-page mode. It would appear that
  // dynamically created script blocks do not run in the global context

  window.omiseCardFields = [
    'name',
    'number',
    'expiration_month',
    'expiration_year',
    'security_code'
  ];

  window.omiseCheckout = function omiseCheckout() {
    if (typeof Omise === 'undefined') {
      alert('{l s='Unable to process the payment, loading the external card processing library is failed. Please contact the merchant.' mod='omise'}');
      return false;
    }

    omiseSetCardFormLockedState(omiseCheckoutForm, true);

    const card = window.omiseCardFields.reduce(function(obj, field) {
      obj[field] = omiseCheckoutForm[field].value;
      return obj;
    }, {});

    Omise.setPublicKey('{$omise_public_key}');
    Omise.createToken('card', card, omiseCreateTokenCallback);
  };

  window.omiseCreateTokenCallback = function omiseCreateTokenCallback(statusCode, response) {
    if (statusCode === 200) {
      document.getElementById('omise_card_token').value = response.id;
      document.getElementById('omise_checkout_form').submit();
    } else {
      alert(response.message);
      omiseSetCardFormLockedState(omiseCheckoutForm, false);
    }
  };

  window.omiseCheckoutForm = window.omiseCardFields.concat(['checkout_button', 'checkout_text']).reduce(function(obj, field) {
    obj[field] = document.getElementById('omise_card_' + field);
    return obj;
  }, {});

  window.omiseSetCardFormLockedState = function omiseSetCardFormLockedState(form, state) {
    window.omiseCardFields.concat(['checkout_button']).map(function(field) { form[field].disabled = state; });
    form.checkout_text.innerHTML = state ? '{l s='Processing' mod='omise'}' : '{l s='Submit Payment' mod='omise'}';
  }

</script>
