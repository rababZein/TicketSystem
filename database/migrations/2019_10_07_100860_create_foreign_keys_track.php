<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeysTrack extends Migration {

	public function up()
	{
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->foreign('created_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->foreign('updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->foreign('task_id')->references('id')->on('tasks')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->dropForeign('tracking_tasks_created_by_foreign');
		});
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->dropForeign('tracking_tasks_updated_by_foreign');
		});
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->dropForeign('tracking_tasks_task_id_foreign');
		});
	}
}
