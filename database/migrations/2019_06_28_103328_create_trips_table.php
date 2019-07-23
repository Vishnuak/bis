<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('bus_id');
            $table->time('start')->nullable();
            $table->time('end')->nullable();
            $table->string('start_lat', 15)->nullable();
            $table->string('start_long', 15)->nullable();
            $table->string('end_lat', 15)->nullable();
            $table->string('end_long', 15)->nullable();
            $table->longText('stops_details');
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
        Schema::dropIfExists('trips');
    }
}
