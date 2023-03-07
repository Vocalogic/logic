<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailSeeder extends Seeder
{
    const CAT_FINANCE = 1;  // Quotes, Invoices, any Billing related emails
    const CAT_LEADS   = 2;  // Leads/Discovery/Updates,etc
    const CAT_ACCOUNT = 3;  // Account Information
    const CAT_SALES   = 4;  // Sales/Affiliate Emails


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        $this->buildEmail('lead.quote', self::CAT_FINANCE, 'Quote for Lead',
            'Email sent with a quote attached to a lead',
            'You have a new quote from {setting.brand-name}');

        $this->buildEmail('account.quote', self::CAT_FINANCE, 'Quote for Existing Customer',
            'Email sent with a quote attached to an existing customer',
            '[{setting.brand-name}] {quote.name}');

        $this->buildEmail('account.verify', self::CAT_ACCOUNT, 'Verify Email Request',
            'Verify your email address',
            'Verify your E-mail address with {setting.brand-name}');

        $this->buildEmail('account.payment', self::CAT_FINANCE, 'Payment Applied',
            'Email sent when a payment has been made on an invoice',
            'Payment of {transaction.amountFormatted} applied to invoice #{invoice.id}');

        $this->buildEmail('account.coterm', self::CAT_FINANCE, 'Co-Term Quote for Existing Customer',
            'When a quote is replacing an executed quote to update service.',
            'You have an updated quote from {setting.brand-name}');

        $this->buildEmail('account.cotermexe', self::CAT_FINANCE, 'Co-Term Quote Executed',
            'When a co-termed quote is executed.',
            'Your services have been updated and will be reflected next billing period.');

        $this->buildEmail('quote.signed', self::CAT_FINANCE, 'Quote Signed MSA',
            'When a MSA has been signed.',
            'Your {setting.brand-name} Signed Agreement Copy');


        $this->buildEmail('account.welcome', self::CAT_ACCOUNT, 'Welcome Email', 'Sent when a lead becomes an account',
            'Welcome to {setting.brand-name}!');

        $this->buildEmail('account.order', self::CAT_ACCOUNT, 'Vendor Order Email',
            'This is sent to a vendor when an order is submitted.',
            '[#{hardware_order.id}] New {setting.brand-name} Order');

        $this->buildEmail('account.loa', self::CAT_ACCOUNT, 'Customer LOA Sign Request',
            'Email sent when customer needs to verify/sign an LOA.',
            '[#{lnpOrder.id}] IMPORTANT! LOA for Number Transfer to {setting.brand-name}', 'Voip');
        $this->buildEmail('account.loasigned', self::CAT_ACCOUNT, 'Customer Copy of Signed LOA',
            'Email sent when customer signs the LOA.',
            '[#{lnpOrder.id}] Copy of Signed Authorization', 'Voip');

        $this->buildEmail('account.lnporder', self::CAT_ACCOUNT, 'Provider New LNP Order',
            'Sent to provider when ready for submission.',
            '[#{lnpOrder.id}] {setting.brand-name} New LNP Order (BTN: {lnpOrder.p_btn})', 'Voip');

        $this->buildEmail('account.lnpupdate', self::CAT_ACCOUNT, 'Customer LNP Order Update',
            'Sent to customer when an LNP order is updated.',
            '[#{lnpOrder.id}] Port Order Update (BTN: {lnpOrder.p_btn})', 'Voip');

        $this->buildEmail('account.updatedServices', self::CAT_ACCOUNT, 'Customer has Updated Services',
            'Sent to customer when services change or bill date changes.',
            '{setting.brand-name} Account Services Update');

        $this->buildEmail('account.invoice', self::CAT_FINANCE, 'New Invoice', 'Email sent with an invoice attached',
            'Invoice #{invoice.id} for {invoice.totalFormatted} due on {invoice.dueFormatted}');
        $this->buildEmail('account.credit', self::CAT_FINANCE, 'Credit Applied',
            'Email sent with a credit memo attached',
            'Credit Invoice #{invoice.id} for {invoice.totalFormatted} has been applied to your account.');
        $this->buildEmail('account.declined', self::CAT_FINANCE, 'Card Declined',
            'Email sent when customers credit card declines',
            "{setting.brand-name} Invoice #{invoice.id} - Payment Declined");

        $this->buildEmail('invoice.pastdue', self::CAT_FINANCE, 'Invoice Past Due',
            'Email sent when an invoice is past due.',
            "{setting.brand-name} Invoice #{invoice.id} is {invoice.daysPastDue} days PAST DUE");

        $this->buildEmail('invoice.suspensionPending', self::CAT_FINANCE, 'Invoice Past Due - Pending Suspension',
            'Email sent when an invoice is past due and has reached the pending suspension period.',
            "[NOTICE OF PENDING SUSPENSION] Invoice #{invoice.id} is {invoice.daysPastDue} days PAST DUE");

        $this->buildEmail('invoice.lateFeeCharged', self::CAT_FINANCE, 'Invoice Late Fee Charged',
            'Email sent when and invoice is past due and a late fee has been charged.',
            "Invoice #{invoice.id} has been updated to include a Late Fee");

        $this->buildEmail('invoice.terminationPending', self::CAT_FINANCE, 'Invoice Past Due - Pending Termination',
            'Email sent when an invoice is past due and has reached the pending termination period.',
            "[NOTICE OF PENDING TERMINATION] Invoice #{invoice.id} is {invoice.daysPastDue} days PAST DUE");

        $this->buildEmail('account.customer_order', self::CAT_ACCOUNT, 'Customer Order Email',
            'Email sent to customer when new order built',
            '[OR-{order.id}] New {setting.brand-name} Order Created.');
        $this->buildEmail('user.forgot', self::CAT_ACCOUNT, 'User Reset Password',
            'Email sent to customer/admin when password needs to be reset.',
            '{setting.brand-name} Portal Password Reset');

        $this->buildEmail('account.cardrequest', self::CAT_ACCOUNT, 'Credit Card Request',
            'Email sent when a new credit card needs to be added',
            '[{setting.brand-name}] A new credit card is needed on your account');

        $this->buildEmail('account.suspend', self::CAT_ACCOUNT, 'Service Suspension Pending',
            'Email sent when services are scheduled to be suspended',
            '[{setting.brand-name}] Service Suspension Pending');
        $this->buildEmail('account.terminate', self::CAT_ACCOUNT, 'Service Termination Pending',
            'Email sent when services are scheduled to be terminated',
            '[{setting.brand-name}] Service Termination Pending');

        $this->buildEmail('account.suspendImmediate', self::CAT_ACCOUNT, 'Service Suspended',
            'Email sent when service is suspended',
            '[{setting.brand-name}] Service: {accountItem.code} has been SUSPENDED');
        $this->buildEmail('account.terminateImmediate', self::CAT_ACCOUNT, 'Service Terminated',
            'Email sent when service is terminated',
            '[{setting.brand-name}] Service: {accountItem.code} has been TERMINATED');

        $this->buildEmail('system.invite', self::CAT_LEADS, 'Partner Invite',
            'Email sent when a new partner invite is sent',
            '[{partner.name}] You have a new partner Request!');

        $this->buildEmail('system.partnerLead', self::CAT_LEADS, 'Partner Sent Lead',
            'Email sent when a partner receives a new lead',
            '{partner.name} sent you a new lead: {lead.company}');

        $this->buildEmail('agent.commission', self::CAT_FINANCE, 'Agent Commission Generated',
            'Email sent when a sales agent has a newly generated commission',
            'New Commission ({commission.amountHuman}) assigned to you for {commission.accountHuman}');
        $this->buildEmail('agent.batch', self::CAT_FINANCE, 'Agent Commissions Batched',
            'Email sent when a sales agent has a new commission batch created',
            'New Commission Payout #{commissionBatch.id} for {commissionBatch.totalHuman} is pending payment');
        $this->buildEmail('agent.batchPaid', self::CAT_FINANCE, 'Agent Commission Batch Paid',
            'Email sent when a payment has been sent to the sales agent.',
            'Payment Sent for Commission Batch #{commissionBatch.id}');

        $this->buildEmail('sales.staleLead', self::CAT_SALES, 'Stale Lead',
            'Sent to sales agents when a lead has gone stale',
            '[{lead.company}] Lead has gone stale, Please Update');

        $this->buildEmail('lead.projectReview', self::CAT_LEADS, 'Project Review',
        'Sent to customer when a project is ready for review',
        '[{project.name}] Your project is ready for review!');


        EmailTemplate::placeholders();


    }

    /**
     * @param string      $ident
     * @param int         $cat
     * @param string      $name
     * @param string      $description
     * @param string      $subject
     * @param string|null $module
     * @return void
     */
    private function buildEmail(
        string $ident,
        int $cat,
        string $name,
        string $description,
        string $subject,
        ?string $module = null
    ): void {
        $template = EmailTemplate::whereIdent($ident)->first();
        if (!$template)
        {
            EmailTemplate::create([
                'ident'                      => $ident,
                'email_template_category_id' => $cat,
                'name'                       => $name,
                'description'                => $description,
                'subject'                    => $subject,
                'body'                       => "Placeholder for $name",
                'module'                     => $module
            ]);
        }
        else
        {
            $template->update([
                'email_template_category_id' => $cat,
                'name'                       => $name,
                'description'                => $description
            ]);
        }
    }
}
