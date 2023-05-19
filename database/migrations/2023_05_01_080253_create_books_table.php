<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('book_id');  
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->string('category')->nullable();
            $table->string('year')->nullable();
            $table->string('isbn');
            $table->string('date_purchased')->nullable();
            $table->string('price')->nullable();
            $table->string('shelf_no')->nullable();
            $table->string('book_description')->nullable();
            $table->string('book_image')->nullable();
            $table->string('status')->nullable();
            $table->string('borrowers')->nullable();
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
        Schema::dropIfExists('books');
    }
}
