<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('semester');
            $table->string('mid_status')->nullable();
            $table->float('mid_percentage', 8, 2)->nullable();
            $table->string('mid_attachment')->nullable();
            $table->string('final_status')->nullable();
            $table->float('final_percentage', 8, 2)->nullable();
            $table->string('final_attachment')->nullable();
            $table->string('board_status')->nullable();
            $table->float('board_percentage', 8, 2)->nullable();
            $table->string('board_attachment')->nullable();
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
        Schema::dropIfExists('results');
    }
}
