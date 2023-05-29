<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('isbn');
            $table->integer('rating1')->default(0);
            $table->integer('rating2')->default(0);
            $table->integer('rating3')->default(0);
            $table->integer('rating4')->default(0);
            $table->integer('rating5')->default(0);
            $table->integer('total_mul')->default(0);
            $table->integer('total_add')->default(0);
            $table->float('average')->default(0);
            $table->integer('total_rated')->default(0);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
