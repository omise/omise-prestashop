<div class="row">
  <div class="col-xs-12">
    <p class="payment_module">
      <div class="box">
        <div class="row">
          <div class="col-sm-12">
            <h3>{l s=$omise_title mod='omise'}</h3>
          </div>
          <div class="col-sm-12">
            <form id="omiseInstallmentsCheckoutForm" method="post" action="{$action|escape:'html'}">
              <ul class="omise-internet-banking">
                <li class="item">
                  <input class="no-uniform" id="omiseInstallmentsBay" name="offsite" type="radio" value="installment_bay" autocomplete="off">
                  <label for="omiseInstallmentsBay">
                    <div class="omise-logo-wrapper bay">
                      <img src="{$base_dir}/modules/omise/img/bay.svg" class="bay">
                    </div>
                    <span class="title">{l s='Krungsri' mod='omise'}</span><br>
                  </label>
                </li>
                <li class="item">
                  <input class="no-uniform" id="omiseInstallmentsBbl" name="offsite" type="radio" value="installment_bbl" autocomplete="off">
                  <label for="omiseInstallmentsBbl">
                    <div class="omise-logo-wrapper bbl">
                      <img src="{$base_dir}/modules/omise/img/bbl.svg" class="bbl">
                    </div>
                    <span class="title">{l s='Bangkok Bank' mod='omise'}</span><br>
                  </label>
                </li>
                <li class="item">
                  <input class="no-uniform" id="omiseInstallmentsFirstChoice" name="offsite" type="radio" value="installment_first_choice" autocomplete="off">
                  <label for="omiseInstallmentsFirstChoice">
                    <div class="omise-logo-wrapper first_choice">
                      <img src="{$base_dir}/modules/omise/img/firstchoice.svg" class="firstchoice">
                    </div>
                    <span class="title">{l s='First Choice' mod='omise'}</span><br>
                  </label>
                </li>
                <li class="item">
                  <input class="no-uniform" id="omiseInstallmentsKbank" name="offsite" type="radio" value="installment_kbank" autocomplete="off">
                  <label for="omiseInstallmentsKbank">
                    <div class="omise-logo-wrapper kbank">
                      <img src="{$base_dir}/modules/omise/img/kbank.svg" class="kbank">
                    </div>
                    <span class="title">{l s='KBank' mod='omise'}</span><br>
                  </label>
                </li>
                <li class="item">
                  <input class="no-uniform" id="omiseInstallmentsKtc" name="offsite" type="radio" value="installment_ktc" autocomplete="off">
                  <label for="omiseInstallmentsKtc">
                    <div class="omise-logo-wrapper ktc">
                      <img src="{$base_dir}/modules/omise/img/ktc.svg" class="ktc">
                    </div>
                    <span class="title">{l s='KTC' mod='omise'}</span><br>
                  </label>
                </li>
              </ul>

              {* <div class="fee-warning"><label>{l s='Your bank may charge a small fee for installment payments.' mod='omise'}</label></div> *}

              <button class="button btn btn-default standard-checkout button-medium" id="omiseInstallmentsCheckoutButton">
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

  // IMPORTANT - this window.xxx stuff looks weird and unnecessary, but it's necessary to make
  // the JS work correctly when the checkout is in one-page mode. It would appear that
  // dynamically created script blocks do not run in the global context
  
  window.omise_msg_select_bank = "{l s='Please select a bank before continuing.' js=1 mod='omise'}";

  window.omiseHasAnyBankSelected = function omiseHasAnyBankSelected() {
    return Array.prototype.slice.call(document.getElementsByName('offsite')).some(function(el) { return el.checked; });
  }

  window.omiseInstallmentsCheckout = function omiseInstallmentsCheckout(event) {
    event.preventDefault();
    if (!omiseHasAnyBankSelected()) {
      omiseDisplayMessage(omise_msg_select_bank);
      return false;
    }
    document.getElementById('omiseInstallmentsCheckoutForm').submit();
  }

  /**
   * Remove the Uniform style.
   *
   * To display the list of banks at the Omise installment payment method, currently, it uses the similar
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
  window.omiseRestoreUniformStyle = function omiseRestoreUniformStyle() {
    $.uniform.restore('.no-uniform');
  }

  document.getElementById('omiseInstallmentsCheckoutButton').addEventListener('click', window.omiseInstallmentsCheckout);
  window.addEventListener('load', window.omiseRestoreUniformStyle);
  window.addEventListener('resize', window.omiseRestoreUniformStyle);
  window.setTimeout(window.omiseRestoreUniformStyle, 100);

</script>
