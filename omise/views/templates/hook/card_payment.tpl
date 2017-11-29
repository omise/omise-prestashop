<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="row">
        <div class="col-sm-12">
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
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="omise_card_expiration_month">{l s='Expiration month' mod='omise'}</label>
                  <select class="form-control form-control-select" id="omise_card_expiration_month">
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
              <div class="col-sm-6 pull-right">
                <div class="form-group">
                  <label for="omise_card_expiration_year">{l s='Expiration year' mod='omise'}</label>
                  <select class="form-control form-control-select" id="omise_card_expiration_year">
                    {html_options values=$list_of_expiration_year output=$list_of_expiration_year}
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="omise_card_security_code">{l s='Security code' mod='omise'}</label>
                  <input class="form-control" id="omise_card_security_code" type="password" placeholder="{l s='Security code' mod='omise'}">
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.omise.co/omise.js.gz"></script>

<script>
  (function() {
    var originalPaymentConfirmationText;
    var paymentConfirmationButton;

    var createOmiseToken = function createOmiseToken(form) {
      var card = {
        name: form.name.value,
        number: form.number.value,
        expiration_month: form.expiration_month.value,
        expiration_year: form.expiration_year.value,
        security_code: form.security_code.value,
      };

      Omise.setPublicKey('{$omise_public_key}');
      Omise.createToken('card', card, omiseCreateTokenCallback);
    };

    var isOmiseCardPaymentOptionSelected = function isOmiseCardPaymentOptionSelected() {
      var omiseCardPaymentOption = document.querySelector('[data-module-name="omise-card-payment"]');

      if (omiseCardPaymentOption.checked) {
        return true;
      }

      return false;
    };

    var lockOmiseCardPaymentForm = function lockOmiseCardPaymentForm(form) {
      form.name.disabled = true;
      form.number.disabled = true;
      form.expiration_month.disabled = true;
      form.expiration_year.disabled = true;
      form.security_code.disabled = true;
    };

    var omiseCardPaymentForm = {
      name: document.getElementById('omise_card_holder_name'),
      number: document.getElementById('omise_card_number'),
      expiration_month: document.getElementById('omise_card_expiration_month'),
      expiration_year: document.getElementById('omise_card_expiration_year'),
      security_code: document.getElementById('omise_card_security_code'),
    };

    var omiseCreateTokenCallback = function omiseCreateTokenCallback(statusCode, response) {
      if (statusCode === 200) {
        document.getElementById('omise_card_token').value = response.id;
        document.getElementById('omise_checkout_form').submit();
      } else {
        alert(response.message);
        unlockOmiseCardPaymentForm(omiseCardPaymentForm);
        paymentConfirmationButton.disabled = false;
        paymentConfirmationButton.innerHTML = originalPaymentConfirmationText;
      }
    };

    var unlockOmiseCardPaymentForm = function unlockOmiseCardPaymentForm(form) {
      form.name.disabled = false;
      form.number.disabled = false;
      form.expiration_month.disabled = false;
      form.expiration_year.disabled = false;
      form.security_code.disabled = false;
    };

    document.addEventListener('DOMContentLoaded', function () {
      paymentConfirmationButton = document.getElementById('payment-confirmation').getElementsByTagName('button')[0];
      originalPaymentConfirmationText = paymentConfirmationButton.innerHTML;

      paymentConfirmationButton.addEventListener('click', function (event) {
        if (isOmiseCardPaymentOptionSelected()) {
          event.preventDefault();
          event.stopPropagation();

          if (typeof Omise === 'undefined') {
            alert('{l s='Unable to process the payment, loading the external card processing library is failed. Please contact the merchant.' mod='omise'}');
            return false;
          }

          lockOmiseCardPaymentForm(omiseCardPaymentForm);
          paymentConfirmationButton.disabled = true;
          paymentConfirmationButton.innerHTML = '{l s='Processing' mod='omise'}';

          createOmiseToken(omiseCardPaymentForm);

          return false;
        }
      });
    });
  })();
</script>
