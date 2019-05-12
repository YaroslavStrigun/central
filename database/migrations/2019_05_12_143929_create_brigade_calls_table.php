<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrigadeCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brigade_calls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('call_id');
            $table->unsignedBigInteger('brigade_status_id')->nullable();
            $table->unsignedBigInteger('brigade_id');
            $table->dateTime('arrival_time')->nullable();
            $table->dateTime('departure_time')->nullable();
            $table->timestamps();

            $table->foreign('call_id')->references('id')->on('calls')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('brigade_status_id')->references('id')->on('brigade_statuses')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('brigade_id')->references('id')->on('brigades')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brigade_calls');
    }
}
