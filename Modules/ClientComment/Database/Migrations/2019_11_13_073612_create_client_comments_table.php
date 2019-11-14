<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_comments', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();
            $table->longText('comment');
            $table->integer('client_id')->unsigned();
            $table->timestamps();

            // add foreign key
			$table->foreign('created_by')->references('id')->on('users')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->foreign('updated_by')->references('id')->on('users')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->foreign('client_id')->references('id')->on('users')
            ->onDelete('restrict')
            ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_comments');
    }
}
