<div class="row">
  <div class="col-xs-12">
    <p class="payment_module">
      <div class="box">
        <div class="row">
          <div class="col-sm-4">
            <h3>{$omise_title}</h3>
              <form>
                <input id="omise_card_token" name="omise_card_token" type="hidden">
                <div class="row">
                  <div class="form-group col-sm-12">
                    <label class="required" for="omise_card_number">&nbsp;{l s='Card number' mod='omise'}</label>
                    <input class="form-control" id="omise_card_number" type="text" placeholder="{l s='Card number' mod='omise'}">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-12">
                      <label class="required" for="omise_card_holder_name">&nbsp;{l s='Card holder name' mod='omise'}</label>
                      <input class="form-control" id="omise_card_holder_name" type="text" placeholder="{l s='Card holder name' mod='omise'}">
                    </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="required" for="omise_card_expiration_month">&nbsp;{l s='Expiration month' mod='omise'}</label>
                      <input class="form-control" id="omise_card_expiration_month" type="text" placeholder="{l s='MM' mod='omise'}">
                    </div>
                  </div>
                  <div class="col-sm-6 pull-right">
                    <div class="form-group">
                      <label class="required" for="omise_card_expiration_year">&nbsp;{l s='Expiration year' mod='omise'}</label>
                      <input class="form-control" id="omise_card_expiration_year" type="text" placeholder="{l s='YYYY' mod='omise'}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="required" for="omise_card_security_code">&nbsp;{l s='Security code' mod='omise'}</label>
                      <input class="form-control" id="omise_card_security_code" type="password" placeholder="{l s='Security code' mod='omise'}">
                    </div>
                  </div>
                </div>
                <button class="button btn btn-default" id="omise_checkout" onclick="omiseCheckout(); return false;">{l s='Checkout' mod='omise'}</button>
            </form>
          </div>
        </div>
      </div>
    </p>
  </div>
</div>

<script src="https://cdn.omise.co/omise.js.gz"></script>

<script>
const omiseCheckout = function omiseCheckout() {
  document.getElementById('omise_checkout').disabled = true;

  const card = {
    name: document.getElementById('omise_card_holder_name').value,
    number: document.getElementById('omise_card_number').value,
    expiration_month: document.getElementById('omise_card_expiration_month').value,
    expiration_year: document.getElementById('omise_card_expiration_year').value,
    security_code: document.getElementById('omise_card_security_code').value,
  };

  Omise.setPublicKey('{$omise_public_key}');
  Omise.createToken('card', card, omiseCreateTokenCallBack);
}

const omiseCreateTokenCallBack = function omiseCreateTokenCallBack(statusCode, response) {
  if (statusCode === 200) {
    document.getElementById('omise_card_token').value = response.id;
  } else {
    alert(response.message);
    document.getElementById("omise_checkout").disabled = false;
  }
}
</script>
