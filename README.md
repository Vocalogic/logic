### Logic - Open Source Sales and Billing Tool for Small Business

**Logic** is an open source project started by Chris Horne ([@devhorne](https://www.twitter.com/devhorne)), initially to
manage the day to day business process for [Vocalogic](https://www.vocalogic.com), A business telephone/VOIP Provider in
Atlanta, GA.

We wanted potential customers to be able to browse a shop, pick products and services, generate a quote and either
download the quote or start services completely unassisted. We also needed flexible automated billing, financing
options, contract signing and more. Vocal (the phone side) and Logic (the business process) prides itself
on efficiency for admins to perform tasks, and customer experience for the end user.

#### Logic Provides:

* A branded customer portal where customers can update credit cards, see services, get support, order services, etc.
* Product catalog with built-in profit metrics
* Automated billing with multiple merchants
* Lead / Quote / Contract management and signing
* Commission assignment on deals for sales agents based on SPIFF or MRR
* Order fulfillment for shipping (physical products) or provisioning (services)
* Cash flow tools
* Account management for monthly services
* Flexible configuration and branding

### Check out the [live demo here](https://demo.logic.host).

This project was written using [Laravel 9](https://www.laravel.com), and PHP 8.2.

### Installation Requirements and Instructions

* A linux webserver running nginx or apache.
* PHP v8.2
* MariaDB v10.6+
* Composer
* Redis v5.0.7+
* NPM 6.14+
* NodeJS v14.21+

### Cloud Hosting and Management

If you just want to use the application and host with Vocalogic, please contact **sales@vocalogic.com**. This helps
further fund development and grow the project.

### Self-Hosted Installation Steps

If you would rather just download and host the project yourself on your own servers, you can do so using the steps
below. Feel free to reach out for support contract options for large production environments to help further
fund development of this application. Please refer to the [full installation guide](https://logic.readme.io/docs) for
more information.

#### Create a MySQL Database

````
CREATE DATABASE logic;
GRANT all on logic.* to 'logic'@'localhost' IDENTIFIED by 'MyPassw0rd';
````

#### Clone the Project

````
git clone https://www.github.com/Vocalogic/logic logic
cd logic
cp .env.example .env
````

#### Edit the .env file

You will need to edit a few fields inside the .env file

````
APP_URL=http://localhost        # Your FQDN to your installation
APP_NAME="Logic"                # You can leave this
BYPASS_ENABLED=false            # This allows auto-login from Vocalogic for support. Set to true to enable. 
REDIS_QUEUE=logic               # Your redis queue name. Some use 'default'
APP_TIMEZONE="America/New_York" # Your PHP Formatted Timezone

DB_HOST=127.0.0.1               # Database Host
DB_PORT=3306                    # Database Port
DB_DATABASE=logic               # Database Name
DB_USERNAME=logic               # Database Username     
DB_PASSWORD=                    # Database Password
````

#### Finish using Upgrade Command

````
./artisan key:generate
./artisan logic:upgrade
````

**NOTE**: If you receive any errors from the above command, please review
the [step-by-step installation guide](https://logic.readme.io/docs) for
additional help.

#### Final Commands

````
chmod 777 storage -R          # This is required for sessions, file uploads, etc.
chmod 777 bootstrap -R        # Cached Files Directory needs to be writable.
./artisan key:gen             # Generate the application Key
````

For detailed step-by-step instructions please refer to the installation guide.

Finally, visit your URL, and you will be redirected to the installation page where you will set up your company
and initial admin user.

### User Manual

Please refer to the [user manual](https://logic.readme.io/docs) on how to get started.

### Code Contributing

We are excited to open source our first project, and are excited to see where this takes us. If you are
interested in contributing or have any questions feel free to chat with us on
our [discord server](https://discord.gg/4KBnrXBUNU).

For contributing guidelines and more information on the project and how it is organized internally, check out the
contributing guide for more information.

### Financially Contributing

As this is open sourced software, supporting this project financially is always welcomed but not required. There are a
few ways you can contribute to Logic.

* Donations - Support our developers on [Patreon](https://patreon.com/vocalogic)
* [Hosting](https://www.vocalogic.com/hosting) with Vocalogic - Get a cloud server with Vocalogic or have us host your Logic instance.
* Pizza. You could send Pizza to our offices at 190 Bluegrass Valley Parkway, Alpharetta, GA. 30005. Just no pineapple please.


