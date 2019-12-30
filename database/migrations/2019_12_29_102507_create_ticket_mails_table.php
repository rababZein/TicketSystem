<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_mails', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('ticket_id')->unsigned();
            $table->string('email');
            $table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();

            // add foreign key
			$table->foreign('created_by')->references('id')->on('users')
                    ->onDelete('restrict')
                    ->onUpdate('restrict');

            $table->foreign('updated_by')->references('id')->on('users')
                    ->onDelete('restrict')
                    ->onUpdate('restrict');

            $table->foreign('ticket_id')->references('id')->on('tickets')
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
        Schema::dropIfExists('ticket_mails');
    }
}
