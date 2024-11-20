# Change Log

## [[1.7.11] 2024-11-20](https://github.com/omise/omise-prestashop/releases/tag/v1.7.11)
- Add transaction ID and set order status to canceled on failed payment. (PR: [#100](https://github.com/omise/omise-prestashop/pull/100))

## [[1.7.10] 2023-09-05](https://github.com/omise/omise-prestashop/releases/tag/v1.7.10)
- *`Removed`* hard coded certificate.

## [[1.7.9] 2020-04-09](https://github.com/omise/omise-prestashop/releases/tag/v1.7.9)
- *`Fixed`* Small issue with HTTPS at checkout

## [[1.7.8] 2020-03-31](https://github.com/omise/omise-prestashop/releases/tag/v1.7.8)
- *`Fixed`* Small issue with TrueMoney fixed

## [[1.7.7] 2020-03-30](https://github.com/omise/omise-prestashop/releases/tag/v1.7.7)
- *`Added`* Fairly major rewrite of the plugin
- *`Fixed`* Issue with Internet Banking images not appearing on PrestaShop 1.6
- *`Added`* 'Citi Pay with Points' payment method
- *`Added`* 'TrueMoney' payment method
- *`Fixed`* Issue with 'Omise Test Mode' banner disappearing when credit card payment option switched off

## [[1.7.6] 2019-11-07](https://github.com/omise/omise-prestashop/releases/tag/v1.7.6)
- *`Fixed`* A small typo in the previous update was causing big issues. Fixed

## [[1.7.5] 2019-09-09](https://github.com/omise/omise-prestashop/releases/tag/v1.7.5)
- *`Added`* Support for more currencies - PR [#74](https://github.com/omise/omise-prestashop/pull/74)

## [[1.7.4] 2019-05-31](https://github.com/omise/omise-prestashop/releases/tag/v1.7.4)
- *`Added`* Highly visible warning message on checkout when Omise plugin has been left in sandbox mode

## [[1.7.3] 2019-01-07](https://github.com/omise/omise-prestashop/releases/tag/v1.7.3)
- *`Fixed`* Problem with URLs of internet banking images

## [[1.7.2] 2018-06-16](https://github.com/omise/omise-prestashop/releases/tag/v1.7.2)
- *`Fixed`* 500 error occurring when payments fail on Prestashop 1.6
- *`Fixed`* English on the 'payment error' page
- *`Fixed`* Longstanding issue with checkboxes sometimes not appearing to specifiy what currencies to allow for the Omise payment plugin
- *`Fixed`* Error thrown by the Webhooks controller

## [[1.7.1] 2018-06-13](https://github.com/omise/omise-prestashop/releases/tag/v1.7.1)
- *`Fixed`* Credit card and internet banking payments will now work in 'one page checkout' mode on PrestaShop 1.6

## [[1.7] 2018-06-13](https://github.com/omise/omise-prestashop/releases/tag/v1.7)
- *`Added`* Support for Alipay payments (Thailand only)

## [[1.6] 2018-06-13](https://github.com/omise/omise-prestashop/releases/tag/v1.6)
- *`Updated`* Renewed support for PrestaShop 1.6

## [[1.5] 2018-06-13](https://github.com/omise/omise-prestashop/releases/tag/v1.5)
- *`Added`* Support for EUR, GBP, and USD currencies
- *`Changed`* Internet banking fee warnings now generic to prevent them being outdated

## [[1.4] 2017-11-30](https://github.com/omise/omise-prestashop/releases/tag/v1.4)
- *`Added`* Implement a new feature, Omise Webhooks, and handle an event, charge.complete
- *`Added`* Add PrestaShop order ID to metadata of Omise charge
- *`Changed`* Save and display payment method name as selected by payer instead of fixed module name, Omise
- *`Updated`* Update the module to work with PrestaShop 1.7

## [[1.3] 2017-11-03](https://github.com/omise/omise-prestashop/releases/tag/v1.3)
- *`Added`* Specify Omise API version to be 2015-11-17

## [[1.2] 2017-06-21](https://github.com/omise/omise-prestashop/releases/tag/v1.2)
- *`Added`* Save the Omise charge ID to order payment transaction ID for reference
- *`Changed`* 3-D Secure payment, if charge is failed, update the order status to be **Canceled** instead of remain it **Processing in progress**

## [[1.1] 2017-03-29](https://github.com/omise/omise-prestashop/releases/tag/v1.1)
- *`Added`* Internet banking payment
- *`Changed`* Change the order status to be canceled, if the status of Omise charge is failed

## [[1.0] 2017-03-03](https://github.com/omise/omise-prestashop/releases/tag/v1.0)
- *`Added`* Create charge (auto capture)
- *`Added`* 3-D Secure payment
- *`Added`* Support currencies IDR, JPY, SGD AND THB