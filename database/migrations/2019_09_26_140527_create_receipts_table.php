<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReceiptsTable extends Migration {

	public function up()
	{
		Schema::create('receipts', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at');
			$table->timestamp('updated_at')->nullable();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->string('name');
			$table->longText('description')->nullable();
			$table->double('total')->default(0);
			$table->boolean('is_paid')->default(0);
			$table->integer('task_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('receipts');
	}
}