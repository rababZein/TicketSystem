<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHistoryToRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            
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
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign('roles_created_by_foreign');
            $table->dropForeign('roles_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
}
