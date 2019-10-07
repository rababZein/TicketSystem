<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTrackingTasksTable extends Migration {

	public function up()
	{
		Schema::create('tracking_tasks', function(Blueprint $table) {
			$table->increments('id');
			$table->datetime('created_at');
			$table->datetime('updated_at')->nullable();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->longText('comment')->nullable();
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