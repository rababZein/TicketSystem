<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();

            $table->string('debitor_number')->nullable();
            $table->string('customer_number')->nullable();

            $table->string('company')->nullable();
            $table->string('addition_company')->nullable();
            $table->string('address')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();

            $table->date('birth_date')->nullable();
            $table->string('eBay-user')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('commerical_register')->nullable();
            $table->string('street_number')->nullable();
            $table->string('additional_address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city_code')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();

            $table->date('customer_from')->nullable();
            $table->string('customer_group')->nullable();
            $table->date('first_contact_by')->nullable();
            $table->date('customer_of_company')->nullable();
            $table->string('language')->nullable();
            $table->string('print_templates_set')->nullable();
            $table->string('payment_deadline')->nullable();
            $table->string('payment')->nullable();
            $table->string('discount')->nullable();
            //$table->string('credit_card')->nullable();

            $table->longText('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')
              ->onDelete('restrict')
              ->onUpdate('restrict');

            $table->foreign('created_by')->references('id')->on('users')
              ->onDelete('restrict')
              ->onUpdate('restrict');

            $table->foreign('updated_by')->references('id')->on('users')
              ->onDelete('restrict')
              ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metadata');
    }
}
