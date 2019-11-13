<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('project_id')->unsigned()->nullable();
            $table->integer('client_id')->unsigned()->nullable();
            $table->integer('ticket_id')->unsigned()->nullable();
            $table->integer('task_id')->unsigned()->nullable();
            $table->integer('receipt_id')->unsigned()->nullable();
            $table->timestamps();

            // add foreign key
			$table->foreign('user_id')->references('id')->on('users')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->foreign('project_id')->references('id')->on('projects')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->foreign('client_id')->references('id')->on('users')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->foreign('ticket_id')->references('id')->on('tickets')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->foreign('task_id')->references('id')->on('tasks')
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
        Schema::dropIfExists('activities');
    }
}
