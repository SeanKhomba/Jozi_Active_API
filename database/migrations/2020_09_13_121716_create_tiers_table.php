<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('minimum_bookings');
            $table->string('discount_amount');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('tiers')->insert([
            'name' => "Gold",
            'minimum_bookings' => 15,
            'discount_amount' => "200",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('tiers')->insert([
            'name' => "Silver",
            'minimum_bookings' => 10,
            'discount_amount' => "100",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('tiers')->insert([
            'name' => "Bronze",
            'minimum_bookings' => 5,
            'discount_amount' => "50",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tiers');
    }
}
