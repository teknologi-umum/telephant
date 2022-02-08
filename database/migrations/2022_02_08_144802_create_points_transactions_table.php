<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('points_id')->nullable();
            $table->integer('points')->nullable();
            $table->integer('op')->nullable(); // 0 = plus, 1 = minus
            $table->bigInteger('bapacks_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points_transactions');
    }
}
