# Change Log

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