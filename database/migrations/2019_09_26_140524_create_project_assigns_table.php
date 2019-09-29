<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectAssignsTable extends Migration {

	public function up()
	{
		Schema::create('project_assigns', function(Blueprint $table) {
			$table->integer('assign_to')->unsigned();
			$table->integer('project_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('project_assigns');
	}
}