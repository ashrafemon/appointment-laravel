<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreakHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('break_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('day_name');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('break_hours');
    }
}
