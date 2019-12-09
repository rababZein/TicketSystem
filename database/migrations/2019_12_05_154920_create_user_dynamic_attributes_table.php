<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDynamicAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_dynamic_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dynamic_attribute_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->longText('value');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');
            $table->foreign('dynamic_attribute_id')->references('id')->on('dynamic_attributes')
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
        Schema::dropIfExists('user_dynamic_attributes');
    }
}
