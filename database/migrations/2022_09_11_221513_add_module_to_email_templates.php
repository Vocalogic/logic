<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('module')->nullable();
        });

        \App\Models\EmailTemplate::whereIn('ident', [
            'account.order',
            'account.loa',
            'account.loasigned',
            'account.lnporder',
            'account.lnpupdate',
            'account.customerorder'
        ])->update(['module' => 'Voip']);

        \App\Models\EmailTemplate::whereIn('ident', [
            'account.quote',
            'lead.quote',
        ])->update(['module' => 'SimpleQuote']);

        \App\Models\EmailTemplate::whereIn('ident', [
            'lead.discovery',
        ])->update(['module' => 'SimpleLead', 'email_template_category_id' => 1]);

        \App\Models\EmailTemplateCategory::where('name', 'Lead/Sales')->delete();


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('module');
        });
    }
};
