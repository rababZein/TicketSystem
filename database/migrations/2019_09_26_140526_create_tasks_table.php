<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTasksTable extends Migration {

	public function up()
	{
		Schema::create('tasks', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at');
			$table->timestamp('updated_at')->nullable();
			$table->string('name');
			$table->longText('description')->nullable();
			$table->integer('responsible_id')->unsigned()->nullable();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->integer('ticket_id')->unsigned()->nullable();
			$table->integer('project_id')->unsigned();
			$table->float('count_hours')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('tasks');
	}
}