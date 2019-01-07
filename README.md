<p align="center"><a href='https://www.omise.co'><img src='https://cdn.omise.co/assets/omise-logo-with-text.svg' height='60'></a></p>

**Omise PrestaShop** is the official payment extension which provides support for Omise payment gateway for store builders working on the PrestaShop platform.

## Supported Versions

Our aim is to support as many versions of PrestaShop as we can.  

**Here's the list of versions we tested on:**

- PrestaShop 1.7.4, PHP 7.0.30
- PrestaShop 1.6.19, PHP 5.6.30

**Can't find the version you're looking for?**  
Submit your requirement as an issue to [GitHub's issue channel](https://github.com/omise/omise-prestashop/issues).

## Getting Started

### Installation Instructions

The instructions below will guide you through all the steps in installing the module.

1. Download the [latest version of Omise PrestaShop](https://github.com/omise/omise-prestashop/releases/download/v1.7.3/omise-prestashop-v1.7.3.zip).

2. Login to your PrestaShop back office and go to **Modules & Services**.

    ![](https://user-images.githubusercontent.com/4145121/33422261-b097cbc2-d5e7-11e7-8270-f2e20cc2ad68.png)

3. Click **UPLOAD A MODULE**.

    ![](https://user-images.githubusercontent.com/4145121/33428747-b0f73aa2-d5fc-11e7-93c1-2ada62d88e2c.png)

4. Select the file that you downloaded.

    ![](https://user-images.githubusercontent.com/4145121/33428763-bb96fccc-d5fc-11e7-8810-2b9f618b999b.png)

### First Time Setup

After installation, you are required to configure the module.

1. Login to your PrestaShop back office and go to **Modules & Services**.

    ![](https://user-images.githubusercontent.com/4145121/33422261-b097cbc2-d5e7-11e7-8270-f2e20cc2ad68.png)

2. Click **Installed modules**.

    ![](https://user-images.githubusercontent.com/4145121/33425834-837cb688-d5f2-11e7-8086-96b615cab1a8.png)

3. Click **CONFIGURE**.

    ![](https://user-images.githubusercontent.com/4145121/33425839-8ab131f4-d5f2-11e7-9514-39694a6f6fee.png)

The module configuration page will appear.

![](https://user-images.githubusercontent.com/4145121/33425845-8e2d6aa0-d5f2-11e7-95c7-0f91baf91857.png)

The table below is the settings for the module and the description for each setting.

| Setting             | Description                                                                              |
| ------------------- | -----------------------------------------------------------------------------------------|
| Enable/Disable      | Enables or disables 'Omise payment module'                                               |
| Sandbox             | If selected, all transactions will be performed in TEST mode and TEST keys will be used. |
| Public key for test | Your TEST public key can be found in your Omise dashboard.                               |
| Secret key for test | Your TEST secret key can be found in your Omise dashboard.                               |
| Public key for live | Your LIVE public key can be found in your Omise dashboard.                               |
| Secret key for live | Your LIVE secret key can be found in your Omise dashboard.                               |
| Webhooks endpoint   | The URL for webhooks configuration in your Omise dashboard.                              |
| Title               | Title of Omise card payment shown at checkout.                                           |
| 3-D Secure support  | Enables or disables 3-D Secure payment.                                                  |
| Internet Banking    | Enables or disables internet banking payment.                                            |
| Alipay              | Enables or disables Alipay payments.                                                     |

- To enable the module, select the setting for `Enable/Disable` to `Yes`.
- To enable `sandbox` mode, select the setting for `Sandbox` to `Yes`.

**Note:**

If the setting for `Sandbox` is set to `Yes`, the keys for TEST will be used. If the setting for `Sandbox` is set to `No`, the keys for LIVE will be used.

## Contributing

Thanks for your interest in contributing to Omise PrestaShop. We're looking forward to hearing your thoughts and willing to review your changes.

The following subjects are instructions for contributors who consider to submit changes and/or issues.

### Submit the changes

You're all welcome to submit a pull request. Please consider the [pull request template](https://github.com/omise/omise-prestashop/blob/master/.github/PULL_REQUEST_TEMPLATE.md) and fill the form when you submit a new pull request.

Learn more about [pull request](https://help.github.com/articles/about-pull-requests).

### Submit the issue

To report problems, feel free to submit the issue through [GitHub's issue channel](https://github.com/omise/omise-prestashop/issues) by following the [Create an Issue Guideline](https://guides.github.com/activities/contributing-to-open-source/#contributing).

Learn more about [issue](https://guides.github.com/features/issues).

## License

Omise PrestaShop is open source software released under the [MIT License](https://github.com/omise/omise-prestashop/blob/master/LICENSE).
