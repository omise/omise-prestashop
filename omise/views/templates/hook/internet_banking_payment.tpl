<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="row">
        <div class="col-sm-12">
          <form id="omiseInternetBankingCheckoutForm" method="post" action="{$link->getModuleLink('omise', 'internetbankingpayment', [], true)|escape:'html'}">
            <ul class="omise-internet-banking">
              <li class="item">
                <input class="no-uniform" id="omiseInternetBankingScb" name="offsite" type="radio" value="internet_banking_scb" autocomplete="off">
                <label for="omiseInternetBankingScb">
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
                <input class="no-uniform" id="omiseInternetBankingKtb" name="offsite" type="radio" value="internet_banking_ktb" autocomplete="off">
                <label for="omiseInternetBankingKtb">
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
                <input class="no-uniform" id="omiseInternetBankingBay" name="offsite" type="radio" value="internet_banking_bay" autocomplete="off">
                <label for="omiseInternetBankingBay">
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
                <input class="no-uniform" id="omiseInternetBankingBbl" name="offsite" type="radio" value="internet_banking_bbl" autocomplete="off">
                <label for="omiseInternetBankingBbl">
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
      </div>
    </div>
  </div>
</div>
