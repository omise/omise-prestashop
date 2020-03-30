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
                <label for="omise_card_name">{l s='Name on card' mod='omise'}</label>
                <input class="form-control" id="omise_card_name" type="text" placeholder="{l s='Name on card' mod='omise'}">
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

    var
      originalPaymentConfirmationText,
      paymentConfirmationButton,
      $ = document.getElementById.bind(document),

      omiseCardFields = [
        'name',
        'number',
        'expiration_month',
        'expiration_year',
        'security_code'
      ],

      omiseCardPaymentForm = omiseCardFields.reduce(function(obj, field) {
        obj[field] = $('omise_card_' + field);
        return obj;
      }, {}),

      createOmiseToken = function createOmiseToken(form) {
        var card = omiseCardFields.reduce(function(obj, field) { obj[field] = form[field].value; return obj;}, {});
        Omise.setPublicKey('{$omise_public_key}');
        Omise.createToken('card', card, omiseCreateTokenCallback);
      },

      isOmiseCardPaymentOptionSelected = function isOmiseCardPaymentOptionSelected() {
        return document.querySelector('[data-module-name="omise-card-payment"]').checked;
      },

      setPayFormLockState = function setPayFormLockState(form, state) {
        omiseCardFields.forEach(function(field) { form[field].disabled = state; });
      },

      omiseCreateTokenCallback = function omiseCreateTokenCallback(statusCode, response) {
        if (statusCode === 200) {
          $('omise_card_token').value = response.id;
          $('omise_checkout_form').submit();
        } else {
          alert(response.message);
          setPayFormLockState(omiseCardPaymentForm, false);
          paymentConfirmationButton.disabled = false;
          paymentConfirmationButton.innerHTML = originalPaymentConfirmationText;
        }
      },

      handlePaymentConfirmClick = function handlePaymentConfirmClick(event) {
       if (isOmiseCardPaymentOptionSelected()) {
          event.preventDefault();
          event.stopPropagation();

          if (typeof Omise === 'undefined') {
            alert('{l s='Unable to process the payment, loading the external card processing library is failed. Please contact the merchant.' mod='omise'}');
            return false;
          }

          setPayFormLockState(omiseCardPaymentForm, true);
          paymentConfirmationButton.disabled = true;
          paymentConfirmationButton.innerHTML = '{l s='Processing' mod='omise'}';

          createOmiseToken(omiseCardPaymentForm);

          return false;
        }
      },

      setup = function setup() {
        paymentConfirmationButton = $('payment-confirmation').getElementsByTagName('button')[0];
        originalPaymentConfirmationText = paymentConfirmationButton.innerHTML;
        paymentConfirmationButton.addEventListener('click', handlePaymentConfirmClick);
      }

    ;

    document.addEventListener('DOMContentLoaded', setup);

  })();
</script>
