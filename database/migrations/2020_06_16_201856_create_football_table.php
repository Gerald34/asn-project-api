<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFootballTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('football', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('activity_id');
            $table->string('team_id');
            $table->string('opponent');
            $table->string('team_manager');
            $table->dateTime('event_date');
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
        Schema::dropIfExists('football');
    }
}
