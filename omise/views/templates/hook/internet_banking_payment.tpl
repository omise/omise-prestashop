<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="row">
        <div class="col-sm-12">
          <h3>{l s='Internet Banking' mod='omise'}</h3>
        </div>
        <div class="col-sm-8 col-md-5 col-lg-4">
          <form id="omiseInternetBankingCheckoutForm" method="post" action="{$link->getModuleLink('omise', 'internetbankingpayment', [], true)|escape:'html'}">
            <ul class="omise-internet-banking">
              <li class="item">
                <input class="no-uniform" id="omiseInternetBankingScb" name="offsite" type="radio" value="internet_banking_scb" autocomplete="off">
                <label for="omiseInternetBankingScb">
                  <div class="omise-logo-wrapper scb">
                    <img src="modules/omise/img/scb.svg" class="scb">
                  </div>
                  <div class="omise-bank-text-wrapper">
                    <span class="title">{l s='Siam Commercial Bank' mod='omise'}</span><br>
                    <span class="secondary-text">{l s='Fee: 15 THB (same zone), 30 THB (out zone)' mod='omise'}</span>
                  </div>
                </label>
              </li>
              <li class="item">
                <input class="no-uniform" id="omiseInternetBankingKtb" name="offsite" type="radio" value="internet_banking_ktb" autocomplete="off">
                <label for="omiseInternetBankingKtb">
                  <div class="omise-logo-wrapper ktb">
                    <img src="modules/omise/img/ktb.svg" class="ktb">
                  </div>
                  <div class="omise-bank-text-wrapper">
                    <span class="title">{l s='Krungthai Bank' mod='omise'}</span><br>
                    <span class="secondary-text">{l s='Fee: 15 THB (same zone), 15 THB (out zone)' mod='omise'}</span>
                  </div>
                </label>
              </li>
              <li class="item">
                <input class="no-uniform" id="omiseInternetBankingBay" name="offsite" type="radio" value="internet_banking_bay" autocomplete="off">
                <label for="omiseInternetBankingBay">
                  <div class="omise-logo-wrapper bay">
                    <img src="modules/omise/img/bay.svg" class="bay">
                  </div>
                  <div class="omise-bank-text-wrapper">
                    <span class="title">{l s='Krungsri Bank' mod='omise'}</span><br>
                    <span class="secondary-text">{l s='Fee: 15 THB (same zone), 15 THB (out zone)' mod='omise'}</span>
                  </div>
                </label>
              </li>
              <li class="item">
                <input class="no-uniform" id="omiseInternetBankingBbl" name="offsite" type="radio" value="internet_banking_bbl" autocomplete="off">
                <label for="omiseInternetBankingBbl">
                  <div class="omise-logo-wrapper bbl">
                    <img src="modules/omise/img/bbl.svg" class="bbl">
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
      </div>
    </div>
  </div>
</div>

{strip}
  {if $conditions}
    {addJsDefL name=omise_msg_select_bank}{l s='Please select a bank before continuing.' js=1 mod='omise'}{/addJsDefL}
  {/if}
{/strip}

<script>
  const omiseDisplayMessage = function omiseDisplayMessage(message) {
    if ($.prototype.fancybox) {
      $.fancybox.open([
          {
            type: 'inline',
            autoScale: true,
            minHeight: 30,
            content: '<p class="fancybox-error">' + message + '</p>',
          }],
        {
          padding: 0,
        });
    } else {
      alert(message);
    }
  }

  const omiseHasAnyBankSelected = function omiseHasAnyBankSelected() {
    var selectedBank = document.getElementsByName('offsite');

    for (var i = 0; i < selectedBank.length; i++) {
      if (selectedBank[i].checked == true) {
        return true;
      }
    }

    return false;
  }

  const omiseInternetBankingCheckout = function omiseInternetBankingCheckout(event) {
    event.preventDefault();

    if (omiseHasAnyBankSelected() == false) {
      omiseDisplayMessage(omise_msg_select_bank);
      return false;
    }

    document.getElementById('omiseInternetBankingCheckoutForm').submit();
  }

  /**
   * Remove the Uniform style.
   *
   * To display the list of banks at the Omise internet banking payment method, currently, it uses the similar
   * style sheet with others Omise plugins to remain the display consistency.
   *
   * But PrestaShop uses a jQuery plugin, Uniform, to style the elements. This plugin adds additional elements and it
   * make the different display.
   *
   * So, to remain the display consistency with the similar style sheet, the Uniform style for some elements
   * need to be removed.
   *
   * Uniform is a jQuery plugin that has been bundled in PrestaShop to style the elements.
   * @see /themes/default-bootstrap/js/autoload/15-jquery.uniform-modified.js
   *
   * Reference about Uniform, jQuery plugin, on GitHub: https://github.com/square/uniform
   */
  const omiseRestoreUniformStyle = function omiseRestoreUniformStyle() {
    $.uniform.restore('.no-uniform');
  }

  document.getElementById('omiseInternetBankingCheckoutButton').addEventListener('click', function(event) {
    omiseInternetBankingCheckout(event);
  });

  window.addEventListener('load', function() {
    omiseRestoreUniformStyle();
  });

  window.addEventListener('resize', function() {
    omiseRestoreUniformStyle();
  });
</script>
