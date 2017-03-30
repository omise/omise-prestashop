<p align="center"><a href='https://www.omise.co'><img src='https://assets.omise.co/assets/omise-logo-ed530feda8c7bf8b0c990d5e4cf8080a0f23d406fa4049a523ae715252d0dc54.svg' height='60'></a></p>

**Omise PrestaShop** is the official payment extension which provides support for Omise payment gateway for store builders working on the PrestaShop platform.

## Supported Versions

Our aim is to support as many versions of PrestaShop as we can.  

**Here's the list of versions we tested on:**

- PrestaShop 1.6.1.6, PHP 5.6.28
- PrestaShop 1.6.1.7, PHP 5.6.30

**Can't find the version you're looking for?**  
Submit your requirement as an issue to [GitHub's issue channel](https://github.com/omise/omise-prestashop/issues).

## Getting Started

### Installation Instructions

#### Manually

The steps below are the method to install the module manually. This method requires the privilege to access your PrestaShop file on your site.

1. Download the [Omise PrestaShop latest version](https://github.com/omise/omise-prestashop/archive/master.zip).
2. Extract the file that you downloaded.

After extracted the file, you will found a folder, **omise**. Copy and place it into the directory, **/modules**, of your PrestaShop site.
<p align="center"><img width="600" alt="omise-prestashop-master" src="https://cloud.githubusercontent.com/assets/4145121/23548905/44a8fa5c-003c-11e7-97ef-d46111d98c53.png"></p>

3. Login to your PrestaShop back office, go to **Module and Services** page.
<p align="center"><img width="800" src="https://cloud.githubusercontent.com/assets/4145121/23548922/577a299e-003c-11e7-8ad2-95789f0a62e8.png"></p>

4. Type **omise** at the search box. The Omise module will be displayed at the list.
<p align="center"><img width="800" src="https://cloud.githubusercontent.com/assets/4145121/24504148/471ec17e-157f-11e7-91ae-170f39e2da50.png"></p>

5. Click **Install** button.
<p align="center"><img width="800" src="https://cloud.githubusercontent.com/assets/4145121/24504149/471fd1c2-157f-11e7-9106-44cc67699f1c.png"></p>

6. Confirm the installation by click **Proceed with the installation** button.
<p align="center"><img width="800" src="https://cloud.githubusercontent.com/assets/4145121/23548960/82beae4a-003c-11e7-9895-fc48704df3b4.png"></p>

### First Time Setup

After the installation, you can configure the module by:

1. Login to your PrestaShop back office, go to **Module and Services** page.
<p align="center"><img width="800" src="https://cloud.githubusercontent.com/assets/4145121/23548922/577a299e-003c-11e7-8ad2-95789f0a62e8.png"></p>

2. Type **omise** at the search box. The Omise module will be displayed at the list.
<p align="center"><img width="800" src="https://cloud.githubusercontent.com/assets/4145121/24504148/471ec17e-157f-11e7-91ae-170f39e2da50.png"></p>

3. Click **Configuration** button.
<p align="center"><img width="800" src="https://cloud.githubusercontent.com/assets/4145121/24504149/471fd1c2-157f-11e7-9106-44cc67699f1c.png"></p>

The system will display the module configuration page.
<p align="center"><img width="800" src="https://cloud.githubusercontent.com/assets/4145121/24504150/47264c28-157f-11e7-866f-9dbfa94702c6.png"></p>

The table below is the settings for the module and the description for each setting.

| Setting             | Description                                                                              |
| ------------------- | -----------------------------------------------------------------------------------------|
| Enable/Disable      | Enables or disables 'Omise payment module'                                               |
| Sandbox             | If selected, all transactions will be performed in TEST mode and TEST keys will be used. |
| Public key for test | Your TEST public key can be found in your Omise dashboard.                               |
| Secret key for test | Your TEST secret key can be found in your Omise dashboard.                               |
| Public key for live | Your LIVE public key can be found in your Omise dashboard.                               |
| Secret key for live | Your LIVE secret key can be found in your Omise dashboard.                               |
| Title               | Title of Omise payment gateway shown at checkout.                                        |
| 3-D Secure support  | Enables or disables 3-D Secure payment.                                                  |
| Internet Banking    | Enables or disables internet banking payment.                                            |

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