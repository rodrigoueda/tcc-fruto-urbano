<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpatialpointsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('spatialpoints', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			
			$table->integer('user_id')->unsigned();
			$table->integer('city_id')->unsigned();

			$table->enum('type', ['PODA_DRASTICA', 'LOCAL_PLANTIO', 'VEGETACAO']);
			$table->string('image')->nullable();
			$table->string('address');
			$table->string('species')->nullable();
			$table->text('comments')->nullable();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('city_id')->references('id')->on('cities')->onDelete('restrict');
		});

		if (strcasecmp(env('DB_DRIVER'), 'pgsql') == 0) {
            DB::statement('ALTER TABLE spatialpoints ADD COLUMN point geometry(Point,4326) NULL;');
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('spatialpoints');
	}

}
