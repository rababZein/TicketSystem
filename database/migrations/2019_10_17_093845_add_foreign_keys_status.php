<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('status', function(Blueprint $table) {
			$table->foreign('updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('status', function(Blueprint $table) {
			$table->foreign('created_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
        });
        Schema::table('projects', function(Blueprint $table) {
			$table->foreign('status_id')->references('id')->on('status')
						->onDelete('restrict')
						->onUpdate('restrict');
        });
        Schema::table('tasks', function(Blueprint $table) {
			$table->foreign('status_id')->references('id')->on('status')
						->onDelete('restrict')
						->onUpdate('restrict');
        });
        Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('status_id')->references('id')->on('status')
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
        Schema::table('projects', function(Blueprint $table) {
			$table->dropForeign('projects_status_id_foreign');
        });
        Schema::table('tasks', function(Blueprint $table) {
			$table->dropForeign('tasks_status_id_foreign');
        });
        Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_status_id_foreign');
        });
        Schema::table('status', function(Blueprint $table) {
			$table->dropForeign('status_updated_by_foreign');
		});
		Schema::table('status', function(Blueprint $table) {
			$table->dropForeign('status_created_by_foreign');
		});
    }
}
