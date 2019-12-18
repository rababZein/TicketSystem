<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

	public function up()
	{
		Schema::create('categories', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at');
			$table->timestamp('updated_at')->nullable();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->string('name')->unique();
			$table->longText('description')->nullable();

			// add foreign key
			$table->foreign('created_by')->references('id')->on('users')
				  ->onDelete('restrict')
				  ->onUpdate('restrict');

			$table->foreign('updated_by')->references('id')->on('users')
				  ->onDelete('restrict')
				  ->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::drop('categories');
	}
}