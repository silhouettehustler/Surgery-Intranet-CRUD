<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserShiftDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_shift_days', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shift_day_id')->unsigned();;
            $table->foreign("shift_day_id")->references('id')->on('shift_days');
            $table->integer('user_id')->unsigned();;
            $table->foreign("user_id")->references('id')->on('users');
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
        Schema::dropIfExists('user_shift_days');
    }
}
