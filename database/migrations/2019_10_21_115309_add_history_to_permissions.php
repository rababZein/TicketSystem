<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHistoryToPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
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
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign('permissions_created_by_foreign');
            $table->dropForeign('permissions_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
}
