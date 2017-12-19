<div class="additional-information">
  <form id="omise-internet-banking-payment-form" method="post" action="{$link->getModuleLink('omise', 'internetbankingpayment', [], true)|escape:'html'}">
    <ul class="omise-internet-banking">
      <li class="item">
        <input id="omise-internet-banking-scb" name="offsite" type="radio" value="internet_banking_scb" autocomplete="off">
        <label for="omise-internet-banking-scb">
          <div class="omise-logo-wrapper scb">
            <img src="/modules/omise/img/scb.svg" class="scb">
          </div>
          <div class="omise-bank-text-wrapper">
            <span class="title">{l s='Siam Commercial Bank' mod='omise'}</span><br>
            <span class="secondary-text">{l s='Fee: 15 THB (same zone), 30 THB (out zone)' mod='omise'}</span>
          </div>
        </label>
      </li>
      <li class="item">
        <input id="omise-internet-banking-ktb" name="offsite" type="radio" value="internet_banking_ktb" autocomplete="off">
        <label for="omise-internet-banking-ktb">
          <div class="omise-logo-wrapper ktb">
            <img src="/modules/omise/img/ktb.svg" class="ktb">
          </div>
          <div class="omise-bank-text-wrapper">
            <span class="title">{l s='Krungthai Bank' mod='omise'}</span><br>
            <span class="secondary-text">{l s='Fee: 15 THB (same zone), 15 THB (out zone)' mod='omise'}</span>
          </div>
        </label>
      </li>
      <li class="item">
        <input id="omise-internet-banking-bay" name="offsite" type="radio" value="internet_banking_bay" autocomplete="off">
        <label for="omise-internet-banking-bay">
          <div class="omise-logo-wrapper bay">
            <img src="/modules/omise/img/bay.svg" class="bay">
          </div>
          <div class="omise-bank-text-wrapper">
            <span class="title">{l s='Krungsri Bank' mod='omise'}</span><br>
            <span class="secondary-text">{l s='Fee: 15 THB (same zone), 15 THB (out zone)' mod='omise'}</span>
          </div>
        </label>
      </li>
      <li class="item">
        <input id="omise-internet-banking-bbl" name="offsite" type="radio" value="internet_banking_bbl" autocomplete="off">
        <label for="omise-internet-banking-bbl">
          <div class="omise-logo-wrapper bbl">
            <img src="/modules/omise/img/bbl.svg" class="bbl">
          </div>
          <div class="omise-bank-text-wrapper">
            <span class="title">{l s='Bangkok Bank' mod='omise'}</span><br>
            <span class="secondary-text">{l s='Fee: 15 THB (same zone), 20 THB (out zone)' mod='omise'}</span>
          </div>
        </label>
      </li>
    </ul>
  </form>
</div>

<div id="omise-message" hidden="hidden">
  <p class="fancybox-error">{l s='Please select a bank before continuing.' js=1 mod='omise'}</p>
</div>

<script>
  (function() {
    var omiseDisplayMessage = function omiseDisplayMessage(message) {
      if ($.prototype.fancybox) {
        $.fancybox.open([
            {
              type: 'inline',
              autoScale: true,
              minHeight: 30,
              content: $('#omise-message').html(),
            }],
          {
            padding: 0,
          });
      } else {
        alert(message);
      }
    };

    var omiseHasAnyBankSelected = function omiseHasAnyBankSelected() {
      var selectedBank = document.getElementsByName('offsite');

      for (var i = 0; i < selectedBank.length; i++) {
        if (selectedBank[i].checked == true) {
          return true;
        }
      }

      return false;
    };

    var isOmiseInternetBankingOptionSelected = function isOmiseInternetBankingOptionSelected() {
      var omiseInternetBankingOption = document.querySelector('[data-module-name="omise-internet-banking-payment"]');

      if (omiseInternetBankingOption.checked) {
        return true;
      }

      return false;
    };

    document.addEventListener('DOMContentLoaded', function () {
      var paymentConfirmationButton = document.getElementById('payment-confirmation').getElementsByTagName('button')[0];

      paymentConfirmationButton.addEventListener('click', function (event) {
        if (isOmiseInternetBankingOptionSelected()) {
          event.preventDefault();
          event.stopPropagation();

          if (omiseHasAnyBankSelected() == false) {
            omiseDisplayMessage('{l s='Please select a bank before continuing.' js=1 mod='omise'}');
            return false;
          }

          document.getElementById('omise-internet-banking-payment-form').submit();
        }
      });
    });
  })();
</script>
