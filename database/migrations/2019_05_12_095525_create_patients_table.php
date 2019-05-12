<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name')->nullable();
            $table->text('address')->nullable();
            $table->integer('age')->nullable();
            $table->boolean('male')->default(0);
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('social_status_id')->nullable();
            $table->string('further_stay')->nullable();
            $table->string('departure_type')->nullable();
            $table->boolean('reasonableness')->default(1);
            $table->string('place_off_call')->nullable();
            $table->string('call_type')->nullable();
            $table->unsignedBigInteger('trauma_id')->nullable();
            $table->dateTime('pathological_date')->nullable();
            $table->unsignedBigInteger('result_id')->nullable();
            $table->boolean('unsuccessful_departure')->default(0);
            $table->text('previous diagnosis')->nullable();
            $table->string('anamnesis')->nullable();
            $table->json('objective_data')->nullable();
            $table->json('medicaid')->nullable();
            $table->json('state_after_relief')->nullable();
            $table->timestamps();

            $table->foreign('social_status_id')->references('id')->on('social_statuses')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('trauma_id')->references('id')->on('traumas')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('result_id')->references('id')->on('results')
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
        Schema::dropIfExists('patients');
    }
}
