<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnedBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returned_books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn');
            $table->string('student_id');
            $table->string('receiver');
            $table->string('date_received');
            $table->string('time')->nullable();
            $table->integer('rated')->default(0);
            $table->integer('total')->default(0);
            $table->integer('reviewed')->default(0);
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
        Schema::dropIfExists('returned_books');
    }
}
