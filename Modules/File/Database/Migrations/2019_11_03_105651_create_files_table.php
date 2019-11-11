<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration {

	public function up()
	{
		Schema::create('files', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('topic_id')->unsigned();
			$table->timestamp('created_at');
			$table->timestamp('updated_at')->nullable();
			$table->string('name');
			$table->integer('created_by')->unsigned();
			$table->longText('description')->nullable();
			$table->integer('updated_by')->unsigned()->nullable();

			// add foreign key
			$table->foreign('created_by')->references('id')->on('users')
				  ->onDelete('restrict')
				  ->onUpdate('restrict');

			$table->foreign('updated_by')->references('id')->on('users')
				  ->onDelete('restrict')
				  ->onUpdate('restrict');

			$table->foreign('topic_id')->references('id')->on('topics')
				  ->onDelete('restrict')
				  ->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::drop('files');
	}
}