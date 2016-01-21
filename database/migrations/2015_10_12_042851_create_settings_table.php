<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('index');
			$table->string('subindex');
			$table->string('parameter');
			$table->string('description');
			$table->text('value');
			$table->enum('type', array('string', 'text', 'bool'));
			
			$table->unique(array('index', 'subindex', 'parameter'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

}
