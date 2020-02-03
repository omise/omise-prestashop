<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="row">
        <div class="col-sm-12">
          <form id="omiseTrueMoneyCheckoutForm" method="post" action="{$action|escape:'html'}">
            <div class="row">
              <div class="form-group col-sm-4">
                <input class="form-control" id="true_number" type="text" maxlength="10" placeholder="{l s='Phone number' mod='omise'}">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('omiseTrueMoneyCheckoutForm').onsubmit = function(event) {
    var gotPhoneNumber = !!document.getElementById('true_number').value.trim();
    gotPhoneNumber || window.omiseDisplayMessage('{l s='Please enter phone number before continuing.' js=1 mod='omise'}');
    return gotPhoneNumber;
  }
</script>
