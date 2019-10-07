<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('projects', function(Blueprint $table) {
			$table->foreign('owner_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('projects', function(Blueprint $table) {
			$table->foreign('created_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('projects', function(Blueprint $table) {
			$table->foreign('updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tasks', function(Blueprint $table) {
			$table->foreign('responsible_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tasks', function(Blueprint $table) {
			$table->foreign('created_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tasks', function(Blueprint $table) {
			$table->foreign('updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tasks', function(Blueprint $table) {
			$table->foreign('ticket_id')->references('id')->on('tickets')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tasks', function(Blueprint $table) {
			$table->foreign('project_id')->references('id')->on('projects')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('receipts', function(Blueprint $table) {
			$table->foreign('created_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('receipts', function(Blueprint $table) {
			$table->foreign('updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('receipts', function(Blueprint $table) {
			$table->foreign('task_id')->references('id')->on('tasks')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('project_assigns', function(Blueprint $table) {
			$table->foreign('assign_to')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('project_assigns', function(Blueprint $table) {
			$table->foreign('project_id')->references('id')->on('projects')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('project_id')->references('id')->on('projects')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('created_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('updated_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->foreign('create_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->foreign('update_by')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->foreign('task_id')->references('id')->on('tasks')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('projects', function(Blueprint $table) {
			$table->dropForeign('projects_owner_id_foreign');
		});
		Schema::table('projects', function(Blueprint $table) {
			$table->dropForeign('projects_created_by_foreign');
		});
		Schema::table('projects', function(Blueprint $table) {
			$table->dropForeign('projects_updated_by_foreign');
		});
		Schema::table('tasks', function(Blueprint $table) {
			$table->dropForeign('tasks_responsible_id_foreign');
		});
		Schema::table('tasks', function(Blueprint $table) {
			$table->dropForeign('tasks_created_by_foreign');
		});
		Schema::table('tasks', function(Blueprint $table) {
			$table->dropForeign('tasks_updated_by_foreign');
		});
		Schema::table('tasks', function(Blueprint $table) {
			$table->dropForeign('tasks_ticket_id_foreign');
		});
		Schema::table('tasks', function(Blueprint $table) {
			$table->dropForeign('tasks_project_id_foreign');
		});
		Schema::table('receipts', function(Blueprint $table) {
			$table->dropForeign('receipts_created_by_foreign');
		});
		Schema::table('receipts', function(Blueprint $table) {
			$table->dropForeign('receipts_updated_by_foreign');
		});
		Schema::table('project_assigns', function(Blueprint $table) {
			$table->dropForeign('project_assigns_assign_to_foreign');
		});
		Schema::table('project_assigns', function(Blueprint $table) {
			$table->dropForeign('project_assigns_project_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_project_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_created_by_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_updated_by_foreign');
		});
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->dropForeign('tracking_tasks_create_by_foreign');
		});
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->dropForeign('tracking_tasks_update_by_foreign');
		});
		Schema::table('tracking_tasks', function(Blueprint $table) {
			$table->dropForeign('tracking_tasks_task_id_foreign');
		});
	}
}
