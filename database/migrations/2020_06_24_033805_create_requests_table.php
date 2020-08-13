<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('meetPointLatitude');
            $table->double('meetPointLongitude');
            $table->double('destinationLatitude');
            $table->double('destinationLongitude');
            $table->integer('neededSeats')->default(1);
            $table->time('time');
            $table->boolean('response')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ride_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ride_id')->references('id')->on('rides')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requestts');
    }
}
