<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->references('id')->on('stations')->cascadeOnDelete();
            $table->foreignId('device_id')->references('id')->on('devices')->cascadeOnDelete();
            $table->tinyInteger('action'); //ENTER, EXIT, DRIVE_THROUGH
            $table->tinyInteger('status'); //PROCESSED, NOT PROCESSED
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
        Schema::dropIfExists('stations_logs');
    }
}
