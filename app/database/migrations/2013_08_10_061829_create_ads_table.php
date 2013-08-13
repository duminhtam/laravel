<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ads', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title', 255);
			$table->text('description');
			$table->float('price', 10);
			$table->string('currency', 3);
			$table->tinyInteger('col', 2)->default(1);
			$table->tinyInteger('row', 2)->default(1);
			$table->string('url', 255)->unique(); //url with unique index, search will using url
			$table->string('date', 255);
			$table->string('img', 255);
			$table->string('category', 255);
			$table->string('date_posted', 25);

            $table->index('row');
            $table->index('col');
			$table->timestamps();
		});

        //create new_ads view
        DB::statement('CREATE VIEW new_ads AS
                      SELECT *
                      FROM ads
                      ORDER BY id DESC;
                      ');
	}
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ads');
        DB::statement('DROP VIEW new_ads;
                      ');
	}

}
