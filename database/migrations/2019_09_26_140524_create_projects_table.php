<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration {

	public function up()
	{
		Schema::create('projects', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at');
			$table->timestamp('updated_at')->nullable();
			$table->string('name')->unique();
			$table->longText('description')->nullable();
			$table->integer('owner_id')->unsigned();
			$table->integer('task_rate');
			$table->integer('budget_hours')->nullable();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('projects');
	}
}