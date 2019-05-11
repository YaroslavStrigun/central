<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsForUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('brigade_id')->nullable();
            $table->boolean('male')->default(0);
            $table->text('document')->nullable();
            $table->text('qualification')->nullable();
            $table->text('education')->nullable();
            $table->string('experience')->nullable();
            $table->text('work_place')->nullable();
            $table->text('certificate')->nullable();

            $table->foreign('brigade_id')->references('id')->on('brigades')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('brigade_id');
            $table->dropColumn('male');
            $table->dropColumn('document');
            $table->dropColumn('qualification');
            $table->dropColumn('education');
            $table->dropColumn('experience');
            $table->dropColumn('work_place');
            $table->dropColumn('certificate');
        });
    }
}
