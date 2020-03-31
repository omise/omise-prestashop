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
                <div class="form-group col-sm-3">
                  <input class="form-control" id="truemoney_number" name="truemoney_number" type="text" {if $initialPhone ne ''}value="{$initialPhone|escape:'html'}" {/if}maxlength="10" placeholder="{l s='Phone number' mod='omise'}">
                </div>
              </div>
              <button class="button btn btn-default standard-checkout button-medium" id="omiseTrueMoneyCheckoutButton">
                <span>{l s='Submit Payment' mod='omise'}</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </p>
  </div>
</div>

<script>
  document.getElementById('omiseTrueMoneyCheckoutForm').onsubmit = function(event) {
    var gotPhoneNumber = !!document.getElementById('truemoney_number').value.trim();
    gotPhoneNumber || window.omiseDisplayMessage('{l s='Please enter phone number before continuing.' js=1 mod='omise'}');
    return gotPhoneNumber;
  }
</script>
