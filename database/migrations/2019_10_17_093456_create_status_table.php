<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatusTable extends Migration {

	public function up()
	{
		Schema::create('status', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at');
			$table->timestamp('updated_at')->nullable();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->integer('created_by')->unsigned()->nullable();
			$table->string('name');
			$table->longText('description')->nullable();
			$table->boolean('main')->default(0);
		});
	}

	public function down()
	{
		Schema::drop('status');
	}
}