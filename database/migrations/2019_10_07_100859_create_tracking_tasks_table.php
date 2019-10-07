<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTrackingTasksTable extends Migration {

	public function up()
	{
		Schema::create('tracking_tasks', function(Blueprint $table) {
			$table->increments('id');
			$table->datetime('create_at');
			$table->datetime('update_at')->nullable();
			$table->integer('create_by')->unsigned();
			$table->integer('update_by')->unsigned()->nullable();
			$table->longText('description')->nullable();
			$table->integer('task_id')->unsigned();
			$table->datetime('start_at');
			$table->datetime('end_at');
			$table->float('count_time')->default('0');
		});
	}

	public function down()
	{
		Schema::drop('tracking_tasks');
	}
}