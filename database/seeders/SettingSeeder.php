<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->buildSetting('brand.url', 'Brand URL', 'input', null, 'Brand',
            'Enter the URL (ex http://logic.mycompany.com');

        $this->buildSetting('brand.name', 'Brand Name', 'input', null, 'Brand', 'Enter the name of your company/brand');
        $this->buildSetting('brand.address', 'Company Address', 'input', null, 'Brand',
            'Enter the address for your company (used on Invoices)');
        $this->buildSetting('brand.address2', 'Company Address (Line 2)', 'input', null, 'Brand',
            'Enter the suite/unit (optional)');
        $this->buildSetting('brand.csz', 'City/State/Zip', 'input', null, 'Brand',
            'Enter the city, state and zip for your invoices.');
        $this->buildSetting('brand.theme', 'Select Color Scheme', 'select', 'indigo', 'Brand',
            'Select the color scheme for Logic', 'plain,blue,indigo,cyan,green,orange,blush,red,dynamic');
        $this->buildSetting('brand.contrast', 'Select Contrast Mode', 'select', 'light', 'Brand',
            'Select a contrast mode (i.e light or dark mode)', 'light,dark,theme-dark,high-contrast');
        $this->buildSetting('brand.license', 'License Key', 'input', null, 'Brand',
            'Enter your Logic License Key');

        $this->buildSetting('brandImage.dark', 'Dark Logo (PNG)', 'file', null, 'BrandImage',
            'Select a logo that should be used with light backgrounds');
        $this->buildSetting('brandImage.light', 'Light Logo (PNG)', 'file', null, 'BrandImage',
            'Select a logo that should be used with dark backgrounds');
        $this->buildSetting('brandImage.icon', 'FavIcon (PNG)', 'file', null, 'BrandImage',
            'Select an icon for browser tabs');
        $this->buildSetting('brandImage.watermark', 'Watermark (Quotes/Invoices)', 'file', null, 'BrandImage',
            'Select an image that will be watermarked.');


        // Quote Setup
        $this->buildSetting('quotes.length', 'Quote Expires (in days)', 'number', 30, 'Quote',
            'Enter the default number of days for a quote to expire after sending.', '0|30');
        $this->buildSetting('quotes.margin', 'Margin Target (in percent)', 'number', 80, 'Quote',
            'Enter the target profit margin percentage (default 80).', '10|99');
        $this->buildSetting('quotes.commexp', 'Include Commissions in Expenses?', 'select', 'Yes', 'Quote',
            'When calculating profit margin, include commissions?', 'Yes,No');

        $this->buildSetting('quotes.msrp', 'Use MSRP for Guest Quotes?', 'select', 'Yes', 'Quote',
            'When a guest creates a quote, should msrp be used or auto-apply base pricing?', 'Yes,No');
        $this->buildSetting('quotes.modify', 'Allow Customers to Change QTY?', 'select', 'Yes', 'Quote',
            'When creating a quote for a customer, allow them to update qty?', 'Yes,No');
        $this->buildSetting('quotes.selfterm', 'Allow Guests in Shop to Self-Contract?', 'select', 'No', 'Quote',
            'Allow guests to select a contract term for optional discount pricing?', 'Yes,No');
        $this->buildSetting('quotes.terms', 'Term Options', 'tags', '12,24,36', 'Quote',
            'Enter a list of months that are available to choose from');

        $this->buildSetting('quotes.assumedTerm', 'Assumed Months Uncontracted', 'number', 12, 'Quote',
            'Enter the number of months (if uncontracted) to assume account will live for profitability report.', '1|36');

        $this->buildSetting('quotes.pricingMethod', 'Auto-Price Based on MSRP or Cost?', 'select', 'Cost', 'Quote',
            'Select if you would like auto-pricing to either discount MSRP or Increase based on cost', 'Cost,MSRP');
        $this->buildSetting('quotes.desiredPerc', 'Auto-Selling Price Percentage', 'number', 20, 'Quote',
            'Enter the percentage to set pricing above cost or below MSRP by default', '0|200');
        $this->buildSetting('quotes.variancePerc', 'Auto-Selling Price Variance Percentage', 'number', 20, 'Quote',
            'When setting low/high sales pricing, this percentage will dictate how much of the desired price can be modified lower or higher', '0|200');
        $this->buildSetting('quotes.openai', 'OpenAI Key', 'password', null, 'Quote',
            'Enter your OpenAI API Key for Product Definition Assistance (BETA)');
        $this->buildSetting('quotes.subtractExpense', 'Subtract Expenses before Commission?', 'select', 'No', 'Quote',
            'Should opex expenses be subtracted before figuring commission?', 'Yes,No');

        $this->buildSetting('quotes.msa', 'Master Services Agreement', 'textarea', $this->getMSA(), 'MSA',
            'Enter the text to be shown for signing the master services agreement. All TOS will be appeneded afterwards.');

        // Invoice Setup
        $this->buildSetting('invoices.net', 'Default Net Terms', 'number', 30, 'Invoice',
            'Enter your default net terms of when invoices should be due', '0|30');
        $this->buildSetting('invoices.pastdue', 'Number of Days to Send Past Due Notice', 'number', 7, 'Invoice',
            'Enter the number of days between sending a past due invoice notification.', '0|30');
        $this->buildSetting('invoices.suspensionDays', 'Number of Days Past Due before Suspension', 'number', 15, 'Invoice',
            'Enter the number of days until services are to be suspended for non-payment.', '0|60');
        $this->buildSetting('invoices.terminationDays', 'Number of Days Past Due before Termination', 'number', 30, 'Invoice',
            'Enter the number of days until services are to be terminated for non-payment.', '0|60');

        $this->buildSetting('invoices.default', 'Default Payment Type', 'select', 'Credit Card', 'Invoice',
            'Enter the default payment type for when accounts are created.', 'Credit Card,Check,EFT,Cash');
        $this->buildSetting('invoices.cancelmtm', 'Cancellation Days Required (No Contract)', 'number', 30, 'Invoice',
            'If a customer wishes to cancel with a month to month agreement, how many days are required?', '0|90');
        $this->buildSetting('invoices.cancelcontract', 'Cancellation Days Required (w/ Contract)', 'number', 90,
            'Invoice',
            'If a customer wishes to cancel in a contract, how many days before end of contract is required?', '0|90');
        $this->buildSetting('invoices.help', 'Invoice Help Area', 'textarea', $this->getInvoiceHelp(), 'Invoice',
            'Enter the additional help area for a monthly invoice');


        // Leads Configuration
        $this->buildSetting('leads.aging', 'Require Lead Update (in days)', 'number', 0, 'Lead',
            'Enter the number of days before a lead is going stale (0 = disable this alert)', '0|365');
        $this->buildSetting('version', 'Version', 'input', '', 'Core', 'Version Information');

        // Mail Information
        $this->buildSetting('mail.type', 'Mail Transport', 'select', 'SMTP', 'Mail',
            'Select the method Logic uses to send email.', 'SMTP,Mailgun,GMail');
        $this->buildSetting('mail.host', 'Mail Host', 'input', '', 'Mail',
            'Enter the hostname of the mail server');
        $this->buildSetting('mail.port', 'Mail TCP Port', 'input', '', 'Mail',
            'Enter the port number for transport');
        $this->buildSetting('mail.username', 'Mail Username', 'input', '', 'Mail',
            'Enter the username for the email account to use');
        $this->buildSetting('mail.password', 'Mail Password', 'password', '', 'Mail',
            'Enter the password for the mail account.');

        $this->buildSetting('mail.enc', 'Mail Encryption Type', 'select', 'SSL', 'Mail',
            'Select encryption method to use.', 'None,SSL,TLS');
        $this->buildSetting('mail.fromname', 'Mail From Name', 'input', '', 'Mail',
            'Enter the name to use for email (i.e. My Company Support)');
        $this->buildSetting('mail.fromemail', 'Mail From E-mail', 'input', '', 'Mail',
            'Enter the Email Address to send mail from. (i.e. support@mycompany.com)');

        $this->buildSetting('mail.mgdomain', 'Mailgun Domain (if using Mailgun)', 'input', '', 'Mail',
            'Enter the mailgun domain you are sending from (ie.. mx.mycompany.com)');

        $this->buildSetting('mail.mgsecret', 'Mailgun API Secret (if using Mailgun)', 'password', '', 'Mail',
            'Enter the mailgun secret/API key');

        $this->buildSetting('account.reminder', 'Account Periodic Checkin (in days)', 'number', '90', 'Account',
            'Enter the number of days of inactivity from customer before checking in', '7|365');

        $this->buildSetting('account.2fa_method', 'Account 2FA Method', 'select', 'Email', 'Account',
            'Select the 2FA Method for unrecognized IPs', 'Email,SMS');

        $this->buildSetting('account.2fa_days', 'Account 2FA Forced in Days', 'number', '7', 'Account',
            'Enter the number of days that 2FA should be forced even if same IP', '1|90');

        $this->buildSetting('account.term_payoff', 'Account Contract Payoff Percentage', 'number', '80', 'Account',
            'Enter the percentage of contracted amount that must be paid off during cancellation (default 80% of remainder)', '1|99');



        // Shop Seeder
        $this->buildSetting('shop.contact', 'Order Contact Number:', 'input', '', 'Shop',
            'Enter the phone number to display for ordering on the shop.');

        $this->buildSetting('shop.email', 'Order E-mail Address:', 'input', '', 'Shop',
            'Enter the email address to display for ordering questions.');

        $this->buildSetting('shop.hours', 'Hours of Operation:', 'input', '', 'Shop',
            'Enter the hours of operation for customers who want to call you.');

        $this->buildSetting('shop.verification', 'Quote/Order Verification Method', 'select', 'Email', 'Shop',
            'How should users be verified before creating a quote or executing an order?', 'Email,SMS');

        $this->buildSetting('shop.info', 'Company Footer Description:', 'textarea', '', 'Shop',
            'Enter a few sentences for SEO describing your company.');
        $this->buildSetting('shop.ticker', 'Ticker Message:', 'textarea', '', 'Shop',
            'Enter promo for top ticker in shop (one per line)');

        $this->buildSetting('shop.hero', 'Landing Hero Image (1920x637)', 'file', null, 'Shop',
            'Select an image to display when someone arrives at your shop');

        $this->buildSetting('shop.small_header', 'Small Header Message:', 'input', '', 'Shop',
            'Example: Welcome to my company/Special Offer!');

        $this->buildSetting('shop.large_header', 'Large Header Message:', 'input', '', 'Shop',
            'Example: New Arrivals! New Products, etc.');

        $this->buildSetting('shop.header_detail', 'Under Large Header:', 'input', '', 'Shop',
            'Example: Call us for details.');
        $this->buildSetting('shop.header_color', 'Text Color for Overlay:', 'color', '', 'Shop',
            'Select a color for the primary text if overlaying on image.');
        $this->buildSetting('shop.ga', 'Google Analytics Key:', 'input', '', 'Shop',
            'Enter Google Analytics Key (G-XXXXX)');
        $this->buildSetting('shop.tawk', 'Tawk.to API Key:', 'password', '', 'Shop',
            'Enable Tawk.to Live Chat from www.tawk.to');

        $this->buildSetting('shop.tawk_embed', 'Tawk.to Embed Src:', 'input', '', 'Shop',
            'Copy/Paste the embed.src portion (ex. https://embed.tawk.to/xxxx/xxxx');

        $this->buildsetting('shop.color', 'Shop Color Scheme', 'color', '', 'Shop',
            'Select the color scheme to use for your shop.');
        $this->buildsetting('shop.color2', 'Shop Button/Alt Color', 'color', '', 'Shop',
            'Select a complementing color for buttons and alternate color.');

        $this->buildSetting('shop.mode', 'Light/Dark Mode', 'select', 'light', 'Shop',
            'Select mode for shop', 'light,dark');

        $this->buildSetting('orders.stale', 'Order Stale Alert', 'input', '7', 'Order',
            'How many days before an order goes stale without an update?');
        $this->buildSetting('shop.showCategories', 'Show All Items on Shop Landing Page?', 'select', 'No', 'Shop',
            'Show all your items in your shop in a carousel format on the front page?', 'Yes,No');
    }


    /**
     * Check it setting exists.
     * @param string $name
     * @return bool
     */
    private function check(string $name): bool
    {
        return Setting::where('ident', $name)->exists();
    }


    /**
     * Build setting for use in the settings area.
     * @param string      $ident
     * @param string      $question
     * @param string      $type
     * @param string|null $default
     * @param string      $category
     * @param string|null $help
     * @param string|null $opts
     * @return void
     */
    private function buildSetting(
        string $ident,
        string $question,
        string $type,
        ?string $default,
        string $category,
        ?string $help,
        ?string $opts = null
    ) {
        if (!$this->check($ident))
        {
            (new Setting)->create([
                'ident'    => $ident,
                'question' => $question,
                'type'     => $type,
                'default'  => $default,
                'category' => $category,
                'help'     => $help,
                'opts'     => $opts,
                'value'    => $default
            ]);
        }
    }

    /**
     * Get invoice markdown
     * @return string
     */
    private function getInvoiceHelp(): string
    {
        return "### How Can I Pay?
You can login to our customer portal and pay via credit card.
If you have opted in for automatic payments, your card on file will be charged on your due date.
";
    }

    /**
     * Generate Default MSA
     * @return string
     */
    private function getMSA(): string
    {
        return "
The Agreement is made this day, {quote.msaStart}, by and between **{setting.brand-name}** located at {setting.brand-address} {setting.brand-csz} (hereinafter known as \"Provider\"), and **{quote.company-name}** (hereinafter known as \"Customer\").
\n\n
WHEREAS, Customer has requested services from Provider specified in the Quote Review or Cart Checkout process
<br/><br/>
\n\n
WHEREAS, Customer has verified all applicable services before executing agreement and
<br/><br/>
\n\n
WHEREAS, Customer has selected Provider to provide products and/or services;
<br/><br/>
\n\n
NOW THEREFORE, in consideration of the mutual benefits, promises, and undertakings, the
sufficiency and receipt of which are acknowledged, the following terms and conditions are agreed to by
the parties to this Contract:
<br/><br/>
\n\n
**1. Incorporation by Reference.** The following are made a part hereof as if the same were fully set
forth herein, and if any discrepancies arise between the documents, they will prevail in the
following order: (1) this Contract, (2) Quote for Services, including any addenda
and (3) any and all Terms of Services agreed to upon execution of this order.
<br/><br/>

**2. Term of Contract.** The term of this contract shall be for ({quote.term}) months with the option for
renewals under the terms, conditions and unit pricing of the original contract for up to four (12)
additional months, unless either party gives written notification to the other party sixty (60) days
prior to expiration of the then-current term that they do not wish to renew.
<br/><br/>

**3. Costs.** Provider agrees to perform all work pursuant to this Contract according to the fee
schedule attached as \"Quote for Service\" (the \"Contract Price\"). All travel-related reimbursable expenses
shall not exceed the current GSA rates. Any subcontractor reimbursable expenses may have a
markup not to exceed 10% of actual cost.
";

    }

}
