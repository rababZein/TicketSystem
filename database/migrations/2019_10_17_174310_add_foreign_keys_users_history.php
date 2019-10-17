<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysUsersHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->foreign('updated_by')->references('id')->on('users')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');
        });
		Schema::table('users', function(Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')
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
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('users_updated_by_foreign');
        });
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('users_created_by_foreign');
        });
    }
}
