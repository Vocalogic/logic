Invoice #{invoice.id} is currently <b>{invoice.daysPastDue} days past due</b>.

Invoices that are at least {{setting('invoices.lateFeeDays')}} days past due are subject to a {invoice.lateFeePercentage}% late fee.

A fee of <b>${invoice.lateFeeFormatted}</b> has been added to your invoice and has been attached below.

Please note that invoices that remain unpaid after {setting.invoices-suspensionDays} days are subject to suspension. Invoices that remain unpaid for {setting.invoices-terminationDays} days are subject to automatic termination.
