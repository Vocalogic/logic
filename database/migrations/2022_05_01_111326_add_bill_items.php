<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_tags', function($t)
        {
           $t->increments('id');
           $t->timestamps();
           $t->string('tag');
        });

        Schema::create('bill_categories', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('type');                    // SERVICE or PRODUCT
            $t->string('name');                    // Name of category
            $t->string('description')->nullable(); // Optional description
        });

        Schema::create('bill_items', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('bill_category_id');
            $t->string('code');              // VL-1234
            $t->string('name');              // Yealink T-54W
            $t->string('type');              // SERVICE or PRODUCT
            $t->string('description', 1024); // Description for quote
            $t->double('nrc')->nullable()->default(0.00);
            $t->double('mrc')->nullable()->default(0.00);

            $t->double('ex_capex')->nullable()->default(0.00);         // Cost of Phone one time
            $t->string('ex_capex_description')->nullable();            // Purchase of Phone
            $t->boolean('ex_capex_once')->nullable()->default(true);   // Capex per qty or per line item
            $t->double('ex_opex')->nullable()->default(0.00);          // Monthly Opex?
            $t->string('ex_opex_description')->nullable();             // Monthly Service etc.
            $t->boolean('ex_opex_once')->nullable()->default(true);    // Opex per qty or per line item (i.e. account)
            $t->string('ex_opexfreq')->nullable()->default('MONTHLY'); // monthly, quarterly, bi-annual, annually


            $t->integer('photo_id')->nullable()->default(0);          // Image
            $t->integer('slick_id')->nullable()->default(0);          // PDF to be attached in quotes.
            $t->string('feature_headline')->nullable();               // 16-line Professional Phone (for comparison)
            $t->string('feature_list', 1024)->nullable();             // nl separated feature block for comparison
            $t->integer('feature_priority')->nullable()->default(1);  // Order in the comparison list.
        });

        Schema::create('bill_item_tags', function($t)
        {
           $t->increments('id');
           $t->timestamps();
           $t->integer('item_tag_id');
           $t->integer('bill_item_id');
        });

        Schema::create('bill_item_overrides', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('account_id');
            $t->integer('bill_item_id');
            $t->double('nrc')->nullable()->default(0.00);
            $t->double('mrc')->nullable()->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bill_item_overrides');
        Schema::drop('bill_items');
        Schema::drop('bill_categories');
        Schema::drop('item_tags');
        Schema::drop('bill_item_tags');
    }
};
