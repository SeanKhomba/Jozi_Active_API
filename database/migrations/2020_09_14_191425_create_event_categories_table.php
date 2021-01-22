<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_categories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->softDeletes();
            $table->timestamps();
        });

        $categories = [ 
             'Football',
             'Basketball',
             'Cricket',
             'Golf',
             'Motorsport',
             'Athletics',
             'Cycling',
             'Tennis',
             'Boxing',
             'Mixed Martial Arts',
             'Table Tennis',
             'Esports',
             'Snooker',
             'Hockey',
             'Netball',
             'Softball'
            ];
        foreach ($categories as $category) {
            DB::table('event_categories')->insert([
                'name' => $category,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_categories');
    }
}
