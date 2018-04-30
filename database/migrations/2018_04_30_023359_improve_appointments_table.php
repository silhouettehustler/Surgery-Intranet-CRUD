<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImproveAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->date("date");
            $table->time('start_time');
            $table->time('end_time');
            $table->string("description");
            $table->integer('user_id')->unsigned();
            $table->foreign("user_id")->references('id')->on('users');
            $table->integer('employee_id')->unsigned();
            $table->foreign("employee_id")->references('id')->on('users');
            $table->boolean("cancelled")->default(0);
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
        Schema::dropIfExists('appointments');
    }
}
