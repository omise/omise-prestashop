<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="row">
        <div class="col-sm-12">
          <form id="omise-installments-payment-form" method="post" action="{$action|escape:'html'}">
            <ul class="omise-internet-banking">
              <li class="item">
                <input id="omise-installments-bay" name="offsite" type="radio" value="installment_bay" autocomplete="off">
                <label for="omise-installments-bay">
                  <div class="omise-logo-wrapper bay">
                    <img src="{$urls.base_url}/modules/omise/img/bay.svg" class="bay">
                  </div>
                  <span class="title">{l s='Krungsri' mod='omise'}</span>
                </label>
              </li>
              <li class="item">
                <input id="omise-installments-bbl" name="offsite" type="radio" value="installment_bbl" autocomplete="off">
                <label for="omise-installments-bbl">
                  <div class="omise-logo-wrapper bbl">
                    <img src="{$urls.base_url}/modules/omise/img/bbl.svg" class="bbl">
                  </div>
                  <span class="title">{l s='Bangkok Bank' mod='omise'}</span>
                </label>
              </li>
              <li class="item">
                <input id="omise-installments-firstchoice" name="offsite" type="radio" value="installment_first_choice" autocomplete="off">
                <label for="omise-installments-firstchoice">
                  <div class="omise-logo-wrapper firstchoice">
                    <img src="{$urls.base_url}/modules/omise/img/firstchoice.svg" class="firstchoice">
                  </div>
                  <span class="title">{l s='First Choice' mod='omise'}</span>
                </label>
              </li>
              <li class="item">
                <input id="omise-installments-kbank" name="offsite" type="radio" value="installment_kbank" autocomplete="off">
                <label for="omise-installments-kbank">
                  <div class="omise-logo-wrapper kbank">
                    <img src="{$urls.base_url}/modules/omise/img/kbank.svg" class="kbank">
                  </div>
                  <span class="title">{l s='KBank' mod='omise'}</span>
                </label>
              </li>
              <li class="item">
                <input id="omise-installments-ktc" name="offsite" type="radio" value="installment_ktc" autocomplete="off">
                <label for="omise-installments-ktc">
                  <div class="omise-logo-wrapper ktc">
                    <img src="{$urls.base_url}/modules/omise/img/ktc.svg" class="ktc">
                  </div>
                  <span class="title">{l s='KTC' mod='omise'}</span>
                </label>
              </li>
            </ul>
            {* <div class="fee-warning"><label>{l s='Your bank may charge a small fee for internet banking payments.' mod='omise'}</label></div> *}
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {

    function omiseHasAnyBankSelected() {
      return Array.prototype.slice.call(document.getElementsByName('offsite')).some(function(el){ return el.checked; });
    }

    function isOmiseInternetBankingOptionSelected() {
      return document.querySelector('[data-module-name="omise-internet-banking-payment"]').checked;
    }

    function setup() {
      document.getElementById('payment-confirmation').getElementsByTagName('button')[0].addEventListener('click', handlePaymentConfirmClick)
    }

    function handlePaymentConfirmClick(event) {
      if (isOmiseInternetBankingOptionSelected()) {
        event.preventDefault();
        event.stopPropagation();

        if (!omiseHasAnyBankSelected()) {
          omiseDisplayMessage('{l s='Please select a bank before continuing.' js=1 mod='omise'}');
          return false;
        }

        document.getElementById('omise-internet-banking-payment-form').submit();
      }
    }

    document.addEventListener('DOMContentLoaded', setup);

  })();
</script>
