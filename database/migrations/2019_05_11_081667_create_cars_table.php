<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('brand');
            $table->string('type');
            $table->date('graduation_year');
            $table->string('commissioning_year');
            $table->string('state_number');
            $table->string('board_number');
            $table->boolean('operative')->default(1);
            $table->boolean('rented')->default(0);
            $table->unsignedBigInteger('station_id');
            $table->timestamps();

            $table->foreign('station_id')->references('id')->on('stations')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
