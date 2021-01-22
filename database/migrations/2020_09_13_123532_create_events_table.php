<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->integer("category_id");
            $table->string("name");
            $table->string("description")->nullable();
            $table->string("location")->nullable();
            $table->dateTime('date', 0);
            $table->string("start_time");
            $table->string("end_time");
            $table->string("price");
            $table->integer("avg_rating")->nullable();
            $table->integer("quantity_available");
            $table->integer("active")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
