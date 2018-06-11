# xero-php sample app

This is a sample app for the xero-php SDK. Currently, this app focuses on demonstrating the functionality of all Accounting API endpoints and their related actions.  

You'll be able to connect to a Xero Organisation and make real API calls. The code used to make each API call will be displayed along with the results returned from Xero's API.

### Xero App
This sample app uses a Xero [Public](http://developer.xero.com/documentation/auth-and-limits/public-applications/) App.

Go to [http://app.xero.com](http://app.xero.com) and login with your Xero user account to create a Xero Public app. You'll set the callback URL and get your consumer key & secret.

### Setup App

Make sure you have [Composer](https://getcomposer.org) installed, then create this project using

```bash
composer create-project xero/xero-php-sample-app
``` 

This will download this project and all its dependencies into a folder called `xero-php-sample-app`

### Configure

There is an example config file in `config/xero.example.php`, which contains all required parameters.  Copy this file to `config/xero.php` and insert your consumer key and secret.

```php
	return [
        'oauth' => [
            //This is just for demo purposes, it should be set from a more reliable source for production use
            'callback'        => "http://$_SERVER[HTTP_HOST]/application/callback",
    
            'consumer_key'    => 'YOUR_CONSUMER_KEY',
            'consumer_secret' => 'YOUR_CONSUMER_SECRET',
    
            'signature_location'  => \XeroPHP\Remote\OAuth\Client::SIGN_LOCATION_QUERY,
        ],
        'curl' => [
            CURLOPT_USERAGENT   => 'XeroPHP Sample App',
            CURLOPT_CAINFO => APP_ROOT . '/certs/ca-bundle.crt',
        ],
    ];
```

### Running

The best way to run this project (with the least configuration), is using PHP's development web server.  Change to the webroot and start it on your chosen port

```bash
cd xero-php-sample-app/public
php -S localhost:8999
```

You should now be able to navigate to [http://localhost:8999](http://localhost:8999) and use the application.

### What isn't supported in the xero-php SDK?
We couldn't find a way to demonstrate the following actions with the xero-php SDK.

**Reports**
* TenNinetyNine
* AgedPayablesByContact
* AgedReceivablesByContact
* BankStatement - missing required bankAccountID
* BankSummary
* ExecutiveSummary
* TrialBalance

**TaxRates**
* Allows you to Delete a tax rate for a Xero organisation

_The following actions are all supported, but examples not yet implemented in this application_

**ContactGroups**

* Allows you to remove a contact from a contact group
* Allows you to remove all contacts from a contact group

**Currencies**

* Add currencies to your organisation

**Invoices**
* Retrieve the online invoice Url for sales invoices 

**InvoiceReminder**
* PR made to SDK to add support for this

**Tracking Categories**
* Add new options 
* Rename options 
* Update options 
* Delete options

## Acknowledgement

Special thanks to all the [Contributors](https://github.com/calcinai/xero-php/graphs/contributors) to the [xero-php](https://github.com/calcinai/xero-php) SDK


## License

This software is published under the [MIT License](http://en.wikipedia.org/wiki/MIT_License).

	Copyright (c) 2018 Xero Limited

	Permission is hereby granted, free of charge, to any person
	obtaining a copy of this software and associated documentation
	files (the "Software"), to deal in the Software without
	restriction, including without limitation the rights to use,
	copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the
	Software is furnished to do so, subject to the following
	conditions:

	The above copyright notice and this permission notice shall be
	included in all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
	OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
	HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
	FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
	OTHER DEALINGS IN THE SOFTWARE.
